<?php
class page{
	private $header;
	private $navigator;
	private $presentation;
	private $body;
	private $footer;
	private $testModeInfo;
	
	private $shoppingCart;//to define the page locaiton
	private $itemDetailsPage;//to define the page of showing itemdetails
	function __construct(){
		//set the page as the default
		$this->setHeader();
		$this->setNavigator();
		$this->setFooter();
	}
	/* the function that can modify by the project */
	public function getItemList($itemResult){
		/* this method is to get a stylied list of item to show */
		//this is given an result set use fetch_array to get the datas
		$content='';
		while ($item=$itemResult->fetch_array()) {
			$content.=$this->itemListStru($item['name'], $item['cover'], $item['price'], $item['id']);
		}
		return $content;
		
	}
	public function getItemDetails ($valueSet){
		while ($item=$valueSet->fetch_array) {
			/* this method is use in the item details page
			 * will return a string can show the item details;
			 **/
		}
	}
	public function getForm($formName,$actionPage='',$valueArray=null){
		//initial two var that could be use
		$content="";$formTitle;
		switch ($formName) {
			case 'item':
				//set the title of the form
				$formTitle="新建书目";
				//select the class list from the database
				$content=new itemClass();
				$content=$content->getOptionList();
				//the content is setted & tested which is coded greatly by machine
				$content="
					<div class='form-group'>
						<label for='name' class='col-sm-2 control-label'>书名</label>
						<div class='col-sm-10'>
							<input type='text' class='form-control' id='name' name='name'
								placeholder='书名' />
						</div>
					</div>
					<div class='form-group'>
						<label for='amount' class='col-sm-2 control-label'>存量</label>
						<div class='col-sm-10'>
							<input type='text' class='form-control' id='amount' name='amount'
								placeholder='存量' />
						</div>
					</div>
					<div class='form-group'>
						<label for='class' class='col-sm-2 control-label'>类别</label>
						<div class='col-sm-10'>
							<select class='form-control' id='class' name='class' >
								".$content."
							</select>
						</div>
					</div>
					<div class='form-group'>
						<label for='price' class='col-sm-2 control-label'>售价</label>
						<div class='col-sm-10'>
							<input type='text' class='form-control' id='price' name='price'
								placeholder='售价' />
						</div>
					</div>
					<div class='form-group'>
						<label for='orginalPrice' class='col-sm-2 control-label'>原价</label>
						<div class='col-sm-10'>
							<input type='text' class='form-control' id='orginalPrice'
								name='orginalPrice' placeholder='原价' />
						</div>
					</div>
					<div class='form-group'>
						<label for='cover' class='col-sm-2 control-label'>封面</label>
						<div class='col-sm-10'>
							<input type='file'  id='cover' name='cover'/>
						</div>
					</div>
					<div class='form-group'>
						<label for='code' class='col-sm-2 control-label'>二维码</label>
						<div class='col-sm-10'>
							<input type='text' class='form-control' id='code' name='code'
								placeholder='二维码' />
						</div>
					</div>
					<div class='form-group'>
						<label for='description' class='col-sm-2 control-label'>描述</label>
						<div class='col-sm-10'>
							<textarea type='text' class='form-control' id='description'
								name='description' placeholder='描述'></textarea>
						</div>
					</div>
					<div class='form-group'>
						<div class='col-sm-offset-2 col-sm-10'>
							<button type='submit' class='btn btn-info'>添加书目</button>
						</div>
					</div>";
				break;
			case 'itemClass':
				$formTitle='新建类别';
				//the form use to new a item class
				$content="<div class='form-group'><label for='classCode' class='col-sm-2 control-label'>英文缩写</label>
						<div class='col-sm-10'>
						<input type='text' class='form-control' id='classCode' name='classCode' placeholder='英文缩写'/>
						</div></div><div class='form-group'><label for='className' class='col-sm-2 control-label'>中文类名</label>
						<div class='col-sm-10'>
						<input type='text' class='form-control' id='className' name='className' placeholder='中文类名'/>
						</div></div>
						<div class='form-group'>
							<div class='col-sm-offset-2 col-sm-10'>
								<button type='submit' class='btn btn-info'>添加书目</button>
							</div>
						</div>";
			break;
			case 'search':
				
				break;
		}
		//add the content part to a form around it 
		$content=$this->getFormStru($content, $formTitle, $actionPage);
		//then return it 
		return $content;
	}
	/* basic method of this the framwork */
	private function itemListStru($name,$pic,$price,$id){
		/* this method is to item structure */
		$content="
		<a href='".$this->itemDetailsPage."?id=".$id."'><div class='col-xs-6 col-sm-4 col-md-3 col-lg-2 '>
            <div class='item panel panel-default'>
                    <img src='".$pic."' alt='".$name."' class='img-responsive '> 
                <div class='item-info'>
                    <p><span class='lead'>".$name."</span><br />
                    <b>￥".$price."</b></p>
                    <a href='".$this->shoppingCart."?id=".$id."' class='btn btn-success'>放入购物车</a>
                </div>
            </div>
        </div></a>";
		
		return $content;
	}
	public function getPageHeader($content){
		/* 
		 * this method is return a structrue of pageheader
		 * which contain the given content 
		 **/
		$content="<div class='page-header'><h2>".$content."</h2></div>";
		return $content;
	}
	private function getFormStru($content,$formName,$actionPage){
		$form='';
		//the form name here is title
		if ($formName!=null) {
			$form.="<div class='form-group'><div class='col-sm-offset-2 col-sm-10'><h2>".$formName."</h2></div></div>";
		}
		$form="<form action='".$actionPage."' enctype='multipart/form-data' class='form-horizontal' method='post'>".$form.$content."</form>";
		return $form;
	}
	public function getPState($content,$state='info'){
		//set the p class that use below
		if ($state == 'success') {
			$class = 'bg-success';
		} elseif ($state == 'info') {
			$class='bg-info';
		}
		elseif ($state=='danger'){
			$class='bg-danger';
		}
		elseif($state=='warning'){
			$class='bg-warning;';
		}
		elseif ($state=='primary'){
			$class='bg-primary';
		}
		return "<p class='".$class."' style='padding:20px'>".$content."</p>";
	}
	public function container($content,$class=""){
		//add a container by the content
		return '<div class="container '.$class.'">'.$content."</div>";
	}
	/*fundemantal method that build the fram */
	public function getPage(){
		//this function will return the string of the page
		return $this->header.'<body>'.$this->navigator.$this->presentation.$this->body.$this->getTestModeInfo().$this->footer.'</body>';
	}
	public function setHeader($header=""){
		//here can set the layout and value of header
		$this->header="
			<html>
			<head>
			<meta charset='UTF-8' />
			<meta name='viewport'
				content='width=device-width, initial-scale=1, user-scalable=no'>
			<title>二手书城</title>
			<link rel='stylesheet' href='css/bootstrap.min.css' />
			<link rel='stylesheet' href='all.css' />
			<style>
			</style>
			</head>";
	}
	public function setNavigator($nav=""){
		//set the different type of navibar
		$this->navigator="
<div class='site-nav navbar navbar-inverse navbar-fixed-top'>
    <div class='container'> 
        <div class='navbar-header'><a href='index.php' class='navbar-brand'>二手书城</a></div>
    </div>
</div>";
	}
	public function setPresentation(){
		$pre="
<div class='jumbotron  site-header site-bg'>
    <div class='container responsive'>
        <div class='row'>
            <div class='col-xs-12 col-sm-5 col-md-4 col-lg-4'>
                <h1>找到任何</h1>
                <form action=''>
                    <div class='form'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='你想要的书籍'>
                        </div>
                        <div class='form-group'>
                            <button class='btn btn-success' ><span class='glyphicon glyphicon-search'></span>搜索</button>
                        </div>
                    </div>   
                </form>
            </div>
        </div>
    </div> 
</div>";
		$this->presentation=$pre;
	}
	public function appendBody($body="") {
		$this->body.=$body;
	}
	public function getTestModeInfo(){
		if (!empty($this->testModeInfo)){
			//because the information has a format to show
			$info=$this->getPState($this->testModeInfo,'danger');
			return $info;
		}
	}
	public function appendTestModeInfo($info=''){
		//this method is to store debugging info
		$this->testModeInfo.=$info;
	}
	public function setFooter($footer="") {
		$this->footer="
<script src='js/jquery.js'></script>
<script src='js/bootstrap.min.js' ></script>
</body>
</html>";
	}
	
}