<?php

class FeedbackController extends BaseController {
	/*
	  |--------------------------------------------------------------------------
	  | 万用表单
	  |--------------------------------------------------------------------------
	  |方法：
	  |getFeedbackData 获取浏览数据
	  |addForm 添加新表单
	  |modifyColumn 编辑表单字段
	 */

	public function getFeedbackData() {
		$cus_id = Auth::id();
		$postFun = new CommonController;
		echo $postFun->postsend("http://www.baidu.com/");
	}

	public function getmessageboard() {
		$message['cus_id'] = Auth::id();
		$message['page'] = Input::get('page');
		$message['per_page'] = Input::get('per_page');
		$postFun = new CommonController;
		echo $postFun->postsend("http://swap.5067.org/admin/list.php", $message);
	}

	public function messagestate() {
		$message['cus_id'] = Auth::id();
		$message['id'] = Input::get('id');
		$postFun = new CommonController;
		$a = Input::get('status');
		if (Input::get('status') == NULL) {
			echo $postFun->postsend("http://swap.5067.org/admin/del.php", $message);
		} else {
			$message['status'] = Input::post('status');
			echo $postFun->postsend("http://swap.5067.org/admin/status.php", $message);
		}
	}

}

?>
