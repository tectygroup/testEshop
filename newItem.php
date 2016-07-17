<?php
//initial the framwork environment
include_once 'framework.php';
//set the framwork environment
$framwork=new framwork();
//plug in the actionClass part of code
$framwork->plugIn('backend/actionClass');
//to have a header and footer
$framwork->setFramwork();
//the object item will be used plenty in this page
//we use object to store and processs the information
$item=new item();
//the value define of the location of this page is define in here
$thisPage='newItem.php';
//judge is there a input
if (!isset($_POST['name'])&&!isset($_SESSION['name'])){
	/* there is not input */
	//put the form in an contianer
	$body=$framwork->page->container($framwork->page->getForm('item',$thisPage));
	$framwork->page->appendBody($body);
}
else{
	/* there is some the input */
	if ($item->isCollected()) {
		/* all data has been coolected */
		//if there have a new class then do this check
		if (!empty($_POST['className'])){
			//these code is to operate the new class
			//to judge where it have to create a new class
			$class=new itemClass();
			//value the classCode to the object item
			$item->class=$class->classCode;
			//set info of class into database
			$class->setter();
		}
		//set the data of item into database
		if ($item->setter()){
			//add the success info
			$framwork->page->appendBody(
					$framwork->page->container(
							$framwork->page->getPState('成功导入一条书目<br /><a href="newItem.php" class="btn btn-info">再创建一条</a>','success')));
	
		}
	
	}
	elseif ($item->isCollected(TRUE)){
		/* everythings has been collected but item class */
		//to have a newclass in the database
		$body=$framwork->page->container($framwork->page->getForm('itemClass',$thisPage));
		$framwork->page->appendBody($body);
		$item->passArgsBySession();
	}

	else {
		/* the data has been collected fullfully */
		//adding didn't success
		$framwork->page->appendBody(
				$framwork->page->container(
						$framwork->page->getPState('请出输入必要的信息<br />','danger')));
		$framwork->page->appendBody(
				$framwork->page->container(
						$framwork->page->getForm('item',$thisPage)));
	}
}
//all the logical things of newing an item will be integrate in the item class
echo $framwork->page->getPage();