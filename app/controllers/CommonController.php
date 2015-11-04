<?php

class CommonController extends BaseController{
    /*
	|--------------------------------------------------------------------------
	| 公共函数库
	|--------------------------------------------------------------------------
    | IconsList 获取文字图标库
    | postsend 发起post请求
	*/ 
    
    public function IconsList(){
        $icons=Config::get('icons');
        $result = ['err' => 0, 'msg' => '','icons'=>$icons];
        return Response::json($result);
    }
    
    public function postsend($url,$data=array()){
        $ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, "$url");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);  //定义超时3秒钟  
		// POST数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// POST参数
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		//执行并获取url地址的内容
		$output = curl_exec($ch);
		$errorCode = curl_errno($ch);
		//释放curl句柄
		curl_close($ch);
		if(0 !== $errorCode) {
			return false;
		}
		return $output;
    }
}

?>
