<?php

class Classify extends Eloquent {

	protected $table = 'classify';
	public $timestamps = true;

	public function getClassifyselect() {
		$list = Classify::where('cus_id', '=', $id)->get()->toArray();
		foreach ($list as $key => $l) {
			$value[$key]['id'] = $l['id'];
			$value[$key]['name'] = $l['name'];
			$value[$key]['p_id'] = $l['p_id'];
			if ($l['id'] == $v['value']['id']) {
				$value[$key]['checked'] = 1;
			} else {
				$value[$key]['checked'] = 0;
			}
		}
	}

	public function classifyList() {
		$customer = Auth::user()->name;
		$cus_id = Auth::id();
		//$classify = Classify::where('cus_id', $cus_id)->whereNotIn('type',array(5,6,7,8))->orderBy('sort')->orderBy('id')->get()->toArray();
		$classify = Classify::where('cus_id', $cus_id)->orderBy('sort')->orderBy('id')->get()->toArray();
		return $this->toTree($classify);
	}

	public function toTree($arr, $pid = 0) {
		$tree = array();
               // var_dump($arr);exit();
		foreach ($arr as $k => $v) {
                        if($v['type']==6){
                            if(empty($v['open_page'])){
                                $v['open_page']=1;
                            }
                            if(empty($v['url'])){
                                $v['url']='javascript:;';
                            }
                            if($v['open_page']==2){
                                $v['link']=$v['url'].'" target="_blank';
                            }else{
                                $v['link']=$v['url'];
                            }
                        }
			if ($v['p_id'] == $pid) {
				$tree[] = $v;
			}
		}
		if (empty($tree)) {
			return null;
		}
		foreach ($tree as $k => $v) {
			$tree[$k]['childmenu'] = $this->toTree($arr, $v['id']);
		}
		return $tree;
	}

	/*
	  public function toTree(&$arr, $pid = 0) {
	  $tree = array();
	  foreach ($arr as $k => &$v) {
	  if ($v['p_id'] == $pid) {
	  $v['used']=1;
	  $tree[] = $v;
	  }
	  }
	  if (empty($tree) && empty($arr)) {
	  return null;
	  }
	  foreach ($tree as $k => $v) {
	  $tree[$k]['childmenu'] = $this->toTree($arr, $v['id']);
	  }
	  foreach($arr as $value){
	  if(!isset($value['used'])){
	  $value['p_id']=0;
	  $tree[]=$value;
	  }
	  }
	  return $tree;
	  }
	 */
}
