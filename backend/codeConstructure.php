<?php
include_once '../backend/dbConnector.php';

class codeConstru{
	private $dataStru;
	function __construct(){
		$this->dataStru=$this->getDataStucture();
	}
	function construCode(){
		$code='';
		foreach ($this->dataStru as $tableName=>$tableStru){
			$code=$code.$this->getForm($tableName, $tableStru);
			$code=$code.$this->getBackendPage($tableName,$tableStru);
		}
		return  $code;
	}
	function getForm($tableName,$tableStru) {
		//the header of a form
		$form="<form action='' class='form-horizontal'>";
		foreach ($tableStru as $struName=>$struValue){
			if ($struName!='id' && $struName!='primary key'){
				//the id and the setting part of the sql would no be explain.
				$form=$form."<div class='form-group'>";
				$form=$form."<label for='".$struName."' class='col-sm-2 control-label'>".$struName.'</label>';
				$form=$form.'
						<div class="col-sm-10">
						<input type="text" class="form-control" id="'.$struName.'" name="'.$struName.'" placeholder="'.$struName.'"/>
						</div>';
				$form=$form."</div>";
			}
			
		}
		//footer of a form
		$form=$form."
				<div class='col-sm-offset-2 col-sm-10'>
				<button type='submit' class='btn btn-info'>submit</button></div></form>";
		return $form;
	}
	function getBackendPage($tableName,$tableStru) {
		$classCode='class '.$tableName.'{';
		//the functionArea is to store the functions of the clas temporaly
		$functionArea='';$functionArea2='';
		foreach ($tableStru as $struName=>$struValue){
			if ($struName!='id' && $struName!='primary key'){
				$classCode.="public $".$struName.";";
				$functionArea.='$this->'.$struName.'=$_POST["'.$struName.'"];';
				$functionArea2.=" ".$struName.",";
			}
			
		}
		//to modify the sql sentence
		$functionArea2=substr($functionArea2, 0,-1);
		$functionArea='function __construct(){'.$functionArea.'}';
		$functionArea.='private function getter(){ $sql="select '.$functionArea2.' from '.$tableName.'"; $con=new dbConn();$con->sqlQuery($sql);}';
		$classCode=$classCode.$functionArea.'}';
		
		return $classCode;
	}
	
}