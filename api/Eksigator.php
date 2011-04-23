<?
require_once("../SourPHP/classes/SourPHP.php");
require_once("../SourPHP/classes/SourPHPUrlCacheToFile.php");

include_once("mysqlConf.php"); //db conf
include_once("amqp/MessageSender.php"); //amqp message sender

/**
 * Eksigator
 *
 * @author    Osman Yuksel <yuxel |AT| sonsuzdongu |DOT| com>
 */
class Eksigator{

    const STATUS_READ = 0;
    const STATUS_UNREAD = 1;

    const MAX_ID = 2099999999;

    const CACHE_TIME = 3600; //seconds

    const TIME_DIFF = 10500; 

    const USER_CACHE_FILE_PREFIX = "/tmp/eksigator_";
    
    const URL_CACHE_PREFIX = "/tmp/url_";

    const USE_RABBIT_MQ = true;


    /**
     * init db and fetcher
     */
    function __construct(){
        session_start();

        $this->connectDb(); 
        $this->fetcher = new SourPHP();
        $cacheHandler = new SourPHPUrlCacheToFile();

        if (self::USE_RABBIT_MQ) {
            $fetchSender = new MessageSender();

            $urlFetcher = function($url, $hash, $cacheHandler) {
                $fetchSender->send($url, $hash);
            };

            $this->fetcher->setUrlFetcher($urlFetcher);
        }

        $this->fetcher->setCacheLifeTime( self::CACHE_TIME );
        $this->fetcher->setCacheHandler ( $cacheHandler );
        $this->fetcher->setCachePrefix ( self::URL_CACHE_PREFIX );

        $delay = 300; //difference between eksisozluk's server and our server
        $now = time();

        $this->now = $now - $delay;
        
    }

    /**
     * connect to db
     */
    function connectDb(){
        $this->dbLink = mysql_pconnect ( DB_HOST, DB_USER, DB_PASS ) ;
        $db_selected = mysql_select_db( DB_NAME, $this->dbLink);
    }

    /**
     * authenticate user via username and apiKey
     *
     * @param string $userName  username as email
     * @param string $apiKey apiKey
     * @return boolean
     */
    function authenticateUser($userName, $apiKey){
        if( !$userName || !$apiKey) {
            return false;
        }

        $userName = addslashes ( $userName );
        $apiKey   = addslashes ( $apiKey );

        //if existed on session dont ask to mysql
        if( $_SESSION[$apiKey] == $userName ) {

            $this->userId     = $_SESSION['eksigatorApi']['userId'];
            $this->userApiKey = $_SESSION['eksigatorApi']['apiKey'];
            $this->userAuth   = $_SESSION['eksigatorApi']['auth'];

            return true;
        }

        $sql = "select * from users where email='$userName' and apiKey ='$apiKey' limit 1";
        $result = mysql_query($sql, $this->dbLink);

        while ($row = mysql_fetch_assoc($result)) {
            $this->userId = $row['id'];
            $this->userApiKey = $row['apiKey'];
        
            $_SESSION[$apiKey] = $userName;
            $_SESSION['eksigatorApi']['apiKey'] = $row['apiKey'];
            $_SESSION['eksigatorApi']['userId'] = $row['id'];
            $_SESSION['eksigatorApi']['auth'] = $row['auth'];


            return true;
        }

        return false;
    }


    /**
     * get users subscriptionList from db
     */
    function getUserSubscriptionList(){

        $sql = "select title, lastRead, status, lastId from entries where userId='" . $this->userId . "' and deleted=0";
        $result = mysql_query($sql, $this->dbLink);


        $subscriptionLists = array();
        $count = 0;
        while ($row = mysql_fetch_assoc($result)) {
            $subscriptionLists[$count] = $row;
            $subscriptionLists[$count]['title'] = stripslashes ( $row['title'] );
            $count++;
        }

        return $subscriptionLists;
    }

    /**
     * update title of user 
     *
     * @param string $title entry title
     * @param int $lastRead timestamp of last read entry
     * @param int $status , read = 0, unread = 1
     * @param int $lastId id of last read entry
     */
    function updateUserTitle ( $title, $lastRead, $status, $lastId ) {
        $title = addslashes ( $title);
        $lastRead = (int) $lastRead;
        $status = (int) $status;
        $lastId = (int) $lastId;

        $sql = "update entries set 
                title = '$title',
                lastRead = '$lastRead',
                status = '$status',
                lastId = '$lastId'
                where 
                title='$title'
                and deleted=0
                and userId='$this->userId'";

        mysql_query($sql, $this->dbLink);

        return array("userId"=>$this->userId,
                     "title"=> stripslashes ( $title ),
                     "lastRead"=> (string) $lastRead,
                     "status"=> (string) $status,
                     "lastId"=> (string) $lastId);
    }


    /**
     * remove title from database
     *
     * @param string $title entry title
     */
    function removeFromList ( $title ) {
        $title = addslashes ( $title);

        $sql = "update entries
                set deleted = $this->now 
                where title='$title'
                and deleted=0
                and userId ='$this->userId'";

        mysql_query($sql, $this->dbLink);
    }


    /**
     * add entry to list
     *
     * @param string $title entry title
     */
    function addToList( $title ) {
        //remove first
        $this->removeFromList( $title ) ;
        $title = addslashes ( $title);
        $lastRead = (int) $this->now;
        $status = (int) 0;
        $lastId = (int) self::MAX_ID;

        $sql = "insert into entries (userId, title, lastRead, status, lastId,  added)
            values ( '$this->userId', '$title', '$lastRead', '$status', '$lastId', '". $this->now."')";

        mysql_query($sql, $this->dbLink);
    }

    /**
     * set title as read
     *
     * @param string $title entry title
     */
    function setItemAsRead($title){
        $lastRead = (int) $this->now;
        $status = 0;
        $lastId = self::MAX_ID;
        $this->updateUserTitle ( $title, $lastRead, $status, $lastId );
    }

    /**
     * output data as json
     * 
     * @param mixed array 
     */
    function toJson($array){
        $data = json_encode($array);
        header("Content-type: application/x-javascript"); 

        //JSONP
        if($_GET['jsoncallback'] ) {
            echo $_GET['jsoncallback'] . '(' . $data . ');';
        }
        else {
            echo $data;
        }
    }


    /**
     * fetches new entries if cache time expired
     */
    function getSubscriptionStatus() {

        $cacheTime = self::CACHE_TIME; //seconds
        $this->userCacheFile =  self::USER_CACHE_FILE_PREFIX.$this->userId;

        $mtime = @filemtime($this->userCacheFile);
        $mtime = $mtime + self::TIME_DIFF;
        $now = $this->now;
        $timeDiff = $now - $mtime;

        $list = $this->getUserSubscriptionList();

        if( ($timeDiff) > $cacheTime) {

            foreach($list as $key=>$value) {
                $title = $value['title'];
                $lastRead = $value['lastRead'];
                $status = $value['status'];

                //if not read yet, skip
                if($status == self::STATUS_READ) {
                    //fetch new entries
                    $changeStatus = $this->fetcher->getEntriesByTitleAfterGivenTime($title, $lastRead);
                    //get first entry
                    $changeStatus = $changeStatus[0];

                    if($changeStatus) { //if entry changed
                        $newStatus   = (string) self::STATUS_UNREAD;
                        $newId       = (string) $changeStatus['entryId'];
                        $newLastRead = (string) $changeStatus['dateCreated'];

                        $list[$key] = $this->updateUserTitle ( $title, $newLastRead, $newStatus, $newId );
                    }

                }

            }
            //update userCache
            @touch($this->userCacheFile);  
        }

        return empty($list) ? null : $list;
    }


    /**
     * fetches all url's last page
     */
    function fetchAll(){

        //disable url caching
        $this->fetcher->setCacheLifeTime( -1 );

        $sql = "select distinct(title) from entries where deleted=0";
        $result = mysql_query($sql, $this->dbLink);
        $now = time();

        while ($row = mysql_fetch_assoc($result)) {
            $title = $row['title'];
            $this->fetcher->getEntriesByTitleAfterGivenTime($title, $now);
            echo $title." <br/>\n";
        }



    }



    /**
     * fetches some title according to an algorightm by time
     */
    function fetchByLimit(){

        $now = time();

        $currentHour = date("H");
        $maxQuery = 500; //max query per hour

        //disable url caching
        $this->fetcher->setCacheLifeTime( -1 );

        $sql = "select count(distinct(title)) as count from entries where deleted=0";
        $result = mysql_query($sql, $this->dbLink);
        
        $row = mysql_fetch_assoc($result);
        $total = $row['count'];

        $howManyTimes = ceil( $total / $maxQuery );

        $currentBlock = $currentHour % $howManyTimes;

        $start = $maxQuery * $currentBlock;


        $sql = "select distinct(title) from entries where deleted=0 limit $start,$maxQuery";
        $result = mysql_query($sql, $this->dbLink);
        
        while ($row = mysql_fetch_assoc($result)) {
            $title = $row['title'];
            $this->fetcher->getEntriesByTitleAfterGivenTime($title, $now);
            echo $title." <br/>\n";
        }

    }
}
