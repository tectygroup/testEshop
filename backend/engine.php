<?php
class engine {
	public $page;
	function __construct($page=0) {
		//setting the page of the class
		$this->page=$page;
	}
	function search($keyword,$isAmount=FALSE){
		//is amount protal is to define whether it will have the amount==0 judgement
		if (!isset($keyword)){
			//return the default setting
			$result=$this->pageSelect(null, null);
		}
		else {
			$like=array();
			foreach (explode(' ', $keyword) as $value){
				if ($value!=null){
					//divide the sentence in to keywords.
					$like[]=$value;
				}
			}
			//initial the var that is a middle value to output
			$where=null;
			if ($isAmount!=null){
				$where=array('amount'=>'0');
			}
			$result=$this->pageSelect($where, $like);
		}
		return  $result;
	}
	public function selectByClass(){
		
	}
	private function pageSelect($where,$like){
		//explode where and like protal to outside, page 0 is the actual page1 
		//this is to select the data in databse, a page is 24 items
		$itemTable=$GLOBALS['conn']->getTableName('itemClass');
		$classTable=$GLOBALS['conn']->getTableName('item');
		//use where keyword to select the name with the specific value
		$sql='select * from '.$itemTable.' inner join '
				.$classTable.' on '.$itemTable.'.classCode ='.$classTable.'.class '.$this->multiExplain($where,$like).' limit '.($this->page*24).',24';
		return $GLOBALS['conn']->sqlQuery($sql);
	}
	private function multiExplain($where,$like){
		//this function is to explain to the sql sentence if there multi where statement.
		$conditionState='';

		if(!isset($where) && !isset($like)){
			//do nothing while it hasn't set the data..
		}
		else{
			//add the value into statement and by another function
			$conditionState='where ';
			$conditionState.=$this->forJudgeValue($where);
			$conditionState.=$this->forJudgeValue($like,'like');
			//to delete the addtion 'and'
			$conditionState=substr($conditionState, 0,-4);
			return $conditionState;
		}
	}
	private function forJudgeValue($setValue,$judge='='){
		//if the function use where to judge it will use its default setting => use = as its keywords

		$conditionState='';
		if ($setValue!=null) {
			if ($judge=='like'){
				foreach ($setValue as $value){
					//formate the sentence by the pattern
					//like sentence seach the value in name and className defaultly
					$conditionState.="(name ".$judge.' "%'.$value.'%" or  className '.$judge.' "%'.$value.'%") and';
				};
			}
			else {
				foreach ($setValue as $key=>$value){
					//formate the sentence by the pattern
					$conditionState.=" ".$key.' '.judge.' "'.$value.'" and ';
				};
			}
			return $conditionState;
		}
		else return null;
	}
}