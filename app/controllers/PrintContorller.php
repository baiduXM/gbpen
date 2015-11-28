<?php
/**
 * 输出预览或用于生成静态时输出到缓冲区
 * @author 財財 530176577@qq.com
 * 创建时间 2015年3月26日
 *
 * @package 统一平台
 */

class PrintController extends BaseController{
    
    /**
     * 用户id
     * 
     * @var int
     */
    public $cus_id;
    
    /**
     * 用户名
     * 
     * @var string
     */
    public $customer;
    /**
     * 访问域名（结尾不带斜杠）
     * 
     * @var string
     */
    public $domain;
    /**
     * 客户端域名（结尾不带斜杠）
     * 
     * @var string
     */
    public static $cus_domain;
    /**
     * 显示类型（preview表示预览、否则表示在线）
     * 
     * @var string
     */
    public $showtpye;
    /**
     * 网站类型（mobile表示手机网站、否则表示PC站）
     * 
     * @var string
     */
    public $type;
    /**
     * 用于在线时的视图位置指定
     * 
     * @var string
     */
    public $site_url;
    /**
     * 当前模版id
     * 
     * @var int
     */
    public $tpl_id;
    /**
     * 当前模版名称
     * 
     * @var string
     */
    public $themename;
    /**
     * 图片目录路径
     * 
     * @var string
     */
    public $source_dir;
    /**
     * 定义用户id、用户名、
     * @param string $showtpye
     * @param string $type
     */
    
    
    function __construct($showtpye='preview',$type='pc'){
        $this->showtype = $showtpye;
        $this->type = $type;
        $this->cus_id = Auth::id();
        $this->customer = Auth::user()->name;
        if($this->showtype=='preview'){
                $this->source_dir = asset('customers/'.$this->customer.'/images/').'/';
            if($this->type=='mobile'){
                $this->domain = url().'/mobile';
                $this->tpl_id = WebsiteInfo::where('cus_id',$this->cus_id)->pluck('mobile_tpl_id');
                $this->themename = DB::table('template')->leftJoin('website_info','website_info.mobile_tpl_id','=','template.id')->where('website_info.cus_id','=',$this->cus_id)->pluck('template.name');
                $this->source_dir = asset('customers/'.$this->customer.'/mobile/images/').'/';
                self::$cus_domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_domain');        
            } 
            else{
                $this->domain = url();
                $this->tpl_id = WebsiteInfo::where('cus_id',$this->cus_id)->pluck('pc_tpl_id');
                $this->themename = DB::table('template')->leftJoin('website_info','website_info.pc_tpl_id','=','template.id')->where('website_info.cus_id','=',$this->cus_id)->pluck('template.name');
                self::$cus_domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
            }
            $this->site_url = url('/templates/'.$this->themename).'/';
        }
        else{
            if($this->type=='mobile'){
                $this->tpl_id = WebsiteInfo::where('cus_id',$this->cus_id)->pluck('mobile_tpl_id');
                $this->themename = DB::table('template')->leftJoin('website_info','website_info.mobile_tpl_id','=','template.id')->where('website_info.cus_id','=',$this->cus_id)->pluck('template.name');
                $this->domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_domain');  
            }
            else{
                $this->tpl_id = WebsiteInfo::where('cus_id',$this->cus_id)->pluck('pc_tpl_id');
                $this->themename = DB::table('template')->leftJoin('website_info','website_info.pc_tpl_id','=','template.id')->where('website_info.cus_id','=',$this->cus_id)->pluck('template.name');
                $this->domain = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
            }
            self::$cus_domain =$this->domain;
            $this->site_url = $this->domain.'/';
            $this->source_dir = $this->domain.'/images/';
        }
    }
    
    /**
     * 合并页面请求，合并json和数据库内容，返回合并后的数组
     *
     * @param string $themename 模版名称
     * @param string $pagename 页面名称
     * @return array 合并后的数组
     */
    public function pagedata($pagename){
        if($this->type=='pc'){ 
            $tpl_id = websiteInfo::where('id',$this->cus_id)->pluck('pc_tpl_id');
        }
        else{
            $tpl_id = websiteInfo::where('id',$this->cus_id)->pluck('mobile_tpl_id');
        }
        $website_confige = WebsiteConfig::where('cus_id',$this->cus_id)->where('key',$pagename)->where('type',1)->where('template_id',$tpl_id)->pluck('value');
        $website_confige_value = unserialize($website_confige);
        $json_path = public_path('templates/'.$this->themename.'/json/'.$pagename.'.json');
        $json = file_exists($json_path) ? file_get_contents($json_path) : '{}';
        if($website_confige_value){
            $default = json_decode(trim($json),TRUE);
            $result = $this->array_merge_recursive_new($default,$website_confige_value);
            //$result = $website_confige_value;
            $this->replaceUrl($result);
            $result=$this->dataDeal($result);
            foreach($result as &$v){
                if($v['type']=='list'){
                   if(isset($v['config']['filter'])){
                       if($v['config']['filter']=='list'){                
                            $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                       }else{
                           $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                       }       
                    }else{
                         $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                    }
                }elseif($v['type']=='navs'){
                    $v['config']['ids']=array_merge($v['config']['ids']);
                }
            }
        }
        else{
            $result = json_decode(trim($json),TRUE);
            if($result===NULL){
                dd("$pagename.json文件错误");
            }
            $this->replaceUrl($result);
             //dd($result);
            $result=$this->dataDeal($result);
            $classify = new Classify;
            $templates= new TemplatesController;
            $c_arr = Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3,4,5,6))->where('pc_show','=',1)->get()->toArray();
            foreach($result as &$v){
                if($v['type']=='list'){
                    if(isset($v['config']['mustchild']) && $v['config']['mustchild']==true){
                        if(isset($v['config']['filter'])){
                            if($v['config']['filter']=='page'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(4));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);
                            }elseif($v['config']['filter']=='list'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(1,2,3));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
			                }
				            /*20151021添加feeback filter*/
				            elseif($v['config']['filter']=='feedback'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(5));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr); 
                            }elseif($v['config']['filter']=='ALL'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(1,2,3,4,5,6));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr); 
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }else{
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(1,2,3,4));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);                            
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }
                        }else{
                            $c_arr=$classify->toTree($c_arr);
                            $templates->unsetFalseClassify($c_arr,array(1,2,3));
                            $templates->unsetLastClassify($c_arr);
                            $c_arr=array_merge($c_arr);                             
                            $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                        }
                        if(count($c_arr)){
                            $v['config']['id'] = $c_arr[0]['id'];
                        }
                    }else{
                        if(isset($v['config']['filter'])){
                            if($v['config']['filter']=='page'){
                                $v['config']['id']=Classify::where('cus_id',$this->cus_id)->where('type',4)->where('pc_show',1)->pluck('id');
                            }elseif($v['config']['filter']=='list'){
                                 $v['config']['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3))->where('pc_show',1)->pluck('id');
                                 $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
							}
							/*20151021添加feeback filter*/
							elseif($v['config']['filter']=='feedback'){
                                 $v['config']['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(5))->where('pc_show',1)->pluck('id'); 
                            }elseif($v['config']['filter']=='ALL'){
                                 $v['config']['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3,4,5,6))->where('pc_show',1)->pluck('id');  
                                 $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }else{
                                $v['config']['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3,4))->where('pc_show',1)->pluck('id');  
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }       
                        }else{
                            $v['config']['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3))->where('pc_show',1)->pluck('id');   
                            $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                        }                        
                    }
                }elseif($v['type']=='page'){
                    if(isset($v['config']['mustchild']) && $v['config']['mustchild']==true){
                        $c_arr=Classify::where('cus_id',$this->cus_id)->where('type',4)->where('pc_show',1)->get()->toArray();
                        $c_arr = $classify->toTree($c_arr);
                        $templates->unsetLastClassify($c_arr);
                        $c_arr=array_merge($c_arr);
                        if(count($c_arr)){
                           $v['config']['id'] = $c_arr[0]['id'];
                        }
                    }else{
                        $v['config']['id']=Classify::where('cus_id',$this->cus_id)->where('type',4)->where('pc_show',1)->pluck('id');
                    }

                }elseif($v['type']=='navs'){
                    if(isset($v['config']['mustchild']) && $v['config']['mustchild']==true){
                        if(isset($v['config']['filter'])){
                            if($v['config']['filter']=='page'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(4));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);
                            }elseif($v['config']['filter']=='list'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(1,2,3));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }elseif($v['config']['filter']=='ALL'){
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(1,2,3,4,6));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }else{
                                $c_arr=$classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr,array(1,2,3,4));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr=array_merge($c_arr);                               
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }
                        }else{
                            $c_arr=$classify->toTree($c_arr);
                            $templates->unsetFalseClassify($c_arr,array(1,2,3));
                            $templates->unsetLastClassify($c_arr);
                            $c_arr=array_merge($c_arr);                         
                            $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                        }  
                    }else{
                        if(isset($v['config']['filter'])){
                            if($v['config']['filter']=='page'){
                                $c_arr[0]['id']=Classify::where('cus_id',$this->cus_id)->where('type',4)->where('pc_show',1)->pluck('id');
                            }elseif($v['config']['filter']=='list'){
                                $c_arr[0]['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3))->where('pc_show',1)->pluck('id');
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }elseif($v['config']['filter']=='ALL'){
                                $c_arr[0]['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3,4,6))->where('pc_show',1)->pluck('id');  
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }else{
                                $c_arr[0]['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3,4))->where('pc_show',1)->pluck('id');  
                                $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                            }       
                        }else{
                            $c_arr[0]['id']=Classify::where('cus_id',$this->cus_id)->whereIn('type',array(1,2,3))->where('pc_show',1)->pluck('id');   
                            $v['config']['limit']=isset($v['config']['limit'])?$v['config']['limit']:20;
                        }                        
                    }
                    $ids="";
                    $num=$v['config']['limit'];
                    if(count($c_arr)){
                        $ids=array();
                        for($i=0;$i<$num;$i++){
                            $ids[$i]=$c_arr[0]['id'];
                        }
                    }
                    $v['config']['ids']=$ids;
                }
            }
        }
		
        return $result;
    }

    /**
     * 数据处理
     *
     * @param array $result 要补全路径的数组
     *
     *
     */
    public function dataDeal($data){
        if (!is_array($data)) dd('json格式错误！');
        $slimming = array();
        foreach ($data as $k => $v) {
            $checkDataK = true;
            foreach ($v as $dk => $vv)
                if (!preg_match('/^(value|type|description|config)$/',$dk)) $checkDataK = false;
            if (!$checkDataK || !array_key_exists('value', $v) || !array_key_exists('type', $v) || !array_key_exists('description', $v))
                dd('json文件中每个变量的元素必须包含【value、type、description】元素，可选【config】\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json');
                $slimming[$k] = $v;
            // PHP端数据填充与验证
            switch ($v['type']) {
                case 'text':
                    if (!is_string($slimming[$k]['value'])) dd('json文件中type为【text】格式的value值必须为【字符串】\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typetext');
                    break;
                case 'textarea':
                    if (!is_string($slimming[$k]['value'])) dd('json文件中type为【textarea】格式的value值必须为【字符串】\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typetextarea');
                    $slimming[$k] = preg_replace("/\r\n/", "<br />", $slimming[$k]);
                    $slimming[$k] = preg_replace("/\n/", "<br />", $slimming[$k]);
                    break;
                case 'image':
                    /*
                    if (!array_key_exists('title', $slimming[$k]['value']) || !array_key_exists('image', $slimming[$k]['value']) || !array_key_exists('link', $slimming[$k]['value'])) {
                        echo json_encode(array(
                    		'err' => 2001,
                    		'data' => null,
                    		'msg' => 'json文件中type为【image】格式的value值必须包含【title、image、link】元素，可选【description】\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typeimage'
                		));
                		exit;
                	}
                    
                    if (!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config']) || (!is_numeric($slimming[$k]['config']['width']) && !is_numeric($slimming[$k]['config']['height']))) {
                        $msg = 'json文件中type为【image】格式应配置【config】的【width】【height】配置项\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typeimage';
                        if (array_key_exists('config', $slimming[$k]) && is_array($slimming[$k]['config']) && array_key_exists('forcesize', $slimming[$k]['config']) && $slimming[$k]['config']['forcesize'] === true) {
                            dd($msg);
                        }else{
                            dd('Error: '.$msg);
                        }
                    }
                     
                     */
                    break;
                case 'images':
					if (!is_array($slimming[$k]) || !count($slimming[$k])) dd('json文件中type为【images】格式不正确！\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typeimages');
                    foreach ($slimming[$k]['value'] as $key => $val) {
						if (!array_key_exists('title', $val) || !array_key_exists('image', $val) || !array_key_exists('link', $val))
							dd('json文件中type为【images】格式value值的每个子元素必须包含【title、image、link】元素，可选【description】\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typeimages');
					}
                    /*
					if (!array_key_exists('config',$slimming[$k]) || !is_array($slimming[$k]['config']) || (!is_numeric($slimming[$k]['config']['width']) && !is_numeric($slimming[$k]['config']['height']))) {
						$msg = 'json文件中type为【images】格式应配置【config】的【width】【height】配置项\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:json#typeimages';
						if (array_key_exists('config', $slimming[$k]) && is_array($slimming[$k]['config']) && array_key_exists('forcesize', $slimming[$k]['config']) && $slimming[$k]['config']['forcesize'] === true) {
							dd($msg);
						}else{
							dd('Error: '.$msg);
						}
					}*/
					break;
                case 'page':
					if(!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config'])) $slimming[$k]['config'] = array();
					$slimming[$k]['config']['filter'] = "page";
					$slimming[$k]['type'] = "list";
                    
                case 'nav':
                    if(!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config'])) $slimming[$k]['config'] = array();					
					if(!array_key_exists('filter', $slimming[$k]['config']) || empty($slimming[$k]['config']['filter'])) $slimming[$k]['config']['filter'] = "ALL";
					$slimming[$k]['type'] = "list";
				case 'navs':
                    if(!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config'])) $slimming[$k]['config'] = array();
					if(!array_key_exists('filter', $slimming[$k]['config']) || empty($slimming[$k]['config']['filter'])) $slimming[$k]['config']['filter'] = "ALL";                    
                    $slimming[$k]['config']['limit'] = array_key_exists('limit', $slimming[$k]['config']) ? $slimming[$k]['config']['limit'] : 0;
                 
				case 'list':
					if(!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config'])) $slimming[$k]['config'] = array();
					if(!array_key_exists('filter', $slimming[$k]['config']) || empty($slimming[$k]['config']['filter'])) $slimming[$k]['config']['filter'] = "list";
					$slimming[$k]['config']['limit'] = array_key_exists('limit', $slimming[$k]['config']) ? $slimming[$k]['config']['limit'] : 20;
					$slimming[$k]['config']['star_only'] = array_key_exists('star_only', $slimming[$k]['config']) && $slimming[$k]['config']['star_only'] ? 1 : 0;
				 	break;
                case 'quickbar':
                    if ($this->type == 'pc') dd('PC模板的json文件中没有type为【quickbar】的变量！\r\n如果你现在制作的是手机模板，请修改config.ini文件对应参数。详情参见：http://pme.eexx.me/doku.php?id=ued:template:config#config_%E6%A8%A1%E6%9D%BF%E9%85%8D%E7%BD%AE%E9%83%A8%E5%88%86');
					if (!is_string($slimming[$k]) || !count($slimming[$k])) dd('json文件中type为【navs】格式不正确！\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:mindex#%E5%BA%95%E9%83%A8%E5%AF%BC%E8%88%AA%E5%AE%9A%E4%B9%89%E6%96%B9%E6%B3%95');
					foreach ($slimming[$k] as $i => $v) {
						if (!array_key_exists('name',$v) ||
							!array_key_exists('image',$v) ||
							!array_key_exists('data',$v) ||
							!array_key_exists('type',$v) ||
							!array_key_exists('enable',$v)
						) dd('json文件中type为【navs】格式value值的每个子元素必须包【name、image、data、type、enable】元素，可选【childmenu】\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:mindex#%E5%BA%95%E9%83%A8%E5%AF%BC%E8%88%AA%E5%AE%9A%E4%B9%89%E6%96%B9%E6%B3%95');
						if (!preg_match("/^(tel|sms|im|link|share)$/",$v['type']))
							dd('json文件中type为【navs】格式value值的子元素的【type】值只能为【tel、sms、im、link、share】其中之一\r\n详情参见：http://pme/wiki/doku.php?id=ued:template:mindex#%E5%BA%95%E9%83%A8%E5%AF%BC%E8%88%AA%E5%AE%9A%E4%B9%89%E6%96%B9%E6%B3%95');
						if (!array_key_exists('enable',$v)) {
							unset($slimming[$k][$i]);
						}
						switch ($v['type']) {
							case 'tel':
								$slimming[$k]['link'] = 'tel://'.$v['data'];
								break;
							case 'sms':
								$slimming[$k]['link'] = 'sms://'.$v['data'];
								break;
							case 'share':
							case 'im':
								$slimming[$k]['link'] = 'javascript:void(0);';
								break;
							case 'link':
								if (!array_key_exists('childmenu',$v) && !count($v['childmenu'])) {
									$slimming[$k]['link'] = 'javascript:void(0);';
									foreach ($slimming[$k]['childmenu'] as $kk => $vv) {
										if (!array_key_exists('enable',$v['childmenu'])) {
											unset($slimming[$k]['childmenu'][$i]);
										}
										$slimming[$k]['childmenu']['link'] = $vv['data'];
									}
								}else{
									$slimming[$k]['link'] = $v['data'];
								}
								break;
							default:
								$slimming[$k]['link'] = $v['data'];
						}
					}
				 	break;
            }
        }
        return $slimming;
    }
    
    
    /**
     * 递归替换数组中的相对位置url添加加域名
     *
     * @param array $result 要补全路径的数组
     *
     *
     */
    public function replaceUrl(&$result){
        foreach($result as $k => $v){
            if(is_array($v)){
                $this->replaceUrl($result[$k]);
            }
            else{
                if(($k==='link' || $k==='image') && !strstr($v,'http://') && !strstr($v,'https://') && $v!=""){
                    if($k==='link'){
                        $result[$k] = $this->domain.$v;
                    }else{             
                        if(file_exists(public_path("customers/".$this->customer.'/images/')."l/page_index/".$v)){
                            $result[$k] = $this->source_dir."l/page_index/".$v;
                        }else{
                            $result[$k] = $this->site_url.'images/'.ltrim($v,'images/');
                        }
                    }
                }
            }
        }
    }

    /**
     * 手机底部功能条
     */
    public function quickBarJson(){
        $config_str=file_get_contents(public_path('/templates/'.$this->themename).'/config.ini');
        $search="/QuickBar=(.*)/i";
        $result=preg_match($search,$config_str,$config_arr);
        dd($config_arr);
        if($result!=0){
            if ($config_arr[1] != 'custom') {
                $quickbar_arr=explode('|',$config_arr[1]);
                $tmpStyleConfigQuickbar = explode(',',$quickbar_arr[0]);  
                //config
                $config['enable']=true;
                $config['type']=1;
                $config['style']=array();
               if(count($tmpStyleConfigQuickbar)){
                   $keys=array('barColor','navtopColor','textColor','iconColor');
                   foreach($tmpStyleConfigQuickbar as $key=>$val){
                       $arr=explode('|',$val);
                       $config['style'][$keys[$key]]=$arr[0];
                   }
                   if(!key_exists('iconColor',$config['style'])){
                       $config['style']['iconColor']=$config['style']['textColor'];
                   }
               }
                
                //quickbar按钮
                $global_data=WebsiteConfig::where('cus_id',$this->cus_id)->where('type',2)->where('template_id',$this->tpl_id)->pluck('value');
                if($global_data){
                    $global_data = unserialize($global_data);
                    $global_data=$this->detailList($global_data);
                }else{
                    $global_data=$this->mobilePageList('global',true);
                    $global_data=$this->detailList($global_data);
                }
                $this->replaceUrl($global_data);
                $quickbar="";
                if(isset($global_data['bottomnavs']) && is_array($global_data['bottomnavs'])){
                    foreach($global_data['bottomnavs'] as &$val){
                        $val['id']=isset($val['id'])?$val['id']:'';                 
                        switch($val['type']){
                            case "tel" :
                                $val['icon']=isset($val['icon'])?$val['icon']:'&#xe602;';
                                $val['link']="tel:".$val['data'];
                                break;
                            case "sms" :
                                $val['icon']=isset($val['icon'])?$val['icon']:'&#xe604;';
                                $val['link']="sms:".$val['data'];
                                break;
                            case "im"  :
                                $val['icon']=isset($val['icon'])?$val['icon']:'&#xe606;';
                                $val['link']=$val['data'];
                                $val['enable']=0;
                                break;
                            case "share" :
                                $val['icon']=isset($val['icon'])?$val['icon']:'&#xe600;';
                                $val['link']='javascript:void(0);';
                                break;
                            case "link" :                 
                                $val['icon']=isset($val['icon'])?$val['icon']:'&#xe605;';
                                $address=CustomerInfo::where('cus_id',$this->cus_id)->pluck('address');
                                $val['link']='http://map.baidu.com/mobile/webapp/search/search/qt=s&wd='.$address.'/vt=map/?fromhash=1';
                                break;
                            }
                    }
                    $quickbar=$global_data['bottomnavs'];
                }else{
                    $quickbar=[
                        ['name'=>'电话','icon'=>'&#xe602;','image'=>'icon/2.png','data'=>'','link'=>'tel://','type'=>'tel','enable'=>1],
                        ['name'=>'短信','icon'=>'&#xe604;','image'=>'icon/3.png','data'=>'','link'=>'sms://','type'=>'sms','enable'=>1],
                        ['name'=>'咨询','icon'=>'&#xe606;','image'=>'icon/5.png','data'=>'10000@QQ','link'=>'javascript:void(0);','type'=>'im','enable'=>0],
                        ['name'=>'地图','icon'=>'&#xe605;','image'=>'icon/4.png','data'=>'','link'=>'http://map.baidu.com','type'=>'link','enable'=>1],
                        ['name'=>'分享','icon'=>'&#xe600;','image'=>'icon/8.png','data'=>'','link'=>'javascript:void(0);','type'=>'share','enable'=>1],
                        ['name'=>'搜索','icon'=>'&#xe636;','image'=>'icon/8.png','data'=>'','link'=>'javascript:void(0);','type'=>'search','enable'=>0],
                    ];  
                }
                //快捷导航
                $navs = Classify::where('cus_id',$this->cus_id)->where('mobile_show',1)->select('id','type','name','en_name','icon','url','p_id','en_name')->OrderBy('sort','asc')->get()->toArray();
                if(count($navs)){
                    if($this->showtype=='preview'){
                        foreach($navs as &$nav){
                            $nav['icon']='<i class="iconfont">'.$nav['icon'].'</i>';
                            if(in_array($nav['type'],array('1','2','3','4'))){
                                $nav['url']=$this->domain."/category/".$nav['id'];
                            }
                        }  
                    }else{
                        foreach($navs as &$nav){
                            $nav['icon']='<i class="iconfont">'.$nav['icon'].'</i>';
                            if(in_array($nav['type'],array('1','2','3','4'))){
                                $nav['url']=$this->domain."/category/".$nav['id'].'.html';
                            }
                        }  
                    }
                }
                $classify=new Classify();
                $catlist=$classify->toTree($navs);
                array_unshift($catlist,array('id'=>null,'name'=>'首页','en_name'=>'Home','url'=>$this->site_url,'childmenu'=>null));
                $quickbarCallback=array('config'=>$config,'quickbar'=>$quickbar,'catlist'=>$catlist);
                if($this->showtype=='preview'){
                    echo "quickbarCallback(".json_encode($quickbarCallback).")";
                }else{
                    file_put_contents(public_path("customers/".$this->customer.'/quickbar.json'),"quickbarCallback(".json_encode($quickbarCallback).")");
                }
            }
        }
    }
    
    
    /**
     * 根据栏目id获取页面的公共数据，包括logo、path、stylecolor、navs、logo、footprint等
     *
     * @param int $c_id 栏目id,只为用于导航navs的状态
     * @return array 返回一个包含公共数据的数组
     */
    private function pagePublic($c_id=0){
        if($this->type=='pc'){
            $navs = Classify::where('cus_id',$this->cus_id)->where('pc_show',1)->whereIN('type',[1,2,3,4,6])->select('id','type','img','icon','name','url','p_id','en_name','meta_description as description')->OrderBy('sort','asc')->get()->toArray();
        }else{
            $navs = Classify::where('cus_id',$this->cus_id)->where('mobile_show',1)->select('id','type','img','icon','name','url','p_id','en_name','meta_description as description')->OrderBy('sort','asc')->get()->toArray();
        }
        $navs=$this->toTree($navs,0,TRUE);
        
        if($c_id){
            $current_arr=$this->currentCidArray($c_id);
            $navs= $this->addCurrent($navs,$current_arr);
        }
        $customer_info = CustomerInfo::where('cus_id',$this->cus_id)->first();
        if($this->type=='pc'){
            $stylecolor = websiteInfo::leftJoin('color', 'color.id', '=', 'website_info.pc_color_id')->where('cus_id',$this->cus_id)->pluck('color_en');
            $logo = $this->showtype=='preview' ? asset('customers/'.$this->customer.'/images/l/common/'.$customer_info->logo) : $this->domain.'/images/l/common/'.$customer_info->logo; 
            $headscript=$customer_info->pc_header_script;
            $footprint=$customer_info->footer.'<p>技术支持：<a href="http://www.12t.cn/">厦门易尔通网络科技有限公司</a> 人才支持：<a href="http://www.xgzrc.com/">厦门人才网</a></p>';
            $footscript=$customer_info->pc_footer_script;
            $footscript .= '<script type="text/javascript" src="http://chanpin.xm12t.com.cn/js/quickbar-1.js"></script>'; 
            $site_another_url=$this->showtype=='preview' ?'':$customer_info->mobile_domain;
        }else{
            $logo = $this->showtype=='preview' ? asset('customers/'.$this->customer.'/images/l/common/'.$customer_info->logo_small) : $this->domain.'/images/l/common/'.$customer_info->logo_small; 
            $stylecolor = websiteInfo::leftJoin('color', 'color.id', '=', 'website_info.mobile_color_id')->where('cus_id',$this->cus_id)->pluck('color_en');
            $headscript=$customer_info->mobile_header_script;
            $footprint=$customer_info->mobile_footer;
            $footscript=$customer_info->mobile_footer_script;
            $footscript .= '<script type="text/javascript" src="http://chanpin.xm12t.com.cn/js/quickbar.js?'.$this->cus_id.'"></script>';  
            $site_another_url=$this->showtype=='preview' ?'':$customer_info->pc_domain;
            $config_arr=parse_ini_file(public_path('/templates/'.$this->themename).'/config.ini',true);
            if(!is_array($config_arr)) dd('【config.ini】文件不存在！文件格式说明详见：http://pme/wiki/doku.php?id=ued:template:config');
        }
        //获取global信息
        if($this->type=='pc'){
            $global_data=$this->pagedata('global');
            $global_data=$this->detailList($global_data);
        }else{
            $global_data=WebsiteConfig::where('cus_id',$this->cus_id)->where('type',2)->where('template_id',$this->tpl_id)->pluck('value');
            if($global_data){
                $global_data = unserialize($global_data);
                $global_data=$this->detailList($global_data);
            }else{
                $global_data=$this->mobilePageList('global',true);
                $global_data=$this->detailList($global_data);
            }
            $this->replaceUrl($global_data);
            if(isset($global_data['bottomnavs']) && is_array($global_data['bottomnavs'])){
                foreach($global_data['bottomnavs'] as &$val){
                    $val['id']=isset($val['id'])?$val['id']:'';
                    $val['icon']=isset($val['icon'])?$val['icon']:'';
                    switch($val['type']){
                        case "tel" :
                            $val['link']="tel:".$val['data'];
                            break;
                        case "sms" :
                            $val['link']="sms:".$val['data'];
                            break;
                        case "im"  :
                            $val['link']=$val['data'];
                            break;
                        case "share" :
                            $val['link']='javascript:void(0);';
                            break;
                        case "link" :
                            if(isset($val['childmenu']) && count($val['childmenu']) > 0){
                                $val['link'] = 'javascript:void(0);';
                                foreach($val['childmenu'] as &$menu) {
                                    $menu['link'] = $menu['data'];
                                }
                            }else{
                                    $val['link'] = $val['data'];
                            }
                            break;
                        }
                }
            }
        }
        $contact= CustomerInfo::where('cus_id',$this->cus_id)->select('company','contact_name as name','mobile','telephone','fax','email as mail','qq','address')->first()->toArray();
        $pc_domain=CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_domain');
        if(!empty($pc_domain)){
            $domain_arr=parse_url($pc_domain);
            $pc_domain=$domain_arr['host'];     
            $pc_domain="http://wwvv.".ltrim($pc_domain,'www.');
        }
        $result = [
            'stylecolor'=>$stylecolor,
            'navs'=>$navs,
            'favicon' => rtrim($this->source_dir,'images/').'/images/l/common/'.$customer_info->favicon,
            'logo'=>$logo,
            'headscript'=>$headscript,
            'footprint'=>$footprint,
            'footscript'=>$footscript,
            'global'=>$global_data,
            'site_url'=>$this->site_url,
            'site_another_url'=>$site_another_url,
            'contact'=>$contact,
            'search_action'=>$pc_domain.'/search.html' //'http://swap.gbpen.com'
        ];
        
        return $result;
    }
    
    /**
     * 获取首页数据，包括logo、path、stylecolor、navs、logo、footprint等
     *
     * @param array $data 合并后的数据
     * @return array 返回一个包含公共数据的数组
     */
 private function detailList($data){
        $index = [];
        $list = [];
        if($data==NULL){
            return $index;
        }
        foreach($data as $k=>$v){
            if($v['type']=='list'){
                if(isset($v['config']['id']) && is_numeric($v['config']['id']) && $v['config']['id'] > 0){
                    $c_info=Classify::where('id',$v['config']['id'])->where('cus_id',$this->cus_id)->where('pc_show',1)->first();
                    $cids = explode(',',$this->getChirldenCid($v['config']['id']));//取得所有栏目id
                }else{
                    $c_info=false;
                    $cids=false;
                }
                if(isset($v['config']['filter'])){
                    if($v['config']['filter']=='list'){           
                       if(isset($v['config']['star_only'])&&$v['config']['star_only']){
                           if($cids){
                               $articles = Articles::whereIn('c_id',$cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->where('is_star','1')->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                           }else{
                               $articles = Articles::where('pc_show','1')->where('cus_id',$this->cus_id)->where('is_star','1')->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                           }   
                        }
                       else{
                           if($cids){
                               $articles = Articles::whereIn('c_id',$cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                           }else{
                               $articles = Articles::where('pc_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get(); 
                           }
                       }
                       if($articles->count()!=0){
                           $abc=[];
                           foreach($articles as $key=>$d){
                               if($d->title_bold==0 && $d->title_color=='rgb(0, 0, 0)'){
                                   $abc['data'][$key]['title'] = $d->title;
                               }else{
                                   $bold=($d->title_bold==1)?'font-weight:bold;':'font-weight:normal;';
                                   $font_color=($d->title_color!='rgb(0, 0, 0)')?'color:'.$d->title_color.';':'';
                                   $abc['data'][$key]['title'] = '<strong style="'.$bold.$font_color.'">'.$d->title.'</strong>';
                               }
                               $d_c_info = Classify::where('id',$d->c_id)->first();
                               $abc['data'][$key]['image'] = $this->source_dir.'l/articles/'.$d->img;
                               if($this->showtype=='preview'){
                                   $abc['data'][$key]['category']['link'] = $this->domain.'/category/'.$d->c_id;
                                   $abc['data'][$key]['link'] = $this->domain.'/detail/'.$d->id;
                               }
                               else{
                                   $abc['data'][$key]['category']['link'] = $this->domain.'/category/'.$d->c_id.'.html';
                                   $abc['data'][$key]['link'] = $this->domain.'/detail/'.$d->id.'.html';
                               }
                               $abc['data'][$key]['category']['name'] = $d_c_info->name;
                               $abc['data'][$key]['category']['en_name'] = $d_c_info->en_name;
                               $abc['data'][$key]['category']['icon'] = '<i class="iconfont">'.$d_c_info->icon.'</i>';
                               $abc['data'][$key]['description'] = $d->introduction;
                               $abc['data'][$key]['pubdate'] = $d->created_at; 
                               $abc['data'][$key]['pubtimestamp'] = strtotime($d->created_at);
                               unset($v['value']);
                           }
                           $v['value']=$abc;
                       }else{
                           $v['value']=array('data'=>null);
                       }
                    }elseif($v['config']['filter']=='page'){             
                        unset($v['value']);
                        $v['value']['content']=($c_info?$c_info->meta_description:'');
                    }
                }else{              
                    if(isset($v['config']['star_only'])&&$v['config']['star_only']){
                           if($cids){
                               $articles = Articles::whereIn('c_id',$cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->where('is_star','1')->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                           }else{
                               $articles = Articles::where('pc_show','1')->where('cus_id',$this->cus_id)->where('is_star','1')->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                           }   
                        }
                       else{
                           if($cids){
                               $articles = Articles::whereIn('c_id',$cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                           }else{
                               $articles = Articles::where('pc_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get(); 
                           }
                       }
                    if($articles->count()!=0){
                        $abc=[];
                        foreach($articles as $key=>$d){
                            if($d->title_bold==0 && $d->title_color=='rgb(0, 0, 0)'){
                                $abc['data'][$key]['title'] = $d->title;
                            }else{
                                $bold=($d->title_bold==1)?'font-weight:bold;':'font-weight:normal;';
                                $font_color=($d->title_color!='rgb(0, 0, 0)')?'color:'.$d->title_color.';':'';
                                $abc['data'][$key]['title'] = '<strong style="'.$bold.$font_color.'">'.$d->title.'</strong>';
                            }
                            $d_c_info = Classify::where('id',$d->c_id)->first();
                            $abc['data'][$key]['image'] = $this->source_dir.'l/articles/'.$d->img;
                            if($this->showtype=='preview'){
                                $abc['data'][$key]['category']['link'] = $this->domain.'/category/'.$d->c_id;
                                $abc['data'][$key]['link'] = $this->domain.'/detail/'.$d->id;
                            }else{
                                $abc['data'][$key]['category']['link'] = $this->domain.'/category/'.$d->c_id.'.html';
                                $abc['data'][$key]['link'] = $this->domain.'/detail/'.$d->id.'.html';
                            }
                            $abc['data'][$key]['category']['name'] = $d_c_info->name;
                            $abc['data'][$key]['category']['en_name'] = $d_c_info->en_name;
                            $abc['data'][$key]['category']['icon'] = '<i class="iconfont">'.$d_c_info->icon.'</i>';
                            $abc['data'][$key]['description'] = $d->introduction;
                            $abc['data'][$key]['pubdate'] = $d->created_at;
                            $abc['data'][$key]['pubtimestamp'] = strtotime($d->created_at);
                            unset($v['value']);
                        }
                        $v['value']=$abc;
                    }else{
                       $v['value']=array('data'=>null);
                    } 
                }
                $v['value']['name']=($c_info?$c_info->name:'');
                $v['value']['en_name']=($c_info?$c_info->en_name:'');
                $v['value']['icon']=($c_info?'<i class="iconfont">'.$c_info->icon.'</i>':'');
                $v['value']['link']='';
                if($this->showtype=='preview'){
                    $v['value']['image']=($c_info?$this->source_dir.'l/category/'.$c_info->img:'');
                    $v['value']['link']=($c_info?$this->domain.'/category/'.$c_info->id:'');
                }else{
                    $v['value']['image']=($c_info?$this->domain.'/images/l/category/'.$c_info->img:'');
                    $v['value']['link']=($c_info?$this->domain.'/category/'.$c_info->id.'.html':'');
                }
                $v['value']['description']=($c_info?$c_info->meta_description:'');
                $v['value']['type']=($c_info?$c_info->type:'');
                $childrenMenu=array();
                if($cids){
                    foreach($cids as $cid){
                        $c_c_info=Classify::where('id',$cid)->where('cus_id',$this->cus_id)->where('pc_show',1)->select('id','name','en_name','img as image','icon','meta_description as description','p_id')->first();                        
                        if($c_c_info){
                            $c_c_info=$c_c_info->toArray();
                            if($this->showtype=='preview'){
                                $c_c_info['image']=($c_c_info?$this->source_dir.'l/category/'.$c_c_info['image']:'');
                                $c_c_info['link']=($c_c_info?$this->domain.'/category/'.$c_c_info['id']:'');
                            }else{
                                $c_c_info['image']=($c_c_info?$this->domain.'/images/l/category/'.$c_c_info['image']:'');
                                $c_c_info['link']=($c_c_info?$this->domain.'/category/'.$c_c_info['id'].'.html':'');
                            }
                            $c_c_info['icon']=($c_c_info?'<i class="iconfont">'.$c_c_info['icon'].'</i>':'');
                            $c_c_info['current']=0;
                            $c_c_info['selected']=0;
                            $c_cids = explode(',',$this->getChirldenCid($cid));//取得所有栏目id
                            if(isset($v['config']['star_only'])&&$v['config']['star_only']){
                                $articles = Articles::whereIn('c_id',$c_cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->where('is_star','1')->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                            }else{
                                $articles = Articles::whereIn('c_id',$c_cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at','title_bold','title_color')->take($v['config']['limit'])->get();
                            }
                            if($articles->count()!=0){
                                $abc=[];
                                foreach($articles as $key=>$d){
                                    if($d->title_bold==0 && $d->title_color=='rgb(0, 0, 0)'){
                                        $abc[$key]['title'] = $d->title;
                                    }else{
                                        $bold=($d->title_bold==1)?'font-weight:bold;':'font-weight:normal;';
                                        $font_color=($d->title_color!='rgb(0, 0, 0)')?'color:'.$d->title_color.';':'';
                                        $abc[$key]['title'] = '<strong style="'.$bold.$font_color.'">'.$d->title.'</strong>';
                                    }
                                    $d_c_info = Classify::where('id',$d->c_id)->first();
                                    $abc[$key]['image'] = $this->source_dir.'l/articles/'.$d->img;
                                    if($this->showtype=='preview'){
                                        $abc[$key]['category']['link'] = $this->domain.'/category/'.$d->c_id;
                                        $abc[$key]['link'] = $this->domain.'/detail/'.$d->id;
                                    }else{
                                        $abc[$key]['category']['link'] = $this->domain.'/category/'.$d->c_id.'.html';
                                        $abc[$key]['link'] = $this->domain.'/detail/'.$d->id.'.html';
                                    }
                                    $abc[$key]['category']['name'] = $d_c_info->name;
                                    $abc[$key]['category']['en_name'] = $d_c_info->en_name;
                                    $abc[$key]['category']['icon'] = '<i class="iconfont">'.$d_c_info->icon.'</i>';
                                    $abc[$key]['description'] = $d->introduction;
                                    $abc[$key]['pubdate'] = $d->created_at;
                                    $abc[$key]['pubtimestamp'] = strtotime($d->created_at);
                                }
                                $c_c_info['data']=$abc;
                            }else{
                                $c_c_info['data']=[];
                            } 
                        }
                        $childrenMenu[]=$c_c_info;
                    }
                }
                $classify= new Classify();
                $v['value']['current']=0;
                $v['value']['selected']=0;
                $v['value']['id']=isset($v['config']['id'])?$v['config']['id']:NULL;
                $v['value']['childmenu']=isset($v['config']['id'])?$classify->toTree($childrenMenu,$v['config']['id']):NULL;
            }elseif($v['type']=='page'){
                if(isset($v['config']['id'])){
                    $c_info=Classify::where('id',$v['config']['id'])->where('cus_id',$this->cus_id)->where('pc_show',1)->first();
                    $cids = explode(',',$this->getChirldenCid($v['config']['id']));//取得所有栏目id
                }else{
                    $c_info=false;
                    $cids=false;
                }
                if($c_info){         
                    unset($v['value']);
                    $v['value']['content']=$c_info->meta_description;
                    $v['value']['name']=$c_info->name;
                    $v['value']['en_name']=$c_info->en_name;
                    $v['value']['icon']='<i class="iconfont">'.$c_info->icon.'</i>';
                    $v['value']['link']='';
                    if($this->showtype=='preview'){
                        $v['value']['image']=$this->source_dir.'l/category/'.$c_info->img;
                        $v['value']['link']=$this->domain.'/category/'.$c_info->id;
                    }else{
                        $v['value']['image']=$this->domain.'/images/l/category/'.$c_info->img;
                        $v['value']['link']=$this->domain.'/category/'.$c_info->id.'.html';
                    }
                    $v['value']['description']=$c_info->meta_description;
                    $v['value']['type']=$c_info->type;
                }
                $childrenMenu=array();
                if($cids){
                    foreach($cids as $cid){
                        $c_c_info=Classify::where('id',$cid)->where('cus_id',$this->cus_id)->where('pc_show',1)->select('id','name','en_name','img as image','icon','meta_description as description','p_id')->first();
                         if($c_c_info){
                            $c_c_info=$c_c_info->toArray();
                            if($this->showtype=='preview'){
                                $c_c_info['image']=($c_c_info?$this->source_dir.'l/category/'.$c_c_info['image']:'');
                                $c_c_info['link']=($c_c_info?$this->domain.'/category/'.$c_c_info['id']:'');
                            }else{
                                $c_c_info['image']=($c_c_info?$this->domain.'/images/l/category/'.$c_c_info['image']:'');
                                $c_c_info['link']=($c_c_info?$this->domain.'/category/'.$c_c_info['id'].'.html':'');
                            }
                            $c_c_info['current']=0;
                            $c_c_info['selected']=0;
                        }               
                        $childrenMenu[]=$c_c_info;
                    }
                }
                $classify= new Classify();
                $v['value']['current']=0;
                $v['value']['selected']=0;
                $v['value']['id']=$v['config']['id'];
                $v['value']['childmenu']=$classify->toTree($childrenMenu,$v['config']['id']);
            }elseif($v['type']=='navs'){
                if(isset($v['config']['ids']) && count($v['config']['ids'])){
                    unset($v['value']);
                    $i=0;
                    foreach($v['config']['ids'] as $id){
                        $c_info=Classify::where('id',$id)->where('cus_id',$this->cus_id)->where('pc_show',1)->first();
                        $v['value'][$i]['name']=$c_info?$c_info->name:'';
                        $v['value'][$i]['en_name']=$c_info?$c_info->en_name:'';
                        $v['value'][$i]['icon']=$c_info?'<i class="iconfont">'.$c_info->icon.'</i>':'';
                        if($this->showtype=='preview'){
                            $v['value'][$i]['image']=$c_info?$this->source_dir.'l/category/'.$c_info->img:'';
                            $v['value'][$i]['link']=$c_info?$this->domain.'/category/'.$c_info->id:'';
                        }else{
                            $v['value'][$i]['image']=$c_info?$this->domain.'/images/l/category/'.$c_info->img:'';
                            $v['value'][$i]['link']=$c_info?$this->domain.'/category/'.$c_info->id.'.html':'';
                        }
                        $v['value'][$i]['current']=0;
                        $v['value'][$i]['selected']=0;
                        $v['value'][$i]['id']=$id;
                        $i++; 
                    }
                }
            }           
            $index[$k] = $v['value'];
        }

        return $index;
    }
    
    /**
     * 获取栏目列表页对应分页的列表
     *
     * @param int $id 栏目列表id
     * @param int $page 当前所在页码
     * @return array 返回一个包含公共数据的数组
     */
    private function pageList($id,$page){
        $list = [];
        $cids = explode(',',$this->getChirldenCid($id));//取得所有栏目id
        if($this->type=='mobile'){
            $page_number = CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_page_count');//每页显示个数
            $links_count = CustomerInfo::where('cus_id',$this->cus_id)->pluck('mobile_page_links');//分页链接显示个数
            $offset = ($page-1)*$page_number;
            $total = Articles::whereIn('c_id',$cids)->where('mobile_show','1')->select('id','title','img','introduction','created_at','title_bold','title_color')->count();
            $list = Articles::whereIn('c_id',$cids)->where('mobile_show','1')->orderBy('is_top','desc')->orderBy('created_at','desc')->select('id','title','img','introduction','created_at','title_bold','title_color','c_id')->skip($offset)->take($page_number)->get();
        }
        else{
            $page_number = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_count');//每页显示个数
            $links_count = CustomerInfo::where('cus_id',$this->cus_id)->pluck('pc_page_links');//分页链接显示个数
            $offset = ($page-1)*$page_number;
            $total = Articles::whereIn('c_id',$cids)->where('pc_show','1')->select('id','title','img','introduction','created_at','title_bold','title_color')->count();
            $list = Articles::whereIn('c_id',$cids)->where('pc_show','1')->orderBy('is_top','desc')->orderBy('created_at','desc')->select('id','title','img','introduction','created_at','title_bold','title_color','c_id')->skip($offset)->take($page_number)->get();
        }
        $page_count = ceil($total/$page_number);
        $article = [];
        if($total){
            if($this->showtype=='preview'){
                $links = $this->pageLinks($id,$page,$page_count,$links_count);
                foreach($list as $key=>$d){
                    $a_c_info = Classify::where('id',$d->c_id)->first();
                    $article[$key]['category']['link'] = $this->domain.'/category/'.$d->c_id;
                    $article[$key]['category']['name'] = $a_c_info->name;
                    $article[$key]['category']['en_name'] = $a_c_info->en_name;
                    $article[$key]['category']['icon'] = '<i class="iconfont">'.$a_c_info->icon.'</i>';
                    if($d->title_bold==0 && $d->title_color=='rgb(0, 0, 0)'){
                        $article[$key]['title'] = $d->title;
                    }else{
                        $bold=($d->title_bold==1)?'font-weight:bold;':'font-weight:normal;';
                        $font_color=($d->title_color!='rgb(0, 0, 0)')?'color:'.$d->title_color.';':'';
                        $article[$key]['title'] = '<strong style="'.$bold.$font_color.'">'.$d->title.'</strong>';
                    }
                    $article[$key]['image'] = $this->source_dir.'s/articles/'.$d->img;
                    $article[$key]['link'] = $this->domain.'/detail/'.$d->id;
                    $article[$key]['description'] = $d->introduction;
                    $article[$key]['pubdate'] = $d->created_at;
                    $article[$key]['pubtimestamp'] = strtotime($d->created_at);
                }
            }
            else{
                $links = $this->pageLinks($id,$page,$page_count,$links_count);
                foreach($list as $key=>$d){
                    $a_c_info = Classify::where('id',$d->c_id)->first();
                    $article[$key]['category']['link'] = $this->domain.'/category/'.$d->c_id.'.html';
                    $article[$key]['category']['name'] = $a_c_info->name;
                    $article[$key]['category']['en_name'] = $a_c_info->en_name;
                    $article[$key]['category']['icon'] = '<i class="iconfont">'.$a_c_info->icon.'</i>';
                    if($d->title_bold==0 && $d->title_color=='rgb(0, 0, 0)'){
                        $article[$key]['title'] = $d->title;
                    }else{
                        $bold=($d->title_bold==1)?'font-weight:bold;':'font-weight:normal;';
                        $font_color=($d->title_color!='rgb(0, 0, 0)')?'color:'.$d->title_color.';':'';
                        $article[$key]['title'] = '<strong style="'.$bold.$font_color.'">'.$d->title.'</strong>';
                    }
                    $article[$key]['image'] = $this->source_dir.'s/articles/'.$d->img;
                    $article[$key]['link'] = $this->domain.'/detail/'.$d->id.'.html';
                    $article[$key]['description'] = $d->introduction;
                    $article[$key]['pubdate'] = $d->created_at;
                    $article[$key]['pubtimestamp'] = strtotime($d->created_at);
                }
    
            }
            if(!empty($links)){
                $page_links = [
                    'page_count'=> $page_count,
                    'per_page'=>$page_number,
                    'first_link'=>$links['first'],
                    'current_page' => $page,
                    'prev_link'=>$links['prev'],
                    'next_link'=>$links['next'],
                    'last_link'=>$links['last'],
                ];
                unset($links['first']);
                unset($links['prev']);
                unset($links['next']);
                unset($links['last']);
                $page_links['nears_link'] = $links;
            }
            else{
                $page_links = [                 
                    'page_count'=> 1,
                    'per_page'=>1,
                    'first_link'=>'javascript:;',
                    'current_page' => 1,
                    'prev_link'=>'javascript:;',
                    'next_link'=>'javascript:;',
                    'last_link'=>'javascript:;',
                ];
                $page_links['nears_link'] = [1=>1];
            }
            $result =['page_links'=>$page_links,'data'=>$article];
        }
        else{
             $page_links = [
                    'page_count'=> 1,
                    'per_page'=>1,
                    'first_link'=>'javascript:;',
                    'current_page' => 1,
                    'prev_link'=>'javascript:;',
                    'next_link'=>'javascript:;',
                    'last_link'=>'javascript:;',
                ];
            $page_links['nears_link'] = [1=>1];
            $result =['page_links'=>$page_links,'data'=>[]];
        }
        return $result;
    }
    
    /**
     * 获取分页导航相应的页面链接(预览)
     *
     * @param int $id 栏目id
     * @param int $page 当前页
     * @param int $pageCount 总页数
     * @param int $showPageNum 显示页码个数
     * @return array  返回分页的对应链接
     */
    public function pageLinks($id,$page,$pageCount,$showPageNum){
        if($pageCount==1){
            return $links=[];
        }
        if ($page<=1){
            $links['first'] = "";
            $links['prev'] = "";
        }else{
            $links['first']= $this->showtype=='preview' ? $this->domain.'/category/'.$id : $this->domain.'/category/'.$id.'.html';
            $links['prev']= $this->showtype=='preview' ? $this->domain.'/category/'.$id.'_'.($page - 1) : $this->domain.'/category/'.$id.'_'.($page - 1).'.html';
        }
    
        if ($page<1){
            $page=1;
        }elseif($page>$pageCount){
            $page=$pageCount;
        }
        $pageNumHalf=floor($showPageNum/2);
    
        if ($pageCount <= $showPageNum){
            $startpage = 1;
            $endpage = $pageCount;
        }elseif (($page-$pageNumHalf) >= 1 && ($page+$pageNumHalf) <= $pageCount){
            if($showPageNum%2==0 && $showPageNum==2){
                $startpage = $page;
            }elseif($showPageNum%2==0 && $showPageNum>2){
                $startpage = $page-$pageNumHalf+1;
            }else{
                $startpage = $page-$pageNumHalf;
            }
            $endpage = $page+$pageNumHalf;
        }elseif (($page-$pageNumHalf) < 1){
            $startpage = 1;
            $endpage = $showPageNum;
        }elseif (($page+$pageNumHalf) > $pageCount){
            $startpage = $pageCount-($showPageNum-1);
            $endpage = $pageCount;
        }
         
        for ($i=$startpage; $i<=$endpage; $i++){
            if ($i == $page){
                $links[$i] = $i;
            }else{
                $links[$i] = $this->showtype=='preview' ? $this->domain.'/category/'.$id.'_'.$i : $this->domain.'/category/'.$id.'_'.$i.'.html';
            }
        }
    
        if ($page >= $pageCount){
    
            $links['next']= '';
            $links['last']= '';
    
        }else{
            $links['next']= $this->showtype=='preview' ? $this->domain.'/category/'.$id.'_'.($page + 1) : $this->domain.'/category/'.$id.'_'.($page + 1).'.html';
            $links['last'] = $this->showtype=='preview' ? $this->domain.'/category/'.$id.'_'.$pageCount : $this->domain.'/category/'.$id.'_'.$pageCount.'.html';
        }
    
        return $links;
    }
    
 /**
     * 
     * 地图功能
     * 
     * 
     */
    // Smarty Plugins
    public static function createMap($params){
            $params['scale_size'] = isset($params['scale_size'])?$params['scale_size']:17;
            $params['width'] = isset($params['width'])?$params['width']:'80%';
            $params['height'] = isset($params['height'])?$params['height']:'200px';
            $s = '<div id="allmap" style="width: '.$params['width'].';height: '.$params['height'].'"></div>';
            $s.= '<script>';
            $s.= 'var map_js = document.createElement("script");
                map_js.setAttribute("type", "text/javascript");
                map_js.setAttribute("src", "http://api.map.baidu.com/getscript?v=2.0&ak=GylcTxXNQ3zkyCffjBYPHOb8&services=&t=20150514110922");
                var headobj = document.getElementsByTagName("head")[0];headobj.appendChild(map_js);
                map_js.onload = map_js.onreadystatechange = function() {
                var map = new BMap.Map("allmap");
                var point = new BMap.Point(118.1038860000,24.4892310000);
                map.centerAndZoom(point,12);
                var myGeo = new BMap.Geocoder();
                map.enableScrollWheelZoom(true);';
                if (array_key_exists('address', $params)) {
                    $s.= 'myGeo.getPoint("'.$params['address'].'", function(point){
                        if (point) {
                          map.centerAndZoom(point,'.$params['scale_size'].');
                          map.addOverlay(new BMap.Marker(point));
                        }else{
                          alert("您选择地址没有解析到结果!");
                        }
                        }, "厦门市");';
                }else{
                     $s.= 'myGeo.getPoint("软件园二期观日路36号", function(point){
                        if (point) {
                          map.centerAndZoom(point,'.$params['scale_size'].');
                          map.addOverlay(new BMap.Marker(point));
                        }else{
                          alert("您选择地址没有解析到结果!");
                        }
                        }, "厦门市");';
                }
            $s.= '};
              </script>';
            echo $s;
        }   
      
    /**
     * 
     * 分享功能
     * 
     * 
     */
	public static function createShare($params){
		$s = '<div class="bdsharebuttonbox" data-tag="share_1">
		  <a class="bds_mshare" data-cmd="mshare"></a>
		  <a class="bds_qzone" data-cmd="qzone" href="#"></a>
		  <a class="bds_tsina" data-cmd="tsina"></a>
		  <a class="bds_baidu" data-cmd="baidu"></a>
		  <a class="bds_renren" data-cmd="renren"></a>
		  <a class="bds_tqq" data-cmd="tqq"></a>
		  <a class="bds_more" data-cmd="more">更多</a>
		  <a class="bds_count" data-cmd="count"></a>
		</div>'."\n";
		// 显示类型
        $s.="<script>\n";
		$params['style'] = isset($params['style'])?$params['style']:"1";
		$s.="window._bd_share_config = {
		  common : {
			bdText : \"";
        $s.=isset($params['shareText'])?$params['shareText']:'';
        $s.="\",
        bdMiniList: ['mshare', 'qzone', 'tsina', 'bdysc', 'weixin', 'renren', 'tqq', 'kaixin001', 'tqf', 'tieba', 'douban', 'bdhome', 'sqq', 'thx', 'ibaidu', 'meilishuo', 'mogujie', 'huaban', 'duitang', 'hx', 'fx', 'youdao', 'sdo', 'qingbiji', 'people', 'xinhua', 'mail', 'isohu', 'yaolan', 'wealink', 'ty', 'iguba', 'linkedin', 'copy', 'print'], 
			bdDesc : \"";
        $s.=isset($params['shareDesc'])?$params['shareDesc']:'';
        $s.="\", 
			bdUrl : \"";
        $s.=isset($params['shareUrl'])?$params['shareUrl']:rtrim(self::$cus_domain,'/').$_SERVER['REQUEST_URI'].'.html';
        $s.="\",   
			bdPic : \"";
        $s.=isset($params['sharePic'])?$params['sharePic']:'';
        $s.="\",
            bdMini : \"";
        $s.=isset($params['bdMini'])?$params['bdMini']:'';
        $s.="\",
            bdMinilist : ['qzone','tsina','huaban','tqq','renren']";
        $s.=isset($params['bdMinilist'])?$params['bdMinilist']:'';
        $s.="
		  },
		  share : [{
			bdSize :";
        $s.=isset($params['viewSize'])?$params['viewSize']:16;
		$s.="}],
		  slide : [{     
			bdImg : 0,
			bdPos : 'right',
			bdTop : 200
		  }],
		  image : [{
			viewType : 'list',
			viewPos : 'top',
			viewColor : 'black',
			viewSize : '16',
			viewList : ['qzone','tsina','huaban','tqq','renren']
		  }],
		  selectShare : [{
			bdselectMiniList : ['qzone','tqq','kaixin001','bdxc','tqf']
		  }]
		};\n";
        if($params['style'] == "1"){
            $s.="delete _bd_share_config.slide\n";
        }elseif($params['style'] == "2"){
            $s.="delete _bd_share_config.share\n";
        }else{
            $s.="null\n";
        }
        if(isset($params['image'])){
            $s.="null\n";
        }else{
            $s.="delete _bd_share_config.image\n";
        }
		$s.="with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];\n";
		$s.="</script>";
        echo $s;
	}
        
    /**
     * PC显示首页
     */
    public function homepagePreview(){
        $result = $this->pagePublic();
        $customer_info = CustomerInfo::where('cus_id',$this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        $result['pagenavs']=false;
        $result['posnavs']=false;
        $data = $this->pagedata('index');
        $index = $this->detailList($data);
        $result = array_add($result, 'index', $index);
        $json_keys=$this->getJsonKey('index.html');
        if(count($json_keys)){
            foreach($json_keys as $key){
                $result[$key]=$this->detailList($this->pagedata($key));
            }
        }
        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->setTemplateDir(app_path('views/templates/'.$this->themename));
        $smarty->registerPlugin('function','mapExt',array('PrintController','createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController','createShare'));
        $smarty->assign($result);
        $smarty->display('index.html');
        //return View::make('templates.'.$this->themename.'.index',$result);

    }
    
    /**
     * 手机首页
     */
    public function mhomepagePreview(){
        $result = $this->pagePublic();
        $customer_info = CustomerInfo::where('cus_id',$this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        //获取模板目录
        $data = $this->pagedata('index');
        $show_navs = DB::table('mobile_homepage')->leftJoin('classify','classify.id','=','mobile_homepage.c_id')->where('mobile_homepage.index_show',1)->where('classify.mobile_show',1)->where('mobile_homepage.cus_id','=',$this->cus_id)->orderBy('mobile_homepage.s_sort','asc')->select('classify.id','classify.p_id','classify.name','classify.en_name','classify.type','classify.meta_description','classify.page_id','classify.url','classify.img','classify.icon','mobile_homepage.star_only','mobile_homepage.show_num','mobile_homepage.m_index_showtype')->get();
        $mIndexCats=array();
        if(count($show_navs) > 0){
            if($this->showtype=='preview'){
                foreach($show_navs as $nav){
                    $mIndexCat=array();
                    $mIndexCat['id']=$nav->id;
                    $mIndexCat['p_id']=$nav->p_id;
                    $mIndexCat['name']=$nav->name;
                    $mIndexCat['en_name']=$nav->en_name;
                    $mIndexCat['icon'] = '<i class="iconfont">'.$nav->icon.'</i>';
                    $mIndexCat['image']=$this->source_dir.'l/category/'.$nav->img;
                    if($nav->url){
                        $mIndexCat['link']=$nav->url;
                    }else{
                        $mIndexCat['link'] = $this->domain."/category/".$nav->id;
                    }
                    $mIndexCat['type']=$nav->type;
                    $mIndexCat['showtype']=$nav->m_index_showtype;
                    $mIndexCat['description']=$nav->meta_description;
                    $id_arr = explode(',',$this->getChildrenClassify($nav->id));
                    if($nav->type==1 || $nav->type==2 || $nav->type==3){
                        $art_arr=array();
                        if($nav->star_only){
                            //是否只显示推荐
                            $articles=Articles::whereIn('c_id',$id_arr)->where('mobile_show',1)->where('is_star',1)->take($nav->show_num)->get();
                        }else{
                            $articles=Articles::whereIn('c_id',$id_arr)->where('mobile_show',1)->take($nav->show_num)->get();
                        }
                        if(count($articles) > 0){
                            $i=0;
                            foreach($articles as &$article){
                                $art_arr[$i]['title']=$article->title;
                                $art_arr[$i]['image']=$this->source_dir.'s/articles/'.$article->img;
                                $art_arr[$i]['link']=$this->domain."/detail/".$article->id;
                                $art_arr[$i]['description']=$article->introduction;
                                $art_arr[$i]['pubdate']=$article->created_at;
                                $art_arr[$i]['pubtimestamp']=strtotime($article->created_at); 
                                $art_arr[$i]['category']['name']=$nav->name;
                                $art_arr[$i]['category']['en_name']=$nav->name;
                                if($nav->url){
                                    $art_arr[$i]['category']['link'] = $nav->url;
                                }else{
                                    $art_arr[$i]['category']['link'] = $this->domain."/category/".$nav->id;
                                }
                                $i++;
                            }
                        }
                        $mIndexCat['data']=$art_arr;
                    }elseif($nav->type==4){
                        $content=Page::where('id',$nav->page_id)->pluck('content');
                        $mIndexCat['content']=$content;
                    }elseif($nav->type==5 || $nav->type==6 || $nav->type==7 || $nav->type==8){
                        //暂时缺省
                    }   
                     $mIndexCats[]=$mIndexCat;
                }
            }else{
                foreach($show_navs as $nav){
                    $mIndexCat=array();
                    $mIndexCat['id']=$nav->id;
                    $mIndexCat['p_id']=$nav->p_id;
                    $mIndexCat['name']=$nav->name;
                    $mIndexCat['en_name']=$nav->en_name;
                    $mIndexCat['icon'] = '<i class="iconfont">'.$nav->icon.'</i>';
                    $mIndexCat['image']=$this->source_dir."/l/category/".$nav->img;
                    if($nav->url){
                        $mIndexCat['link']=$nav->url;
                    }else{
                        $mIndexCat['link']=$this->domain."/category/".$nav->id.".html";
                    }
                    $mIndexCat['type']=$nav->type;
                    $mIndexCat['showtype']=$nav->m_index_showtype;
                    $mIndexCat['description']=$nav->meta_description;
                    $id_arr = explode(',',$this->getChildrenClassify($nav->id));
                    if($nav->type==1 || $nav->type==2 || $nav->type==3){
                        $art_arr=array();
                        if($nav->star_only){
                            //是否只显示推荐
                            $articles=Articles::whereIn('c_id',$id_arr)->where('mobile_show',1)->where('is_star',1)->take($nav->show_num)->get();
                        }else{
                            $articles=Articles::whereIn('c_id',$id_arr)->where('mobile_show',1)->take($nav->show_num)->get();
                        }
                        if(count($articles) > 0){
                            $i=0;
                            foreach($articles as &$article){
                                $art_arr[$i]['title']=$article->title;
                                $art_arr[$i]['image']=$this->source_dir."/s/articles/".$article->img;
                                $art_arr[$i]['link']=$this->domain."/detail/".$article->id.".html";
                                $art_arr[$i]['description']=$article->introduction;
                                $art_arr[$i]['pubdate']=$article->created_at;
                                $art_arr[$i]['pubtimestamp']=strtotime($article->created_at);
                                $art_arr[$i]['category']['name']=$nav->name;
                                $art_arr[$i]['category']['en_name']=$nav->name;
                                if($nav->url){
                                    $art_arr[$i]['category']['link'] = $nav->url;
                                }else{
                                    $art_arr[$i]['category']['link'] = $this->domain."/category/".$nav->id;
                                }                                
                                $i++;
                            }
                        }
                        $mIndexCat['data']=$art_arr;
                    }elseif($nav->type==4){
                        $content=Page::where('id',$nav->page_id)->pluck('content');
                        $mIndexCat['content']=$content;
                    }elseif($nav->type==5 || $nav->type==6 || $nav->type==7 || $nav->type==8){
                        //暂时缺省
                    }
                    $mIndexCats[]=$mIndexCat;
                }
            }
        }
        $classify=new Classify;
        foreach($mIndexCats as $key=>$val){
            $mIndexCats[$key]['childmenu']=$classify->toTree($mIndexCats,$mIndexCats[$key]['id']);
        }
        $result['mIndexCats']=$mIndexCats;
        //print_r($mIndexCats);
        //exit;
        $smarty = new Smarty;
        $smarty->setTemplateDir(app_path('views/templates/'.$this->themename));
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->registerPlugin('function','mapExt',array('PrintController','createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController','createShare'));
        $smarty->assign($result);
        $smarty->display('index.html');
        //return View::make('templates.'.$this->themename.'.index',$result);
    }
    
    /**
     * 显示栏目页的某个分页
     *
     * @param int $id 栏目id
     * @param int $page 当前页码
     */
    public function categoryPreview($id,$page){
        $result = $this->pagePublic($id);
        foreach($result['navs'] as $nav){
            if($nav['current']==1){
                $pagenavs = $nav['childmenu'];
                break;
            }
            else{
                $pagenavs = [];
            }
        }      
        $classify = Classify::find($id);
        $result['title'] = $classify->name;
        $result['keywords'] = $classify->meta_keywords;
        $result['description'] = $classify->meta_description;
        $result['list']['name'] = $classify->name;
        $result['list']['en_name'] = $classify->en_name;
        $result['list']['description'] = $classify->meta_description;
        $result['list']['icon'] = '<i class="iconfont">'.$classify->icon.'</i>';
        $result['list']['image'] = $this->source_dir.'s/category/'.$classify->img;
        $result['list']['type'] = $classify->type;
        if($this->showtype=='preview'){
            $result['list']['link'] = $this->domain.'/category/'.$id;
        }
        else{
            $result['list']['link'] = $this->domain.'/category/'.$id.'.html';
        }
        $result['pagenavs'] = $pagenavs;
        $result['posnavs'] =$this->getPosNavs($id);
        if($classify->type==1){//文字列表
            $viewname = 'list-text';
        }
        elseif ($classify->type==2) {//图片列表
            $viewname = 'list-image';
        }
        elseif($classify->type==3){//图文列表
            $viewname = 'list-imagetext';
        }
        elseif($classify->type==4){//内容单页
            $viewname = 'list-page';
        }
        else{//跳转404

        }
        //echo $viewname;exit;
        if(in_array($classify->type,array(1,2,3,4))){
            $sub = str_replace('-', '_', $viewname);
            $data = $this->pagedata($viewname);
            $index = $this->detailList($data);
            $result = array_add($result, $sub, $index);
            $data_index = $this->pagedata($viewname);
            $index_list = $this->pageList($id,$page);
            if($classify->type==4){
                if($this->showtype=='preview'){
                    $result['list']['content'] =Page::where('id',$classify->page_id)->pluck('content');   
                }else{
                    $result['list']['content'] =preg_replace('/\/customers\/'.$this->customer.'/i','',Page::where('id',$classify->page_id)->pluck('content'));                   
                }   
            }else{
                $result['list']['data'] = $index_list['data'];         
            }
            $result['page_links'] = $index_list['page_links'];
            $json_keys=$this->getJsonKey($viewname.'.html');
            if(count($json_keys)){
                foreach($json_keys as $key){
                    $result[$key]=$this->detailList($this->pagedata($key));
                }
            }
            $smarty = new Smarty;
            $smarty->setTemplateDir(app_path('views/templates/'.$this->themename));
            $smarty->setCompileDir(app_path('storage/views/compile'));
            $smarty->registerPlugin('function','mapExt',array('PrintController','createMap'));
            $smarty->registerPlugin('function', 'shareExt', array('PrintController','createShare'));
            $smarty->assign($result);
            $smarty->display($viewname.'.html');
            //return View::make('templates.'.$this->themename.'.'.$viewname,$result);
        }
    }
    
    /*
     * 获取当前包含的页面 
     * 
     *
     */
    private function getJsonKey($viewname,&$json_keys=[]){
        $content=file_get_contents(app_path('views/templates/'.$this->themename.'/').$viewname);
        preg_match_all('/\{include\s+file=[\"|\'](.*\.html)\s*[\"|\']\}/i',$content,$i_arr);
        if(count($i_arr)){
            foreach($i_arr[1] as $i_c){              
                $i_info=pathinfo($i_c);
                $json_keys[]=$i_info['filename'];
                $this->getJsonKey($i_info['filename'].'.html',$json_keys);
            }
        }
        return $json_keys;
    }
    
    /**
     * 显示文章页
     *
     * @param int $id 文章id
     */
    public function articlePreview($id){
        $article = Articles::find($id);
        
        $a_moreimg = Moreimg::where('a_id',$id)->get()->toArray();
        array_unshift($a_moreimg,array('title'=>$article->title,'img'=>$article->img));
        $images=array();
        if(count($a_moreimg)){
            $i=0;
            foreach($a_moreimg as $a_img){
                $images[$i]['title'] = $a_img['title'];
                $images[$i]['image'] = $this->source_dir.'l/articles/'.$a_img['img'];
                $i++;
            }
        }
        $list_id = Articles::where('c_id',$article->c_id)->where($this->type.'_show','1')->orderBy('is_top','desc')->orderBy('created_at','desc')->select('id','title','img','introduction','created_at')->lists('id');
        foreach($list_id as $key=>$val){
            $article_prev=NULL;
            $article_next=NULL;
            if($val==$id){
                if($key!=0){
                    $prev_id=$list_id[$key-1];
                    $article_prev = Articles::find($prev_id);       
                }
                if($key<(count($list_id)-1)){
                    $next_id=$list_id[$key+1];
                    $article_next = Articles::find($next_id);
                }
                break;
            }
        }
        $result = $this->pagePublic($article->c_id);        
        foreach($result['navs'] as $nav){
            if($nav['current']==1){
                $pagenavs = $nav['childmenu'];
                break;
            }
            else{
                $pagenavs = [];
            }
        }
        $result['pagenavs']=$pagenavs;
        $result['posnavs']=$this->getPosNavs($article->c_id);
        $result['title'] = $article->title;
        $result['keywords'] = $article->keywords;
        $result['description'] = $article->introduction;
        $result['article']['title'] = $article->title;
        $result['article']['keywords'] = $article->keywords;
        $result['article']['description'] = $article->introduction;
        $result['article']['viewcount'] = '<em id="article-viewcount">0</em>';
        $article_type = Articles::leftJoin('classify','classify.id','=','article.c_id')->where('article.id',$id)->pluck('article_type');
        if($article_type==1){//新闻内容
            $viewname = 'content-news';
        }
        elseif ($article_type==2) {//产品内容
            $viewname = 'content-product';
        }
        else{//跳转404
    
        }
        //关联文章查询
        $pa = new PhpAnalysis();

        $pa->SetSource($article->title);

        //设置分词属性
        $pa->resultType = 2;
        $pa->differMax  = true;
        $pa->StartAnalysis();

        //获取你想要的结果
        $keywords=$pa->GetFinallyIndex();
        if(count($keywords)){
            $relation_where="";
            foreach($keywords as $key=>$word){
                $relation_where.="or title like '%$key%' ";
            }
            $relation_where=ltrim($relation_where,'or');
            $prefix = Config::get('database.connections.mysql.prefix');     
            $related_data=DB::select("select id,title,img as image,introduction,created_at,c_id from {$prefix}article where cus_id={$this->cus_id} and ($relation_where)");
            $related=array();
            if(count($related_data)){
                foreach($related_data as $val){
                    $temp_arr=[];  
                    $temp_arr['title']=$val->title;
                    $temp_arr['description']=$val->introduction;
                    $temp_arr['image']=$this->source_dir.'l/articles/'.$val->image;
                    if($this->showtype=='preview'){
                        $temp_arr['link'] = $this->domain.'/detail/'.$val->id;
                        $temp_arr['category']['link']= $this->domain.'/category/'.$val->id.'.html';
                    }else{
                        $temp_arr['link'] = $this->domain.'/detail/'.$val->id.'.html';
                        $temp_arr['category']['link']= $this->domain.'/category/'.$val->id.'.html';
                    }
                    $temp_arr['pubdate']=$val->created_at;
                    $temp_arr['pubtimestamp']=strtotime($val->created_at);
                    $a_c_info=Classify::where('id',$val->c_id)->first();
                    $temp_arr['category']['name']=$a_c_info->name;
                    $temp_arr['category']['en_name']=$a_c_info->en_name;
                    $temp_arr['category']['icon']='<i class="iconfont">'.$a_c_info->icon.'</i>';
                    $related[]=$temp_arr;
                }
            }
        }
        //dd($article_prev);
        if($this->showtype=='preview'){
            if($article_next===NULL){
                $result['article']['next']['title']='已经是最后一篇';
                $result['article']['next']['link'] = '';
            }
            else{
                $result['article']['next']['title'] = $article_next->title;
                $result['article']['next']['link'] = $this->domain.'/detail/'.$article_next->id;
            }
            if($article_prev===NULL){
                $result['article']['prev']['title']='已经是第一篇';
                $result['article']['prev']['link'] = '';
            }
            else{
                $result['article']['prev']['title'] = $article_prev->title;
                $result['article']['prev']['link'] = $this->domain.'/detail/'.$article_prev->id;
            }
            $result['article']['image'] = $this->source_dir.'l/articles/'.$article->img;
            $result['article']['images'] = $images;
            $result['article']['content'] = $article->content;
        }
        else{
            if($article_next===NULL){
                $result['article']['next']['title']='已经是最后一篇';
                $result['article']['next']['link'] = '';
            }
            else{
                $result['article']['next']['title'] = $article_next->title;
                $result['article']['next']['link'] = $this->domain.'/detail/'.$article_next->id.'.html';
            }
            if($article_prev===NULL){
                $result['article']['prev']['title']='已经是第一篇';
                $result['article']['prev']['link'] = '';
            }
            else{
                $result['article']['prev']['title'] = $article_prev->title;
                $result['article']['prev']['link'] = $this->domain.'/detail/'.$article_prev->id.'.html';
            }
            $result['article']['image'] = $this->source_dir.'l/articles/'.$article->img;
            $result['article']['images'] = $images;
            $result['article']['content'] = preg_replace('/\/customers\/'.$this->customer.'/i','',$article->content);
        }
        $result['article']['description'] = $article->introduction;
        $result['article']['pubdate']=$article->created_at;
        $result['article']['pubtimestamp']=strtotime($article->created_at);
        
        $result['article']['category']=$result['posnavs'][count($result['posnavs'])-1];
        $result['related']=$related;
        $json_keys=$this->getJsonKey($viewname.'.html');
        if(count($json_keys)){
            foreach($json_keys as $key){
                $result[$key]=$this->detailList($this->pagedata($key));
            }
        }
        $smarty = new Smarty;
        $smarty->setTemplateDir(app_path('views/templates/'.$this->themename));
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->registerPlugin('function','mapExt',array('PrintController','createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController','createShare'));
        $smarty->assign($result);
        $smarty->display($viewname.'.html');
        //return View::make('templates.'.$this->themename.'.'.$viewname,$result); 
    }
    
    /**
     * 根据栏目id获取其顶级id
     *
     * @param  int $id 栏目
     * @return int 顶级id
     */
    private function getPid($id){
        $p_id=Classify::where('id',$id)->pluck('p_id');
        if($p_id > 0){
            $new_pid=$this->getPid($p_id);
            if($new_pid > 0){
                $p_id=$new_pid;
            }
        }else{
            $p_id=$id;
        }
        return $p_id;
    }
    
    /**
     * 根据从数据库查询的栏目数据数组,转为树形结构的数组
     * 
     * @param array $arr 栏目数据数组
     * @param int $pid  起始栏目id
     * @param bool $isNav 是否是顶级导航(顶级导航会显示下面的几篇文章)
     * @return array 树形结构的数组
     */
    private function toTree($arr, $pid = 0,$isNav = FALSE) {
        $tree = array();
        foreach ($arr as $k => $v) {
            if ($v['p_id'] == $pid) {
                $v['image']=$this->source_dir.'l/category/'.$v['img'];
                $v['icon']='<i class="iconfont">'.$v['icon'].'</i>';
                unset($v['img']);
                $tree[] = $v;
            }
        }
        if (empty($tree)) {
            return null;
        }
        foreach ($tree as $k => $v) {
            $data = [];
            if($v['type'] != 6){
                $tree[$k]['link'] = $this->showtype=='preview' ? $this->domain.'/category/'.$v['id'] : $this->domain.'/category/'.$v['id'].'.html';
                if($isNav==TRUE){
                    $cids = explode(',',$this->getChirldenCid($v['id']));//取得所有栏目id
                    if($this->type=='mobile'){
                        $articles = Articles::whereIn('c_id',$cids)->where('mobile_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at')->take(20)->get();
                    }
                    else{
                        $articles = Articles::whereIn('c_id',$cids)->where('pc_show','1')->where('cus_id',$this->cus_id)->select('id','c_id','title','img','introduction','created_at')->take(20)->get();
                    }
                    if(!empty($articles)){
                        $abc = [];
                        foreach($articles as $key=>$d){
                            $data[$key]['title'] = $d->title;
                            $classify = Classify::where('id',$d->c_id)->first();
                            
                            $data[$key]['category']['link'] = $this->showtype=='preview' ? $this->domain.'/category/'.$d->c_id : $this->domain.'/category/'.$d->c_id.'.html';
                            $data[$key]['image'] = $this->source_dir.'s/articles/'.$d->img;
                            $data[$key]['link'] = $this->showtype=='preview' ? $this->domain.'/detail/'.$d->id : $this->domain.'/detail/'.$d->id.'.html';
                            $data[$key]['category']['name'] = $classify->name;
                            $data[$key]['category']['en_name'] = $classify->en_name;
                            $data[$key]['category']['icon'] = '<i class="iconfont">'.$classify->icon.'</i>';
                            $data[$key]['description'] = $d->introduction;
                            $data[$key]['pubdate'] = $d->created_at;
                            $data[$key]['pubtimestamp'] = strtotime($d->created_at);
                            unset($v['value']);
                        }
                        $tree[$k]['data'] = $data;
                    }
                    else{
                        $tree[$k]['data'] = [];
                    }
                }
            }
            else{
                $tree[$k]['link'] = $v['url'];
            }
            $tree[$k]['current'] = 0;
            $tree[$k]['selected'] = 0;
            $tree[$k]['childmenu'] = $this->toTree($arr, $v['id']);
        }
        return $tree;
    }
    
    /**
     * 根据栏目id，获取包含其本身的栏目id串
     *
     * @param int $cid 栏目
     * @return string 以“,”分割的栏目id串
     */
    public function getChirldenCid($cid=0){
        $result = $cid;
        $cids = Classify::where('p_id',$cid)->lists('id');
        if(!empty($cids)){
            foreach ($cids as $v) {
                $result.= ','.$this->getChirldenCid($v);
            }
        }
        return $result;
    
    }
    
    private function currentCidArray($id,&$arr=array()){
        $arr[]=$id;
        $p_id=Classify::where('id',$id)->pluck('p_id');
        if($p_id > 0){
            $new_pid=$this->currentCidArray($p_id,$arr);
        }
        return $arr;
    }
    
    private function addCurrent(&$c_list,$current_ids){
        if(count($c_list)){
            foreach($c_list as &$c_arr){
                if($c_arr['childmenu']){
                    $this->addCurrent($c_arr['childmenu'],$current_ids);
                }
                if(in_array($c_arr['id'],$current_ids)){
                    $c_arr['current']=1;
                }
                else{
                    $c_arr['current']=0;
                }           
                if($c_arr['id']==$current_ids[0]){
                    $c_arr['selected']=1;
                }
                else{
                    $c_arr['selected']=0;
                }     
            }
        }
        return $c_list;
    }
    
    /**
     * 合并多个个多维数组，键值相同时总是合并
     * @param array 数组
     * @return array
     */
    /*
    public function array_merge_recursive_new() {
        $arrays = func_get_args();
        $base = array_shift($arrays);
        //dd($arrays);
        foreach ($arrays as $array) {
            reset($base); //important
            while (list($key, $value) = @each($array)) {
                if (is_array($value) && @is_array($base[$key])) {
                    $base[$key] = $this->array_merge_recursive_new($base[$key], $value);
                } else {
                    $base[$key] = $value;
                }
            } 
        }
    
        return $base;
    }
    */
    
    /*
     * 搜索页面数据
     */
    public function searchPreview(){
        $result = $this->pagePublic();
        $customer_info = CustomerInfo::where('cus_id',$this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        $result['pagenavs']=false;
        $result['posnavs']=false;
        if($this->type!='mobile'){
            if(file_exists(app_path('views/templates/'.$this->themename.'/searchresult.html'))){
                $json_keys=$this->getJsonKey('searchresult.html');
                if(count($json_keys)){
                    foreach($json_keys as $key){
                        $result[$key]=$this->detailList($this->pagedata($key));
                    }
                }
            }else{
                return false;
            }
            
        }
        return json_encode($result);
    }
    
    public function array_merge_recursive_new($old_arr,$new_arr) {
            foreach($old_arr as $key => $val){
                if(array_key_exists($key, $new_arr)){
                    if($old_arr[$key]['type']=='list' || $old_arr[$key]['type']=='nav'){
                        $old_arr[$key]['config']['star_only']=$new_arr[$key]['value']['star_only'];
                        $old_arr[$key]['config']['id']=isset($new_arr[$key]['value']['id'])?$new_arr[$key]['value']['id']:'';              
                    }elseif($old_arr[$key]['type']=='page'){
                        $old_arr[$key]['config']=$new_arr[$key]['value'];              
                    }elseif($old_arr[$key]['type']=='navs'){
                        $old_arr[$key]['config']['ids']=$new_arr[$key]['value']['ids'];
                    }else{
                        $old_arr[$key]['value']=$new_arr[$key]['value'];                       
                    }
                }else{
                    if($old_arr[$key]['type']=='list' || $old_arr[$key]['type']=='nav'){
                        $old_arr[$key]['config']['star_only']=0;
                        $old_arr[$key]['config']['id']=0;              
                    }elseif($old_arr[$key]['type']=='page' || $old_arr[$key]['type']=='navs'){
                        $old_arr[$key]['config']=array();            
                    }else{
                        if(is_array($old_arr[$key]['value']) && count($old_arr[$key]['value']) > 0){
                            foreach($old_arr[$key]['value'] as &$v){
                                $v='';
                            }               
                        }
                    }
                }
            }
            return $old_arr;
    }
    
    public function mobilePageList($page,$is_index=false){
        $json_content=@file_get_contents(public_path("/templates/$this->themename/json/$page.json"));
        if($json_content){
            $json_content = json_decode($json_content,true);
            if($is_index){
                return $json_content;
            }else{
                  $data=$json_content[$page];
            }
        }else{
            return null;
        }
        return $data; 
    }
    
    private function getChildrenClassify($p_id){
        $id_str = $p_id;
        $ids=DB::table('classify')->where('p_id','=',$p_id)->lists('id');
        if(count($ids) > 0){
            foreach($ids as &$id){
                $id_str.=','.$this->getChildrenClassify($id);
            }
        }
        return $id_str;
    }
    
    private function getPosNavs($c_id,&$posnavs=array()){
        $classify=Classify::where('id',$c_id)->first();
        $arr['name']=$classify->name;
        $arr['en_name']=$classify->en_name;
        if($this->showtype=='preview'){
            $arr['link'] = $this->domain.'/category/'.$c_id;
        }
        else{
            $arr['link'] = $this->domain.'/category/'.$c_id.'.html';
        }
        array_unshift($posnavs, $arr);
        if($classify->p_id > 0){
            $this->getPosNavs($classify->p_id,$posnavs);
        }
        return $posnavs;
    }
    
    /*
     * 推送模板信息 数据信息等
     */
    public function sendCusAllData($url=''){
        $id=$this->cus_id;
        $type=$this->type;
        $string=$this->searchPreview();
        if($string){
            $file_arr=$this->getFile(app_path('views/templates/'.$this->themename));
            $file_name="";
            $file_content="";
            foreach($file_arr as &$file){
                 if(preg_match('/^_.*/i',$file) || $file=='searchresult.html'){
                     $file_name.=$file.'@';
                     $file_content.=file_get_contents(app_path('views/templates/'.$this->themename.'/'.$file)).'@#@';
                 }
            }
            $file_name=rtrim($file_name,'@');
            $file_content=rtrim($file_content,'@#@');
            $postFun=new CommonController;
            $postFun->postsend($url,array("id"=>$id,'type'=>$type,'string'=>$string,'file_name'=>$file_name,'file_content'=>$file_content));
        }
    }
    
    //获取文件列表
    public function getFile($dir) {
        $fileArray[]=NULL;
        if (false != ($handle = opendir ( $dir ))) {
            $i=0;
            while ( false !== ($file = readdir ( $handle )) ) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".."&&strpos($file,".")) {
                    $fileArray[$i] = $file;
                    if($i==100){
                        break;
                    }
                    $i++;
                }
            }
            //关闭句柄
            closedir ( $handle );
        }
        return $fileArray;
    }
}