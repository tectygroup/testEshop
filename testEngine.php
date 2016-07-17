<?php
include 'framework.php';
$fram=new framwork();
$fram->setFramwork();
$fram->page->setNavigator();
$fram->page->setPresentation();
$fram->engineStart();
$keyowrd='';
$keyowrd='无声  ';
$result=$fram->engine->search($keyowrd);
$fram->page->appendBody($fram->page->container($fram->page->getPageHeader('搜索结果<small>-'.$keyowrd.'</small>').$fram->page->getItemList($result)));
echo $fram->page->getPage();
?>