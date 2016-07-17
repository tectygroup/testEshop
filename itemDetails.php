<?php
/* this page is to get a item details */
include 'framwork.php';
$fram=new framwork();
$fram->setFramwork();
$fram->plugIn('backend/actionClass');
if (empty($_GET['id'])){
	//enter this page unporperly
	
	
}
else {
	//enter the page with a proper given id;
	$id=$_GET['id'];
	$item=new item($id);
}
