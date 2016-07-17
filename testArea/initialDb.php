<?php
//this page is to initial the database environment
include '../framework.php';
$fram=new framwork();
$fram->plugIn('backend/actionClass');
/* initial the tables */
//dbConnector has been pluged in 
$dbConstru=new dbConstruct();



/* initial the data in itemclass */
//define the initial class name and code
$itemClass = array (
		'A' => '马克思主义、列宁主义、毛泽东思想、邓小平理论',
		'B' => '哲学、宗教',
		'C' => '社会科学总论',
		'D' => '政治、法律',
		'E' => '军事',
		'F' => '经济',
		'G' => '文化、科学、教育、体育',
		'H' => '语言、文字',
		'I' => '文学',
		'J' => '艺术',
		'K' => '历史、地理',
		'N' => '自然科学总论',
		'O' => '数理科学和化学',
		'P' => '天文学、地球科学',
		'Q' => '生物科学',
		'R' => '医药、卫生',
		'S' => '农业科学',
		'T' => '工业技术',
		'U' => '交通运输',
		'V' => '航空、航天',
		'X' => '环境科学、安全科学',
		'Z' => '综合性图书');

//then cry to add these value into database
foreach ($itemClass as $itemCode=>$itemName){
	$item=new itemClass($itemCode,$itemName);
	$item->setter();
}
