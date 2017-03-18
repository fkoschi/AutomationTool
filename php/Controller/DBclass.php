 <?php 

	define('SERVERNAME', 'FI422HELSTD081\sqlexpress');

    class DB {   
             
       public $serverName = SERVERNAME;
       public $connectionInfo;
       public $connect;    
	   public $database;
	   public $verbunden;
	   public $sql;
	   public $stmt;
	   public $row = array();
	   public $row_count;
	   public $fetch_method;
	   public $obj;
       
       public function set_database($database){
			$this->database =  $database;			
            $this->connectionInfo = array("Database"=> $this->database);			
       }       
	   public function set_sql($sql) {
			$this->sql = $sql;			
	   }
       public function _connect() {
            $this->connect = sqlsrv_connect($this->serverName,$this->connectionInfo);
            
            if( $this->connect ) {
		      $this->verbunden = true;
            } else{
		      $this->verbunden = false;
		      die( print_r( sqlsrv_errors(), true));
            }
       }
	   public function _query() {
			$this->stmt = sqlsrv_query( $this->connect, $this->sql);
			
			if( $this->stmt === false ) {
				die( print_r( sqlsrv_errors(), true) );
			}		
			
	   }
	   public function _fetch_array($method) {
			
			if( $method == 1 )
				$this->fetch_method = "SQLSRV_FETCH_ASSOC";
				else 
					$this->fetch_method = 'SQLSRV_FETCH_NUMUERIC';
			$i = 0;
			while( $row = sqlsrv_fetch_array( $this->stmt, SQLSRV_FETCH_ASSOC) ) {
				
				$this->row[$i] = $row;
				$i++;
			}
			return $this->row;
	   }
	   public function _fetch_object(){
			$i = 0;
			while( $obj = sqlsrv_fetch_object( $this->stmt )) {
				$this->obj[$i] = $obj;
				$i++;
			}
			return $this->obj;
	   }
	   public function _num_rows() {
					
			$this->row_count = sqlsrv_num_rows( $this->stmt );
			
			if( $this->row_count === false ){
				echo "Error in retrieveing row count.";
				} else {
					return $this->row_count;
					}
	   }
	   public function _close() {
			sqlsrv_close($this->connect);
	   }
		
		public function _count_tasks($table) {
			
			$sql = "SELECT COUNT(ID) as rows FROM [" . $table . "]" ;			
			$this->sql = $sql;					
			$this->_query();			
			$result = $this->_fetch_array(1);
			
			echo '<div id="num_of_tasks">';		
			echo '<p>Gesamtanzahl:' . $result[0]["rows"] . '</p>';
			echo '</div>';
			
		}
       
	}
    
	
?>