<?
class Db {
	public function __construct($dbConf) {
		$conn = @mysql_pconnect ( $dbConf->host, $dbConf->user, $dbConf->pass ) or die("Veritabanina baglanilamiyor");
		$dbSelect = @mysql_select_db ( $dbConf->name, $conn ) or die("veritabani secilemiyor");
	}
	
	public function query($query) {
		//sql query
		$q = @mysql_query ( $query );
		//num_rows
		$this->lastQuery=$query;
		$this->lastQueryCount = ( int ) @mysql_num_rows ( $q );
		$this->totalQuery++;
		return $q;
	}
	
	public function fetch($query) {
		//can be get by foreach
		$res = array ();
		$q = $this->query ( $query );
		while ( $data = @mysql_fetch_assoc ( $q ) ) {
			$res[] = $data;
		}
		return $res;
	}
	
	function __destruct() {
		@mysql_close ();
	}

}
