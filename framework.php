<?php

class framwork{
	//here to define the situation of the plug
	private $initialPlug=array(
			'backend/config',
			'backend/dbConnector',
			'frontend/pageConstruct',
			'backend/dbConnector'
	);
	//this setting is use to  decade the root location of this file
	private $fileRoot;
	public $page;
	public $engine;
	function __construct() {
		$this->plugIn($this->initialPlug);
		//use the pageConstruct tools
		$this->page=new page();
		//let this framwork to support the session
		session_start();
		/*start two core part as a golbal args
		 * which is dbconnector and Config
		 */
		$GLOBALS['config']=new configSet();
		$GLOBALS['conn']=new dbConn();
		//universial include
		$this->fileRoot=$GLOBALS['config']->getRoot();
	}
	public function engineStart($page=0){
		//cause the engine feature is a core feature
		//so write this method to simplify its creation
		$this->plugIn('backend/engine');
		$this->engine=new engine($page);
	}
	public function setFramwork($header="",$navigator='',$footer="") {
		/*
		 * this function is trying to deliver the arguments of the setting of
		 *footer and header, which have been defind in the Page class
		 */
		$this->page->setHeader($header);
		$this->page->setNavigator($navigator);
		$this->page->setFooter($footer);
	}
	public function plugIn($fileLocation){
		//to initial the framwork by plug the files
		// do not need the suffix of the file
		if (is_array($fileLocation)){
			foreach ($fileLocation as $file){
				include_once ($this->fileRoot.$file.'.php');
			}
		}
		else {
			include_once ($this->fileRoot.$fileLocation.'.php');
		}
	}

}