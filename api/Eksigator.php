<?
require_once("../SourPHP/classes/SourPHP.php");

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

    function __construct(){
        $this->connectDb(); 
        $this->fetcher = new SourPHP();
    }

    function connectDb(){
        $this->dbLink = mysql_pconnect ( DB_HOST, DB_USER, DB_PASS ) ;
        $db_selected = mysql_select_db( DB_NAME, $this->dbLink);
    }

    function authenticateUser($userName, $apiKey){
        $userName = addslashes ( $userName );
        $apiKey   = addslashes ( $apiKey );

        $sql = "select id from users where email='$userName' and apiKey ='$apiKey' limit 1";
        $result = mysql_query($sql, $this->dbLink);

        while ($row = mysql_fetch_assoc($result)) {
            $this->userId = $row['id'];
            $this->userApiKey = $row['apiKey'];
            return true;
        }

        return false;
    }


    function getUserSubscriptionList(){

        $sql = "select * from entries where userId='" . $this->userId . "'";
        $result = mysql_query($sql, $this->dbLink);

        $subscriptionLists = array();
        while ($row = mysql_fetch_assoc($result)) {
            $subscriptionLists[] = $row;
        }

        return $subscriptionLists;
    }

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
                      and userId='$this->userId'";

        mysql_query($sql, $this->dbLink);

        return array("userId"=>$this->userId,
                     "title"=> $title,
                     "lastRead"=> (string) $lastRead,
                     "status"=> (string) $status,
                     "lastId"=> (string) $lastId);
    }


    function removeFromList ( $title ) {
        $title = addslashes ( $title);

        $sql = "delete from entries
            where title='$title'
            and userId ='$this->userId'";

        mysql_query($sql, $this->dbLink);
    }


    function addToList( $title ) {
        $this->removeFromList( $title ) ;
        $title = addslashes ( $title);
        $lastRead = (int) time();
        $status = (int) 0;
        $lastId = (int) self::MAX_ID;

        $sql = "insert into entries (userId, title, lastRead, status, lastId )
            values ( '$this->userId', '$title', '$lastRead', '$status', '$lastId')";

        mysql_query($sql, $this->dbLink);
    }

    function setItemAsRead($title){
        $lastRead = (int) time();
        $status = 0;
        $lastId = self::MAX_ID;
        $this->updateUserTitle ( $title, $lastRead, $status, $lastId );
    }

    function toJson($text){
        $data = json_encode($text);
        header("Content-type: application/x-javascript"); 

        if($_GET['jsoncallback'] ) {
            echo $_GET['jsoncallback'] . '(' . $data . ');';
        }
        else {
            echo $data;
        }
    }


    function getSubscriptionStatus() {

        $cacheTime = self::CACHE_TIME; //seconds
        $this->userCacheFile = "/tmp/eksigator_". $this->userId;

        $mtime = @filemtime($this->userCacheFile);
        $now = time();
        $timeDiff = $now - $mtime;

        $list = $this->getUserSubscriptionList();

        if( ($timeDiff) > $cacheTime) {

            foreach($list as $key=>$value) {
                $title = $value['title'];
                $lastRead = $value['lastRead'];
                $status = $value['status'];

                if($status == self::STATUS_READ) {
                    $changeStatus = $this->fetcher->getEntriesByTitleAfterGivenTime($title, $lastRead);
                    $changeStatus = $changeStatus[0];

                    if($changeStatus) {
                        $newStatus   = (string) self::STATUS_UNREAD;
                        $newId       = (string) $changeStatus['entryId'];
                        $newLastRead = (string) $changeStatus['dateCreated'];
                    }
                    else{
                        $newStatus   = (string) self::STATUS_READ;
                        $newId       = (string) self::MAX_ID;
                        $newLastRead = (string) time();
                    }

                    $list[$key] = $this->updateUserTitle ( $title, $newLastRead, $newStatus, $newId );
                }

            }
            @touch($this->userCacheFile);  
        }


        return $list;
    }



}
