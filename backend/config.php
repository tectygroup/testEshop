<?php
class configSet{
	public $mysqlAddress='localhost';
	public $mysqlUserName='root';
	public $mysqlPassword='';
	public $mysqlDbName='test';
	public $mysqlTablePrefix="Bk_";
	public $testMode=FALSE;
	public $fileRoot='';
	function __construct(){
		//initial the file root var in this file
		$this->fileRoot=$_SERVER['DOCUMENT_ROOT'].'/eshop/';
	}
	function testmodeEcho($key,$value){
		//if the site enter the test mode echo the given string
		if ($this->testMode==true){
			echo $key.': ';
			var_dump($value);
			echo '<br />';

		}
	}
	function getRoot($fileLocation='') {
		return $this->fileRoot.$fileLocation;
	}
}