<?php

class CommonController extends BaseController {
	/*
	  |--------------------------------------------------------------------------
	  | 公共函数库
	  |--------------------------------------------------------------------------
	  | IconsList 获取文字图标库
	  | postsend 发起post请求
	  | qrcode   手机、pc二维码生成
	 */

	public function IconsList() {
		$icons = Config::get('icons');
		$result = ['err' => 0, 'msg' => '', 'icons' => $icons];
		return Response::json($result);
	}

	public function postsend($url, $data = array()) {
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, "$url");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);  //定义超时3秒钟  
		// POST数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// POST参数
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		//执行并获取url地址的内容
		$output = curl_exec($ch);
		$errorCode = curl_errno($ch);
		//释放curl句柄
		curl_close($ch);
		if (0 !== $errorCode) {
			return false;
		}
		return $output;
	}

	public function quickBarJsonInit() {
		include_once '../public/QRcode.php';
		$Mobile = new PrintController('preview', 'mobile');
		$QuickBar = WebsiteConfig::where('cus_id', $Mobile->cus_id)->where('type', 2)->where('template_id', '0')->where('key', 'quickbar')->pluck('value');
		if ($QuickBar)
			$MobileQuickBar = unserialize($QuickBar);
		$DefaultQuickBar = [
			['pc' => 0, 'mobile' => 0, 'name' => '电话', 'icon' => '&#xe602;', 'image' => 'icon/2.png', 'data' => '18459276266', 'link' => 'tel://', 'type' => 'tel', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '短信', 'icon' => '&#xe604;', 'image' => 'icon/3.png', 'data' => '18459276267', 'link' => 'sms://', 'type' => 'sms', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '咨询', 'icon' => '&#xe606;', 'image' => 'icon/5.png', 'data' => '小E:156568451@QQ|客服-XX:10000@QQ|客服-YY:10000@53kf', 'type' => 'im', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '地图', 'icon' => '&#xe605;', 'image' => 'icon/4.png', 'data' => '', 'type' => 'map', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '分享', 'icon' => '&#xe600;', 'image' => 'icon/8.png', 'data' => 'qzone,tqq,tsina,ibaidu', 'type' => 'share', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '外链', 'icon' => '&#xe632;', 'image' => 'icon/8.png', 'data' => 'http://www.gbpen.com/', 'type' => 'link', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '搜索', 'icon' => '&#xe636;', 'image' => 'icon/8.png', 'data' => '', 'type' => 'search', 'enable_pc' => 1, 'enable_mobile' => 1],
			['pc' => 0, 'mobile' => 0, 'name' => '微信二维码', 'icon' => '&#xe61b;', 'image' => 'icon/a.png', 'data' => '', 'for' => 'vx_barcode', 'type' => 'follow', 'enable_pc' => 0, 'enable_mobile' => 0],
			//                ['pc'=>0,'mobile'=>0,'name'=>'qq二维码','icon'=>'&#xe60f;','image'=>'icon/a.png','data'=>'','for'=>'qq_barcode','type'=>'follow','enable_pc'=>0,'enable_mobile'=>0],
			//                ['pc'=>0,'mobile'=>0,'name'=>'PC二维码','icon'=>'&#xe630;','image'=>'icon/a.png','data'=>$this->qrcode('pc_domain'),'for'=>'pc_barcode','type'=>'follow','enable_pc'=>0,'enable_mobile'=>0],
			['pc' => 0, 'mobile' => 0, 'name' => '手机二维码', 'icon' => '&#xe64b;', 'image' => 'icon/a.png', 'data' => $this->qrcode('mobile_domain'), 'for' => 'm_barcode', 'type' => 'follow', 'enable_pc' => 0, 'enable_mobile' => 0],
		];
		if (!$QuickBar) {
			$QuickBar = ['err' => 0, 'msg' => '获取成功！', 'data' => $DefaultQuickBar];
		} else {
			$QuickBar = ['err' => 0, 'msg' => '获取成功！', 'data' => $MobileQuickBar];
		}
		return json_encode($QuickBar);
	}

	public function quickBarJsonModify() {
		$Mobile = new PrintController('preview', 'mobile');
                $org_img='';
                $vx_bar_img='';
                $data=Input::get('QuickBar');
		$QuickBar = serialize($data);
                foreach((array)$data as $v){
                    if($v['for']==='vx_barcode'){
                        $vx_bar_img=basename($v['data']);
                    }
                }
                $websiteconfig = WebsiteConfig::where('cus_id', $Mobile->cus_id)->where('type', 2)->where('template_id', '0')->where('key', 'quickbar')->pluck('value');
                foreach((array)  unserialize($websiteconfig) as $v){
                    if($v['for']==='vx_barcode'){
                        $org_img=  basename($v['data']);
                    }
                }
		$id = WebsiteConfig::where('cus_id', $Mobile->cus_id)->where('type', 2)->where('template_id', '0')->where('key', 'quickbar')->pluck('id');
		if ($id) {
			$QuickData = WebsiteConfig::find($id);
		} else {
			$QuickData = new WebsiteConfig();
			$QuickData->cus_id = $Mobile->cus_id;
			$QuickData->type = 2;
			$QuickData->template_id = 0;
			$QuickData->key = 'quickbar';
		}
		$QuickData->value = $QuickBar;
		$result = $QuickData->save();
		if ($result) {
                        if($org_img!=$vx_bar_img){
                            $imgdel=new ImgDel();
                            $imgdel->mysave($org_img,'common');
                        }
			$json_result = ['err' => 0, 'msg' => '保存成功'];
		} else {
			$json_result = ['err' => 1001, 'msg' => '该栏目存在文章，需转移才能创建子栏目', 'data' => []];
		}
		return Response::Json($json_result);
	}

	public function getqrcode() {
		$barcode = Input::get('barcode');
		$data = $this->qrcode($barcode);
		if ($data) {
			$json_result = ['err' => 0, 'msg' => '获取成功', 'data' => $data];
		} else {
			$json_result = ['err' => 1001, 'msg' => '不存在手机站', 'data' => []];
		}
		return Response::Json($json_result);
	}

	private function qrcode($barcode) {
		//        $qrencode=new QRencode();
		$customer = Auth::user()->name;
		$id = Auth::id();
		$customer_info = Customer::where('id', $id)->first();
		$domain = $customer_info->$barcode;
		if ($domain == "") {
			$customer_info = CustomerInfo::where('id', $id)->first();
			$domain = $customer_info->$barcode;
			return '';
		}
		//        $url= str_replace('http://', '', $domain);
		//        $errorCorrectionLevel = "L";
		//        $path='customers/'.$customer.'/images/l/common/';
		//        $outfile=public_path($path);
		//        $enc = $qrencode->factory($errorCorrectionLevel,6);
		//        $enc->encodePNG($url, $outfile.$barcode.".png", $saveandprint=false);
		//        return asset($path.$barcode.".png");
		return 'http://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . $domain;
	}

	public function quickBarRewrite() {
		$result = WebsiteConfig::where('cus_id', Auth::id())->where('key', 'quickbar')->delete();
		$json_result = ['err' => 0, 'msg' => '重置成功'];
		return $json_result;
	}

}

?>
