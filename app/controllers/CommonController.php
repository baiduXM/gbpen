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
    
    public function quickBarJsonInit(){
        $Mobile = new PrintController('preview','mobile');
        $QuickBar=WebsiteConfig::where('cus_id',$Mobile->cus_id)->where('type',2)->where('template_id','0')->where('key','quickbar')->pluck('value');
        if($QuickBar) $MobileQuickBar=unserialize($QuickBar);
        $DefaultQuickBar=[
                ['pc'=>0,'mobile'=>0,'name'=>'电话','icon'=>'&#xe602;','image'=>'icon/2.png','data'=>'18459276266','link'=>'tel://','type'=>'tel','enable_pc'=>1,'enable_mobile'=>1],
                ['pc'=>0,'mobile'=>0,'name'=>'短信','icon'=>'&#xe604;','image'=>'icon/3.png','data'=>'18459276267','link'=>'sms://','type'=>'sms','enable_pc'=>1,'enable_mobile'=>1],
                ['pc'=>0,'mobile'=>0,'name'=>'咨询','icon'=>'&#xe606;','image'=>'icon/5.png','data'=>'小E:156568451@QQ|客服-XX:10000@QQ|客服-YY:10000@53kf','type'=>'im','enable_pc'=>1,'enable_mobile'=>1],
                ['pc'=>0,'mobile'=>0,'name'=>'地图','icon'=>'&#xe605;','image'=>'icon/4.png','data'=>'','type'=>'link','enable_pc'=>1,'enable_mobile'=>1],
                ['pc'=>0,'mobile'=>0,'name'=>'分享','icon'=>'&#xe600;','image'=>'icon/8.png','data'=>'qzone,tqq,tsina,ibaidu','type'=>'share','enable_pc'=>1,'enable_mobile'=>1],
                ['pc'=>0,'mobile'=>0,'name'=>'搜索','icon'=>'&#xe636;','image'=>'icon/8.png','data'=>'','type'=>'search','enable_pc'=>1,'enable_mobile'=>1],
            ];  
        if(!$QuickBar){
            $QuickBar = ['err' => 0, 'msg' => '获取成功！','data' =>$DefaultQuickBar];
        }else{
            $QuickBar = ['err' => 0, 'msg' => '获取成功！','data' =>$MobileQuickBar];
        }
        return json_encode($QuickBar);
    }
    
    public function quickBarJsonModify(){
        $Mobile = new PrintController('preview','mobile');
        $QuickBar = serialize(Input::get('QuickBar'));
        $id=WebsiteConfig::where('cus_id',$Mobile->cus_id)->where('type',2)->where('template_id','0')->where('key','quickbar')->pluck('id');
        if($id){
            $QuickData=WebsiteConfig::find($id);
        }else{
            $QuickData=new WebsiteConfig();
            $QuickData->cus_id=$Mobile->cus_id;
            $QuickData->type=2;
            $QuickData->template_id=0;
            $QuickData->key='quickbar';
        }
        $QuickData->value=$QuickBar;
        $result=$QuickData->save();
        if($result){
            $json_result = ['err' => 0, 'msg' => '保存成功'];
        }else{
            $json_result = ['err' => 1001, 'msg' => '该栏目存在文章，需转移才能创建子栏目','data'=>[]];
        }
        return Response::Json($json_result);
    }
}

?>
