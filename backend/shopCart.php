<?php
class shopCart{
	private $shopCart;
	function __construct(){
		
	}
	function getter(){
		$this->shopCart=$_SESSION['shopCart'];
		$itemList=explode($this->shopCart, '|');
		return $itemList;
	}
	function setter($itemID){
		//set the itmeID into the string in the array
		$_SESSION['shopCart'].='|'.$itemID;	
	}
}