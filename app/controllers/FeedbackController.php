<?php

class FeedbackController extends BaseController{
    /*
	|--------------------------------------------------------------------------
	| 万用表单
	|--------------------------------------------------------------------------
	|方法：
    |getFeedbackData 获取浏览数据
    |addForm 添加新表单
    |modifyColumn 编辑表单字段
	*/ 
    
    
    
    public function getFeedbackData(){
        $cus_id=Auth::id();
        $postFun=new CommonController;
        echo $postFun->postsend("http://www.baidu.com/");
        
    }
    
    public function addForm(){
        
    }
    
    public function modifyColumn(){
        
    }
	
	public function getmessageboard(){
		$message['cus_id']=Auth::id();
		$message['page']=Input::get('page');
		$message['per_page']=Input::get('per_page');
		$postFun=new CommonController;
		echo $postFun->postsend("http://www.message.com/message/list.php",$message);
	}
}

?>
