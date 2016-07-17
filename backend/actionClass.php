<?php
class item {
	public $name;
	public $amount;
	public $class;
	public $price;
	public $orginalPrice;
	public $cover;
	public $code;
	public $description;
	function __construct($id=NULL) {
		//auto initial this class
		if (isset($_POST ["name"])){
			$this->name = $_POST ["name"];
			$this->amount = $_POST ["amount"];
			$this->class = $_POST ["class"];
			$this->price = $_POST ["price"];
			$this->orginalPrice = $_POST ["orginalPrice"];
			$this->cover = $_FILES ["cover"];
			$this->code = $_POST ["code"];
			$this->description = $_POST ["description"];
		}
		elseif (isset($_SESSION ["name"])){
			//use the session method to construct this object
			$this->name = $_SESSION ["name"];
			$this->amount = $_SESSION ["amount"];
			$this->class = $_SESSION ["class"];
			$this->price = $_SESSION ["price"];
			$this->orginalPrice = $_SESSION ["orginalPrice"];
			$this->cover = $_SESSION ["cover"];
			$this->code = $_SESSION ["code"];
			$this->description = $_SESSION ["description"];
		}
		elseif ($id!=null){
			/* this proptal is use by the item details page */
			
		}
		//add the debug setting
		/* $GLOBALS['config']->testmodeEcho('name',$this->name);
		$GLOBALS['config']->testmodeEcho('amount',$this->amount);
		$GLOBALS['config']->testmodeEcho('class',$this->class);
		$GLOBALS['config']->testmodeEcho('price',$this->price);
		$GLOBALS['config']->testmodeEcho('originalPrice',$this->orginalPrice);
		$GLOBALS['config']->testmodeEcho('cover',$this->cover);
		$GLOBALS['config']->testmodeEcho('code',$this->code);
		$GLOBALS['config']->testmodeEcho('description',$this->description); */
	}
	private function getter($id) {
		/* this method is to get the value set of an item */
		$sql = "select name, amount, class, price, orginalPrice, cover, code, description from item";
		return $GLOBALS['conn']->sqlQuery ( $sql );
	}
	public function getValueSet(){
		$array=array();
		$array['name']=$this->name;
		$array['amount']=$this->amount;
		$array['class']=$this->class;
		$array['price']=$this->price;
		$array['originalPrice']=$this->orginalPrice;
		//don't need to pass the cover data because it doesn't mean anything
		$array['code']=$this->code;
		$array['description']=$this->description;
		return $array;
	}
	public function isCollected($exceptClass=FALSE){
		if ($this->amount != null) {
			// the collecting of class is define as there is class or both of classCode and ClassName have collected
			// or we tell it don't need to judge whether the class has collected
			if (($this->class != null or $exceptClass) or (! empty ( $_POST ['clasName'] ) && ! empty ( $_POST ['classCode'] ))) {
				if ($this->cover != null) {
					if ($this->price != null) {
						// non-active judement about the data have been selected
						
						return true;
					}
				}
			}
		} 
		else {
			return false;
		}
	}
	public function getInputForm($actionPage){
		/* this method is to replace the method in the page object */
		//set the title of the form
		$formTitle="新建书目";
		//new a object to get the optionList of class name
		$class=new itemClass();
		$optionList=$class->getOptionList($this->class);

		$content=$this->getFormStru($content, '添加新书目', $actionPage);
		return $content;
		
	}
	private  function coverPicStore(){
		//to get the file name with lowercast
		$filename=strtolower($this->cover['name']);
		//set the files that allow
		$allowFile=explode('|', 'png|jpg|jpeg|gif|bmp');
		$filename=explode(".", $filename);
		$fileType=end($filename);
		$filename=$filename[0];
		$filename=date('YmdHis').hash('md5',$filename);
		/*now we have the filename, filetype
		 *  and we want to move the file to the correct place 
		 *  then have its location
		 */
		if (in_array($fileType, $allowFile)){
			//generate the filename with the file location.
			$filename='img/'.$filename.'.'.$fileType;
			move_uploaded_file($this->cover['tmp_name'], $GLOBALS['config']->getRoot($filename));
		}
		$this->cover=$filename;
	}
	public function  setter(){
		if ($this->isCollected()) {
			//make sure that everything has been collected
			$this->coverPicStore();
			//let this object storing the picture
			//this is to set the item information
			$sql="insert into ".$GLOBALS['config']->mysqlTablePrefix."item (name, amount, class, price, orginalPrice, cover, code, description) values 
					('".$this->name."', '".$this->amount."', '".$this->class."', '".$this->price."', '".$this->orginalPrice."', '".$this->cover."', '".$this->code."', '".$this->description."' ) ";
			$GLOBALS['conn']->sqlQuery($sql);
			$this->destoryArgsInSession();
			return true;
		}
		else{
			return false;
		}
	}

	public function passArgsBySession(){
		echo 'passed something.';
		//use this method to pass the args into the session
		$_SESSION ["name"] = $this->name;
		$_SESSION ["amount"] = $this->amount;
		$_SESSION ["class"] = $this->class;
		$_SESSION ["price"] = $this->price;
		$_SESSION ["orginalPrice"] = $this->orginalPrice;
		$_SESSION ["cover"] = $this->cover;
		$_SESSION ["code"] = $this->code;
		$_SESSION ["description"] = $this->description;
	}
	private function destoryArgsInSession(){
		//destory the args pass in the session
		unset($_SESSION ["name"],$_SESSION ["amount"],$_SESSION ["class"],
				$_SESSION ["price"],$_SESSION ["orginalPrice"],$_SESSION ["cover"],
				$_SESSION ["code"],$_SESSION ["description"]);
	}
	/* this method is to set a from structure of a form */
	private function getFormStru($content,$formName,$actionPage){
		$form='';
		//the form name here is title
		if ($formName!=null) {
			$form.="<div class='form-group'><div class='col-sm-offset-2 col-sm-10'><h2>".$formName."</h2></div></div>";
		}
		$form="<form action='".$actionPage."' enctype='multipart/form-data' class='form-horizontal' method='post'>".$form.$content."</form>";
		return $form;
	}
}
class itemClass {
	public $classCode;
	public $className;
	function __construct($classCode="",$className="") {
		if (($classCode==null && $className==null)&&(!empty($_POST['classCode'])&!empty($_POST['className']) )){
			//set the object by the POST values
			$this->classCode = $_POST ["classCode"];
			$this->className = $_POST ["className"];
		}
		else {
			//set this object by the given value
			$this->classCode=$classCode;
			$this->className=$className;
		}
	}
	public function getDataSet(){
		$arry=array();
		$arry['classCode']=$this->classCode;
		$arry['clasName']=$this->className;
		return $arry;
	}
	private function getter() {
		//this will return the array of the itemClass
		$classList=$GLOBALS['conn']->sqlQuery('select classCode, className from '.$GLOBALS['conn']->getTableName('itemClass'));
		//transform an object into an array to operate it
		$arr=array();
		while ($class=$classList->fetch_array()){
			$arr[]=$class;
		}
		return $arr;
	}
	public function getOptionList($presetKey=''){
		//this will transfrom the class list into an option list
		$classList=$this->getter();
		array_unshift($classList, array('','如果要创建一个类别，请选这个'));
		//declare an variable to store the temp info
		$content='';
		foreach ($classList as $class){
			//this is to define whether it has been collected
			$selected='';
			if ($presetKey==$class[0]){
				$selected='selected="selected"';
			}
			//form the content option by the data in the database
			$content.="<option value='".$class[0]."' ".$selected.">".$class[1]."</option>";
		}
		return $content;
	}
	public function  setter(){
		//this is to set the itemClass information
		$sql="insert into ".$GLOBALS['config']->mysqlTablePrefix."itemClass (classCode, className) values
				('".$this->classCode."', '".$this->className."') ";
		$GLOBALS['conn']->sqlQuery($sql);
	}

}
class order {
	public $itemlist;
	public $totalPrice;
	public $netPrice;
	public $orderState;
	public $orderTime;
	public $pickupTime;
	public $finishTime;
	function __construct() {
		$this->itemlist = $_POST ["itemlist"];
		$this->totalPrice = $_POST ["totalPrice"];
		$this->netPrice = $_POST ["netPrice"];
		$this->orderState = $_POST ["orderState"];
		$this->orderTime = $_POST ["orderTime"];
		$this->pickupTime = $_POST ["pickupTime"];
		$this->finishTime = $_POST ["finishTime"];
	}
	
	private function getter() {
		$sql = "select itemlist, totalPrice, netPrice, orderState, orderTime, pickupTime, finishTime from ".$GLOBALS['config']->mysqlTablePrefix."order";
		$con = new dbConn ();
		$con->sqlQuery ( $sql );
	}
}

?>