<?php
//this couldn't work sometimes
// require_once '../backend/config.php';


class dbConn{
	private $mysql=null;
	//the con in this part means the config 
	private $con;
	function __construct(){
		//always connect the Database with one link
		$this->con=$GLOBALS['config'];
		$address=$this->con->mysqlAddress;
		$userName=$this->con->mysqlUserName;
		$password=$this->con->mysqlPassword;
		$dbName=$this->con->mysqlDbName;
		$this->mysql=new mysqli($address,$userName,$password,$dbName);
	}
	function sqlQuery($sql){
		//send the sql sentence as a function
		$this->con->testmodeEcho('SqlSentence',$sql);
		return $this->mysql->query($sql);
	}
	function getTableName($tableName) {
		return $GLOBALS['config']->mysqlTablePrefix.$tableName;
	}
}
class dbConstruct {
	private $config;
	private $conn;
	function __construct() {
		$this->config=new configSet();
		$this->conn=new dbConn();
		//try to find if there is a table of item
		$sql='show tables where tables_in_'.$this->config->mysqlDbName.'="'.$this->config->mysqlTablePrefix.'item"';
		$result=$this->conn->sqlQuery($sql);
		$result=$result->fetch_array();
		if (empty($result)){
		 	//if there is a table, get the values of it
			$this->constructTableStru();
		}
	}
	function getDataStucture(){
		//this function record the structure of the table of this eshop
		$dataStru=array(
				'item'=>array(
						'id'=>'int(100) not null auto_increment',
						'name'=>'varchar(255) not null',
						'amount'=>'int(100)',
						'class'=>'varchar(8) not null',
						'price'=>'decimal(6,2) not null',
						'orginalPrice'=>'decimal(6,2)',
						'cover'=>'varchar(255)',
						'code'=>'int(200)',
						'description'=>'longtext',
						'primary key'=>'(id)'
						
				),
				'itemClass'=>array(
						'classCode'=>'varchar(8) not null',
						'className'=>'varchar(255) not null',
						'unique'=>'(classCode,className)'
				),
				'order'=>array(
						'id'=>'int(100) not null auto_increment',
						'itemlist'=>'longtext not null',
						'totalPrice'=>'decimal(6,2)',
						'netPrice'=>'decimal(6,2)',
						'orderState'=>'int(2)',
						'orderTime'=>'timestamp default current_timestamp',
						'pickupTime'=>'datetime',
						'finishTime'=>'datetime',
						'ownerName'=>'varchar(255)',
						'primary key'=>'(id)'
				)
		);
		return $dataStru;
	}
	private function constructTableStru() {
		//apply the table struct into the database
		$tables=$this->getDataStucture();
		foreach ($tables as $tableName=>$tableStru){
			//try to create the table
			$table=new dbTable($tableName);
			$table->create($tableStru);
		}
	}
}
class dbTable{
	private $tableName;
	private $config;
	private $dbName;
	private $conn;
	public function __construct($tableName){
		$this->config=new configSet();
		$this->dbName=$this->config->mysqlDbName;
		$this->conn=new dbConn();
		//state the table name according to the setted prefix
		$this->tableName=$this->config->mysqlTablePrefix.$tableName;		
	}
	
	public function explainTableStructure($tableStru){
		$sql='create table '.$this->tableName.' (';
		foreach ($tableStru as $tableKey=>$tableStru){
			//state the structure to the sql form
			$sql=$sql.$tableKey.' '.$tableStru.' ,';
		}
		$sql=substr($sql, 0,-1);
		$sql=$sql.')';
		return $sql;
	}
	public function getTable(){
		//return the array of the table values
		
	}
	public function create($tableStru){
		//due to the structure form and create the data table
		$this->conn->sqlQuery($this->explainTableStructure($tableStru));
	}
}