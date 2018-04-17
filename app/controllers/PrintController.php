<?php

/**
 * 输出预览或用于生成静态时输出到缓冲区
 * @author 財財 530176577@qq.com
 * 创建时间 2015年3月26日
 *
 * @package 统一平台
 */
class PrintController extends BaseController
{

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
    public $showtype;

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
     * 是否是换色模板
     *
     * @var string
     */
    public $isColorful;

    /**
     * 换色模板样式路径
     *
     * @var string
     */
    public $site_color;

    /**
     * 换色模板选中的颜色
     *
     * @var string
     */
    public $color_dir;

    /**
     * 是否是模板站
     *
     * @var string
     */
    public $is_demo;

    /**
     * 是否开启小程序
     *
     * @var string
     */
    public $is_applets;

    /**
     * 定义用户id、用户名、
     * @param string $showtpye
     * @param string $type
     */
    function __construct($showtpye = 'preview', $type = 'pc')
    {
        $this->showtype = $showtpye;
        $this->type = $type;
        $this->cus_id = Auth::id();
        $this->customer = Auth::user()->name;
        $cus_conf = Customer::where('id', $this->cus_id)->select('is_demo', 'is_applets')->first()->toArray();
        $this->is_demo = $cus_conf['is_demo'];
        $this->is_applets = $cus_conf['is_applets'];
        if ($this->showtype == 'preview') {
            $this->source_dir = '/customers/' . $this->customer . '/images/'; //asset('customers/' . $this->customer . '/images/') . '/';
            if ($this->type == 'mobile') {
                $this->domain = '/mobile'; //url() . '/mobile';
                // $this->tpl_id = WebsiteInfo::where('cus_id', $this->cus_id)->pluck('mobile_tpl_id');
                $tpl_info = WebsiteInfo::where('cus_id', $this->cus_id)->select('mobile_tpl_id' , 'mobile_color')->first()->toArray();
                $this->tpl_id = $tpl_info['mobile_tpl_id'];
                $this->color_dir = $tpl_info['mobile_color'];
                // $this->themename = DB::table('template')->leftJoin('website_info', 'website_info.mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->pluck('template.name');
                $theme_info = Template::leftJoin('website_info', 'website_info.mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->select('template.name', 'template.name_bak', 'template.isColorful', 'template.color_style')->first()->toArray();
                $this->themename = $theme_info['name'];
                $this->isColorful =  $theme_info['isColorful'];              
                //===预览手机模板旧编号调用===
                if(empty($this->themename) or !is_dir(public_path('/templates/'.$this->themename)) or !is_dir(app_path('/views/templates/'.$this->themename))){
                    $this->themename = $theme_info['name_bak'];
                }
                //===预览手机换色模板处理===
                if ($theme_info['isColorful'] == 1) {
                    //如果换色模板没有选择颜色，或者所选颜色并不是当前模板中所具有的，则使用模板的第一种颜色
                    if (!$this->color_dir || !strpos($theme_info['color_style'], $this->color_dir)) {
                        $color_str = str_replace('#', '', $theme_info['color_style']);
                        $color_arr = explode(',', $color_str);
                        $this->color_dir = $color_arr['0'];
                    }
                }
                $this->source_dir = '/customers/' . $this->customer . '/mobile/images/'; //asset('customers/' . $this->customer . '/mobile/images/') . '/';
                self::$cus_domain = ''; //CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_domain');
            } else {
                $this->domain = ''; //url();
                // $this->tpl_id = WebsiteInfo::where('cus_id', $this->cus_id)->pluck('pc_tpl_id');
                $tpl_info = WebsiteInfo::where('cus_id', $this->cus_id)->select('pc_tpl_id' , 'pc_color')->first()->toArray();
                $this->tpl_id = $tpl_info['pc_tpl_id'];
                $this->color_dir = $tpl_info['pc_color'];
                // $this->themename = DB::table('template')->leftJoin('website_info', 'website_info.pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->pluck('template.name');
                $theme_info = Template::leftJoin('website_info', 'website_info.pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->select('template.name', 'template.name_bak', 'template.isColorful', 'template.color_style')->first()->toArray();
                $this->themename = $theme_info['name']; 
                $this->isColorful =  $theme_info['isColorful'];                
                //===预览PC模板旧编号调用===
                if(empty($this->themename) or !is_dir(public_path('/templates/'.$this->themename)) or !is_dir(app_path('/views/templates/'.$this->themename))){
                    $this->themename = $theme_info['name_bak'];
                }
                //===预览PC换色模板处理===
                if ($theme_info['isColorful'] == 1) {
                    //如果换色模板没有选择颜色，或者所选颜色并不是当前模板中所具有的，则使用模板的第一种颜色
                    if (!$this->color_dir || !strpos($theme_info['color_style'], $this->color_dir)) {
                        $color_str = str_replace('#', '', $theme_info['color_style']);
                        $color_arr = explode(',', $color_str);
                        $this->color_dir = $color_arr['0'];
                    }
                }
                self::$cus_domain = ''; //CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_domain');
            }
            $this->site_url = '/templates/' . $this->themename . '/';
            $this->site_color = 'themes/' . $this->color_dir .'/';
        } else {
            if ($this->type == 'mobile') {
                // $this->tpl_id = WebsiteInfo::where('cus_id', $this->cus_id)->pluck('mobile_tpl_id');
                $tpl_info = WebsiteInfo::where('cus_id', $this->cus_id)->select('mobile_tpl_id' , 'mobile_color')->first()->toArray();
                $this->tpl_id = $tpl_info['mobile_tpl_id'];
                $this->color_dir = $tpl_info['mobile_color'];
                // $this->themename = DB::table('template')->leftJoin('website_info', 'website_info.mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->pluck('template.name');
                $theme_info = Template::leftJoin('website_info', 'website_info.mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->select('template.name', 'template.name_bak', 'template.isColorful', 'template.color_style')->first()->toArray();
                $this->themename = $theme_info['name']; 
                $this->isColorful =  $theme_info['isColorful'];                
                //===推送手机模板旧编号调用===
                if(empty($this->themename) or !is_dir(public_path('/templates/'.$this->themename)) or !is_dir(app_path('/views/templates/'.$this->themename))){
                    $this->themename = $theme_info['name_bak'];
                }
                //===推送手机换色模板处理===
                if ($theme_info['isColorful'] == 1) {
                    //如果换色模板没有选择颜色，或者所选颜色并不是当前模板中所具有的，则使用模板的第一种颜色
                    if (!$this->color_dir || !strpos($theme_info['color_style'], $this->color_dir)) {
                        $color_str = str_replace('#', '', $theme_info['color_style']);
                        $color_arr = explode(',', $color_str);
                        $this->color_dir = $color_arr['0'];
                    }
                }
                $mobile_domain = CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_domain');
                $mobile_domain = str_replace('http://', '', $mobile_domain);
                if (strpos($mobile_domain, '/mobile')) {
                    $this->domain = '/mobile';
                }
                //$this->domain = '';//CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_domain');
            } else {
                // $this->tpl_id = WebsiteInfo::where('cus_id', $this->cus_id)->pluck('pc_tpl_id');
                $tpl_info = WebsiteInfo::where('cus_id', $this->cus_id)->select('pc_tpl_id' , 'pc_color')->first()->toArray();
                $this->tpl_id = $tpl_info['pc_tpl_id'];
                $this->color_dir = $tpl_info['pc_color'];
                $theme_info = Template::leftJoin('website_info', 'website_info.pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $this->cus_id)->select('template.name', 'template.name_bak', 'template.isColorful', 'template.color_style')->first()->toArray();
                $this->themename = $theme_info['name']; 
                $this->isColorful =  $theme_info['isColorful']; 
                //===推送PC模板调用===
                if(empty($this->themename) or !is_dir(public_path('/templates/'.$this->themename)) or !is_dir(app_path('/views/templates/'.$this->themename))){
                    $this->themename = $theme_info['name_bak'];
                }
                //===推送PC换色模板处理===
                if ($theme_info['isColorful'] == 1) {
                    //如果换色模板没有选择颜色，或者所选颜色并不是当前模板中所具有的，则使用模板的第一种颜色
                    if (!$this->color_dir || !strpos($theme_info['color_style'], $this->color_dir)) {
                        $color_str = str_replace('#', '', $theme_info['color_style']);
                        $color_arr = explode(',', $color_str);
                        $this->color_dir = $color_arr['0'];
                    }
                }
                //===推送PC模板调用===
                $this->domain = ''; //CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_domain');
            }
            self::$cus_domain = ''; // $this->domain;
            $this->site_url = $this->domain . '/';
            $this->source_dir = '/images/'; //$this->domain . '/images/';            
            if($this->is_demo == 1) {
                $this->site_color = 'themes/' . $this->color_dir .'/';
            } else {
                $this->site_color = '';
            }
        }
    }

    /**
     * 合并页面请求，合并json和数据库内容，返回合并后的数组
     *
     * @param string $themename 模版名称
     * @param string $pagename 页面名称
     * @param string $jsondata 文件配置数据
     * @return array 合并后的数组
     */
    public function pagedata($pagename, $jsondata = array())
    {
        if ($this->type == 'pc') {
            $tpl_id = websiteInfo::where('cus_id', $this->cus_id)->pluck('pc_tpl_id');
        } else {
            $tpl_id = websiteInfo::where('cus_id', $this->cus_id)->pluck('mobile_tpl_id');
        }
        $website_confige = WebsiteConfig::where('cus_id', $this->cus_id)->where('key', $pagename)->where('type', 1)->where('template_id', $tpl_id)->pluck('value');
        $website_confige_value = unserialize($website_confige);
        //===对多图进行排序===
        if (is_array($website_confige_value)) {
            foreach ($website_confige_value as $key => &$value) {
                if ($key == 'slidepics') {
                    $slidepics_data = $value['value'];
                    if (is_array($slidepics_data)) {
                        foreach ($slidepics_data as $k => $v) {
                            if (isset($v['sort'])) {
                                $sort[$k] = is_numeric($v['sort']) ? $v['sort'] : 100;
                                $value['value'][$k]['sort'] = is_numeric($v['sort']) ? $v['sort'] : 100;
                            } else {
                                $sort[$k] = 100;
                                $value['value'][$k]['sort'] = 100;
                            }
                        }
                    }
                    array_multisort($sort, $slidepics_data);
                    $value['value'] = $slidepics_data;
                }
            }
        }
        //===对多图进行排序_end===
        if (count($jsondata)) { //===有传值===
            $json = isset($jsondata[$pagename . '.json']) ? $jsondata[$pagename . '.json'] : '{}';
        } else { // ===未传值，读取文件数据===
            $json_path = public_path('templates/' . $this->themename . '/json/' . $pagename . '.json');
            $json = file_exists($json_path) ? file_get_contents($json_path) : '{}';
        }
        if ($website_confige_value) {//===数据库中有数据，配置项读取文件中，数据由数据库赋值===
            $default = json_decode(trim($json), TRUE);
            $result = $this->array_merge_recursive_new($default, $website_confige_value);
            $this->replaceUrl($result);
            $result = $this->dataDeal($result);
            foreach ($result as &$v) {
                if ($v['type'] == 'list') {
                    if (isset($v['config']['filter'])) {
                        if ($v['config']['filter'] == 'list') {
                            $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                        } else {
                            $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                        }
                    } else {
                        $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                    }
                } elseif ($v['type'] == 'navs') {
                    if (isset($v['config']['ids'])) {
                        $v['config']['ids'] = array_merge($v['config']['ids']);
                    }
                }
            }
        } else {//===数据库中无模板数据，从文件中读取===
            $result = json_decode(trim($json), TRUE);
            if ($result === NULL) {
                dd("$pagename.json文件错误");
            }
            $this->replaceUrl($result);
            $result = $this->dataDeal($result);

            $classify = new Classify;
            $templates = new TemplatesController;
            $c_arr = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3, 4, 5, 6, 9))->where($this->type . '_show', '=', 1)->get()->toArray();
            if (empty($c_arr)) {
                $c_arr = array();
            }
            foreach ($result as &$v) {
                switch ($v['type']) {
                    case 'list':
                        if (isset($v['config']['mustchild']) && $v['config']['mustchild'] == true) {
                            if (isset($v['config']['filter'])) {
                                if ($v['config']['filter'] == 'page') {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(4));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                } elseif ($v['config']['filter'] == 'list') {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(1, 2, 3));
                                    $templates->unsetLastClassify($c_arr);
                                    if (is_array($c_arr)) {
                                        $c_arr = array_merge($c_arr);
                                    }
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } elseif ($v['config']['filter'] == 'feedback') {/* 20151021添加feeback filter */
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(5, 9));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                } elseif ($v['config']['filter'] == 'ALL') {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(1, 2, 3, 4, 5, 6));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } else {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(1, 2, 3, 4));
                                    $templates->unsetLastClassify($c_arr);
                                    if (is_array($c_arr)) {
                                        $c_arr = array_merge($c_arr);
                                    }
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                }
                            } else {
                                $c_arr = $classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr, array(1, 2, 3));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr = array_merge($c_arr);
                                $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                            }
                            if (count($c_arr)) {
                                $v['config']['id'] = $c_arr[0]['id'];
                            }
                        } else {
                            if (isset($v['config']['filter'])) {
                                if ($v['config']['filter'] == 'page') {
                                    $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->where('type', 4)->where($this->type . '_show', 1)->pluck('id');
                                } elseif ($v['config']['filter'] == 'list') {
                                    $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3))->where($this->type . '_show', 1)->pluck('id');
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } elseif ($v['config']['filter'] == 'feedback') {/* 20151021添加feeback filter */
                                    $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(5, 9))->where($this->type . '_show', 1)->pluck('id');
                                } elseif ($v['config']['filter'] == 'ALL') {
                                    $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3, 4, 5, 6))->where($this->type . '_show', 1)->pluck('id');
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } else {
                                    $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3, 4))->where($this->type . '_show', 1)->pluck('id');
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                }
                            } else {
                                $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3))->where($this->type . '_show', 1)->pluck('id');
                                $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                            }
                        }
                        break;
                    case 'page':
                        if (isset($v['config']['mustchild']) && $v['config']['mustchild'] == true) {
                            $c_arr = Classify::where('cus_id', $this->cus_id)->where('type', 4)->where($this->type . '_show', 1)->get()->toArray();
                            $c_arr = $classify->toTree($c_arr);
                            $templates->unsetLastClassify($c_arr);
                            $c_arr = array_merge($c_arr);
                            if (count($c_arr)) {
                                $v['config']['id'] = $c_arr[0]['id'];
                            }
                        } else {
                            $v['config']['id'] = Classify::where('cus_id', $this->cus_id)->where('type', 4)->where($this->type . '_show', 1)->pluck('id');
                        }
                        break;
                    case 'navs':
                        if (isset($v['config']['mustchild']) && $v['config']['mustchild'] == true) {
                            if (isset($v['config']['filter'])) {
                                if ($v['config']['filter'] == 'page') {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(4));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                } elseif ($v['config']['filter'] == 'list') {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(1, 2, 3));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } elseif ($v['config']['filter'] == 'ALL') {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(1, 2, 3, 4, 6));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } else {
                                    $c_arr = $classify->toTree($c_arr);
                                    $templates->unsetFalseClassify($c_arr, array(1, 2, 3, 4));
                                    $templates->unsetLastClassify($c_arr);
                                    $c_arr = array_merge($c_arr);
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                }
                            } else {
                                $c_arr = $classify->toTree($c_arr);
                                $templates->unsetFalseClassify($c_arr, array(1, 2, 3));
                                $templates->unsetLastClassify($c_arr);
                                $c_arr = array_merge($c_arr);
                                $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                            }
                        } else {
                            if (isset($v['config']['filter'])) {
                                if ($v['config']['filter'] == 'page') {
                                    $c_arr[0]['id'] = Classify::where('cus_id', $this->cus_id)->where('type', 4)->where($this->type . '_show', 1)->pluck('id');
                                } elseif ($v['config']['filter'] == 'list') {
                                    $c_arr[0]['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3))->where($this->type . '_show', 1)->pluck('id');
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } elseif ($v['config']['filter'] == 'ALL') {
                                    $c_arr[0]['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3, 4, 6))->where($this->type . '_show', 1)->pluck('id');
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                } else {
                                    $c_arr[0]['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3, 4))->where($this->type . '_show', 1)->pluck('id');
                                    $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                                }
                            } else {
                                $c_arr[0]['id'] = Classify::where('cus_id', $this->cus_id)->whereIn('type', array(1, 2, 3))->where($this->type . '_show', 1)->pluck('id');
                                $v['config']['limit'] = isset($v['config']['limit']) ? $v['config']['limit'] : 20;
                            }
                        }
                        $ids = "";
                        $num = $v['config']['limit'];
                        if (count($c_arr)) {
                            $ids = array();
                            for ($i = 0; $i < $num; $i++) {
                                $ids[$i] = $c_arr[0]['id'];
                            }
                        }
                        $v['config']['ids'] = $ids;
                        break;
                    case 'form':
//                        var_dump($v);
//                        exit;
                        break;
                    default:
                        break;
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
    public function dataDeal($data)
    {
        if (!is_array($data))
            dd('json格式错误！');
        $slimming = array();
        foreach ($data as $k => $v) {
            $checkDataK = true;
            foreach ($v as $dk => $vv) {
                if (!preg_match('/^(value|type|description|config)$/', $dk)) {
                    $checkDataK = false;
                }
            }
            if (!$checkDataK || !array_key_exists('value', $v) || !array_key_exists('type', $v) || !array_key_exists('description', $v)) {
                dd('json文件中每个变量的元素必须包含【value、type、description】元素，可选【config】\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json');
            }
            $slimming[$k] = $v;
            // PHP端数据填充与验证
            switch ($v['type']) {
                case 'text':
                    if (!is_string($slimming[$k]['value']))
                        dd('json文件中type为【text】格式的value值必须为【字符串】\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typetext');
                    break;
                case 'textarea':
                    if (!is_string($slimming[$k]['value']))
                        dd('json文件中type为【textarea】格式的value值必须为【字符串】\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typetextarea');
                    $slimming[$k] = preg_replace("/\r\n/", "<br />", $slimming[$k]);
                    $slimming[$k] = preg_replace("/\n/", "<br />", $slimming[$k]);
                    break;
                case 'image':
                    /*
                      if (!array_key_exists('title', $slimming[$k]['value']) || !array_key_exists('image', $slimming[$k]['value']) || !array_key_exists('link', $slimming[$k]['value'])) {
                      echo json_encode(array(
                      'err' => 2001,
                      'data' => null,
                      'msg' => 'json文件中type为【image】格式的value值必须包含【title、image、link】元素，可选【description】\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typeimage'
                      ));
                      exit;
                      }

                      if (!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config']) || (!is_numeric($slimming[$k]['config']['width']) && !is_numeric($slimming[$k]['config']['height']))) {
                      $msg = 'json文件中type为【image】格式应配置【config】的【width】【height】配置项\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typeimage';
                      if (array_key_exists('config', $slimming[$k]) && is_array($slimming[$k]['config']) && array_key_exists('forcesize', $slimming[$k]['config']) && $slimming[$k]['config']['forcesize'] === true) {
                      dd($msg);
                      }else{
                      dd('Error: '.$msg);
                      }
                      }

                     */
                    break;
                case 'images':
                    if (!is_array($slimming[$k]) || !count($slimming[$k]))
                        dd('json文件中type为【images】格式不正确！\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typeimages');
                    foreach ($slimming[$k]['value'] as $key => $val) {
                        if (is_array($val)) {
                            if (!array_key_exists('title', $val) || !array_key_exists('image', $val) || !array_key_exists('link', $val)) {
                                dd('json文件中type为【images】格式value值的每个子元素必须包含【title、image、link】元素，可选【description】\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typeimages');
                            }
                        }
                    }
                    /*
                      if (!array_key_exists('config',$slimming[$k]) || !is_array($slimming[$k]['config']) || (!is_numeric($slimming[$k]['config']['width']) && !is_numeric($slimming[$k]['config']['height']))) {
                      $msg = 'json文件中type为【images】格式应配置【config】的【width】【height】配置项\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:json#typeimages';
                      if (array_key_exists('config', $slimming[$k]) && is_array($slimming[$k]['config']) && array_key_exists('forcesize', $slimming[$k]['config']) && $slimming[$k]['config']['forcesize'] === true) {
                      dd($msg);
                      }else{
                      dd('Error: '.$msg);
                      }
                      } */
                    break;
                case 'page':
                    if (!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config']))
                        $slimming[$k]['config'] = array();
                    $slimming[$k]['config']['filter'] = "page";
                    $slimming[$k]['type'] = "list";
                case 'nav':
                    if (!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config']))
                        $slimming[$k]['config'] = array();
                    if (!array_key_exists('filter', $slimming[$k]['config']) || empty($slimming[$k]['config']['filter']))
                        $slimming[$k]['config']['filter'] = "ALL";
                    $slimming[$k]['type'] = "list";
                case 'navs':
                    if (!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config']))
                        $slimming[$k]['config'] = array();
                    if (!array_key_exists('filter', $slimming[$k]['config']) || empty($slimming[$k]['config']['filter']))
                        $slimming[$k]['config']['filter'] = "ALL";
                    $slimming[$k]['config']['limit'] = array_key_exists('limit', $slimming[$k]['config']) ? $slimming[$k]['config']['limit'] : 0;
                case 'list':
                    if (!array_key_exists('config', $slimming[$k]) || !is_array($slimming[$k]['config']))
                        $slimming[$k]['config'] = array();
                    if (!array_key_exists('filter', $slimming[$k]['config']) || empty($slimming[$k]['config']['filter']))
                        $slimming[$k]['config']['filter'] = "list";
                    $slimming[$k]['config']['limit'] = array_key_exists('limit', $slimming[$k]['config']) ? $slimming[$k]['config']['limit'] : 20;
                    $slimming[$k]['config']['star_only'] = array_key_exists('star_only', $slimming[$k]['config']) && $slimming[$k]['config']['star_only'] ? 1 : 0;
                    break;
                case 'quickbar':
                    //if ($this->type == 'pc') dd('PC模板的json文件中没有type为【quickbar】的变量！\r\n如果你现在制作的是手机模板，请修改config.ini文件对应参数。详情参见：http://pme.eexx.me/doku.php?id=ued:template:config#config_%E6%A8%A1%E6%9D%BF%E9%85%8D%E7%BD%AE%E9%83%A8%E5%88%86');
                    if (!is_string($slimming[$k]) || !count($slimming[$k])) {
                        dd('json文件中type为【navs】格式不正确！\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:mindex#%E5%BA%95%E9%83%A8%E5%AF%BC%E8%88%AA%E5%AE%9A%E4%B9%89%E6%96%B9%E6%B3%95');
                    }
                    foreach ($slimming[$k] as $i => $v) {
                        if (!array_key_exists('name', $v) || !array_key_exists('image', $v) || !array_key_exists('data', $v) || !array_key_exists('type', $v) || !array_key_exists('enable', $v)) {
                            dd('json文件中type为【navs】格式value值的每个子元素必须包【name、image、data、type、enable】元素，可选【childmenu】\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:mindex#%E5%BA%95%E9%83%A8%E5%AF%BC%E8%88%AA%E5%AE%9A%E4%B9%89%E6%96%B9%E6%B3%95');
                        }
                        if (!preg_match("/^(tel|sms|im|link|share)$/", $v['type'])) {
                            dd('json文件中type为【navs】格式value值的子元素的【type】值只能为【tel、sms、im、link、share】其中之一\r\n详情参见：http://pme.eexx.me/doku.php?id=ued:template:mindex#%E5%BA%95%E9%83%A8%E5%AF%BC%E8%88%AA%E5%AE%9A%E4%B9%89%E6%96%B9%E6%B3%95');
                        }
                        if (!array_key_exists('enable', $v)) {
                            unset($slimming[$k][$i]);
                        }
                        switch ($v['type']) {
                            case 'tel':
                                $slimming[$k]['link'] = 'tel://' . $v['data'];
                                break;
                            case 'sms':
                                $slimming[$k]['link'] = 'sms://' . $v['data'];
                                break;
                            case 'share':
                            case 'im':
                                $slimming[$k]['link'] = 'javascript:void(0);';
                                break;
                            case 'link':
                                if (!array_key_exists('childmenu', $v) && !count($v['childmenu'])) {
                                    $slimming[$k]['link'] = 'javascript:void(0);';
                                    foreach ($slimming[$k]['childmenu'] as $kk => $vv) {
                                        if (!array_key_exists('enable', $v['childmenu'])) {
                                            unset($slimming[$k]['childmenu'][$i]);
                                        }
                                        $slimming[$k]['childmenu']['link'] = $vv['data'];
                                    }
                                } else {
                                    $slimming[$k]['link'] = $v['data'];
                                }
                                break;
                            case 'search':
                                if ($this->showtype == 'preview')
                                    $slimming[$k]['data'] = 'http://' . $_SERVER['HTTP_HOST'] . '/search-preview';
                                else
                                    $slimming[$k]['data'] = $this->domain . '/search.php';
                                $slimming[$k]['link'] = 'javascript:void(0);';
                            default:
                                $slimming[$k]['link'] = $v['data'];
                        }
                    }
                    break;
                case 'form':
//                    $slimming[$k] = $v;
                    break;
            }
        }
        if (isset($slimming['feedback'])) {
            $slimming['feedback']['value']['posturl'] = 'http://swap.5067.org/message/' . $this->cus_id;
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
    public function replaceUrl(&$result)
    {
        if (!count($result)) {
            return $result;
        }
        foreach ($result as $k => $v) {
            if (is_array($v)) {
                $this->replaceUrl($result[$k]);
            } else {
                if (($k === 'link' || $k === 'image') && !strstr($v, 'http://') && !strstr($v, 'https://') && $v != "") {
                    if ($k === 'link') {
                        $result[$k] = $this->domain . $v;
                    } else {
                        if (file_exists(public_path("templates/" . $this->themename . '/images/') . preg_replace('/^images\/(.*?)$/', '$1', $v))) {
                            $result[$k] = $this->site_url . 'images/' . preg_replace('/^images\/(.*?)$/', '$1', $v);
                        } elseif (file_exists(public_path("templates/" . $this->themename . '/themes/'. $this->color_dir .'/images/') . preg_replace('/^images\/(.*?)$/', '$1', $v))) {
                            //换色模板的路径
                            $result[$k] = $this->site_url . $this->site_color . 'images/' . preg_replace('/^images\/(.*?)$/', '$1', $v);
                        } else {
                            $result[$k] = $this->source_dir . "l/page_index/" . $v;
                        }
//                        if (file_exists(public_path("customers/" . $this->customer . '/images/') . "l/page_index/" . $v)) {
//                            $result[$k] = $this->source_dir . "l/page_index/" . $v;
//                        } else {
//                            $result[$k] = $this->site_url . 'images/' . preg_replace('/^images\/(.*?)$/', '$1', $v);
//                        }
                    }
                }
            }
        }
    }

    /**
     * 手机底部功能条
     */
    public function quickBarJson()
    {
        $CommonCont = new CommonController();
        $quickbar = $CommonCont->quickBarJsonInit();
        $quickbar = json_decode($quickbar, true);
        $quickbar = $quickbar['data'];
        foreach ($quickbar as $key => $value) {
            if ($value['type'] == "colors") {
                $colors = $value['data'];
                unset($quickbar[$key]);
                break;
            }
        }
        $config_str = file_get_contents(public_path('/templates/' . $this->themename) . '/config.ini');        
        if($this->isColorful == 1) {
            //换色模板匹配快捷导航配置
            $search = "/QuickBar_" . $this->color_dir . "=(.*)/i";
        } else {
            $search = "/QuickBar=(.*)/i";
        }        
        $searchtype = "/Type=(.*)/i";
        $result = preg_match($search, $config_str, $config_arr);
        if (!$result or !trim($config_arr[1])) {
            $result = 1;
            $config_arr = array();
            $config_arr[1] = '#AAA,#BBB,#FFF|totop';
        }
        $lang = CustomerInfo::where('cus_id', $this->cus_id)->pluck('lang');
        $templatesC = new TemplatesController;
        $tempname = $templatesC->getTemplatesName($this->type);
        // $flagPlatform = substr($tempname, 0, 2);
        // $flagLanguage = substr($tempname, 2, 1);
        //===新命名的P、M和语言的获取===
        if(preg_match('/G\d{4}(P|M)(CN|EN|TW|JP)\d{2}/', $tempname)){
            $flagPlatform = substr($tempname, 5, 1);
            $flagLanguage = substr($tempname, 6, 2);  
        } elseif (preg_match('/G(P|M)\d{4}/', $tempname)){
            $flagPlatform = substr($tempname, 0, 2);
            $flagLanguage = substr($tempname, 2, 1);
        }
        //===类型的获取end===
        $customerC = new CustomerController;
        $domain = $customerC->getSwitchCustomer(); //双站用户
        if (!empty($domain)) {
            // if ($flagPlatform == 'GM') {//===手机===
            //     $language_url = $domain['switch_mobile_domain'];
            // } elseif ($flagPlatform == 'GP') {//===PC===
            //     $language_url = $domain['switch_pc_domain'];
            // }
            // if ($flagLanguage == 9) {//===英文===
            //     $language = '中文版';
            // } elseif ($flagLanguage == 0) {//===中文===
            //     $language = 'English';
            // }
            //新命名的判断方式
            if ($flagPlatform == 'M' or $flagPlatform == 'GM') {//===手机
                $language_url = $domain['switch_mobile_domain'];
                $current_url = $domain['current_mobile_domain'];
            } elseif ($flagPlatform == 'P' or $flagPlatform == 'GP') {//===PC
                $language_url = $domain['switch_pc_domain'];
                $current_url = $domain['current_pc_domain'];
            }
            if ($flagLanguage == 'EN' or $flagLanguage == 9) {//===英文
                $language = '<li><a href="' . $language_url . '">中文版</a></li>';
                $language .= '<li><a href="' . $current_url . '">English</a></li>';
            } elseif ($flagLanguage == 'CN' or $flagLanguage == 0) {//===中文
                $language = '<li><a href="' . $current_url . '">中文版</a></li>';
                $language .= '<li><a href="' . $language_url . '">English</a></li>';
            }

        }
        if ($result != 0) {//===?===
            if (trim($config_arr[1]) != "custom") {//===非自定义===
                $quickbar_arr = explode('|', $config_arr[1]);
                $config['enable'] = true;
                if ($this->type == 'pc') {
                    $config['type'] = 'p1';
                } else if ($this->type == 'mobile') {
                    $config['type'] = 'm1';
                } else {
                    $config['type'] = 'p1';
                }
                if ($lang == 'en') {
                    $config['language'] = 'en';
                } else {
                    $config['language'] = 'cn';
                }
                $config['style'] = array();
                if (!isset($colors[$this->type])) {
                    $tmpStyleConfigQuickbar = explode(',', $quickbar_arr[0]);
                } else {
                    $tmpStyleConfigQuickbar = $colors[$this->type];
                }
                if (count($tmpStyleConfigQuickbar)) {
                    $keys = array('mainColor', 'secondColor', 'textColor', 'iconColor');
                    foreach ($tmpStyleConfigQuickbar as $key => $val) {
                        $arr = explode('|', $val);
                        $config['style'][$keys[$key]] = $arr[0];
                    }
                    if (!key_exists('iconColor', $config['style'])) {
                        $config['style']['iconColor'] = $config['style']['textColor'] ? $config['style']['textColor'] : '';
                    }
                }
                //换色模板站
                if(!isset($colors['mobile_demo']) or $this->type != 'mobile') {
                    $config['mdemo']['demo'] = 0;
                } else {
                    $config['mdemo']['demo'] = 1;
                    foreach ($colors['mobile_demo'] as $k => $v) {
                        $num = hexdec($k);
                        if(count($v)) {
                            unset($keys);
                            $keys = array('mainColor', 'secondColor', 'textColor', 'iconColor');
                            foreach ($v as $key => $val) {
                                $arr_color = explode('|', $val);
                                $config['mdemo']['style'][$num][$keys[$key]] = $arr_color[0];
                            }
                            if (!key_exists('iconColor', $config['mdemo']['style'][$num])) {
                                $config['mdemo']['style'][$num]['iconColor'] = $config['mdemo']['style'][$num]['textColor'] ? $config['mdemo']['style'][$num]['textColor'] : '';
                            }
                        }
                    }
                }
                $config['module'] = array();
                if (count($quickbar_arr) > 1) {
                    $tmpModulesConfigQuickbar = explode(',', trim($quickbar_arr[1]));
                    foreach ($tmpModulesConfigQuickbar as $key => $val) {
                        if ($val == 'tel') {
                            $tel = Customerinfo::where('cus_id', $this->cus_id)->pluck('telephone');
                            $config['module']['tel'] = $tel; //数据库联系电话                 
                        } elseif ($val == 'totop') {
                            $config['module']['totop'] = 1;
                        }
                    }
                } else {
                    $config['module'] = array();
                }
                foreach ($quickbar as $key => $val) {
                    if ($this->type == 'pc') {
                        $quickbar[$key]['enable'] = intval($quickbar[$key]['enable_pc']);
                    } else {
                        $quickbar[$key]['enable'] = intval($quickbar[$key]['enable_mobile']);
                    }

                    if ($quickbar[$key]['type'] == 'tel') {
                        $quickbar[$key]['link'] = "tel:" . $quickbar[$key]['data'];
                    } elseif ($quickbar[$key]['type'] == 'sms') {
                        $quickbar[$key]['link'] = "sms:" . $quickbar[$key]['data'];
                    } elseif ($quickbar[$key]['type'] == 'im') {
                        $qq = explode('|', $quickbar[$key]['data']);
                        $qq = explode(':', $qq[0]);
                        $qq = explode('@', (isset($qq[1]) ? $qq[1] : ''));
                        if ($this->type == 'pc') {
                            $quickbar[$key]['link'] = 'http://wpa.qq.com/msgrd?v=3&uin=' . $qq[0] . '&site=qq&menu=yes';
                        } else {
                            $quickbar[$key]['link'] = 'http://wpd.b.qq.com/cgi/get_m_sign.php?uin=' . $qq[0];
                        }
                    } elseif ($quickbar[$key]['type'] == 'map') {
                        if ($quickbar[$key]['data'] != null) {
                            $location = explode('|', $quickbar[$key]['data']);
                            $address = explode(',', $location[1]);
                            $quickbar[$key]['link'] = 'http://api.map.baidu.com/marker?location=' . $address[1] . ',' . $address[0] . '&title=目标位置&content=' . $location[0] . '&output=html';
                        } else {
                            $address = CustomerInfo::where('cus_id', $this->cus_id)->pluck('address');
                            $quickbar[$key]['link'] = 'http://api.map.baidu.com/geocoder?address=' . $address . '&output=html';
                        }
                    } elseif ($quickbar[$key]['type'] == 'link') {
                        if ($quickbar[$key]['data'] != null) {
                            $url_arr = explode('|', $quickbar[$key]['data']);
                            $quickbar[$key]['link'] = $url_arr[0];
                        }
                    } elseif ($quickbar[$key]['type'] == 'search') {
                        if ($this->showtype == 'preview') {
                            $quickbar[$key]['data'] = 'http://' . $_SERVER['HTTP_HOST'] . '/search-preview';
                        } else {
                            $quickbar[$key]['data'] = $this->domain . '/search.php';
                        }
                    } elseif ($quickbar[$key]['type'] == 'follow') {
                        if ($this->showtype == 'preview') {
                            if (strpos($quickbar[$key]['data'], 'http://') === FALSE) {
                                $quickbar[$key]['data'] = $quickbar[$key]['serurl'];
                            }
                        }
                    }
                    //删除enable_pc/enable_mobile键值
                    unset($quickbar[$key]['enable_pc']);
                    unset($quickbar[$key]['enable_mobile']);
                }
                //快捷导航
                $navs = Classify::where('cus_id', $this->cus_id)->where('mobile_show', 1)->select('id', 'type', 'open_page', 'name', 'en_name', 'view_name', 'icon', 'url', 'p_id')->OrderBy('sort', 'asc')->get()->toArray();
                if (count($navs)) {
                    if ($this->showtype == 'preview') {
                        foreach ($navs as &$nav) {
                            $nav['icon'] = '<i class="iconfont">' . $nav['icon'] . '</i>';
                            if (in_array($nav['type'], array('1', '2', '3', '4', '5', '9'))) {
//                                if ($nav['view_name']) {
//                                    $nav['url'] = $this->domain . "/category/v/" . $nav['view_name'];
//                                } else {
                                //category链接位置(手机快捷导航(非自定义底部导航))
                                $nav['url'] = $this->domain . "/category/" . $nav['id'];
//                                }
                            }
                        }
                    } else {
                        foreach ($navs as &$nav) {
                            $nav['icon'] = '<i class="iconfont">' . $nav['icon'] . '</i>';
                            if (in_array($nav['type'], array('1', '2', '3', '4', '5', '9'))) {
//                                if ($nav['view_name']) {
//                                    $nav['url'] = $this->domain . "/category/v/" . $nav['view_name'] . '.html';
//                                } else {
                                $nav['url'] = $this->domain . "/category/" . $nav['id'] . '.html';
//                                }
                            }
                        }
                    }
                }
                $classify = new Classify();
                $catlist = $classify->toTree($navs);
                if (!is_array($catlist)) {
                    $catlist = array();
                }
                array_unshift($catlist, array('id' => null, 'name' => '首页', 'en_name' => 'Home', 'url' => $this->site_url, 'childmenu' => null));
                $quickbarCallback = array('config' => $config, 'quickbar' => $quickbar, 'catlist' => $catlist);
                if ($this->showtype == 'preview') {
                    echo "quickbarCallback(" . json_encode($quickbarCallback) . ")"; //===返回===
                } else {
                    if ($this->type == 'pc') {
                        file_put_contents(public_path("customers/" . $this->customer . '/quickbar.json'), "quickbarCallback(" . json_encode($quickbarCallback) . ")");
                    } else {
                        file_put_contents(public_path("customers/" . $this->customer . '/mobile' . '/quickbar.json'), "quickbarCallback(" . json_encode($quickbarCallback) . ")");
                    }
                }
            } else {//===自定义===
                $config_arr[1] = '#AAA,#BBB,#FFF|tel';
                $quickbar_arr = explode('|', $config_arr[1]);
                $tmpStyleConfigQuickbar = explode(',', $quickbar_arr[0]);
                $config['enable'] = true;
                if ($this->type == 'pc') {
                    $config['type'] = 'custom';
                } else {
                    $config['type'] = 'custom';
                }
                $config['style'] = array();
                if (count($tmpStyleConfigQuickbar)) {
                    $keys = array('mainColor', 'secondColor', 'textColor', 'iconColor');
                    foreach ($tmpStyleConfigQuickbar as $key => $val) {
                        $arr = explode('|', $val);
                        $config['style'][$keys[$key]] = $arr[0];
                    }
                    if (!key_exists('iconColor', $config['style'])) {
                        $config['style']['iconColor'] = $config['style']['textColor'] ? $config['style']['textColor'] : '';
                    }
                }
                //换色模板站
                if(!isset($colors['mobile_demo'])) {
                    $config['mdemo']['demo'] = 0;
                } else {
                    $config['mdemo']['demo'] = 1;
                    foreach ($colors['mobile_demo'] as $k => $v) {
                        if(count($v)) {
                            unset($keys);
                            $keys = array('mainColor', 'secondColor', 'textColor', 'iconColor');
                            foreach ($v as $key => $val) {
                                $arr_color = explode('|', $val);
                                $config['mdemo']['style'][$k][$keys[$key]] = $arr_color[0];                                
                            }                            
                            if (!key_exists('iconColor', $config['mdemo']['style'][$k])) {
                                $config['mdemo']['style'][$k]['iconColor'] = $config['mdemo']['style'][$k]['textColor'] ? $config['mdemo']['style'][$k]['textColor'] : '';
                            }
                        }
                    }
                }
                foreach ($quickbar as $key => $val) {
                    if ($this->type == 'pc') {
                        $quickbar[$key]['enable'] = intval($quickbar[$key]['enable_pc']);
                    } else {
                        $quickbar[$key]['enable'] = intval($quickbar[$key]['enable_mobile']);
                    }
                    //删除enable_pc/enable_mobile键值
                    unset($quickbar[$key]['enable_pc']);
                    unset($quickbar[$key]['enable_mobile']);
                }

                foreach ($quickbar as $key => $val) {
                    if ($quickbar[$key]['type'] == 'tel') {
                        $quickbar[$key]['link'] = "tel:" . $quickbar[$key]['data'];
                    } elseif ($quickbar[$key]['type'] == 'sms') {
                        $quickbar[$key]['link'] = "sms:" . $quickbar[$key]['data'];
                    } elseif ($quickbar[$key]['type'] == 'im') {
                        $qq = explode('|', $quickbar[$key]['data']);
                        $qq = explode(':', $qq[0]);
                        $qq = explode('@', $qq[1]);
                        if ($this->type == 'pc') {
                            $quickbar[$key]['link'] = 'http://wpa.qq.com/msgrd?v=3&uin=' . $qq[0] . '&site=qq&menu=yes';
                        } else {
                            $quickbar[$key]['link'] = 'http://wpd.b.qq.com/cgi/get_m_sign.php?uin=' . $qq[0];
                        }
                    } elseif ($quickbar[$key]['type'] == 'map') {
                        if ($quickbar[$key]['data'] != null) {
                            $location = explode('|', $quickbar[$key]['data']);
                            $address = explode(',', $location[1]);
                            $quickbar[$key]['link'] = 'http://api.map.baidu.com/marker?location=' . $address[1] . ',' . $address[0] . '&title=目标位置&content=' . $location[0] . '&output=html';
                        } else {
                            $address = CustomerInfo::where('cus_id', $this->cus_id)->pluck('address');
                            $quickbar[$key]['link'] = 'http://api.map.baidu.com/geocoder?address=' . $address . '&output=html';
                        }
                    } elseif ($quickbar[$key]['type'] == 'link') {
                        if ($quickbar[$key]['data'] != null) {
                            $url_arr = explode('|', $quickbar[$key]['data']);
                            $quickbar[$key]['link'] = $url_arr[0];
                        }
                    } elseif ($quickbar[$key]['type'] == 'search') {
                        if ($this->showtype == 'preview') {
                            $quickbar[$key]['data'] = 'http://' . $_SERVER['HTTP_HOST'] . '/search-preview';
                        } else {
                            $quickbar[$key]['data'] = $this->domain . '/search.php';
                        }
                    } elseif ($quickbar[$key]['type'] == 'follow') {
                        if ($this->showtype == 'preview') {
                            if (strpos($quickbar[$key]['data'], 'http://') === FALSE) {
                                $quickbar[$key]['data'] = $quickbar[$key]['serurl'];
                            }
                        }
                    }
                }

                $navs = Classify::where('cus_id', $this->cus_id)->where('mobile_show', 1)->select('id', 'type', 'name', 'en_name', 'view_name', 'icon', 'url', 'p_id', 'en_name')->OrderBy('sort', 'asc')->get()->toArray();
                if (count($navs)) {
                    if ($this->showtype == 'preview') {
                        foreach ($navs as &$nav) {
                            $nav['icon'] = '<i class="iconfont">' . $nav['icon'] . '</i>';
                            if (in_array($nav['type'], array('1', '2', '3', '4'))) {
//                                if ($nav['view_name']) {
//                                    $nav['url'] = $this->domain . "/category/v/" . $nav['view_name'];
//                                } else {
                                //category链接位置(手机快捷导航(自定义底部导航))
                                $nav['url'] = $this->domain . "/category/" . $nav['id'];
//                                }
                            }
                        }
                    } else {
                        foreach ($navs as &$nav) {
                            $nav['icon'] = '<i class="iconfont">' . $nav['icon'] . '</i>';
                            if (in_array($nav['type'], array('1', '2', '3', '4'))) {
//                                if ($nav['view_name']) {
//                                    $nav['url'] = $this->domain . "/category/v/" . $nav['view_name'] . '.html';
//                                } else {
                                $nav['url'] = $this->domain . "/category/" . $nav['id'] . '.html';
//                                }
                            }
                        }
                    }
                }
                $classify = new Classify();

                $catlist = $classify->toTree($navs);
                if (!is_array($catlist)) {
                    $catlist = array();
                }
                array_unshift($catlist, array('id' => null, 'name' => '首页', 'en_name' => 'Home', 'url' => $this->site_url, 'childmenu' => null));
                if (!empty($domain)) {
                    $catlist[] = array('id' => null, 'name' => $language, 'en_name' => 'language', 'url' => $language_url, 'childmenu' => null); //===
                }
                $quickbarCallback = array('config' => $config, 'quickbar' => $quickbar, 'catlist' => $catlist);
                if ($this->showtype == 'preview') {
                    echo "quickbarCallback(" . json_encode($quickbarCallback) . ")";
                } else {
                    if ($this->type == 'pc') {
                        file_put_contents(public_path("customers/" . $this->customer . '/quickbar.json'), "quickbarCallback(" . json_encode($quickbarCallback) . ")");
                    } else {
                        file_put_contents(public_path("customers/" . $this->customer . '/mobile' . '/quickbar.json'), "quickbarCallback(" . json_encode($quickbarCallback) . ")");
                    }
                }                
            }
            //如果有小程序
            if($this->is_applets and $this->type == 'mobile') {
                //匹配json数据的正则(json类型，搜索页面，栏目链接)
                $wx_pattern = ["/(\"type\"\:\")m1(\")/", "/(\"data\"\:\")\\\\\/(search\.php\")/", "/(\"url\"\:\")\\\\\/(category)?/"];
                //替换为
                $wx_replace = ['${1}w1${2}','${1}\/' . $this->customer . '\/${2}', '${1}\/' . $this->customer . '\/${2}'];
                //匹配替换
                $wx_out = preg_replace($wx_pattern, $wx_replace, json_encode($quickbarCallback));
                //检测目录
                if(!is_dir(public_path("customers/" . $this->customer . '/wx'))) {
                    mkdir(public_path('customers/' . $this->customer . '/wx'));
                }
                //放入文件
                file_put_contents(public_path("customers/" . $this->customer . '/wx/quickbar.json'), "quickbarCallback(" . $wx_out . ")");
            }
        }
    }

    /**
     * 根据栏目id获取页面的公共数据，包括logo、path、stylecolor、navs、logo、footprint等
     *
     * @param int $c_id 栏目id,只为用于导航navs的状态
     * @return array 返回一个包含公共数据的数组
     */
    private function pagePublic($c_id = 0)
    {
        $result = $this->publicdata();
        $result['navs'] = $this->publicnavs($c_id);
        $result['index_navs'] = $result['navs'];
        return $result;
    }

    /**
     * 公共数据
     * @return string
     */
    public function publicdata()
    {
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        //===显示版本切换链接===
        $templatesC = new TemplatesController;
        $tempname = $templatesC->getTemplatesName($this->type);
        // $flagPlatform = substr($tempname, 0, 2);
        // $flagLanguage = substr($tempname, 2, 1);
        //===新命名的P、M和语言的获取===
        if(preg_match('/G\d{4}(P|M)(CN|EN|TW|JP)\d{2}/', $tempname)){
            $flagPlatform = substr($tempname, 5, 1);
            $flagLanguage = substr($tempname, 6, 2);  
        } elseif (preg_match('/G(P|M)\d{4}/', $tempname)){
            $flagPlatform = substr($tempname, 0, 2);
            $flagLanguage = substr($tempname, 2, 1);
        }
        //===类型的获取end===
        $tempscript = "";
        $language_css = "";
        $customerC = new CustomerController;
        $domain = $customerC->getSwitchCustomer(); //双站用户
        $current_url = '#';
        $language_url = '#';
        if (!empty($domain)) {//===中英文双站===
            if ($customer_info->bilingual_v) {
                // if ($flagPlatform == 'GM') {//===手机
                //     $language_url = $domain['switch_mobile_domain'];
                //     $current_url = $domain['current_mobile_domain'];
                // } elseif ($flagPlatform == 'GP') {//===PC
                //     $language_url = $domain['switch_pc_domain'];
                //     $current_url = $domain['current_pc_domain'];
                // }
                // if ($flagLanguage == 9) {//===英文
                //     $language = '<li><a href="' . $language_url . '">中文版</a></li>';
                //     $language .= '<li><a href="' . $current_url . '">English</a></li>';
                // } elseif ($flagLanguage == 0) {//===中文
                //     $language = '<li><a href="' . $current_url . '">中文版</a></li>';
                //     $language .= '<li><a href="' . $language_url . '">English</a></li>';
                // }
                //===新命名的判断方式===
                if ($flagPlatform == 'M' or $flagPlatform == 'GM') {//===手机
                    $language_url = $domain['switch_mobile_domain'];
                    $current_url = $domain['current_mobile_domain'];
                } elseif ($flagPlatform == 'P' or $flagPlatform == 'GP') {//===PC
                    $language_url = $domain['switch_pc_domain'];
                    $current_url = $domain['current_pc_domain'];
                }
                if ($flagLanguage == 'EN' or $flagLanguage == 9) {//===英文
                    $language = '<li><a href="' . $language_url . '">中文版</a></li>';
                    $language .= '<li><a href="' . $current_url . '">English</a></li>';
                } elseif ($flagLanguage == 'CN' or $flagLanguage == 0) {//===中文
                    $language = '<li><a href="' . $current_url . '">中文版</a></li>';
                    $language .= '<li><a href="' . $language_url . '">English</a></li>';
                }
                //===新命名===
                $language_ul = '<ul>'
                    . $language
                    . '</ul>';

                $tempscript = '<script type="text/javascript">'
                    . 'var _body=document.body;'
                    . 'var _div=document.createElement("div");'
                    . '_div.setAttribute("class", "language_div");'
                    . '_div.innerHTML=\'' . $language_ul . '\';'
                    . '_body.appendChild(_div);'
                    . '</script>';
//            $language_css = '<link rel="stylesheet" href="http://swap.5067.org/css/language.css">';
                $language_css = '<style type="text/css">
                    .language_div{z-index:10002; width:160px; height: 36px; position: absolute;top:0px; right:' . $customer_info->bilingual_position . 'px;background-color: ' . $customer_info->bilingual_background_color . '; opacity: ' . $customer_info->bilingual_background_opacity . ';}
                    .language_div ul{ margin: 0 auto;}
                    .language_div ul li{width: 80px;height: 36px;float: right;text-align: center; }
                    .language_div ul li a{ line-height: 36px; display: inline-block; padding: 0 10px;color:' . $customer_info->bilingual_font_color . ';}
                    .language_div ul li a:hover{color:' . $customer_info->bilingual_font_active_color . ';}
                </style>';
            }
        }
        //===显示版本切换链接-end===
        $formC = new FormController();
        $formJS = $this->insetForm(); //===表单嵌入===
        $add_color_css = "";
        if ($this->type == 'pc') {
            $stylecolor = websiteInfo::leftJoin('color', 'color.id', '=', 'website_info.pc_color_id')->where('cus_id', $this->cus_id)->pluck('color_en'); //===!获取模板颜色===
            //===!加载该颜色的CSS文件===
            $css_file_name = public_path('templates/' . $this->themename) . '/css/style_' . $stylecolor . '.css';
            if (file_exists($css_file_name)) {
                $css_file_name = 'templates/' . $this->themename . '/css/style_' . $stylecolor . '.css';
                $add_color_css = '<script type="text/javascript">$("head").append(\'<link rel="stylesheet" href="' . $css_file_name . '" type="text/css">\')</script>';
            }
            $logo = $this->showtype == 'preview' ? '/customers/' . $this->customer . '/images/l/common/' . $customer_info->logo : $this->domain . '/images/l/common/' . $customer_info->logo; //'preview' ? asset('customers/' . $this->customer . '/images/l/common/' . $customer_info->logo) : $this->domain . '/images/l/common/' . $customer_info->logo;
            $floatadv = json_decode($customer_info->floatadv); //===浮动类型===
            if (!empty($floatadv)) {
                foreach ((array)$floatadv as $key => $val) {
                    if (!isset($val->type) || $val->type == 'adv') {
                        if ($this->showtype == 'preview') {
                            $floatadv[$key]->url = ('/customers/' . $this->customer . '/images/l/common/' . $val->adv); //asset('customers/' . $this->customer . '/images/l/common/' . $val->adv);
                        } else {
                            $floatadv[$key]->url = $this->domain . '/images/l/common/' . $val->adv;
                        }
                    } elseif ($val->type == 'form') {
                        $form_id = $val->adv;
                        $formCdata = $formC->getFormdataForPrint($form_id);
                        if (!empty($formCdata)) {
                            $form_content = $formC->showFormHtmlForPrint($formCdata, 'float');
                            $floatadv[$key]->content = $form_content;
                            $floatadv[$key]->cssjs = $formC->assignFormCSSandJSForPrint();
                        }
                    }
                }
            }

            if (count($floatadv)) {
                $url = "http://swap.5067.org/floatadv_new.php";
//                $url = "http://swap.5067.org/floatadv.php";
                $post_data = json_encode($floatadv);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array("data" => $post_data));
                $floatadvprint = curl_exec($ch);
                curl_close($ch);
            }
            $headscript = $customer_info->pc_header_script;
            //===版权选择===
            switch ($customer_info->copyright) {
                case 'en_xiamen':
                    $_href = 'http://www.12t.cn/';
                    $_copyright = 'Technology support：<a href="' . $_href . '">XIAMEN 12t NETWORK TECHNOLOGY CO., LTD.</a>';
                    break;
                case 'en_huizhou':
                    $_href = 'http://www.ydbaidu.net/';
                    $_copyright = 'Technology support：<a href="' . $_href . '">HUIZHOU YIRUITONG NETWORK TECHNOLOGY CO., LTD.</a>';
                    break;
                case 'cn_xiamen':
                    $_href = 'http://www.12t.cn/';
                    $_copyright = '技术支持：<a href="' . $_href . '">厦门易尔通网络科技有限公司</a> ';
                    break;
                case 'cn_huizhou':
                    $_href = 'http://www.ydbaidu.net/';
                    $_copyright = '技术支持：<a href="' . $_href . '">惠州易瑞通网络科技有限公司</a> ';
                    break;
                case 'null':
                    $_href = '';
                    $_copyright = '';
                    break;
                default :
                    $_href = '<a href="http://www.12t.cn/">';
                    $_copyright = '技术支持：' . $_href . '厦门易尔通网络科技有限公司</a>';
                    break;
            }

            switch ($customer_info->talent_support) {
                case 'en_rencai':
                    $talent_support = 'Talent support：<a href="http://www.xgzrc.com/">www.xgzrc.com</a>';
                    break;
                case 'en_null':
                    $talent_support = '';
                    break;
                case 'cn_rencai':
                    $talent_support = '人才支持：<a href="http://www.xgzrc.com/">厦门人才网</a>';
                    break;
                case 'cn_null':
                    $talent_support = '';
                    break;
                default :
                    $talent_support = '人才支持：<a href="http://www.xgzrc.com/">厦门人才网</a>';
                    break;
            }
            if ($customer_info->lang == 'en') {
                $footprint = $customer_info->footer . '<p>' . $_copyright . $talent_support . '</p>';
            } else {
                $footprint = $customer_info->footer . '<p>' . $_copyright . $talent_support . '</p>';
            }
            //===版权选择_end===

            //是换色demo站PC插入换色用的js
            if($this->is_demo == 1) {
                $footscript = '<script type="text/javascript" src="http://chanpin.xm12t.com.cn/js/colorful.js"></script>';
                $colorscript = '<div id="over" style="display: hidden;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: #f5f5f5;opacity:1.0;z-index: 1000;"></div><div id="layout" style="display: hidden;position: absolute;top: 40%;left: 40%;width: 20%;height: 20%;z-index: 1001;text-align:center;"><img src="http://chanpin.xm12t.com.cn/images/2013112931.gif" alt="" /></div><script type="text/javascript" src="http://chanpin.xm12t.com.cn/js/loading.js"></script>';
            } else {
                $footscript = '';
                $colorscript = '';
            }
            $footscript .= $customer_info->pc_footer_script;
            //判断是否有重定向，有则插入重定向js
            if($customer_info->is_redirect){
                $footscript .= '<script>'
                               .'$(document).ready(function() { '
                               .'var host = window.location.host;'
                               .'function GetUrlRelativePath(){'
                               .'var url = document.location.toString();'
                               .'var arrUrl = url.split("//");'
                               .'var start = arrUrl[1].indexOf("/");'
                               .'var relUrl = arrUrl[1].substring(start);'
                               .'if(relUrl.indexOf("?") != -1){'
                               .'relUrl = relUrl.split("?")[0];}'
                               .'return relUrl;}'
                               .'url=GetUrlRelativePath();'
                               .'if(host=="'.$customer_info->ed_url.'"){'
                               .'window.location.href = "http://'.$customer_info->redirect_url.'"+url;}'
                               .'});'
                               .'</script>';
            }
            $footscript .= $formJS;
            $footscript .= '<script type="text/javascript" src="/quickbar/js/quickbar.js?' . $this->cus_id . 'pc"></script>';
            $footscript .= $add_color_css;
//            $footscript .= '<script type="text/javascript" src="http://swap.5067.org/admin/statis.php?cus_id=' . $this->cus_id . '&platform=pc"></script>'; //===添加统计代码PC===
            if ($customer_info->background_music) {
                $bgm = str_replace('"', "", $customer_info->background_music);
                $footscript .= '<script type="text/javascript">$("body").append("<div id=\"bg-music\">' . $bgm . '</div>")
                    $("#bg-music").css("display","none");
                </script>';
            }
            $footscript .= $tempscript;
            $footscript .= $language_css;
            //浏览器版本过低提示
            $footscript .= '<script>'
                           .'$(document).ready(function() { '
                           .'var b_name = navigator.appName; '
                           .'var b_version = navigator.appVersion;'
                           .'var version = b_version.split(";");'
                           .'if(version[1]){'
                           .'var trim_version = version[1].replace(/[ ]/g, "");'
                           .'if (b_name == "Microsoft Internet Explorer") { '
                           .'if (trim_version == "MSIE7.0" || trim_version == "MSIE6.0"|| trim_version == "MSIE8.0") {'
                           .'$("body").append(\'<div class="banbendi" style="width:100%;height:30px;background:#FFFF99;text-align:center;line-height:30px;color:#666666;position:absolute;top:0;left:0;" onClick="hid()">您的浏览器版本过低，会影响网页浏览，请使用更高版本的浏览器</div>\');}}}});'
                           .'function hid(){$(".banbendi").css("display","none");}'
                           .'</script>';
            //PC站的手机外域名
            // $site_another_url = $this->showtype == 'preview' ? '' : $customer_info->mobile_domain ? $customer_info->mobile_domain : $customer_info->mobile_out_domain;
            if($this->showtype == 'preview') {
                $site_another_url = '';
            } else {
                if($customer_info->mobile_domain) {
                    $site_another_url = $customer_info->mobile_domain;
                } else {
                    $site_another_url = $customer_info->mobile_out_domain;
                }
            }
        } else {
            $logo = $this->showtype == 'preview' ? ('/customers/' . $this->customer . '/images/l/common/' . $customer_info->logo_small) : $this->domain . '/images/l/common/' . $customer_info->logo_small; //'preview' ? asset('customers/' . $this->customer . '/images/l/common/' . $customer_info->logo_small) : $this->domain . '/images/l/common/' . $customer_info->logo_small;
            $stylecolor = websiteInfo::leftJoin('color', 'color.id', '=', 'website_info.mobile_color_id')->where('cus_id', $this->cus_id)->pluck('color_en');
            //===!加载该颜色的CSS文件===
            $css_file_name = public_path('templates/' . $this->themename) . '/css/style_' . $stylecolor . '.css';
            if (file_exists($css_file_name)) {
                $css_file_name = 'templates/' . $this->themename . '/css/style_' . $stylecolor . '.css';
                $add_color_css = '<script type="text/javascript">$("head").append(\'<link rel="stylesheet" href="' . $css_file_name . '" type="text/css">\')</script>';
            }
            $headscript = $customer_info->mobile_header_script;
            $footprint = $customer_info->mobile_footer;
            //是换色demo站手机插入换色用的js
            if($this->is_demo == 1) {
                $footscript = '<script type="text/javascript" src="http://chanpin.xm12t.com.cn/js/colorful.js"></script>';
                $colorscript = '<div id="over" style="display: hidden;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: #f5f5f5;opacity:1.0;z-index: 1000;"></div><div id="layout" style="display: hidden;position: absolute;top: 40%;left: 40%;width: 20%;height: 20%;z-index: 1001;text-align:center;"><img src="http://chanpin.xm12t.com.cn/images/2013112931.gif" alt="" /></div><script type="text/javascript" src="http://chanpin.xm12t.com.cn/js/loading.js"></script>';
            } else {
                $footscript = '';
                $colorscript = '';
            }
            $footscript .= $customer_info->mobile_footer_script;
//            $footscript .= $formJS;
            $footscript .= '<script type="text/javascript" src="/quickbar/js/quickbar.js?' . $this->cus_id . 'mobile"></script>';
            $footscript .= $add_color_css;
//            $footscript .= '<script type="text/javascript" src="http://swap.5067.org/admin/statis.php?cus_id=' . $this->cus_id . '&platform=mobile"></script>'; //===添加统计代码MOBILE===
//            $footscript .= $tempscript;
//            $footscript .= $language_css;
            //手机站的PC外域名
            // $site_another_url = $this->showtype == 'preview' ? '' : $customer_info->pc_domain ? $customer_info->pc_domain : $customer_info->pc_out_domain;
            if($this->showtype == 'preview') {
                $site_another_url = '';
            } else {
                if($customer_info->pc_domain) {
                    $site_another_url = $customer_info->pc_domain;
                } else {
                    $site_another_url = $customer_info->pc_out_domain;
                }
            }
            $config_arr = parse_ini_file(public_path('/templates/' . $this->themename) . '/config.ini', true);
            if (!is_array($config_arr)) {
                dd('【config.ini】文件不存在！文件格式说明详见：http://pme.eexx.me/doku.php?id=ued:template:config');
            }
        }
        //获取global信息
        if ($this->type == 'pc') {
            $global_data = $this->pagedata('global');
            $global_data = $this->detailList($global_data);
        } else {
            $global_data = WebsiteConfig::where('cus_id', $this->cus_id)->where('type', 2)->where('template_id', $this->tpl_id)->pluck('value');
            if ($global_data) {
                $global_data = unserialize($global_data);
            } else {
                $global_data = $this->mobilePageList('global', true);
            }
            //===对多图进行排序===
            if (is_array($global_data)) {
                foreach ($global_data as $key => &$value) {
                    if ($key == 'slidepics') {
                        $slidepics_data = $value['value'];
                        if (is_array($slidepics_data)) {
                            foreach ($slidepics_data as $k => $v) {
                                if (isset($v['sort'])) {
                                    $sort[$k] = is_numeric($v['sort']) ? $v['sort'] : 100;
                                    $value['value'][$k]['sort'] = is_numeric($v['sort']) ? $v['sort'] : 100;
                                } else {
                                    $sort[$k] = 100;
                                    $value['value'][$k]['sort'] = 100;
                                }
                            }
                        }
                        array_multisort($sort, $slidepics_data);
                        $value['value'] = $slidepics_data;
                    }
                }
            }
            //===对多图进行排序_end===
            if (count($global_data) > 0) {
                $quickbarKey = false;
                foreach ($global_data as $gkey => $gval) {
                    if ($global_data[$gkey]['type'] == 'quickbar') {
                        $quickbar = WebsiteConfig::where('cus_id', $this->cus_id)->where('key', 'quickbar')->pluck('value');
                        if ($quickbar) {
                            $quickbar = unserialize($quickbar);
                            foreach ($quickbar as $key => $val) {
                                $quickbar[$key]['enable'] = intval($quickbar[$key]['enable_mobile']);
                                if ($quickbar[$key]['type'] == 'tel') {
                                    $quickbar[$key]['link'] = "tel:" . $quickbar[$key]['data'];
                                } elseif ($quickbar[$key]['type'] == 'sms') {
                                    $quickbar[$key]['link'] = "sms:" . $quickbar[$key]['data'];
                                } elseif ($quickbar[$key]['type'] == 'im') {
                                    $qq = explode('|', $quickbar[$key]['data']);
                                    $qq = explode(':', $qq[0]);
                                    $qq = explode('@', $qq[1]);
                                    $quickbar[$key]['link'] = 'http://wpd.b.qq.com/cgi/get_m_sign.php?uin=' . $qq[0];
                                } elseif ($quickbar[$key]['type'] == 'map') {
                                    if ($quickbar[$key]['data'] != null) {
                                        $location = explode('|', $quickbar[$key]['data']);
                                        $address = explode(',', $location[1]);
                                        $quickbar[$key]['link'] = 'http://api.map.baidu.com/marker?location=' . $address[1] . ',' . $address[0] . '&title=目标位置&content=' . $location[0] . '&output=html';
                                    } else {
                                        $address = CustomerInfo::where('cus_id', $this->cus_id)->pluck('address');
                                        $quickbar[$key]['link'] = 'http://api.map.baidu.com/geocoder?address=' . $address . '&output=html';
                                    }
                                } elseif ($quickbar[$key]['type'] == 'link') {
                                    if ($quickbar[$key]['data'] != null) {
                                        $url_arr = explode('|', $quickbar[$key]['data']);
                                        $quickbar[$key]['link'] = $url_arr[0];
                                    }
                                }
                                //TODO:删除enable_pc/enable_mobile键值
                                unset($quickbar[$key]['enable_pc']);
                                unset($quickbar[$key]['enable_mobile']);
                            }
                            $quickbarKey = $gkey;
                        } else {
                            foreach ($global_data[$gkey]['value'] as $key => $val) {
                                if ($global_data[$gkey]['value'][$key]['type'] == 'tel') {
                                    $global_data[$gkey]['value'][$key]['link'] = "tel:" . $global_data[$gkey]['value'][$key]['data'];
                                } elseif ($global_data[$gkey]['value'][$key]['type'] == 'sms') {
                                    $global_data[$gkey]['value'][$key]['link'] = "sms:" . $global_data[$gkey]['value'][$key]['data'];
                                } elseif ($global_data[$gkey]['value'][$key]['type'] == 'im') {
                                    $qq = explode('|', $global_data[$gkey]['value'][$key]['data']);
                                    $qq = explode(':', $qq[0]);
                                    $qq = explode('@', $qq[1]);
                                    $global_data[$gkey]['value'][$key]['link'] = 'http://wpd.b.qq.com/cgi/get_m_sign.php?uin=' . $qq[0];
                                } elseif ($global_data[$gkey]['value'][$key]['type'] == 'link') {
                                    $address = CustomerInfo::where('cus_id', $this->cus_id)->pluck('address');
                                    $global_data[$gkey]['value'][$key]['link'] = 'http://api.map.baidu.com/geocoder?address=' . $address . '&output=html';
                                }
                            }
                            $quickbarKey = $gkey;
                            $quickbar = $global_data[$gkey]['value'];
                        }
                    } elseif ($global_data[$gkey]['type'] == 'images' or $global_data[$gkey]['type'] == 'image') {
                        $img = 1;
                        foreach ($global_data[$gkey]['value'] as $img_key => $img_value) {
                            if ($img_value) {
                                $img = 0;
                            }
                        }
                        if ($img) {
                            $global_data[$gkey]['value'] = array();
                        }
                    }
                }
            }
            $global_data = $this->detailList($global_data);
            $this->replaceUrl($global_data);
            if (!empty($quickbarKey)) {
                $global_data[$quickbarKey] = $quickbar;
            }
        }
        $contact = CustomerInfo::where('cus_id', $this->cus_id)->select('company', 'contact_name as name', 'mobile', 'telephone', 'fax', 'email as mail', 'qq', 'address')->first()->toArray();
        if ($this->showtype == 'preview') {
            if ($this->type == 'pc') {
                $pc_domain = 'http://' . $_SERVER['HTTP_HOST'] . '/search-preview';
            } else {
                $pc_domain = 'http://' . $_SERVER['HTTP_HOST'] . '/mobile/search-preview';
            }
        } else {
            $pc_domain = $this->domain . '/search.php';
        }
        $result = [
            'stylecolor' => $stylecolor,
            'favicon' => $customer_info->favicon ? (rtrim($this->source_dir, 'images/') . '/images/l/common/' . $customer_info->favicon) : '',
            'logo' => $logo,
            'floatadvprint' => isset($floatadvprint) ? $floatadvprint : '',
            'headscript' => $headscript,
            'footprint' => $footprint,
            'footscript' => $footscript,
            'global' => $global_data,
            'site_url' => $this->site_url,
            'site_color' => $this->site_color,
            'colorscript' => $colorscript,
            'site_another_url' => (str_replace('http://', '', $site_another_url) ? $site_another_url : ''),
            'contact' => $contact,
            'search_action' => $pc_domain //'http://swap.gbpen.com'
        ];

        if ($this->type == 'pc') {
            $footer_navs = Classify::where('cus_id', $this->cus_id)->where('footer_show', 1)->select('id', 'type', 'img', 'icon', 'name', 'url', 'p_id', 'en_name', 'meta_description as description', 'open_page')->OrderBy('sort', 'asc')->get()->toArray();
            $footer_navs = $this->toFooter($footer_navs, 0);
            $result['footer_navs'] = $footer_navs;
            $result['type'] = 'pc';
        }
        return $result;
    }

    /**
     * ===嵌入表单===
     * 获取表单信息保存到json
     */
    public function insetForm()
    {
        return '<script type="text/javascript" src="/quickbar/js/form.js?name=' . $this->customer . '"></script>';
    }

    /**
     * 公共导航
     *
     * @param type $c_id
     * @return type
     */
    public function publicnavs($c_id = 0)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        //===whereIN(type:9) 万用表单===
        if ($this->type == 'pc') {
            $navs = Classify::where('cus_id', $this->cus_id)->where('pc_show', 1)->whereIN('type', [1, 2, 3, 4, 5, 6, 9,10])->select('id', 'type', 'img', 'icon', 'name', 'url', 'p_id', 'en_name', 'view_name', 'meta_description as description', 'open_page')->OrderBy('sort', 'asc')->get()->toArray();
        } else {
            $navs = Classify::where('cus_id', $this->cus_id)->where('mobile_show', 1)->select('id', 'type', 'img', 'icon', 'name', 'url', 'p_id', 'en_name', 'view_name', 'meta_description as description', 'open_page')->OrderBy('sort', 'asc')->get()->toArray();
        }
        $navs = $this->toTree($navs, 0, TRUE);
        if ($c_id) {
            $current_arr = $this->currentCidArray($c_id);
            $navs = $this->addCurrent($navs, $current_arr);
        }
        return $navs;
    }

    /**
     * 获取首页数据，包括logo、path、stylecolor、navs、logo、footprint等
     *
     * @param array $data 合并后的数据
     * @return array 返回一个包含公共数据的数组
     */
    private function detailList($data)
    {
        //===获取栏目浏览名称(view_name)===
//        $cate = array();
//        $min_classify = Classify::where('cus_id', $this->cus_id)->select('id', 'view_name')->get();
//        foreach ($min_classify as $key => $value) {
//            $cate[$value['id']] = $value['view_name'];
//        }
        //===获取栏目浏览名称(view_name)===end===
        $index = [];
        $list = [];
        if ($data == NULL) {
            return $index;
        }
        foreach ($data as $k => $v) {
            if ($v['type'] == 'list') {
                if (isset($v['config']['id']) && is_numeric($v['config']['id']) && $v['config']['id'] > 0) {
                    $c_info = Classify::where('id', $v['config']['id'])->where('cus_id', $this->cus_id)->where($this->type . '_show', 1)->first();
                    $cids = explode(',', $this->getChirldenCid($v['config']['id'], 1)); //取得所有栏目id
                } else {
                    $c_info = false;
                    $cids = false;
                }
                if (isset($v['config']['filter'])) {
                    if ($v['config']['filter'] == 'list') {
                        if (isset($v['config']['star_only']) && $v['config']['star_only']) {
                            if ($cids) {
                                $articles = Articles::whereIn('c_id', $cids)->where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->where('is_star', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                            } else {
                                $articles = Articles::where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->where('is_star', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                            }
                        } else {
                            if ($cids) {
                                $articles = Articles::whereIn('c_id', $cids)->where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                            } else {
                                $articles = Articles::where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                            }
                        }

                        if ($articles->count() != 0) {
                            $abc = [];
                            foreach ($articles as $key => $d) {
                                if ($d->title_bold == 0 && $d->title_color == 'rgb(0, 0, 0)') {
                                    $abc['data'][$key]['title'] = $d->title;
                                } else {
                                    $bold = ($d->title_bold == 1) ? 'font-weight:bold;' : 'font-weight:normal;';
                                    $font_color = ($d->title_color != 'rgb(0, 0, 0)') ? 'color:' . $d->title_color . ';' : '';
                                    $abc['data'][$key]['title'] = '<strong style="' . $bold . $font_color . '">' . $d->title . '</strong>';
                                }
                                $d_c_info = Classify::where('id', $d->c_id)->first();
                                $abc['data'][$key]['image'] = $d->img ? ($this->source_dir . 'l/articles/' . $d->img) : '';
                                if ($this->showtype == 'preview') {
//                                    if ($cate[$d->c_id]) {//===判断栏目是否有别名===
//                                        $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $cate[$d->c_id];
//                                    } else {
                                    //category链接位置(首页内容content子栏目)
                                    $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $d->c_id;
//                                    }
                                    $abc['data'][$key]['link'] = $this->domain . '/detail/' . $d->id;
                                } else {
//                                    if ($cate[$d->c_id]) {//===判断栏目是否有别名===
//                                        $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $cate[$d->c_id] . '.html';
//                                    } else {
                                    $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $d->c_id . '.html';
//                                    }
                                    $abc['data'][$key]['link'] = $this->domain . '/detail/' . $d->id . '.html';
                                }
                                if ($d->use_url) {
                                    $abc['data'][$key]['link'] = $d->url;
                                }
                                $abc['data'][$key]['category']['name'] = $d_c_info->name;
                                $abc['data'][$key]['category']['en_name'] = $d_c_info->en_name;
//                                $abc['data'][$key]['category']['view_name'] = $d_c_info->view_name;
                                $abc['data'][$key]['category']['icon'] = '<i class="iconfont">' . $d_c_info->icon . '</i>';
                                $abc['data'][$key]['description'] = $d->introduction;
                                $abc['data'][$key]['pubdate'] = (string)$d->created_at;
                                $abc['data'][$key]['pubtimestamp'] = strtotime($d->created_at);
                                unset($v['value']);
                            }
                            $v['value'] = $abc;
                        } else {
                            $v['value'] = array('data' => null);
                        }
                    } elseif ($v['config']['filter'] == 'page') {
                        unset($v['value']);
                        if ($k == 'about') {
                            $page = Page::where('id', $c_info->page_id)->first();
                            $v['value']['content'] = ($page ? $page->content : '');
                        } else {
                            $v['value']['content'] = ($c_info ? $c_info->meta_description : '');
                        }
                    }
                } else {
                    if (!isset($v['config']['limit'])) {
                        $v['config']['limit'] = '';
                    }
                    if (isset($v['config']['star_only']) && $v['config']['star_only']) {
                        if ($cids) {
                            $articles = Articles::whereIn('c_id', $cids)->where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->where('is_star', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                        } else {
                            $articles = Articles::where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->where('is_star', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                        }
                    } else {
                        if ($cids) {
                            $articles = Articles::whereIn('c_id', $cids)->where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                        } else {
                            $articles = Articles::where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                        }
                    }
                    if ($articles->count() != 0) {
                        $abc = [];
                        foreach ($articles as $key => $d) {
                            if ($d->title_bold == 0 && $d->title_color == 'rgb(0, 0, 0)') {
                                $abc['data'][$key]['title'] = $d->title;
                            } else {
                                $bold = ($d->title_bold == 1) ? 'font-weight:bold;' : 'font-weight:normal;';
                                $font_color = ($d->title_color != 'rgb(0, 0, 0)') ? 'color:' . $d->title_color . ';' : '';
                                $abc['data'][$key]['title'] = '<strong style="' . $bold . $font_color . '">' . $d->title . '</strong>';
                            }
                            $d_c_info = Classify::where('id', $d->c_id)->first();
                            $abc['data'][$key]['image'] = $d->img ? ($this->source_dir . 'l/articles/' . $d->img) : '';
                            if ($this->showtype == 'preview') {
//                                if ($cate[$d->c_id]) {//===判断栏目是否有别名===
//                                    $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $cate[$d->c_id];
//                                } else {
                                //category链接位置(首页内容content子栏目)
                                $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $d->c_id;
//                                }
                                $abc['data'][$key]['link'] = $this->domain . '/detail/' . $d->id;
                            } else {
//                                if ($cate[$d->c_id]) {//===判断栏目是否有别名===
//                                    $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $cate[$d->c_id] . '.html';
//                                } else {
                                $abc['data'][$key]['category']['link'] = $this->domain . '/category/' . $d->c_id . '.html';
//                                }
                                $abc['data'][$key]['link'] = $this->domain . '/detail/' . $d->id . '.html';
                            }
                            if ($d->use_url) {
                                $abc['data'][$key]['link'] = $d->url;
                            }
                            $abc['data'][$key]['category']['name'] = $d_c_info->name;
                            $abc['data'][$key]['category']['en_name'] = $d_c_info->en_name;
//                            $abc['data'][$key]['category']['view_name'] = $d_c_info->view_name;
                            $abc['data'][$key]['category']['icon'] = '<i class="iconfont">' . $d_c_info->icon . '</i>';
                            $abc['data'][$key]['description'] = $d->introduction;
                            $abc['data'][$key]['pubdate'] = (string)$d->created_at;
                            $abc['data'][$key]['pubtimestamp'] = strtotime($d->created_at);
                            unset($v['value']);
                        }
                        $v['value'] = $abc;
                    } else {
                        $v['value'] = array('data' => null);
                    }
                }

                $v['value']['name'] = ($c_info ? $c_info->name : '');
                $v['value']['en_name'] = ($c_info ? $c_info->en_name : '');
                $v['value']['icon'] = ($c_info ? '<i class="iconfont">' . $c_info->icon . '</i>' : '');
                $v['value']['link'] = '';
                if ($this->showtype == 'preview') {
                    $v['value']['image'] = ($c_info ? $this->source_dir . 'l/category/' . $c_info->img : '');
                    //category链接位置(pc首页内容content)
                    $v['value']['link'] = ($c_info ? ($c_info->type == 6) ? $c_info->url ? $c_info->url : '' : $this->domain . '/category/' . $c_info->id : '');
                } else {
                    $v['value']['image'] = ($c_info ? $this->domain . '/images/l/category/' . $c_info->img : '');
                    $v['value']['link'] = ($c_info ? ($c_info->type == 6) ? $c_info->url ? $c_info->url : '' : $this->domain . '/category/' . $c_info->id . '.html' : '');
                }
                $v['value']['description'] = ($c_info ? $c_info->meta_description : '');
                $v['value']['type'] = ($c_info ? $c_info->type : '');
                $childrenMenu = array();
                if ($cids) {
                    foreach ($cids as $cid) {
                        $c_c_info = Classify::where('id', $cid)->where('cus_id', $this->cus_id)->where($this->type . '_show', 1)->select('id', 'name', 'en_name', 'type', 'url', 'open_page', 'img as image', 'icon', 'meta_description as description', 'p_id')->first();
                        if ($c_c_info) {
                            $c_c_info = $c_c_info->toArray();
                            if ($this->showtype == 'preview') {
                                $c_c_info['image'] = ($c_c_info ? $this->source_dir . 'l/category/' . $c_c_info['image'] : '');
                                //category链接位置(列表页左侧导航)
                                $c_c_info['link'] = ($c_c_info ? $this->domain . '/category/' . $c_c_info['id'] : '');
                            } else {
                                $c_c_info['image'] = ($c_c_info ? $this->domain . '/images/l/category/' . $c_c_info['image'] : '');
                                $c_c_info['link'] = ($c_c_info ? $this->domain . '/category/' . $c_c_info['id'] . '.html' : '');
                            }
                            $c_c_info['icon'] = ($c_c_info ? '<i class="iconfont">' . $c_c_info['icon'] . '</i>' : '');
                            $c_c_info['current'] = 0;
                            $c_c_info['selected'] = 0;
                            $c_cids = explode(',', $this->getChirldenCid($cid, 1)); //取得所有栏目id
                            if (isset($v['config']['star_only']) && $v['config']['star_only']) {
                                $articles = Articles::whereIn('c_id', $c_cids)->where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->where('is_star', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                            } else {
                                $articles = Articles::whereIn('c_id', $c_cids)->where($this->type . '_show', '1')->where('cus_id', $this->cus_id)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'use_url', 'url')->take($v['config']['limit'])->get();
                            }
                            if ($articles->count() != 0) {
                                $abc = [];
                                foreach ($articles as $key => $d) {
                                    if ($d->title_bold == 0 && $d->title_color == 'rgb(0, 0, 0)') {
                                        $abc[$key]['title'] = $d->title;
                                    } else {
                                        $bold = ($d->title_bold == 1) ? 'font-weight:bold;' : 'font-weight:normal;';
                                        $font_color = ($d->title_color != 'rgb(0, 0, 0)') ? 'color:' . $d->title_color . ';' : '';
                                        $abc[$key]['title'] = '<strong style="' . $bold . $font_color . '">' . $d->title . '</strong>';
                                    }
                                    $d_c_info = Classify::where('id', $d->c_id)->first();
                                    $abc[$key]['image'] = $d->img ? ($this->source_dir . 'l/articles/' . $d->img) : '';
                                    if ($this->showtype == 'preview') {
//                                        if ($cate[$d->c_id]) {//===判断栏目是否有别名===
//                                            $abc[$key]['category']['link'] = $this->domain . '/category/' . $cate[$d->c_id];
//                                        } else {
                                        $abc[$key]['category']['link'] = $this->domain . '/category/' . $d->c_id;
//                                        }
                                        $abc[$key]['link'] = $this->domain . '/detail/' . $d->id;
                                    } else {
//                                        if ($cate[$d->c_id]) {//===判断栏目是否有别名===
//                                            $abc[$key]['category']['link'] = $this->domain . '/category/' . $cate[$d->c_id] . '.html';
//                                        } else {
                                        $abc[$key]['category']['link'] = $this->domain . '/category/' . $d->c_id . '.html';
//                                        }
                                        $abc[$key]['link'] = $this->domain . '/detail/' . $d->id . '.html';
                                    }
                                    if ($d->use_url) {
                                        $abc[$key]['link'] = $d->url;
                                    }
                                    $abc[$key]['category']['name'] = $d_c_info->name;
                                    $abc[$key]['category']['en_name'] = $d_c_info->en_name;
                                    $abc[$key]['category']['icon'] = '<i class="iconfont">' . $d_c_info->icon . '</i>';
                                    $abc[$key]['description'] = $d->introduction;
                                    $abc[$key]['pubdate'] = (string)$d->created_at;
                                    $abc[$key]['pubtimestamp'] = strtotime($d->created_at);
                                }
                                $c_c_info['data'] = $abc;
                            } else {
                                $c_c_info['data'] = [];
                            }
                        }
                        $childrenMenu[] = $c_c_info;
                    }
                }
                $classify = new Classify();
                $v['value']['current'] = 0;
                $v['value']['selected'] = 0;
                $v['value']['id'] = isset($v['config']['id']) ? $v['config']['id'] : NULL;
                $v['value']['childmenu'] = isset($v['config']['id']) ? $classify->toTree($childrenMenu, $v['config']['id']) : NULL;
            } elseif ($v['type'] == 'page') {
                if (isset($v['config']['id'])) {
                    $c_info = Classify::where('id', $v['config']['id'])->where('cus_id', $this->cus_id)->where($this->type . '_show', 1)->first();
                    $cids = explode(',', $this->getChirldenCid($v['config']['id'], 1)); //取得所有栏目id
                } else {
                    $c_info = false;
                    $cids = false;
                }
                if ($c_info) {
                    unset($v['value']);
                    $v['value']['content'] = $c_info->meta_description;
                    $v['value']['name'] = $c_info->name;
                    $v['value']['en_name'] = $c_info->en_name;
                    $v['value']['icon'] = '<i class="iconfont">' . $c_info->icon . '</i>';
                    $v['value']['link'] = '';
                    if ($this->showtype == 'preview') {
                        $v['value']['image'] = $c_info->img ? ($this->source_dir . 'l/category/' . $c_info->img) : '';
//                        if ($cate[$c_info->c_id]) {//===判断栏目是否有别名===
//                            $v['value']['link'] = $this->domain . '/category/' . $cate[$c_info->c_id];
//                        } else {
                        $v['value']['link'] = $this->domain . '/category/' . $c_info->id;
//                        }
                    } else {
                        $v['value']['image'] = $c_info->img ? ($this->domain . '/images/l/category/' . $c_info->img) : '';
//                        if ($cate[$c_info->c_id]) {//===判断栏目是否有别名===
//                            $v['value']['link'] = $this->domain . '/category/' . $cate[$c_info->c_id] . '.html';
//                        } else {
                        $v['value']['link'] = $this->domain . '/category/' . $c_info->id . '.html';
//                        }
                    }
                    $v['value']['description'] = $c_info->meta_description;
                    $v['value']['type'] = $c_info->type;
                }
                $childrenMenu = array();
                if ($cids) {
                    foreach ($cids as $cid) {
                        $c_c_info = Classify::where('id', $cid)->where('cus_id', $this->cus_id)->where($this->type . '_show', 1)->select('id', 'name', 'type', 'url', 'open_page', 'en_name', 'view_name', 'img as image', 'icon', 'meta_description as description', 'p_id')->first();
                        if ($c_c_info) {
                            $c_c_info = $c_c_info->toArray();
                            if ($this->showtype == 'preview') {
                                if ($c_c_info) {//===判断栏目是否有别名===
                                    $c_c_info['image'] = $this->source_dir . 'l/category/' . $c_c_info['image'];
                                    $c_c_info['link'] = $this->domain . '/category/' . $c_c_info['id'];
                                } else {
                                    $c_c_info['image'] = '';
                                    $c_c_info['link'] = '';
                                }
                            } else {
                                if ($c_c_info) {//===判断栏目是否有别名===
                                    $c_c_info['image'] = $this->source_dir . 'l/category/' . $c_c_info['image'];
                                    $c_c_info['link'] = $this->domain . '/category/' . $c_c_info['id'] . '.html';
                                } else {
                                    $c_c_info['image'] = '';
                                    $c_c_info['link'] = '';
                                }
                            }
                            $c_c_info['current'] = 0;
                            $c_c_info['selected'] = 0;
                        }
                        $childrenMenu[] = $c_c_info;
                    }
                }
                $classify = new Classify();
                $v['value']['current'] = 0;
                $v['value']['selected'] = 0;
                $v['value']['id'] = $v['config']['id'];
                $v['value']['childmenu'] = $classify->toTree($childrenMenu, $v['config']['id']);
            } elseif ($v['type'] == 'navs') {
                if (isset($v['config']['ids']) && count($v['config']['ids'])) {
                    unset($v['value']);
                    $i = 0;
                    foreach ($v['config']['ids'] as $id) {
                        $c_info = Classify::where('id', $id)->where('cus_id', $this->cus_id)->where($this->type . '_show', 1)->first();
                        $v['value'][$i]['name'] = $c_info ? $c_info->name : '';
                        $v['value'][$i]['en_name'] = $c_info ? $c_info->en_name : '';
                        $v['value'][$i]['icon'] = $c_info ? '<i class="iconfont">' . $c_info->icon . '</i>' : '';
                        if ($this->showtype == 'preview') {
                            if ($c_info) {//===判断栏目是否有别名===
                                $v['value'][$i]['image'] = $this->source_dir . 'l/category/' . $c_info->img;
                                $v['value'][$i]['link'] = $this->domain . '/category/' . $c_info->id;
                            } else {
                                $v['value'][$i]['image'] = '';
                                $v['value'][$i]['link'] = '';
                            }
                        } else {
                            if ($c_info) {//===判断栏目是否有别名===
                                $v['value'][$i]['image'] = $this->domain . '/images/l/category/' . $c_info->img;
                                $v['value'][$i]['link'] = $this->domain . '/category/' . $c_info->id . '.html';
                            } else {
                                $v['value'][$i]['image'] = '';
                                $v['value'][$i]['link'] = '';
                            }
                        }
                        $v['value'][$i]['current'] = 0;
                        $v['value'][$i]['selected'] = 0;
                        $v['value'][$i]['id'] = $id;
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
    private function pageList($id, $page)
    {
        $list = [];
        $cids = explode(',', $this->getChirldenCid($id, 1)); //取得所有栏目id
        $a_c_type = Classify::where('id', $id)->select('type')->first(); //取得栏目的type
        $type = $a_c_type->type;
        if ($this->type == 'mobile') {
            $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_page_count'); //每页显示个数
            $links_count = CustomerInfo::where('cus_id', $this->cus_id)->pluck('mobile_page_links'); //分页链接显示个数
            $offset = ($page - 1) * $page_number;
            $total = Articles::whereIn('c_id', $cids)->where('mobile_show', '1')->select('id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color')->count();
            $list = Articles::whereIn('c_id', $cids)->where('mobile_show', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'c_id', 'url', 'use_url')->skip($offset)->take($page_number)->get();
        } else {
            $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_count'); //每页显示个数
            $pc_page_count_switch = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_count_switch'); //页面图文列表图文显示个数是否分开控制开关
            if (isset($pc_page_count_switch) && $pc_page_count_switch == 1) {
                if ($type == 1) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_txt_count'); //每页文字显示个数
                }
                if ($type == 3) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_imgtxt_count'); //每页图文显示个数  
                }
                if ($type == 2) {
                    $page_number = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_img_count'); //每页图片显示个数
                }
            }
            $links_count = CustomerInfo::where('cus_id', $this->cus_id)->pluck('pc_page_links'); //分页链接显示个数
            $offset = ($page - 1) * $page_number;
            $total = Articles::whereIn('c_id', $cids)->where($this->type . '_show', '1')->select('id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color')->count();
            $list = Articles::whereIn('c_id', $cids)->where($this->type . '_show', '1')->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->select('id', 'title', 'img', 'introduction', 'created_at', 'title_bold', 'title_color', 'c_id', 'url', 'use_url')->skip($offset)->take($page_number)->get();
        }
        $page_count = ceil($total / $page_number);
        $article = [];
        if ($total) {
            if ($this->showtype == 'preview') {
                $links = $this->pageLinks($id, $page, $page_count, $links_count);
                foreach ($list as $key => $d) {
                    $a_c_info = Classify::where('id', $d->c_id)->first();
                    $article[$key]['category']['link'] = $this->domain . '/category/' . $d->c_id;
                    $article[$key]['category']['name'] = $a_c_info->name;
                    $article[$key]['category']['en_name'] = $a_c_info->en_name;
                    $article[$key]['category']['icon'] = '<i class="iconfont">' . $a_c_info->icon . '</i>';
                    if ($d->title_bold == 0 && $d->title_color == 'rgb(0, 0, 0)') {
                        $article[$key]['title'] = $d->title;
                    } else {
                        $bold = ($d->title_bold == 1) ? 'font-weight:bold;' : 'font-weight:normal;';
                        $font_color = ($d->title_color != 'rgb(0, 0, 0)') ? 'color:' . $d->title_color . ';' : '';
                        $article[$key]['title'] = '<strong style="' . $bold . $font_color . '">' . $d->title . '</strong>';
                    }
                    $article[$key]['image'] = $d->img ? ($this->source_dir . 's/articles/' . $d->img) : '';
                    $article[$key]['link'] = $this->domain . '/detail/' . $d->id;
                    if ($d->use_url) {
                        $article[$key]['link'] = $d->url;
                    }
                    $article[$key]['description'] = $d->introduction;
                    $article[$key]['pubdate'] = (string)$d->created_at;
                    $article[$key]['pubtimestamp'] = strtotime($d->created_at);
                }
            } else {
                $links = $this->pageLinks($id, $page, $page_count, $links_count);
                foreach ($list as $key => $d) {
                    $a_c_info = Classify::where('id', $d->c_id)->first();
                    $article[$key]['category']['link'] = $this->domain . '/category/' . $d->c_id . '.html';
                    $article[$key]['category']['name'] = $a_c_info->name;
                    $article[$key]['category']['en_name'] = $a_c_info->en_name;
                    $article[$key]['category']['icon'] = '<i class="iconfont">' . $a_c_info->icon . '</i>';
                    if ($d->title_bold == 0 && $d->title_color == 'rgb(0, 0, 0)') {
                        $article[$key]['title'] = $d->title;
                    } else {
                        $bold = ($d->title_bold == 1) ? 'font-weight:bold;' : 'font-weight:normal;';
                        $font_color = ($d->title_color != 'rgb(0, 0, 0)') ? 'color:' . $d->title_color . ';' : '';
                        $article[$key]['title'] = '<strong style="' . $bold . $font_color . '">' . $d->title . '</strong>';
                    }
                    $article[$key]['image'] = $d->img ? ($this->source_dir . 's/articles/' . $d->img) : '';
                    $article[$key]['link'] = $this->domain . '/detail/' . $d->id . '.html';
                    if ($d->use_url) {
                        $article[$key]['link'] = $d->url;
                    }
                    $article[$key]['description'] = $d->introduction;
                    $article[$key]['pubdate'] = (string)$d->created_at;
                    $article[$key]['pubtimestamp'] = strtotime($d->created_at);
                }
            }
            if (!empty($links)) {
                $page_links = [
                    'page_count' => $page_count,
                    'per_page' => $page_number,
                    'first_link' => $links['first'],
                    'art_total' => $total,
                    'total' => $total,
                    'current_page' => $page,
                    'prev_link' => $links['prev'],
                    'next_link' => $links['next'],
                    'last_link' => $links['last'],
                ];
                unset($links['first']);
                unset($links['prev']);
                unset($links['next']);
                unset($links['last']);
                $page_links['nears_link'] = $links;
            } else {
                $page_links = [
                    'page_count' => 1,
                    'per_page' => 1,
                    'first_link' => 'javascript:;',
                    'current_page' => 1,
                    'total' => $total,
                    'prev_link' => 'javascript:;',
                    'next_link' => 'javascript:;',
                    'last_link' => 'javascript:;',
                ];
                $page_links['nears_link'] = [1 => 1];
            }
            $result = ['page_links' => $page_links, 'data' => $article];
        } else {
            $page_links = [
                'page_count' => 1,
                'per_page' => 1,
                'first_link' => 'javascript:;',
                'current_page' => 1,
                'prev_link' => 'javascript:;',
                'next_link' => 'javascript:;',
                'last_link' => 'javascript:;',
            ];
            $page_links['nears_link'] = [1 => 1];
            $result = ['page_links' => $page_links, 'data' => []];
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
    public function pageLinks($id, $page, $pageCount, $showPageNum)
    {
        if ($pageCount == 1) {
            return $links = [];
        }
        if ($page <= 1) {
            $links['first'] = "";
            $links['prev'] = "";
        } else {
            //category链接位置(列表页底部分页-首页)
            $links['first'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $id : $this->domain . '/category/' . $id . '.html';
            //category链接位置(列表页底部分页-上一页)
            $links['prev'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $id . '_' . ($page - 1) : $this->domain . '/category/' . $id . '_' . ($page - 1) . '.html';
        }

        if ($page < 1) {
            $page = 1;
        } elseif ($page > $pageCount) {
            $page = $pageCount;
        }
        $pageNumHalf = floor($showPageNum / 2);

        if ($pageCount <= $showPageNum) {
            $startpage = 1;
            $endpage = $pageCount;
        } elseif (($page - $pageNumHalf) >= 1 && ($page + $pageNumHalf) <= $pageCount) {
            if ($showPageNum % 2 == 0 && $showPageNum == 2) {
                $startpage = $page;
            } elseif ($showPageNum % 2 == 0 && $showPageNum > 2) {
                $startpage = $page - $pageNumHalf + 1;
            } else {
                $startpage = $page - $pageNumHalf;
            }
            $endpage = $page + $pageNumHalf;
        } elseif (($page - $pageNumHalf) < 1) {
            $startpage = 1;
            $endpage = $showPageNum;
        } elseif (($page + $pageNumHalf) > $pageCount) {
            $startpage = $pageCount - ($showPageNum - 1);
            $endpage = $pageCount;
        }
        for ($i = $startpage; $i <= $endpage; $i++) {
            if ($i == $page) {
                $links[$i] = $i;
            } else {
                //category链接位置(列表页底部分页数字)
                $links[$i] = $this->showtype == 'preview' ? $this->domain . '/category/' . $id . '_' . $i : $this->domain . '/category/' . $id . '_' . $i . '.html';
            }
        }

        if ($page >= $pageCount) {

            $links['next'] = '';
            $links['last'] = '';
        } else {
            //category链接位置(列表页底部分页-下一页)
            $links['next'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $id . '_' . ($page + 1) : $this->domain . '/category/' . $id . '_' . ($page + 1) . '.html';
            //category链接位置(列表页底部分页-最后一页)
            $links['last'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $id . '_' . $pageCount : $this->domain . '/category/' . $id . '_' . $pageCount . '.html';
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
    public static function createMap($params)
    {
        $params['scale_size'] = isset($params['scale_size']) ? $params['scale_size'] : 17;
        $params['width'] = isset($params['width']) ? $params['width'] : '80%';
        $params['height'] = isset($params['height']) ? $params['height'] : '200px';
        $s = '<div id="allmap" style="width: ' . $params['width'] . ';height: ' . $params['height'] . '"></div>';
        $s .= '<script>';
        $s .= 'var map_js = document.createElement("script");
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
            $s .= 'myGeo.getPoint("' . $params['address'] . '", function(point){
                        if (point) {
                          map.centerAndZoom(point,' . $params['scale_size'] . ');
                          map.addOverlay(new BMap.Marker(point));
                        }else{
                          alert("您选择地址没有解析到结果!");
                        }
                        }, "厦门市");';
        } else {
            $s .= 'myGeo.getPoint("软件园二期观日路36号", function(point){
                        if (point) {
                          map.centerAndZoom(point,' . $params['scale_size'] . ');
                          map.addOverlay(new BMap.Marker(point));
                        }else{
                          alert("您选择地址没有解析到结果!");
                        }
                        }, "厦门市");';
        }
        $s .= '};
              </script>';
        echo $s;
    }

    /**
     *
     * 分享功能
     *
     *
     */
    public static function createShare($params)
    {
        $customer_info = CustomerInfo::where('cus_id', Auth::id())->first();
        if ($customer_info->lang == 'en') {
            $s = '<div class="bdsharebuttonbox" data-tag="share_1">
          <a class="bds_mshare" data-cmd="mshare"></a>
          <a class="bds_qzone" data-cmd="qzone" href="#"></a>
          <a class="bds_tsina" data-cmd="tsina"></a>
          <a class="bds_baidu" data-cmd="baidu"></a>
          <a class="bds_renren" data-cmd="renren"></a>
          <a class="bds_tqq"></a>
          <a class="bds_more" data-cmd="more">more</a>
          <a class="bds_count" data-cmd="count"></a>
        </div>' . "\n";
        } else {
            $s = '<div class="bdsharebuttonbox" data-tag="share_1">
          <a class="bds_mshare" data-cmd="mshare"></a>
          <a class="bds_qzone" data-cmd="qzone" href="#"></a>
          <a class="bds_tsina" data-cmd="tsina"></a>
          <a class="bds_baidu" data-cmd="baidu"></a>
          <a class="bds_renren" data-cmd="renren"></a>
          <a class="bds_tqq"></a>
          <a class="bds_more" data-cmd="more">更多</a>
          <a class="bds_count" data-cmd="count"></a>
        </div>' . "\n";
        }

        // 显示类型
        $s .= "<script>status = 1;\n";
        $s .= "url=window.location.href;\n";
        $params['style'] = isset($params['style']) ? $params['style'] : "1";
        $s .= "window._bd_share_config = {
          common : {
            bdText : \"";
        $s .= isset($params['shareText']) ? $params['shareText'] : '';
        $s .= "\",
        bdMiniList: ['mshare', 'qzone', 'tsina', 'bdysc', 'weixin', 'renren', 'kaixin001', 'tqf', 'tieba', 'douban', 'bdhome', 'sqq', 'thx', 'ibaidu', 'meilishuo', 'mogujie', 'huaban', 'duitang', 'hx', 'fx', 'youdao', 'sdo', 'qingbiji', 'people', 'xinhua', 'mail', 'isohu', 'yaolan', 'wealink', 'ty', 'iguba', 'linkedin', 'copy', 'print'], 
            bdDesc : \"";
        $s .= isset($params['shareDesc']) ? $params['shareDesc'] : '';
        $s .= "\", 
            bdUrl : ";
        $s .= isset($params['shareUrl']) ? $params['shareUrl'] : "url";
        $s .= ",   
            bdPic : \"";
        $s .= isset($params['sharePic']) ? $params['sharePic'] : '';
        $s .= "\",
            bdMini : \"";
        $s .= isset($params['bdMini']) ? $params['bdMini'] : '';
        $s .= "\",
            bdMinilist : ['qzone','tsina','huaban','tqq','renren']";
        $s .= isset($params['bdMinilist']) ? $params['bdMinilist'] : '';
        $s .= "
          },
          share : [{
            bdSize :";
        $s .= isset($params['viewSize']) ? $params['viewSize'] : 16;
        $s .= "}],
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
        if ($params['style'] == "1") {
            $s .= "delete _bd_share_config.slide\n";
        } elseif ($params['style'] == "2") {
            $s .= "delete _bd_share_config.share\n";
        } else {
            $s .= "null\n";
        }
        if (isset($params['image'])) {
            $s .= "null\n";
        } else {
            $s .= "delete _bd_share_config.image\n";
        }
        $s .= "window.onload=function(){with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];}\n";
        $s .= '$(".bds_tqq").click(function(){window.open("http://v.t.qq.com/share/share.php?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(document.title));});';
        $s .= "</script>";
        echo $s;
    }

    /**
     * PC显示首页
     */
    public function homepagePreview($result = array())
    {
        if ($_SERVER["HTTP_HOST"] != TONGYI_DOMAIN) {
            $result = $this->pagePublic();
            $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
            $result['title'] = $customer_info->title;
            $result['keywords'] = $customer_info->keywords;
            $result['description'] = $customer_info->description;
            $result['pagenavs'] = false;
            $result['posnavs'] = false;
            $data = $this->pagedata('index');
            $index = $this->detailList($data);
            $result = array_add($result, 'index', $index);
            $json_keys = $this->getJsonKey('index.html');
            if (count($json_keys)) {
                foreach ($json_keys as $key) {
                    $result[$key] = $this->detailList($this->pagedata($key));
                }
            }
        }
        if ($_SERVER["HTTP_HOST"] == TONGYI_TUISONG_JUYU_IP) {
            return json_encode($result);
        }
        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        @$smarty->display('index.html');
        //return View::make('templates.'.$this->themename.'.index',$result);
    }

    /**
     * PC推送首页
     */
    public function homepagePush($publicdata)
    {
        $result = $publicdata['result'];
        $result['navs'] = $publicdata['navs'];
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        $result['pagenavs'] = false;
        $result['posnavs'] = false;
        $data = $this->pagedata('index', $publicdata['pagedata']);
        $index = $this->detailList($data);
        $result = array_add($result, 'index', $index);
        $json_keys = $this->getJsonKey('index.html');
        if (count($json_keys)) {
            foreach ($json_keys as $key) {
                $result[$key] = $this->detailList($this->pagedata($key));
            }
        }
        $content = $publicdata['repleace']['index.html'];
        $content = preg_replace($publicdata['pattern'], $publicdata['repleace'], $content);
        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        ob_start();
        $smarty->display('string:' . $content);
        $output = ob_get_contents();
        ob_end_clean();
        if (!count($result['footer_navs'])) {
            $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>首页<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
            $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>Home<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
        }
        return $output;
        //return View::make('templates.'.$this->themename.'.index',$result);
    }

    /**
     * 手机首页
     */
    public function mhomepagePreview($result = array())
    {
        if ($_SERVER["HTTP_HOST"] != TONGYI_DOMAIN) {
            $result = $this->pagePublic();
            $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
            $result['title'] = $customer_info->title;
            $result['keywords'] = $customer_info->keywords;
            $result['description'] = $customer_info->description;
            //获取模板目录
            $data = $this->pagedata('index');
            $show_navs = DB::table('mobile_homepage')->leftJoin('classify', 'classify.id', '=', 'mobile_homepage.c_id')->where('mobile_homepage.index_show', 1)->where('classify.mobile_show', 1)->where('mobile_homepage.cus_id', '=', $this->cus_id)->orderBy('mobile_homepage.s_sort', 'asc')->select('classify.id', 'classify.p_id', 'classify.name', 'classify.en_name', 'classify.type', 'classify.meta_description', 'classify.page_id', 'classify.url', 'classify.img', 'classify.icon', 'mobile_homepage.star_only', 'mobile_homepage.show_num', 'mobile_homepage.m_index_showtype')->get();
            //===调试测试账号===
            $mIndexCats = array();
            if (count($show_navs) > 0) {
                if ($this->showtype == 'preview') {
                    foreach ($show_navs as $nav) {
                        $mIndexCat = array();
                        $mIndexCat['id'] = $nav->id;
                        $mIndexCat['p_id'] = $nav->p_id;
                        $mIndexCat['name'] = $nav->name;
                        $mIndexCat['en_name'] = $nav->en_name;
                        $mIndexCat['icon'] = '<i class="iconfont">' . $nav->icon . '</i>';
                        $mIndexCat['image'] = $nav->img ? ($this->source_dir . 'l/category/' . $nav->img) : '';
                        if ($nav->url) {
                            $mIndexCat['link'] = $nav->url;
                        } else {
                            //category链接位置(手机首页内容content)
                            $mIndexCat['link'] = $this->domain . "/category/" . $nav->id;
                        }
                        $mIndexCat['type'] = $nav->type;
                        $mIndexCat['showtype'] = $nav->m_index_showtype;
                        $mIndexCat['description'] = $nav->meta_description;
                        $id_arr = explode(',', $this->getChildrenClassify($nav->id, 1));
                        if ($nav->type == 1 || $nav->type == 2 || $nav->type == 3) {
                            $art_arr = array();
                            if ($nav->star_only) {
                                //是否只显示推荐
                                $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->where('is_star', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                            } else {
                                $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                            }
                            if (count($articles) > 0) {
                                $i = 0;
                                foreach ($articles as &$article) {
                                    $art_arr[$i]['title'] = $article->title;
                                    $art_arr[$i]['image'] = $article->img ? ($this->source_dir . 's/articles/' . $article->img) : '';
                                    $art_arr[$i]['link'] = $this->domain . "/detail/" . $article->id;
                                    if ($article->use_url) {
                                        $art_arr[$i]['link'] = $article->url;
                                    }
                                    $art_arr[$i]['description'] = $article->introduction;
                                    $art_arr[$i]['pubdate'] = (string)$article->created_at;
                                    $art_arr[$i]['pubtimestamp'] = strtotime($article->created_at);
                                    $art_arr[$i]['category']['name'] = $nav->name;
                                    $art_arr[$i]['category']['en_name'] = $nav->name;
                                    if ($nav->url) {
                                        $art_arr[$i]['category']['link'] = $nav->url;
                                    } else {
                                        $art_arr[$i]['category']['link'] = $this->domain . "/category/" . $nav->id;
                                    }
                                    $i++;
                                }
                            }
                            $mIndexCat['data'] = $art_arr;
                        } elseif ($nav->type == 4) {
                            $content = Page::where('id', $nav->page_id)->pluck('content');
                            $mIndexCat['content'] = $content;
                        } elseif ($nav->type == 5 || $nav->type == 6 || $nav->type == 7 || $nav->type == 8 || $nav->type == 9) {
                            //暂时缺省
                        }
                        $mIndexCats[] = $mIndexCat;
                    }
                } else {
                    foreach ($show_navs as $nav) {
                        $mIndexCat = array();
                        $mIndexCat['id'] = $nav->id;
                        $mIndexCat['p_id'] = $nav->p_id;
                        $mIndexCat['name'] = $nav->name;
                        $mIndexCat['en_name'] = $nav->en_name;
                        $mIndexCat['icon'] = '<i class="iconfont">' . $nav->icon . '</i>';
                        $mIndexCat['image'] = $nav->img ? ($this->source_dir . "/l/category/" . $nav->img) : '';
                        if ($nav->url) {
                            $mIndexCat['link'] = $nav->url;
                        } else {
                            $mIndexCat['link'] = $this->domain . "/category/" . $nav->id . ".html";
                        }
                        $mIndexCat['type'] = $nav->type;
                        $mIndexCat['showtype'] = $nav->m_index_showtype;
                        $mIndexCat['description'] = $nav->meta_description;
                        $id_arr = explode(',', $this->getChildrenClassify($nav->id, 1));
                        if ($nav->type == 1 || $nav->type == 2 || $nav->type == 3) {
                            $art_arr = array();
                            if ($nav->star_only) {
                                //是否只显示推荐
                                $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->where('is_star', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                            } else {
                                $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                            }
                            if (count($articles) > 0) {
                                $i = 0;
                                foreach ($articles as &$article) {
                                    $art_arr[$i]['title'] = $article->title;
                                    $art_arr[$i]['image'] = $article->img ? ($this->source_dir . "/s/articles/" . $article->img) : '';
                                    $art_arr[$i]['link'] = $this->domain . "/detail/" . $article->id . ".html";
                                    if ($article->use_url) {
                                        $art_arr[$i]['link'] = $article->url;
                                    }
                                    $art_arr[$i]['description'] = $article->introduction;
                                    $art_arr[$i]['pubdate'] = (string)$article->created_at;
                                    $art_arr[$i]['pubtimestamp'] = strtotime($article->created_at);
                                    $art_arr[$i]['category']['name'] = $nav->name;
                                    $art_arr[$i]['category']['en_name'] = $nav->name;
                                    if ($nav->url) {
                                        $art_arr[$i]['category']['link'] = $nav->url;
                                    } else {
                                        $art_arr[$i]['category']['link'] = $this->domain . "/category/" . $nav->id;
                                    }
                                    $i++;
                                }
                            }
                            $mIndexCat['data'] = $art_arr;
                        } elseif ($nav->type == 4) {
                            $content = Page::where('id', $nav->page_id)->pluck('content');
                            $mIndexCat['content'] = $content;
                        } elseif ($nav->type == 5 || $nav->type == 6 || $nav->type == 7 || $nav->type == 8 || $nav->type == 9) {
                            //暂时缺省
                        }
                        $mIndexCats[] = $mIndexCat;
                    }
                }
            }
            $classify = new Classify;
            foreach ($mIndexCats as $key => $val) {
                $mIndexCats[$key]['childmenu'] = $classify->toTree($mIndexCats, $mIndexCats[$key]['id']);
            }
            $result['mIndexCats'] = $mIndexCats;
        }
        if ($_SERVER["HTTP_HOST"] == TONGYI_TUISONG_JUYU_IP) {
            return json_encode($result);
        }
        $smarty = new Smarty;
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        @$smarty->display('index.html');
        //return View::make('templates.'.$this->themename.'.index',$result);
    }

    /**
     * 手机首页
     */
    public function mhomepagePush($publicdata)
    {
        $result = $publicdata['result'];
        $result['navs'] = $publicdata['navs'];
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        //获取模板目录
        $data = $this->pagedata('index', $publicdata['pagedata']);
        $show_navs = DB::table('mobile_homepage')->leftJoin('classify', 'classify.id', '=', 'mobile_homepage.c_id')->where('mobile_homepage.index_show', 1)->where('classify.mobile_show', 1)->where('mobile_homepage.cus_id', '=', $this->cus_id)->orderBy('mobile_homepage.s_sort', 'asc')->select('classify.id', 'classify.p_id', 'classify.name', 'classify.en_name', 'classify.type', 'classify.meta_description', 'classify.page_id', 'classify.url', 'classify.img', 'classify.icon', 'mobile_homepage.star_only', 'mobile_homepage.show_num', 'mobile_homepage.m_index_showtype')->get();
        //===调试测试账号===
        $mIndexCats = array();
        if (count($show_navs) > 0) {
            if ($this->showtype == 'preview') {
                foreach ($show_navs as $nav) {
                    $mIndexCat = array();
                    $mIndexCat['id'] = $nav->id;
                    $mIndexCat['p_id'] = $nav->p_id;
                    $mIndexCat['name'] = $nav->name;
                    $mIndexCat['en_name'] = $nav->en_name;
                    $mIndexCat['icon'] = '<i class="iconfont">' . $nav->icon . '</i>';
                    $mIndexCat['image'] = $nav->img ? ($this->source_dir . 'l/category/' . $nav->img) : '';
                    if ($nav->url) {
                        $mIndexCat['link'] = $nav->url;
                    } else {
                        $mIndexCat['link'] = $this->domain . "/category/" . $nav->id;
                    }
                    $mIndexCat['type'] = $nav->type;
                    $mIndexCat['showtype'] = $nav->m_index_showtype;
                    $mIndexCat['description'] = $nav->meta_description;
                    $id_arr = explode(',', $this->getChildrenClassify($nav->id, 1));
                    if ($nav->type == 1 || $nav->type == 2 || $nav->type == 3) {
                        $art_arr = array();
                        if ($nav->star_only) {
                            //是否只显示推荐
                            $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->where('is_star', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                        } else {
                            $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                        }
                        if (count($articles) > 0) {
                            $i = 0;
                            foreach ($articles as &$article) {
                                $art_arr[$i]['title'] = $article->title;
                                $art_arr[$i]['image'] = $article->img ? ($this->source_dir . 's/articles/' . $article->img) : '';
                                $art_arr[$i]['link'] = $this->domain . "/detail/" . $article->id;
                                if ($article->use_url) {
                                    $art_arr[$i]['link'] = $article->url;
                                }
                                $art_arr[$i]['description'] = $article->introduction;
                                $art_arr[$i]['pubdate'] = (string)$article->created_at;
                                $art_arr[$i]['pubtimestamp'] = strtotime($article->created_at);
                                $art_arr[$i]['category']['name'] = $nav->name;
                                $art_arr[$i]['category']['en_name'] = $nav->name;
                                if ($nav->url) {
                                    $art_arr[$i]['category']['link'] = $nav->url;
                                } else {
                                    $art_arr[$i]['category']['link'] = $this->domain . "/category/" . $nav->id;
                                }
                                $i++;
                            }
                        }
                        $mIndexCat['data'] = $art_arr;
                    } elseif ($nav->type == 4) {
                        $content = Page::where('id', $nav->page_id)->pluck('content');
                        $mIndexCat['content'] = $content;
                    } elseif ($nav->type == 5 || $nav->type == 6 || $nav->type == 7 || $nav->type == 8 || $nav->type == 9) {
                        //暂时缺省
                    }
                    $mIndexCats[] = $mIndexCat;
                }
            } else {
                foreach ($show_navs as $nav) {
                    $mIndexCat = array();
                    $mIndexCat['id'] = $nav->id;
                    $mIndexCat['p_id'] = $nav->p_id;
                    $mIndexCat['name'] = $nav->name;
                    $mIndexCat['en_name'] = $nav->en_name;
                    $mIndexCat['icon'] = '<i class="iconfont">' . $nav->icon . '</i>';
                    $mIndexCat['image'] = $nav->img ? ($this->source_dir . "/l/category/" . $nav->img) : '';
                    if ($nav->url) {
                        $mIndexCat['link'] = $nav->url;
                    } else {
                        $mIndexCat['link'] = $this->domain . "/category/" . $nav->id . ".html";
                    }
                    $mIndexCat['type'] = $nav->type;
                    $mIndexCat['showtype'] = $nav->m_index_showtype;
                    $mIndexCat['description'] = $nav->meta_description;
                    $id_arr = explode(',', $this->getChildrenClassify($nav->id, 1));
                    if ($nav->type == 1 || $nav->type == 2 || $nav->type == 3) {
                        $art_arr = array();
                        if ($nav->star_only) {
                            //是否只显示推荐
                            $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->where('is_star', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                        } else {
                            $articles = Articles::whereIn('c_id', $id_arr)->where('mobile_show', 1)->orderBy('is_top', 'desc')->orderBy('sort', 'ASC')->orderBy('created_at', 'DESC')->orderBy('id','DESC')->take($nav->show_num)->get();
                        }
                        if (count($articles) > 0) {
                            $i = 0;
                            foreach ($articles as &$article) {
                                $art_arr[$i]['title'] = $article->title;
                                $art_arr[$i]['image'] = $article->img ? ($this->source_dir . "/s/articles/" . $article->img) : '';
                                $art_arr[$i]['link'] = $this->domain . "/detail/" . $article->id . ".html";
                                if ($article->use_url) {
                                    $art_arr[$i]['link'] = $article->url;
                                }
                                $art_arr[$i]['description'] = $article->introduction;
                                $art_arr[$i]['pubdate'] = (string)$article->created_at;
                                $art_arr[$i]['pubtimestamp'] = strtotime($article->created_at);
                                $art_arr[$i]['category']['name'] = $nav->name;
                                $art_arr[$i]['category']['en_name'] = $nav->name;
                                if ($nav->url) {
                                    $art_arr[$i]['category']['link'] = $nav->url;
                                } else {
                                    $art_arr[$i]['category']['link'] = $this->domain . "/category/" . $nav->id;
                                }
                                $i++;
                            }
                        }
                        $mIndexCat['data'] = $art_arr;
                    } elseif ($nav->type == 4) {
                        $content = Page::where('id', $nav->page_id)->pluck('content');
                        $mIndexCat['content'] = $content;
                    } elseif ($nav->type == 5 || $nav->type == 6 || $nav->type == 7 || $nav->type == 8 || $nav->type == 9) {
                        //暂时缺省
                    }
                    $mIndexCats[] = $mIndexCat;
                }
            }
        }
        $classify = new Classify;
        foreach ($mIndexCats as $key => $val) {
            $mIndexCats[$key]['childmenu'] = $classify->toTree($mIndexCats, $mIndexCats[$key]['id']);
        }
        $result['mIndexCats'] = $mIndexCats;
        $content = $publicdata['repleace']['index.html'];
        $content = preg_replace($publicdata['pattern'], $publicdata['repleace'], $content);
        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        ob_start();
        $smarty->display('string:' . $content);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * 显示栏目页的某个分页
     *
     * @param type $param 栏目id/栏目别名view_name
     * @param type $page 当前页码
     * @param type $result
     * @param type $type 参数类型
     * @return type
     */
    public function categoryPreview($param, $page, $result = array(), $type = 'id')
    {
        if ($type == 'id') {
            $id = $param;
//        } else {
//            $id = Classify::where('view_name', $param)->pluck('id');
        }
        $classify = Classify::find($id);
        if ($_SERVER["HTTP_HOST"] != TONGYI_DOMAIN) {
            $result = $this->pagePublic($id);
            $customerinfo = CustomerInfo::where("cus_id", $this->cus_id)->first();
            foreach ((array)$result['navs'] as $nav) {
                if ($nav['current'] == 1) {
                    $pagenavs = $nav['childmenu'];
                    break;
                } else {
                    $pagenavs = [];
                }
            }
            // $classify = Classify::find($id);
            $result['title'] = ($customerinfo->title != "") ? $customerinfo->title . '-' . $classify->name : $classify->name;
            $result['keywords'] = ($classify->meta_keywords != "") ? $classify->meta_keywords : $customerinfo->keywords;
            $result['description'] = ($classify->meta_description != "") ? $classify->meta_description : $customerinfo->description;
            $result['list']['name'] = $classify->name;
            $result['list']['en_name'] = $classify->en_name;
//            $result['list']['view_name'] = $classify->view_name;
            $result['list']['description'] = $classify->meta_description;
            $result['list']['icon'] = '<i class="iconfont">' . $classify->icon . '</i>';
            $result['list']['image'] = $classify->img ? ($this->source_dir . 's/category/' . $classify->img) : '';
            $result['list']['type'] = $classify->type;
            if ($this->showtype == 'preview') {
                $result['list']['link'] = empty($classify->view_name) ? $this->domain . '/category/' . $id : $this->domain . '/category/v/' . $classify->view_name;
            } else {
                $result['list']['link'] = empty($classify->view_name) ? $this->domain . '/category/' . $id . '.html' : $this->domain . '/category/v/' . $classify->view_name . '.html';
            }
            $result['pagenavs'] = $pagenavs;
            $result['posnavs'] = $this->getPosNavs($id);
            if ($classify->type == 1) {//文字列表
                $viewname = 'list-text';
            } elseif ($classify->type == 2) {//图片列表
                $viewname = 'list-image';
            } elseif ($classify->type == 3) {//图文列表
                $viewname = 'list-imagetext';
            } elseif ($classify->type == 4) {//内容单页
                $viewname = 'list-page';
            } elseif ($classify->type == 5) {//留言板
                $viewname = 'list-page';
            } elseif ($classify->type == 9) {//万用表单
                //===获取数据===
                $viewname = 'list-page';
                $form_id = $classify->form_id;
                $formC = new FormController();
                $formCdata = $formC->getFormdataForPrint($form_id);
            } else {//跳转404
            }
            if (in_array($classify->type, array(1, 2, 3, 4, 5, 9))) {
                $sub = str_replace('-', '_', $viewname);
                $data = $this->pagedata($viewname);
                $index = $this->detailList($data);
                $result = array_add($result, $sub, $index);
                $data_index = $this->pagedata($viewname);
                $index_list = $this->pageList($id, $page);
                if ($classify->type == 4) {
                    if ($this->showtype == 'preview') {
                        $result['list']['content'] = Page::where('id', $classify->page_id)->pluck('content');
                    } else {
                        $result['list']['content'] = preg_replace('/\/customers\/' . $this->customer . '/i', '', Page::where('id', $classify->page_id)->pluck('content'));
                    }
                } elseif ($classify->type == 5) {
                    $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
                    if ($customer_info->lang == 'en') {
                        $result['list']['content'] = '<form action="http://swap.5067.org/message/' . $this->cus_id . '" method="post" name="messageboard" onsubmit="return CheckPost();" class="elegant-aero">
                        <h1>' . $classify->name . '
                        <span>' . $classify->en_name . '</span>
                        </h1>
                        <label>
                        <span>Name :</span>
                        <input id="name" type="text" name="name" placeholder="Name" />
                        </label>
                        <label>
                        <span>Email :</span>
                        <input id="email" type="email" name="email" placeholder="Email Address" />
                        </label>
                        <label>
                        <span>Tel :</span>
                        <input id="telephone" type="tel" name="telephone" placeholder="Telephone" />
                        </label>
                        <label>
                        <label>
                        <span>Content :</span>
                        <textarea id="content" name="content" placeholder="You mind ...."></textarea>
                        </label>
                        <label>
                        <span>&nbsp;</span>
                        <input type="hidden" name="language" value="en" />
                        <input type="submit" class="button" name="submit" value="Submit" />
                        </label>
                        </form>';
                    } else {
                        $result['list']['content'] = '<form action="http://swap.5067.org/message/' . $this->cus_id . '" method="post" name="messageboard" onsubmit="return CheckPost();" class="elegant-aero">
                        <h1>' . $classify->name . '
                        <span>' . $classify->en_name . '</span>
                        </h1>
                        <label>
                        <span>姓名 :</span>
                        <input id="name" type="text" name="name" placeholder="Name" />
                        </label>
                        <label>
                        <span>Email :</span>
                        <input id="email" type="email" name="email" placeholder="Email Address" />
                        </label>
                        <label>
                        <span>联系电话 :</span>
                        <input id="telephone" type="tel" name="telephone" placeholder="Telephone" />
                        </label>
                        <label>
                        <span>内容 :</span>
                        <textarea id="content" name="content" placeholder="You mind ...."></textarea>
                        </label>
                        <label>
                        <span>&nbsp;</span>
                        <input type="hidden" name="language" value="cn" />
                        <input type="submit" class="button" name="submit" value="提交" />
                        </label>
                        </form>';
                    }
                } elseif ($classify->type == 9) {
                    //===显示前端===
                    $result['list']['content'] = $formC->showFormHtmlForPrint($formCdata);
                } else {
                    $result['list']['data'] = $index_list['data'];
                    $result['list']['total'] = $index_list['page_links']['total'];
                }
                $result['page_links'] = $index_list['page_links'];
                $json_keys = $this->getJsonKey($viewname . '.html');
                if (count($json_keys)) {
                    foreach ($json_keys as $key) {
                        $result[$key] = $this->detailList($this->pagedata($key));
                    }
                }
                if ($classify->type == 5) {
                    $result['footscript'] .= '<STYLE TYPE="text/css"> 
                    <!-- 
                    .elegant-aero {
                    margin-left:auto;
                    margin-right:auto;
                    width: 90%;
                    max-width: 500px;
                    /*background: #D2E9FF;*/
                    padding: 20px 20px 20px 20px;
                    /*color: #666;*/
                    }
                    .input[placeholder]{color:#5c5c5c;}
                    .elegant-aero h1 {
                    font: 24px "Trebuchet MS", Arial, Helvetica, sans-serif;
                    padding: 10px 10px 10px 20px;
                    display: block;
                    /*background: #C0E1FF;*/
                    border-bottom: 1px solid #B8DDFF;
                    margin: -20px -20px 15px;
                    }
                    .elegant-aero h1>span {
                    display: block;
                    font-size: 11px;
                    }
                    .elegant-aero label>span {
                    float: left;
                    margin-top: 10px;
                    /*color: #5E5E5E;*/
                    }
                    .elegant-aero label {
                    display: block;
                    margin: 0px 0px 5px;
                    }
                    .elegant-aero label>span {
                        float: left;
                        width: 25%;
                        text-align: right;
                        padding-right: 10px;
                        margin-top: 10px;
                        font-weight: bold;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        white-space: nowrap;
                    }
                    .elegant-aero input[type="text"], .elegant-aero input[type="tel"], .elegant-aero input[type="email"], .elegant-aero textarea, .elegant-aero select {
                    /*color: #888;*/
                    width: 65%;
                    padding: 0px 0px 0px 5px;
                    border: 1px solid #C5E2FF;
                    background: #FBFBFB;
                    outline: 0;
                    -webkit-box-shadow:inset 0px 1px 6px #ECF3F5;
                    box-shadow: inset 0px 1px 6px #ECF3F5;
                    height: 30px;
                    line-height:15px;
                    margin: 2px 4px 16px 0px;
                    }
                    .elegant-aero textarea{
                    height:100px;
                    padding: 5px 0px 0px 5px;
                    width: 65%;
                    }
                    .elegant-aero select {
                    background: #fbfbfb url(\'down-arrow.png\') no-repeat right;
                    background: #fbfbfb url(\'down-arrow.png\') no-repeat right;
                    appearance:none;
                    -webkit-appearance:none;
                    -moz-appearance: none;
                    text-indent: 0.01px;
                    text-overflow: \'\';
                    width: 60%;
                    }
                    .elegant-aero .button{
                    padding: 10px 30px 10px 30px;
                    background: #ACB5B7;
                    border: none;
                    /*color: #FFF;*/
                    box-shadow: 1px 1px 1px #4C6E91;
                    -webkit-box-shadow: 1px 1px 1px #4C6E91;
                    -moz-box-shadow: 1px 1px 1px #4C6E91;
                    text-shadow: 1px 1px 1px #5079A3;
                    }
                    .elegant-aero .button:hover{
                    background: #C5CFD2;
                    color: #6B6262;
                    }--> 
                    </STYLE>
                    <SCRIPT language=javascript>
                    function CheckPost()
                    {   
                        var _name       =messageboard.name.value;
                        var _content    =messageboard.content.value;
                        var _telephone  =messageboard.telephone.value;
                        var _language   =messageboard.language.value;
                        if(_language=="cn"){
                                    if (_name=="")
                                    {
                                        alert("请填写您的姓名");
                                        messageboard.name.focus();
                                        return false;
                                    }
                                    if (_content=="")
                                    {
                                        alert("必须要填写留言内容");
                                        messageboard.content.focus();
                                        return false;
                                    }
                                    if (_telephone!="")
                                    {
                                        if(isNaN(_telephone)){
                                                alert("电话号码请填写数字");
                                                messageboard.telephone.focus();
                                                return false;
                                        }
                                        if(!(/^1[3|4|5|7|8]\d{9}$/.test(_telephone))){
                                                alert("手机号码有误");
                                                messageboard.telephone.focus();
                                                return false;
                                        }
                                    }
                        }else{
                                    if (_name=="")
                                    {
                                        alert("Please fill in your name");
                                        messageboard.name.focus();
                                        return false;
                                    }
                                    if (_content=="")
                                    {
                                        alert("Please fill out the message content");
                                        messageboard.content.focus();
                                        return false;
                                    }
                                    if (_telephone!="")
                                    {
                                        if(isNaN(_telephone)){
                                                alert("Please fill in the Numbers");
                                                messageboard.telephone.focus();
                                                return false;
                                        }
                                        if(!(/^1[3|4|5|7|8]\d{9}$/.test(_telephone))){
                                                alert("Mobile phone number is wrong");
                                                messageboard.telephone.focus();
                                                return false;
                                        }
                                    }
                        }
                    }
                    </SCRIPT>';
                } elseif ($classify->type == 9) {
                    //===加载css\js===
                    $result['footscript'] .= $formC->assignFormCSSandJSForPrint();
                }

                //return View::make('templates.'.$this->themename.'.'.$viewname,$result);
            }elseif ($classify->type == 10) {//新增海报
                if ($this->showtype == 'preview') {
                    $result['list']['content'] = Page::where('id', $classify->page_id)->pluck('content');
                } else {
                    $result['list']['content'] = preg_replace('/\/customers\/' . $this->customer . '/i', '', Page::where('id', $classify->page_id)->pluck('content'));
                }
            } 

            $result["viewname"] = $viewname;
        }
        if ($_SERVER["HTTP_HOST"] == TONGYI_TUISONG_JUYU_IP) {
            return json_encode($result);
        }
        if($classify->type != 10){
            $smarty = new Smarty;
            $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
            $smarty->setCompileDir(app_path('storage/views/compile'));
            $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
            $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
            $smarty->assign($result);
            @$smarty->display($result["viewname"] . '.html');
        }else{
            $html = '<!DOCTYPE">
                    <html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <title>{$title}</title>
                    </head>
                    <body>
                        {$list.content}
                    </body>
                    </html>';
            $smarty = new Smarty;
            $smarty->setCompileDir(app_path('storage/views/compile'));
            $smarty->assign($result);
            @$smarty->display('string:'.$html);
        }

        // $smarty = new Smarty;
        // $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        // $smarty->setCompileDir(app_path('storage/views/compile'));
        // $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        // $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        // $smarty->assign($result);
        // @$smarty->display($result["viewname"] . '.html');
    }

    /**
     * 推送栏目页的某个分页
     *
     * @param int $id 栏目id
     * @param int $page 总页码
     */
    public function categoryPush($id, $page, $publicdata, $last_html_precent, $html_precent)
    {
        $paths = [];
        $result = $publicdata['result'];
        $result['navs'] = $this->publicnavs($id);
        $result['index_navs'] = $result['navs'];
        foreach ((array)$result['navs'] as $nav) {
            if ($nav['current'] == 1) {
                $pagenavs = $nav['childmenu'];
                break;
            } else {
                $pagenavs = [];
            }
        }
        $classify = Classify::find($id);
        $customerinfo = CustomerInfo::where("cus_id", $this->cus_id)->first();
        $result['title'] = ($customerinfo->title != "") ? $customerinfo->title . '-' . $classify->name : $classify->name;
        $result['keywords'] = ($classify->meta_keywords != "") ? $classify->meta_keywords : $customerinfo->keywords;
        $result['description'] = ($classify->meta_description != "") ? $classify->meta_description : $customerinfo->description;
        $result['list']['name'] = $classify->name;
        $result['list']['en_name'] = $classify->en_name;
        $result['list']['description'] = $classify->meta_description;
        $result['list']['icon'] = '<i class="iconfont">' . $classify->icon . '</i>';
        $result['list']['image'] = $classify->img ? ($this->source_dir . 's/category/' . $classify->img) : '';
        $result['list']['type'] = $classify->type;
        if ($this->showtype == 'preview') {
            $result['list']['link'] = $this->domain . '/category/' . $id;
        } else {
            $result['list']['link'] = $this->domain . '/category/' . $id . '.html';
        }
        $result['pagenavs'] = $pagenavs;
        $result['posnavs'] = $this->getPosNavs($id);
        if ($classify->type == 1) {//文字列表
            $viewname = 'list-text';
        } elseif ($classify->type == 2) {//图片列表
            $viewname = 'list-image';
        } elseif ($classify->type == 3) {//图文列表
            $viewname = 'list-imagetext';
        } elseif ($classify->type == 4) {//内容单页
            $viewname = 'list-page';
        } elseif ($classify->type == 5) {//留言板
            $viewname = 'list-page';
        } elseif ($classify->type == 9) {//万用表单
            //===获取数据===
            $viewname = 'list-page';
            $form_id = $classify->form_id;
            $formC = new FormController();
            $formCdata = $formC->getFormdataForPrint($form_id);
        } else {//跳转404
        }
        // if (in_array($classify->type, array(1, 2, 3, 4, 5, 9))) {
        if (in_array($classify->type, array(1, 2, 3, 4, 5, 9, 10))) {//加入海报
            $sub = str_replace('-', '_', $viewname);
            $data = $this->pagedata($viewname, $publicdata['pagedata']);
            $index = $this->detailList($data);
            $result = array_add($result, $sub, $index);
            $data_index = $this->pagedata($viewname);
            if ($classify->type == 4) {
                if ($this->showtype == 'preview') {
                    $result['list']['content'] = Page::where('id', $classify->page_id)->pluck('content');
                } else {
                    $result['list']['content'] = preg_replace('/\/customers\/' . $this->customer . '/i', '', Page::where('id', $classify->page_id)->pluck('content'));
                }
            } elseif ($classify->type == 5) {
                $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
                if ($customer_info->lang == 'en') {
                    $result['list']['content'] = '<form action="http://swap.5067.org/message/' . $this->cus_id . '" method="post" name="messageboard" onsubmit="return CheckPost();" class="elegant-aero">
                    <h1>' . $classify->name . '
                    <span>' . $classify->en_name . '</span>
                    </h1>
                    <label>
                    <span>Name :</span>
                    <input id="name" type="text" name="name" placeholder="Name" />
                    </label>
                    <label>
                    <span>Email :</span>
                    <input id="email" type="email" name="email" placeholder="Email Address" />
                    </label>
                    <label>
                    <span>Tel :</span>
                    <input id="telephone" type="tel" name="telephone" placeholder="Telephone" />
                    </label>
                    <label>
                    <label>
                    <span>Content :</span>
                    <textarea id="content" name="content" placeholder="You mind ...."></textarea>
                    </label>
                    <label>
                    <span>&nbsp;</span>
                    <input type="hidden" name="language" value="en" />
                    <input type="submit" class="button" name="submit" value="Submit" />
                    </label>
                    </form>';
                } else {
                    $result['list']['content'] = '<form action="http://swap.5067.org/message/' . $this->cus_id . '" method="post" name="messageboard" onsubmit="return CheckPost();" class="elegant-aero">
                    <h1>' . $classify->name . '
                    <span>' . $classify->en_name . '</span>
                    </h1>
                    <label>
                    <span>姓名 :</span>
                    <input id="name" type="text" name="name" placeholder="Name" />
                    </label>
                    <label>
                    <span>Email :</span>
                    <input id="email" type="email" name="email" placeholder="Email Address" />
                    </label>
                    <label>
                    <span>联系电话 :</span>
                    <input id="telephone" type="tel" name="telephone" placeholder="Telephone" />
                    </label>
                    <label>
                    <span>内容 :</span>
                    <textarea id="content" name="content" placeholder="You mind ...."></textarea>
                    </label>
                    <label>
                    <span>&nbsp;</span>
                    <input type="hidden" name="language" value="cn" />
                    <input type="submit" class="button" name="submit" value="提交" />
                    </label>
                    </form>';
                }
            } elseif ($classify->type == 9) {
                //===显示前端===
                $result['list']['content'] = $formC->showFormHtmlForPrint($formCdata);
            } elseif ($classify->type == 10) {//海报
                if ($this->showtype == 'preview') {
                    $result['list']['content'] = Page::where('id', $classify->page_id)->pluck('content');
                } else {
                    $result['list']['content'] = preg_replace('/\/customers\/' . $this->customer . '/i', '', Page::where('id', $classify->page_id)->pluck('content'));
                }
            }
            if($classify->type != 10){
               $json_keys = $this->getJsonKey($viewname . '.html');
                if (count($json_keys)) {
                    foreach ($json_keys as $key) {
                        $result[$key] = $this->detailList($this->pagedata($key));
                    }
                } 
            }             
            if ($classify->type == 5) {
                $result['footscript'] .= '<STYLE TYPE="text/css"> 
                <!-- 
                .elegant-aero {
                margin-left:auto;
                margin-right:auto;
                width: 90%;
                max-width: 500px;
                /*background: #D2E9FF;*/
                padding: 20px 20px 20px 20px;
                font: 12px Arial, Helvetica, sans-serif;
                /*color: #666;*/
                }
                .input[placeholder]{color:#5c5c5c;}
                .elegant-aero h1 {
                font: 24px "Trebuchet MS", Arial, Helvetica, sans-serif;
                padding: 10px 10px 10px 20px;
                display: block;
                /*background: #C0E1FF;*/
                border-bottom: 1px solid #B8DDFF;
                margin: -20px -20px 15px;
                }
                .elegant-aero h1>span {
                display: block;
                font-size: 11px;
                }
                .elegant-aero label>span {
                float: left;
                margin-top: 10px;
                /*color: #5E5E5E;*/
                }
                .elegant-aero label {
                display: block;
                margin: 0px 0px 5px;
                }
                .elegant-aero label>span {
                float: left;
                width: 25%;
                text-align: right;
                padding-right: 10px;
                margin-top: 10px;
                font-weight: bold;
                }
                .elegant-aero input[type="text"], .elegant-aero input[type="tel"], .elegant-aero input[type="email"], .elegant-aero textarea, .elegant-aero select {
                color: #888;
                width: 65%;
                padding: 0px 0px 0px 5px;
                border: 1px solid #C5E2FF;
                background: #FBFBFB;
                outline: 0;
                -webkit-box-shadow:inset 0px 1px 6px #ECF3F5;
                box-shadow: inset 0px 1px 6px #ECF3F5;
                font: 200 12px/25px Arial, Helvetica, sans-serif;
                height: 30px;
                line-height:15px;
                margin: 2px 4px 16px 0px;
                }
                .elegant-aero textarea{
                height:100px;
                padding: 5px 0px 0px 5px;
                width: 65%;
                }
                .elegant-aero select {
                background: #fbfbfb url(\'down-arrow.png\') no-repeat right;
                background: #fbfbfb url(\'down-arrow.png\') no-repeat right;
                appearance:none;
                -webkit-appearance:none;
                -moz-appearance: none;
                text-indent: 0.01px;
                text-overflow: \'\';
                width: 70%;
                }
                .elegant-aero .button{
                padding: 10px 30px 10px 30px;
                background: #ACB5B7;
                border: none;
                color: #FFF;
                box-shadow: 1px 1px 1px #4C6E91;
                -webkit-box-shadow: 1px 1px 1px #4C6E91;
                -moz-box-shadow: 1px 1px 1px #4C6E91;
                text-shadow: 1px 1px 1px #5079A3;
                }
                .elegant-aero .button:hover{
                background: #C5CFD2;
                color: #6B6262;
                }--> 
                </STYLE>
                <SCRIPT language=javascript>
                function CheckPost()
                {
                    if(messageboard.language.value=="cn"){
                        if (messageboard.name.value=="")
                        {
                                alert("请填写您的姓名");
                                messageboard.name.focus();
                                return false;
                        }
                        if (messageboard.content.value=="")
                        {
                                alert("必须要填写留言内容");
                                messageboard.content.focus();
                                return false;
                        }
                        if (messageboard.telephone.value!="")
                        {
                                if(isNaN(messageboard.telephone.value)){
                                    alert("电话号码请填写数字");
                                    messageboard.telephone.focus();
                                    return false;
                                }
                                if(!(/^1[3|4|5|7|8]\d{9}$/.test(messageboard.telephone.value))){
                                        alert("手机号码有误");
                                        messageboard.telephone.focus();
                                        return false;
                                }
                        }
                    }else{
                        if (messageboard.name.value=="")
                        {
                                alert("Please fill in your name");
                                messageboard.name.focus();
                                return false;
                        }
                        if (messageboard.content.value=="")
                        {
                                alert("Please fill out the message content");
                                messageboard.content.focus();
                                return false;
                        }
                        if (messageboard.telephone.value!="")
                        {
                                if(isNaN(messageboard.telephone.value)){
                                    alert("Please fill in the Numbers");
                                    messageboard.telephone.focus();
                                    return false;
                                }
                                if(!(/^1[3|4|5|7|8]\d{9}$/.test(messageboard.telephone.value))){
                                        alert("Mobile phone number is wrong");
                                        messageboard.telephone.focus();
                                        return false;
                                }
                        }
                    }
                }
                </SCRIPT>';
            }
            if ($classify->type == 9) {
                //===加载css\js===			
                $result['footscript'] .= $formC->assignFormCSSandJSForPrint();
            }
            $the_result = $result;
            $index_list = $this->pageList($id, 1);
            $the_result['page_links'] = $index_list['page_links'];
            //===显示类型不是'list-page'===
            // if ($classify->type != 5 && $classify->type != 4 && $classify->type != 9) {
            if ($classify->type != 5 && $classify->type != 4 && $classify->type != 9 && $classify->type != 10) {//加入海报
                $the_result['list']['data'] = $index_list['data'];
                $the_result['list']['total'] = $index_list['page_links']['total'];
            }
            //===页面名字.html===
//            if ($classify->view_name) {
//                $path = $this->type == 'pc' ? public_path('customers/' . $this->customer . '/category/v/' . $classify->view_name . '.html') : public_path('customers/' . $this->customer . '/mobile/category/v/' . $classify->view_name . '.html');
//            } else {
            $path = $this->type == 'pc' ? public_path('customers/' . $this->customer . '/category/' . $id . '.html') : public_path('customers/' . $this->customer . '/mobile/category/' . $id . '.html');
//            }
            //===判断是不是海报===
            if($classify->type != 10){//不是海报的执行语句
                $content = $publicdata['repleace'][$viewname . '.html'];
                $content = preg_replace($publicdata['pattern'], $publicdata['repleace'], $content);
                $output = $this->pushdisplay($the_result, $content);                
            }else{
                $output = $this->pushPoster($the_result);
            }
            //===判断结束===
            //原
            // $content = $publicdata['repleace'][$viewname . '.html'];
            // $content = preg_replace($publicdata['pattern'], $publicdata['repleace'], $content);
            // $output = $this->pushdisplay($the_result, $content);
            if (!count($result['footer_navs'])) {
                $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>首页<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
                $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>Home<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
            }
            file_put_contents($path, $output);
            $paths[] = $path;
            //是否开通小程序
            if($this->is_applets == 1 && $this->type == 'mobile') {
                //小程序替换正则(链接，quickbar引入)
                $wx_pattern = ["/(src|href)(=[\"'])(\/)(images|js|css|category|detail|quickbar|themes)/", "/quickbar(\.js\?\d+mobile)/"];
                //替换为
                $wx_replace = ['${1}${2}../${4}', 'quickbar-wpage${1}'];
                //检测和创建目录
                if(!is_dir(public_path('customers/' . $this->customer . '/wx'))) {
                    mkdir(public_path('customers/' . $this->customer . '/wx'));
                    mkdir(public_path('customers/' . $this->customer . '/wx/category'));
                } else {
                    if(!is_dir(public_path('customers/' . $this->customer . '/wx/category'))) {
                        mkdir(public_path('customers/' . $this->customer . '/wx/category'));
                    }
                }
                //页面路径
                $wx_path = public_path('customers/' . $this->customer . '/wx/category/' . $id . '.html');
                //匹配替换
                $wx_out = preg_replace($wx_pattern, $wx_replace, $output);
                //栏目页面
                file_put_contents($wx_path, $wx_out);
                $wx_paths[] = $wx_path;
            }
            $nowpercent = $last_html_precent + $html_precent;
            if (floor($nowpercent) !== floor($last_html_precent)) {
                echo '<div class="prompt">' . floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                ob_flush();
                flush();
                PushQueue::where('pushtime', '<', time() - 60)->delete();
                PushQueue::where('cus_id', $this->cus_id)->update(['pushtime' => time()]);
            }
            $last_html_precent += $html_precent;
            //===显示类型不是'list-page'===
            // if ($classify->type != 5 && $classify->type != 4 && $classify->type != 9) {
            if ($classify->type != 5 && $classify->type != 4 && $classify->type != 9 && $classify->type != 10) {//加入海报
                for ($i = 1; $i <= $page; $i++) {
                    $the_result = $result;
                    $index_list = $this->pageList($id, $i);
                    $the_result['page_links'] = $index_list['page_links'];
                    $the_result['list']['data'] = $index_list['data'];
                    $the_result['list']['total'] = $index_list['page_links']['total'];
                    //===页面名字.html===
//                    if ($classify->view_name) {
//                        $path = $this->type == 'pc' ? public_path('customers/' . $this->customer . '/category/' . $classify->view_name . '_' . $i . '.html') : public_path('customers/' . $this->customer . '/mobile/category/' . $classify->view_name . '_' . $i . '.html');
//                    } else {
                    $path = $this->type == 'pc' ? public_path('customers/' . $this->customer . '/category/' . $id . '_' . $i . '.html') : public_path('customers/' . $this->customer . '/mobile/category/' . $id . '_' . $i . '.html');
//                    }
                    //===end===
                    $output = $this->pushdisplay($the_result, $content);
                    if (!count($result['footer_navs'])) {
                        $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>首页<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
                        $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>Home<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
                    }
                    file_put_contents($path, $output);
                    $paths[] = $path;
                    //是否开通小程序                
                    if($this->is_applets == 1 && $this->type == 'mobile') {
                        //检测和创建目录
                        if(!is_dir(public_path('customers/' . $this->customer . '/wx'))) {
                            mkdir(public_path('customers/' . $this->customer . '/wx'));
                            mkdir(public_path('customers/' . $this->customer . '/wx/category'));
                        } else {
                            if(!is_dir(public_path('customers/' . $this->customer . '/wx/category'))) {
                                mkdir(public_path('customers/' . $this->customer . '/wx/category'));
                            }
                        }
                        //页面路径
                        $wx_path = public_path('customers/' . $this->customer . '/wx/category/' . $id . '_' . $i . '.html');
                        //小程序替换正则(链接，quickbar引入)
                        $wx_pattern = ["/(src|href)(=[\"'])(\/)(images|js|css|category|detail|quickbar|themes)/", "/quickbar(\.js\?\d+mobile)/"];
                        //替换为
                        $wx_replace = ['${1}${2}../${4}', 'quickbar-wpage${1}'];
                        //匹配替换                        
                        $wx_out = preg_replace($wx_pattern, $wx_replace, $output);
                        //栏目页面
                        file_put_contents($wx_path, $wx_out);
                        $wx_paths[] = $wx_path;
                    }
                    $nowpercent = $last_html_precent + $html_precent;
                    if (floor($nowpercent) !== floor($last_html_precent)) {
                        echo '<div class="prompt">' . floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                        ob_flush();
                        flush();
                        PushQueue::where('pushtime', '<', time() - 60)->delete();
                        PushQueue::where('cus_id', $this->cus_id)->update(['pushtime' => time()]);
                    }
                    $last_html_precent += $html_precent;
                }
            }
            $res['paths'] = $paths;
            $res['wx_paths'] = isset($wx_paths) ? $wx_paths : null;
            return $res;
            //return View::make('templates.'.$this->themename.'.'.$viewname,$result);
        }
    }

    private function pushdisplay($result, $content)
    {
        ob_start();
        $smarty = new Smarty;
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        $smarty->display('string:' . $content);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    //海报的生成
    private function pushPoster($result)
    {
        ob_start();
        $content = '<!DOCTYPE">
                    <html>
                    <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <title>{$title}</title>
                    </head>
                    <body>
                        {$list.content}
                    </body>
                    </html>';
        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->assign($result);
        $smarty->display('string:' . $content);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * 获取当前包含的页面
     *
     * @param type $viewname
     * @param type $json_keys
     * @return type
     */
    private function getJsonKey($viewname, &$json_keys = [])
    {
        $content = file_get_contents(app_path('views/templates/' . $this->themename . '/') . $viewname);
        preg_match_all('/\{include\s+file=[\"|\'](.*\.html)\s*[\"|\']\}/i', $content, $i_arr);
        if (count($i_arr)) {
            foreach ((array)$i_arr[1] as $i_c) {
                $i_info = pathinfo($i_c);
                $json_keys[] = $i_info['filename'];
                $this->getJsonKey($i_info['filename'] . '.html', $json_keys);
            }
        }
        return $json_keys;
    }

    /**
     * 显示文章页
     *
     * @param int $id 文章id
     */
    public function articlePreview($id, $result = array())
    {
        if ($_SERVER["HTTP_HOST"] != TONGYI_DOMAIN) {
            $article = Articles::find($id);
            $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
            if ($customer_info->lang == 'en') {
                $lang['the_last'] = 'The last one';
                $lang['the_first'] = 'The first one';
            } else {
                $lang['the_last'] = '已经是最后一篇';
                $lang['the_first'] = '已经是第一篇';
            }
            $a_moreimg = Moreimg::where('a_id', $id)->where('from', '=', null)->get()->toArray();
            array_unshift($a_moreimg, array('title' => $article->title, 'img' => $article->img));
            $images = array();
            if (count($a_moreimg)) {
                $i = 0;
                foreach ($a_moreimg as $a_img) {
                    $images[$i]['title'] = $a_img['title'];
                    $images[$i]['image'] = $a_img['img'] ? ($this->source_dir . 'l/articles/' . $a_img['img']) : '';
                    $i++;
                }
            }
            $list_id = Articles::where('c_id', $article->c_id)->where($this->type . '_show', '1')->where('use_url', '0')->orderBy('is_top', 'desc')->orderBy('sort', 'asc')->orderBy('created_at', 'desc')->orderBy('id','DESC')->select('id', 'title', 'img', 'introduction', 'created_at')->lists('id');
            foreach ((array)$list_id as $key => $val) {
                $article_prev = NULL;
                $article_next = NULL;
                if ($val == $id) {
                    if ($key != 0) {
                        $prev_id = $list_id[$key - 1];
                        $article_prev = Articles::find($prev_id);
                    }
                    if ($key < (count($list_id) - 1)) {
                        $next_id = $list_id[$key + 1];
                        $article_next = Articles::find($next_id);
                    }
                    break;
                }
            }
            $result = $this->pagePublic($article->c_id);
            if (is_array($result['navs']) && !empty($result['navs'])) {
                foreach ((array)$result['navs'] as $nav) {
                    if ($nav['current'] == 1) {
                        $pagenavs = $nav['childmenu'];
                        break;
                    } else {
                        $pagenavs = array();
                    }
                }
            }
            if (empty($pagenavs)) {
                $pagenavs = array();
            }
            $result['pagenavs'] = $pagenavs;
            $result['posnavs'] = $this->getPosNavs($article->c_id);
            $result['title'] = ($customer_info->title != "") ? $customer_info->title . '-' . $article->title : $article->title;
            $result['keywords'] = ($article->keywords != "") ? $article->keywords : $customer_info->keywords;
            $result['description'] = ($article->introduction != "") ? $article->introduction : $customer_info->description;
            $result['article']['title'] = $article->title;
            $result['article']['keywords'] = $article->keywords;
            $result['article']['description'] = $article->introduction;
            $result['article']['viewcount'] = '<em id="article-viewcount">0</em>';
            $result['enlarge'] = 0;
            $article_type = Articles::leftJoin('classify', 'classify.id', '=', 'article.c_id')->where('article.id', $id)->pluck('article_type');
            if ($article_type == 1) {//新闻内容
                $viewname = 'content-news';
            } elseif ($article_type == 2) {//产品内容
                $viewname = 'content-product';
                $result['enlarge'] = $customer_info->enlarge;
                if ($result['enlarge'] && $this->type == 'pc') {
                    $result['footscript'] .= '<script type="text/javascript" src="http://swap.5067.org/js/img.js"></script>';
                }
            } else {//跳转404
            }
            //关联文章查询
            //        $pa = new PhpAnalysis();
            //
            //        $pa->SetSource($article->title);
            //
            //        //设置分词属性
            //        $pa->resultType = 2;
            //        $pa->differMax = true;
            //        $pa->StartAnalysis();
            //
            //        //获取你想要的结果
            //        $keywords = $pa->GetFinallyIndex();
            //        if (count($keywords)) {
            //            $relation_where = "";
            //            foreach ((array) $keywords as $key => $word) {
            //                $relation_where.="or title like '%$key%' ";
            //            }
            //            $relation_where = ltrim($relation_where, 'or');
            //            $prefix = Config::get('database.connections.mysql.prefix');
            //            $related_data = DB::select("select id,title,img as image,introduction,created_at,c_id from {$prefix}article where cus_id={$this->cus_id} and ($relation_where)");
            //            $related = array();
            //            if (count($related_data)) {
            //                foreach ((array) $related_data as $val) {
            //                    $temp_arr = [];
            //                    $temp_arr['title'] = $val->title;
            //                    $temp_arr['description'] = $val->introduction;
            //                    $temp_arr['image'] = $this->source_dir . 'l/articles/' . $val->image;
            //                    if ($this->showtype == 'preview') {
            //                        $temp_arr['link'] = $this->domain . '/detail/' . $val->id;
            //                        $temp_arr['category']['link'] = $this->domain . '/category/' . $val->id . '.html';
            //                    } else {
            //                        $temp_arr['link'] = $this->domain . '/detail/' . $val->id . '.html';
            //                        $temp_arr['category']['link'] = $this->domain . '/category/' . $val->id . '.html';
            //                    }
            //                    $temp_arr['pubdate'] = $val->created_at;
            //                    $temp_arr['pubtimestamp'] = strtotime($val->created_at);
            //                    $a_c_info = Classify::where('id', $val->c_id)->first();
            //                    $temp_arr['category']['name'] = $a_c_info->name;
            //                    $temp_arr['category']['en_name'] = $a_c_info->en_name;
            //                    $temp_arr['category']['icon'] = '<i class="iconfont">' . $a_c_info->icon . '</i>';
            //                    $related[] = $temp_arr;
            //                }
            //            }
            //        }
            $articles = Articles::where($this->type . '_show', '1')->where('c_id', $article->c_id)->where('use_url', '0')->orderBy('is_top', 'desc')->orderBy('sort', 'asc')->orderBy('created_at', 'desc')->orderBy('id','DESC')->get()->toArray();
            $related = array();
            for (; count($related) < 6 && count($related) < count($articles);) {
                $k = rand(0, count($articles) - 1);
                if ($this->showtype == 'preview') {
                    $related[$k]['link'] = $this->domain . '/detail/' . $articles[$k]['id'];
                    $related[$k]['category']['link'] = $this->domain . '/category/' . $articles[$k]['id'] . '.html';
                } else {
                    $related[$k]['link'] = $this->domain . '/detail/' . $articles[$k]['id'] . '.html';
                    $related[$k]['category']['link'] = $this->domain . '/category/' . $articles[$k]['id'] . '.html';
                }
                $related[$k]['title'] = $articles[$k]['title'];
                $related[$k]['description'] = $articles[$k]['introduction'];
                $related[$k]['image'] = $articles[$k]['img'] ? ($this->source_dir . 'l/articles/' . $articles[$k]['img']) : '';
//                $related[$k]['image'] = $articles[$k]['img'] ? ($this->source_dir . 'ueditor/' . $articles[$k]['img']) : ''; //debug
                $related[$k]['pubdate'] = $articles[$k]['created_at'];
                $related[$k]['pubtimestamp'] = strtotime($articles[$k]['created_at']);
                $a_c_info = Classify::where('id', $articles[$k]['c_id'])->first();
                $related[$k]['category']['name'] = $a_c_info->name;
                $related[$k]['category']['en_name'] = $a_c_info->en_name;
                $related[$k]['category']['icon'] = '<i class="iconfont">' . $a_c_info->icon . '</i>';
            }
            if ($this->showtype == 'preview') {
                if ($article_next === NULL) {
                    $result['article']['next']['title'] = $lang['the_last'];
                    $result['article']['next']['link'] = '';
                } else {
                    $result['article']['next']['title'] = $article_next->title;
                    $result['article']['next']['link'] = $this->domain . '/detail/' . $article_next->id;
                }
                if ($article_prev === NULL) {
                    $result['article']['prev']['title'] = $lang['the_first'];
                    $result['article']['prev']['link'] = '';
                } else {
                    $result['article']['prev']['title'] = $article_prev->title;
                    $result['article']['prev']['link'] = $this->domain . '/detail/' . $article_prev->id;
                }
                $result['article']['image'] = $article->img ? ($this->source_dir . 'l/articles/' . $article->img) : '';
                $result['article']['images'] = $images;
                $result['article']['content'] = $article->content;
                //解决编辑器英文引号与分享图标的问题
                // $result['article']['content'] = htmlentities($result['article']['content'],ENT_COMPAT);
            } else {
                if ($article_next === NULL) {
                    $result['article']['next']['title'] = $lang['the_last'];
                    $result['article']['next']['link'] = '';
                } else {
                    $result['article']['next']['title'] = $article_next->title;
                    $result['article']['next']['link'] = $this->domain . '/detail/' . $article_next->id . '.html';
                }
                if ($article_prev === NULL) {
                    $result['article']['prev']['title'] = $lang['the_first'];
                    $result['article']['prev']['link'] = '';
                } else {
                    $result['article']['prev']['title'] = $article_prev->title;
                    $result['article']['prev']['link'] = $this->domain . '/detail/' . $article_prev->id . '.html';
                }
                $result['article']['image'] = $article->img ? ($this->source_dir . 'l/articles/' . $article->img) : '';
                $result['article']['images'] = $images;
                $result['article']['content'] = preg_replace('/\/customers\/' . $this->customer . '/i', '', $article->content);
                //解决编辑器英文引号与分享图标的问题
                // $result['article']['content'] = htmlentities($result['article']['content'],ENT_COMPAT);
            }
            $result['article']['description'] = $article->introduction;
            $result['article']['pubdate'] = (string)$article->created_at;
            $result['article']['pubtimestamp'] = strtotime($article->created_at);

            $result['article']['category'] = $result['posnavs'][count($result['posnavs']) - 1];
            $result['related'] = $related;
            $json_keys = $this->getJsonKey($viewname . '.html');
            if (count($json_keys)) {
                foreach ((array)$json_keys as $key) {
                    $result[$key] = $this->detailList($this->pagedata($key));
                }
            }
            $result["viewname"] = $viewname;
        }
        if ($_SERVER["HTTP_HOST"] == TONGYI_TUISONG_JUYU_IP) {
            return json_encode($result);
        }
        $smarty = new Smarty;
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        @$smarty->display($result["viewname"] . '.html');
    }

    /**
     * 推送文章页
     *
     * @param int $id 文章id
     */
    public function articlepush($c_id, $publicdata, $last_html_precent, $html_precent)
    {
        set_time_limit(0);
        $paths = [];
        $result = $publicdata['result'];
        $result['navs'] = $this->publicnavs($c_id);
        $result['index_navs'] = $result['navs'];
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        if ($customer_info->lang == 'en') {
            $lang['the_last'] = 'The last one';
            $lang['the_first'] = 'The first one';
        } else {
            $lang['the_last'] = '已经是最后一篇';
            $lang['the_first'] = '已经是第一篇';
        }
        if (is_array($result['navs']) && !empty($result['navs'])) {
            foreach ($result['navs'] as $nav) {
                if ($nav['current'] == 1) {
                    $pagenavs = $nav['childmenu'];
                    break;
                } else {
                    $pagenavs = array();
                }
            }
        }
        if (empty($pagenavs)) {
            $pagenavs = array();
        }
        $result['pagenavs'] = $pagenavs;
        $result['posnavs'] = $this->getPosNavs($c_id);
        $result['enlarge'] = 0;
        $article_type = Classify::where('id', $c_id)->pluck('article_type');
        if ($article_type == 1) {//新闻内容
            $viewname = 'content-news';
        } elseif ($article_type == 2) {//产品内容
            $viewname = 'content-product';
            $result['enlarge'] = $customer_info->enlarge;
            if ($result['enlarge'] && $this->type == 'pc') {
                $result['footscript'] .= '<script type="text/javascript" src="http://swap.5067.org/js/img.js"></script>';
            }
        } else {//跳转404
        }

        $result['article']['category'] = $result['posnavs'][count($result['posnavs']) - 1];
        $json_keys = $this->getJsonKey($viewname . '.html');
        if (count($json_keys)) {
            foreach ((array)$json_keys as $key) {
                $result[$key] = $this->detailList($this->pagedata($key, $publicdata['pagedata']));
            }
        }
        $articles = Articles::where($this->type . '_show', '1')->where('c_id', $c_id)->where('use_url', '0')->orderBy('is_top', 'desc')->orderBy('sort', 'asc')->orderBy('created_at', 'desc')->orderBy('id','DESC')->get()->toArray();
        $content = $publicdata['repleace'][$viewname . '.html'];
        $content = preg_replace($publicdata['pattern'], $publicdata['repleace'], $content);
        foreach ((array)$articles as $key => $article) {
            $the_result = array();
            $the_result = $result;
            $a_moreimg = Moreimg::where('a_id', $article['id'])->where('from', '=', null)->get()->toArray();
            array_unshift($a_moreimg, array('title' => $article['title'], 'img' => $article['img']));
            $images = array();
            if (count($a_moreimg)) {
                $i = 0;
                foreach ((array)$a_moreimg as $a_img) {
                    $images[$i]['title'] = $a_img['title'];
                    $images[$i]['image'] = $a_img['img'] ? ($this->source_dir . 'l/articles/' . $a_img['img']) : '';
                    $i++;
                }
            }
            $the_result['title'] = ($customer_info->title != "") ? $customer_info->title . '-' . $article['title'] : $article['title'];
            $the_result['keywords'] = ($article['keywords'] != "") ? $article['keywords'] : $customer_info->keywords;
            $the_result['description'] = ($article['introduction'] != "") ? $article['introduction'] : $customer_info->description;
            $the_result['article']['title'] = $article['title'];
            $the_result['article']['keywords'] = $article['keywords'];
            $the_result['article']['description'] = $article['introduction'];
            $the_result['article']['viewcount'] = '<em id="article-viewcount">0</em>';

            if (!isset($articles[$key + 1])) {
                $the_result['article']['next']['title'] = $lang['the_last'];
                $the_result['article']['next']['link'] = '';
            } else {
                $the_result['article']['next']['title'] = $articles[$key + 1]['title'];
                $the_result['article']['next']['link'] = $this->domain . '/detail/' . ($articles[$key + 1]['id']) . '.html';
            }

            if (!isset($articles[$key - 1])) {
                $the_result['article']['prev']['title'] = $lang['the_first'];
                $the_result['article']['prev']['link'] = '';
            } else {
                $the_result['article']['prev']['title'] = $articles[$key - 1]['title'];
                $the_result['article']['prev']['link'] = $this->domain . '/detail/' . ($articles[$key - 1]['id']) . '.html';
            }
            $the_result['article']['image'] = $article['img'] ? ($this->source_dir . 'l/articles/' . $article['img']) : '';
            $the_result['article']['images'] = $images;
            $the_result['article']['content'] = preg_replace('/\/customers\/' . $this->customer . '/i', '', $article['content']);
            //解决编辑器英文引号与分享图标的问题
            // $the_result['article']['content'] = htmlentities($the_result['article']['content'],ENT_COMPAT);
            
            $the_result['article']['description'] = $article['introduction'];
            $the_result['article']['pubdate'] = $article['created_at'];
            $the_result['article']['pubtimestamp'] = strtotime($article['created_at']);
            //关联文章查询
//                    $pa = new PhpAnalysis();
//
//                    $pa->SetSource($article['title']);
//
//                    //设置分词属性
//                    $pa->resultType = 2;
//                    $pa->differMax = true;
//                    $pa->StartAnalysis();
//
//                    //获取你想要的结果
//                    $keywords = $pa->GetFinallyIndex();
//                    if (count($keywords)) {
//                        $relation_where = "";
//                        foreach ((array) $keywords as $key => $word) {
//                            $relation_where.="or title like '%$key%' ";
//                        }
//                        $relation_where = ltrim($relation_where, 'or');
//                        $prefix = Config::get('database.connections.mysql.prefix');
//                        $related_data = DB::select("select id,title,img as image,introduction,created_at,c_id from {$prefix}article where cus_id={$this->cus_id} and ($relation_where)");
//                        $related = array();
//                        if (count($related_data)) {
//                            foreach ((array) $related_data as $val) {
//                                $temp_arr = array();
//                                $temp_arr['title'] = $val->title;
//                                $temp_arr['description'] = $val->introduction;
//                                $temp_arr['image'] = $this->source_dir . 'l/articles/' . $val->image;
//                                if ($this->showtype == 'preview') {
//                                    $temp_arr['link'] = $this->domain . '/detail/' . $val->id;
//                                    $temp_arr['category']['link'] = $this->domain . '/category/' . $val->id . '.html';
//                                } else {
//                                    $temp_arr['link'] = $this->domain . '/detail/' . $val->id . '.html';
//                                    $temp_arr['category']['link'] = $this->domain . '/category/' . $val->id . '.html';
//                                }
//                                $temp_arr['pubdate'] = $val->created_at;
//                                $temp_arr['pubtimestamp'] = strtotime($val->created_at);
//                                $a_c_info = Classify::where('id', $val->c_id)->first();
//                                $temp_arr['category']['name'] = $a_c_info->name;
//                                $temp_arr['category']['en_name'] = $a_c_info->en_name;
//                                $temp_arr['category']['icon'] = '<i class="iconfont">' . $a_c_info->icon . '</i>';
//                                $related[] = $temp_arr;
//                            }
//                        }
//                    }
            $related = array();
            for (; count($related) < 6 && count($related) < count($articles);) {
                $k = rand(0, count($articles) - 1);
                $related[$k]['link'] = $this->domain . '/detail/' . $articles[$k]['id'] . '.html';
                $related[$k]['category']['link'] = $this->domain . '/category/' . $articles[$k]['id'] . '.html';
                $related[$k]['title'] = $articles[$k]['title'];
                $related[$k]['description'] = $articles[$k]['introduction'];
                $related[$k]['image'] = $articles[$k]['img'] ? ($this->source_dir . 'l/articles/' . $articles[$k]['img']) : '';
                $related[$k]['pubdate'] = $articles[$k]['created_at'];
                $related[$k]['pubtimestamp'] = strtotime($articles[$k]['created_at']);
                $a_c_info = Classify::where('id', $articles[$k]['c_id'])->first();
                $related[$k]['category']['name'] = $a_c_info->name;
                $related[$k]['category']['en_name'] = $a_c_info->en_name;
                $related[$k]['category']['icon'] = '<i class="iconfont">' . $a_c_info->icon . '</i>';
            }
            $the_result['related'] = $related;
            $output = $this->pushdisplay($the_result, $content);
            if (!count($result['footer_navs'])) {
                $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>首页<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
                $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>Home<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
            }
            $path = $this->type == 'pc' ? public_path('customers/' . $this->customer . '/detail/' . $article['id'] . '.html') : public_path('customers/' . $this->customer . '/mobile/detail/' . $article['id'] . '.html');
            file_put_contents($path, $output);
            $paths[] = $path;
            //是否开通小程序                  
            if($this->is_applets == 1 && $this->type == 'mobile') {
                //检测和创建目录
                if(!is_dir(public_path('customers/' . $this->customer . '/wx'))) {
                    mkdir(public_path('customers/' . $this->customer . '/wx'));
                    mkdir(public_path('customers/' . $this->customer . '/wx/detail'));
                } else {
                    if(!is_dir(public_path('customers/' . $this->customer . '/wx/detail'))) {
                        mkdir(public_path('customers/' . $this->customer . '/wx/detail'));
                    }
                }
                //页面路径
                $wx_path = public_path('customers/' . $this->customer . '/wx/detail/' . $article['id'] . '.html');
                //小程序替换正则(链接，quickbar引入)
                $wx_pattern = ["/(src|href)(=[\"'])(\/)(images|js|css|category|detail|quickbar|themes)/", "/quickbar(\.js\?\d+mobile)/"];
                //替换为
                $wx_replace = ['${1}${2}../${4}', 'quickbar-wpage${1}'];
                //匹配替换                        
                $wx_out = preg_replace($wx_pattern, $wx_replace, $output);
                //栏目页面
                file_put_contents($wx_path, $wx_out);
                $wx_paths[] = $wx_path;                
            }
            $nowpercent = $last_html_precent + $html_precent;
            if (floor($nowpercent) !== floor($last_html_precent)) {
                echo '<div class="prompt">' . floor($nowpercent) . '%</div><script type="text/javascript">refresh(' . floor($nowpercent) . ');</script>';
                ob_flush();
                flush();
                PushQueue::where('pushtime', '<', time() - 60)->delete();
                PushQueue::where('cus_id', $this->cus_id)->update(['pushtime' => time()]);
            }
            $last_html_precent += $html_precent;
        }
        $res['paths'] = $paths;
        $res['wx_paths'] = isset($wx_paths) ? $wx_paths : null;
        return $res;
    }

    /**
     * 根据栏目id获取其顶级id
     *
     * @param  int $id 栏目
     * @return int 顶级id
     */
    private function getPid($id)
    {
        $p_id = Classify::where('id', $id)->pluck('p_id');
        if ($p_id > 0) {
            $new_pid = $this->getPid($p_id);
            if ($new_pid > 0) {
                $p_id = $new_pid;
            }
        } else {
            $p_id = $id;
        }
        return $p_id;
    }

    /**
     * 根据从数据库查询的栏目数据数组,添加路径link和icon处理
     *
     * @param array $arr 栏目数据数组
     * @return array
     */
    private function toFooter($arr, $pid = 0)
    {
        $footer = array();
        $needarr = array();
        foreach ((array)$arr as $k => $v) {
            if ($v['p_id'] == $pid) {
                $needarr[] = $v;
            }
        }

        if (empty($needarr)) {
            return null;
        }
        foreach ((array)$needarr as $k => $v) {
            $v['image'] = $v['img'] ? ($this->source_dir . 'l/category/' . $v['img']) : '';
            $v['icon'] = '<i class="iconfont">' . $v['icon'] . '</i>';
            if ($v['type'] != 6) {
                $v['link'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $v['id'] : $this->domain . '/category/' . $v['id'] . '.html';
            } else {
                if ($v['open_page'] == 1) {
                    if (strpos($v['url'], 'http') === false) {
                        $v['link'] = 'http://' . $v['url'];
                    } else {
                        $v['link'] = $v['url'];
                    }
//                    $v['link'] = strpos($v['url'], 'http') === false ? ('http://' . $v['url']) : $v['url'];
                } else {
                    if (strpos($v['url'], 'http') === false) {
                        $v['link'] = 'http://' . $v['url'] . '"target="_blank';
                    } else {
                        $v['link'] = $v['url'] . '"target="_blank';
                    }
//                    $v['link'] = strpos($v['url'], 'http') === false ? ('http://' . $v['url'] . '"target="_blank') : $v['url'] . '"target="_blank';
                }
            }

            unset($v['img']);
            $footer[$k] = $v;
            $footer[$k]["childmenu"] = $this->toFooter($arr, $v['id']);
        }
        return $footer;
    }

    /**
     * 根据从数据库查询的栏目数据数组,转为树形结构的数组
     *
     * @param array $arr 栏目数据数组
     * @param int $pid 起始栏目id
     * @param bool $isNav 是否是顶级导航(顶级导航会显示下面的几篇文章)
     * @return array 树形结构的数组
     */
    private function toTree($arr, $pid = 0, $isNav = FALSE)
    {
        $tree = array();
        foreach ((array)$arr as $k => $v) {
            if ($v['p_id'] == $pid) {
                $v['image'] = $v['img'] ? ($this->source_dir . 'l/category/' . $v['img']) : '';
                $v['icon'] = '<i class="iconfont">' . $v['icon'] . '</i>';
                unset($v['img']);
                $tree[] = $v;
            }
        }
        if (empty($tree)) {
            return null;
        }
        foreach ((array)$tree as $k => $v) {
            $data = [];
            if ($v['type'] != 6) {
//                if (empty($v['view_name'])) {
                //category链接位置(pc/手机导航条)
                $tree[$k]['link'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $v['id'] : $this->domain . '/category/' . $v['id'] . '.html';
//                } else {
//                    $tree[$k]['link'] = $this->showtype == 'preview' ? $this->domain . '/category/v/' . $v['view_name'] : $this->domain . '/category/v/' . $v['view_name'] . '.html';
//                }
                if ($isNav == TRUE) {
                    $cids = explode(',', $this->getChirldenCid($v['id'], 1)); //取得所有栏目id
                    if ($this->type == 'mobile') {
                        $articles = Articles::whereIn('c_id', $cids)->where('mobile_show', '1')->where('cus_id', $this->cus_id)->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at')->take(20)->get();
                    } else {
                        $articles = Articles::whereIn('c_id', $cids)->where('pc_show', '1')->where('cus_id', $this->cus_id)->select('id', 'c_id', 'title', 'img', 'introduction', 'created_at')->take(20)->get();
                    }
                    if (!empty($articles)) {
                        $abc = [];
                        foreach ((array)$articles as $key => $d) {
                            $data[$key]['title'] = $d->title;
                            $classify = Classify::where('id', $d->c_id)->first();
//                            if (empty($classify->view_name)) {
//                                $data[$key]['category']['link'] = $this->showtype == 'preview' ? $this->domain . '/category/' . $d->c_id : $this->domain . '/category/' . $d->c_id . '.html';
//                            } else {
                            $data[$key]['category']['link'] = $this->showtype == 'preview' ? $this->domain . '/category/v/' . $classify->view_name : $this->domain . '/category/v/' . $classify->view_name . '.html';
//                            }
                            $data[$key]['image'] = $d->img ? ($this->source_dir . 's/articles/' . $d->img) : '';
                            $data[$key]['link'] = $this->showtype == 'preview' ? $this->domain . '/detail/' . $d->id : $this->domain . '/detail/' . $d->id . '.html';
                            $data[$key]['category']['name'] = $classify->name;
                            $data[$key]['category']['en_name'] = $classify->en_name;
                            $data[$key]['category']['icon'] = '<i class="iconfont">' . $classify->icon . '</i>';
                            $data[$key]['description'] = $d->introduction;
                            $data[$key]['pubdate'] = (string)$d->created_at;
                            $data[$key]['pubtimestamp'] = strtotime($d->created_at);
                            unset($v['value']);
                        }
                        $tree[$k]['data'] = $data;
                    } else {
                        $tree[$k]['data'] = [];
                    }
                }
            } else {
                if ($v['open_page'] == 1) {
                    $tree[$k]['link'] = strpos($v['url'], 'http') === false ? ('http://' . $v['url']) : $v['url'];
                } else {
                    $tree[$k]['link'] = strpos($v['url'], 'http') === false ? ('http://' . $v['url'] . '"target="_blank') : $v['url'] . '"target="_blank';
                }
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
    public function getChirldenCid($cid = 0, $show = null)
    {
        $result = $cid;
        if ($show == null) {
            $cids = Classify::where('p_id', $cid)->OrderBy('sort', 'asc')->lists('id');
        } else {
            $cids = Classify::where('p_id', $cid)->where($this->type . '_show', $show)->OrderBy('sort', 'asc')->lists('id');
        }
        if (!empty($cids)) {
            foreach ((array)$cids as $v) {
                $result .= ',' . $this->getChirldenCid($v, $show);
            }
        }
        return $result;
    }

    private function currentCidArray($id, &$arr = array())
    {
        $arr[] = $id;
        $p_id = Classify::where('id', $id)->pluck('p_id');
        if ($p_id > 0) {
            $new_pid = $this->currentCidArray($p_id, $arr);
        }
        return $arr;
    }

    private function addCurrent(&$c_list, $current_ids)
    {
        if (count($c_list)) {
            foreach ($c_list as &$c_arr) {
                if ($c_arr['childmenu']) {
                    $this->addCurrent($c_arr['childmenu'], $current_ids);
                }
                if (in_array($c_arr['id'], $current_ids)) {
                    $c_arr['current'] = 1;
                } else {
                    $c_arr['current'] = 0;
                }
                if ($c_arr['id'] == $current_ids[0]) {
                    $c_arr['selected'] = 1;
                } else {
                    $c_arr['selected'] = 0;
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
    public function searchPreview()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $result = $this->pagePublic();
//        $result['navs'] = $publicdata['navs'];
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        $c_id = Classify::where('type', 4)->where($this->type . '_show', 1)->where('cus_id', $this->cus_id)->pluck('id');
        if ($c_id) {
            $current_arr = $this->currentCidArray($c_id);
            $result['navs'] = $this->addCurrent($result['navs'], $current_arr);
        }
        if (is_array($result['navs'])) {
            foreach ($result['navs'] as $nav) {
                if ($nav['current'] == 1) {
                    $pagenavs = $nav['childmenu'];
                    break;
                } else {
                    $pagenavs = [];
                }
            }
        }
//        $result['pagenavs'] = $pagenavs;
        $result['posnavs'] = $this->getPosNavs($c_id); // $this->getPosNavs($c_id);
        $result['posnavs'] = array(0 => array('en_name' => 'Search Result', 'name' => '搜索结果', 'link' => 'javascript:;', 'icon' => ''));
//        $result['posnavs'] = array(0 => array('en_name' => 'Search+', 'name' => '搜索+', 'link' => 'javascript:;', 'icon' => ''));
        //搜索数据替换
        if (!is_file(app_path('views/templates/' . $this->themename . '/searchresult_do.html'))) {
            //搜索数据标记与替换
            if (is_file(app_path('views/templates/' . $this->themename . '/searchresult.html'))) {
                $file_content = file_get_contents(app_path('views/templates/' . $this->themename . '/searchresult.html'));
            } else {
                $file_content = file_get_contents(public_path("packages/searchresult.html"));
            }
            //匹配搜索循环
            preg_match('/(\{foreach[^\}]*from[\s]*=[\s]*\$search\.data[^\}]*\})([\s\S]*?)\{\/foreach\}/', $file_content, $search_foreach);
            $search_content = str_replace($search_foreach[2], '<!--search_content_start-->' . $search_foreach[2] . '<!--search_content_end-->', $file_content);
            //匹配foreach中的item值
            preg_match('/item[\s]*=[\s]*([\S]*)/', $search_foreach[1], $search_view);
            $search_view = $search_view[1];
            //匹配所有查询中循环的值
            preg_match_all('/{[\s]*\$' . $search_view . '[.|\[]([a-z]*)[\]]*}/', $search_foreach[2], $date_replace);
            $search_view = array('title' => '$title', 'image' => '$image', 'link' => '$link', 'description' => '$description', 'pubdate' => '$pubdate', 'pubtimestamp' => '$pubtimestamp', 'pubymd' => '$pubymd');
            foreach ((array)$date_replace[0] as $k => $v) {
                $search_content = str_replace($v, 'search_' . $search_view[$date_replace[1][$k]], $search_content);
            }
            //分页匹配
            $page_content = $search_content;
            $page_link_array = array('100-8_search' => '100-9_search');
            preg_match('/(\{foreach[^\}]*from[\s]*=[\s]*\$page_links\.nears_link[^\}]*\})[\s\S]*?(\{if[^\}]*==[\s]*\$page_links\.current_page[^\}]*\})([\s\S]*?)\{else\}([\s\S]*?)\{\/if\}[\s\S]*?\{\/foreach\}/', $page_content, $page_foreach);
            if ($page_foreach) {
                $page_content = str_replace($page_foreach[3], '<!--page_corrent_link_start-->' . $page_foreach[3] . '<!--page_corrent_link_end-->', $page_content);
                $page_content = str_replace($page_foreach[4], '<!--page_uncorrent_link_start-->' . $page_foreach[4] . '<!--page_uncorrent_link_end-->', $page_content);
                $page_link_array = array('100-8_search' => '100-9_search', '100-1_search' => 'javascript::');
            } else {
                preg_match('/(\{foreach[^\}]*from[\s]*=[\s]*\$page_links\.nears_link[^\}]*\})([\s\S]*?)\{\/foreach\}/', $page_content, $page_foreach);
                if ($page_foreach) {
                    $page_content = str_replace($page_foreach[2], '<!--page_uncorrent_link_start-->' . $page_foreach[2] . '<!--page_uncorrent_link_end-->', $page_content);
                }
            }
            //分页结束
            file_put_contents(app_path('views/templates/' . $this->themename . '/searchresult_do.html'), $page_content);
        }
        $result['search'] = array('total' => '-1000_search', 'keyword' => 'search_$keyword', 'data' => array(0 => array('link' => '', 'title' => '', 'pubdate' => '', 'description' => '')));
        $result['page_links'] = array('current_page' => '100-1_search', 'per_page' => '100-2_search', 'page_count' => '100-3_search', 'first_link' => '100-4_search', 'prev_link' => '100-5_search', 'next_link' => '100-6_search', 'last_link' => '100-7_search', 'nears_link' => $page_link_array);
        //替换结束

        if ($this->type != 'mobile') {
            if (is_file(app_path('views/templates/' . $this->themename . '/searchresult.html'))) {
                $json_keys = $this->getJsonKey('searchresult.html');
                if (count($json_keys)) {
                    foreach ($json_keys as $key) {
                        $result[$key] = $this->detailList($this->pagedata($key));
                    }
                }
            }
        }
        if ($this->type == 'pc') {
            $page_count = $customer_info->pc_page_count;
            $page_link_count = $customer_info->pc_page_links;
        } else {
            $page_count = $customer_info->mobile_page_count;
            $page_link_count = $customer_info->mobile_page_links;
        }

        //文章数据json保存
        $article_data = Articles::where('cus_id', $this->cus_id)->where($this->type . '_show', '1')->orderBy('is_top', 'desc')->orderBy('created_at', 'desc')->orderBy('id','DESC')->select('id', 'title', 'img', 'introduction', 'created_at', 'use_url', 'url')->get()->toArray();
        $article = array();
        foreach ((array)$article_data as $article_img) {
            $article[$article_img['id']]['id'] = $article_img['id'];
            $article[$article_img['id']]['title'] = $article_img['title'];
            $article[$article_img['id']]['img'] = $article_img['img'] ? ($this->source_dir . 'l/articles/' . $article_img['img']) : '';
            $article[$article_img['id']]['introduction'] = $article_img['introduction'];
            $article[$article_img['id']]['created_at'] = strtotime($article_img['created_at']);
            $article[$article_img['id']]['link'] = $this->domain . '/detail/' . $article_img['id'] . '.html';
            if ($article_img['use_url']) {
                $article[$article_img['id']]['link'] = $article_img['url'];
            }
        }
        $article['count'] = $page_count;
        $article['page_link'] = $page_link_count;
        $article_json = json_encode($article);
        if ($this->type == 'pc') {
            file_put_contents(public_path('customers/' . $this->customer . '/article_data.json'), $article_json);
        } else {
            file_put_contents(public_path('customers/' . $this->customer . '/mobile/article_data.json'), $article_json);
        }

        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        $smarty->display('searchresult_do.html');
    }

    /**
     * pushtest搜索页面数据
     *
     * @param type $publicdata
     * @return type
     */
    public function searchPush($publicdata)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $result = $publicdata['result'];
        $result['navs'] = $publicdata['navs'];
        $customer_info = CustomerInfo::where('cus_id', $this->cus_id)->first();
        $result['title'] = $customer_info->title;
        $result['keywords'] = $customer_info->keywords;
        $result['description'] = $customer_info->description;
        $c_id = Classify::where('type', 4)->where($this->type . '_show', 1)->where('cus_id', $this->cus_id)->pluck('id');
        if ($c_id) {
            $current_arr = $this->currentCidArray($c_id);
            $result['navs'] = $this->addCurrent($result['navs'], $current_arr);
        }
        if (is_array($result['navs'])) {
            foreach ($result['navs'] as $nav) {
                if ($nav['current'] == 1) {
                    $pagenavs = $nav['childmenu'];
                    break;
                } else {
                    $pagenavs = [];
                }
            }
        }
//        $result['pagenavs'] = $pagenavs;
//        $result['posnavs'] = $this->getPosNavs($c_id); // $this->getPosNavs($c_id);
        $result['posnavs'] = array(0 => array('en_name' => 'Search Result', 'name' => '搜索结果', 'link' => 'javascript:;', 'icon' => ''));
        //搜索数据替换
        if (!is_file(app_path('views/templates/' . $this->themename . '/searchresult_do.html'))) {
            //搜索数据标记与替换
            if (is_file(app_path('views/templates/' . $this->themename . '/searchresult.html'))) {
                $file_content = file_get_contents(app_path('views/templates/' . $this->themename . '/searchresult.html'));
            } else {
                $file_content = file_get_contents(public_path("packages/searchresult.html"));
            }
            //匹配搜索循环
            preg_match('/(\{foreach[^\}]*from[\s]*=[\s]*\$search\.data[^\}]*\})([\s\S]*?)\{\/foreach\}/', $file_content, $search_foreach);
            $search_content = str_replace($search_foreach[2], '<!--search_content_start-->' . $search_foreach[2] . '<!--search_content_end-->', $file_content);
            //匹配foreach中的item值
            preg_match('/item[\s]*=[\s]*([\S]*)/', $search_foreach[1], $search_view);
            $search_view = $search_view[1];
            //匹配所有查询中循环的值
            preg_match_all('/{[\s]*\$' . $search_view . '[.|\[]([a-z]*)[\]]*}/', $search_foreach[2], $date_replace);
            $search_view = array('title' => '$title', 'image' => '$image', 'link' => '$link', 'description' => '$description', 'pubdate' => '$pubdate', 'pubtimestamp' => '$pubtimestamp', 'pubymd' => '$pubymd');
            foreach ((array)$date_replace[0] as $k => $v) {
                $search_content = str_replace($v, 'search_' . $search_view[$date_replace[1][$k]], $search_content);
            }
            //分页匹配
            $page_content = $search_content;
            $page_link_array = array('100-8_search' => '100-9_search');
            preg_match('/(\{foreach[^\}]*from[\s]*=[\s]*\$page_links\.nears_link[^\}]*\})[\s\S]*?(\{if[^\}]*==[\s]*\$page_links\.current_page[^\}]*\})([\s\S]*?)\{else\}([\s\S]*?)\{\/if\}[\s\S]*?\{\/foreach\}/', $page_content, $page_foreach);
            if ($page_foreach) {
                $page_content = str_replace($page_foreach[3], '<!--page_corrent_link_start-->' . $page_foreach[3] . '<!--page_corrent_link_end-->', $page_content);
                $page_content = str_replace($page_foreach[4], '<!--page_uncorrent_link_start-->' . $page_foreach[4] . '<!--page_uncorrent_link_end-->', $page_content);
                $page_link_array = array('100-8_search' => '100-9_search', '100-1_search' => 'javascript::');
            } else {
                preg_match('/(\{foreach[^\}]*from[\s]*=[\s]*\$page_links\.nears_link[^\}]*\})([\s\S]*?)\{\/foreach\}/', $page_content, $page_foreach);
                if ($page_foreach) {
                    $page_content = str_replace($page_foreach[2], '<!--page_uncorrent_link_start-->' . $page_foreach[2] . '<!--page_uncorrent_link_end-->', $page_content);
                }
            }
            //分页结束
            file_put_contents(app_path('views/templates/' . $this->themename . '/searchresult_do.html'), $page_content);
            $publicdata['repleace']['searchresult_do.html'] = $page_content;
        }
        $result['search'] = array('total' => '-1000_search', 'keyword' => 'search_$keyword', 'data' => array(0 => array('link' => '', 'title' => '', 'pubdate' => '', 'description' => '')));
        $result['page_links'] = array('current_page' => '100-1_search', 'per_page' => '100-2_search', 'page_count' => '100-3_search', 'first_link' => '100-4_search', 'prev_link' => '100-5_search', 'next_link' => '100-6_search', 'last_link' => '100-7_search', 'nears_link' => $page_link_array);
        //替换结束

        if ($this->type != 'mobile') {
            if (is_file(app_path('views/templates/' . $this->themename . '/searchresult.html'))) {
                $json_keys = $this->getJsonKey('searchresult.html');
                if (count($json_keys)) {
                    foreach ($json_keys as $key) {
                        $result[$key] = $this->detailList($this->pagedata($key));
                    }
                }
            }
        }
        if ($this->type == 'pc') {
            $page_count = $customer_info->pc_page_count;
            $page_link_count = $customer_info->pc_page_links;
        } else {
            $page_count = $customer_info->mobile_page_count;
            $page_link_count = $customer_info->mobile_page_links;
        }

        //文章数据json保存
        $article_data = Articles::where('cus_id', $this->cus_id)->where($this->type . '_show', '1')->orderBy('is_top', 'desc')->orderBy('created_at', 'desc')->orderBy('id','DESC')->select('id', 'title', 'img', 'introduction', 'created_at', 'use_url', 'url')->get()->toArray();
        $article = array();
        foreach ((array)$article_data as $article_img) {
            $article[$article_img['id']]['id'] = $article_img['id'];
            $article[$article_img['id']]['title'] = $article_img['title'];
            $article[$article_img['id']]['img'] = $article_img['img'] ? ($this->source_dir . 'l/articles/' . $article_img['img']) : '';
            $article[$article_img['id']]['introduction'] = $article_img['introduction'];
            $article[$article_img['id']]['created_at'] = strtotime($article_img['created_at']);
            $article[$article_img['id']]['link'] = $this->domain . '/detail/' . $article_img['id'] . '.html';
            if ($article_img['use_url']) {
                $article[$article_img['id']]['link'] = $article_img['url'];
            }
        }
        $article['count'] = $page_count;
        $article['page_link'] = $page_link_count;
        $article_json = json_encode($article);
        if ($this->type == 'pc') {
            file_put_contents(public_path('customers/' . $this->customer . '/article_data.json'), $article_json);
        } else {
            file_put_contents(public_path('customers/' . $this->customer . '/mobile/article_data.json'), $article_json);
            //如果开启小程序
            if($this->is_applets) {
                //json的替换正则
                $wx_pattern = "/\\\\\/(images|js|css|category|detail|themes)\\\/";
                //替换json中的链接
                $wx_json = preg_replace($wx_pattern, '.\/${1}\\', $article_json);
                //检测小程序目录是否存在
                if(!is_dir(public_path('customers/' . $this->customer . '/wx'))) {
                    mkdir(public_path('customers/' . $this->customer . '/wx'));
                }
                //生成微信搜索的json文件
                file_put_contents(public_path('customers/' . $this->customer . '/wx/article_data.json'), $wx_json);
            }
        }

        $content = $publicdata['repleace']['searchresult_do.html'];
        $content = preg_replace($publicdata['pattern'], $publicdata['repleace'], $content);
        $smarty = new Smarty;
        $smarty->setCompileDir(app_path('storage/views/compile'));
        $smarty->setTemplateDir(app_path('views/templates/' . $this->themename));
        $smarty->registerPlugin('function', 'mapExt', array('PrintController', 'createMap'));
        $smarty->registerPlugin('function', 'shareExt', array('PrintController', 'createShare'));
        $smarty->assign($result);
        ob_start();
        $smarty->display('string:' . $content);
        $output = ob_get_contents();
        ob_end_clean();
        if (!count($result['footer_navs'])) {
            $output = preg_replace('/<a href="' . str_replace("/", "\/", $result['site_url']) . '"( target="_blank")?( )?>首页<\/a>( )?\|([\s]+)?(<br \/>)?(<br>)?/is', "", $output);
        }
        return $output;
    }

    /**
     *
     * @param type $old_arr
     * @param type $new_arr
     * @return array
     */
    public function array_merge_recursive_new($old_arr, $new_arr)
    {
        foreach ((array)$old_arr as $key => $val) {
            if (array_key_exists($key, $new_arr)) {
                if ($old_arr[$key]['type'] == 'list' || $old_arr[$key]['type'] == 'nav') {
                    $old_arr[$key]['config']['star_only'] = isset($new_arr[$key]['value']['star_only']) ? $new_arr[$key]['value']['star_only'] : '';
                    $old_arr[$key]['config']['id'] = isset($new_arr[$key]['value']['id']) ? $new_arr[$key]['value']['id'] : '';
                } elseif ($old_arr[$key]['type'] == 'page') {
                    // $old_arr[$key]['config'] = $new_arr[$key]['value'];
                    $old_arr[$key]['config']['star_only'] = isset($new_arr[$key]['value']['star_only']) ? $new_arr[$key]['value']['star_only'] : '';
                    $old_arr[$key]['config']['id'] = isset($new_arr[$key]['value']['id']) ? $new_arr[$key]['value']['id'] : '';
                } elseif ($old_arr[$key]['type'] == 'navs') {
                    $old_arr[$key]['config']['ids'] = $new_arr[$key]['value']['ids'];
                } else {
                    $old_arr[$key]['value'] = $new_arr[$key]['value'];
                }
            } else {
                if ($old_arr[$key]['type'] == 'list' || $old_arr[$key]['type'] == 'nav') {
                    $old_arr[$key]['config']['star_only'] = 0;
                    $old_arr[$key]['config']['id'] = 0;
                } elseif ($old_arr[$key]['type'] == 'page' || $old_arr[$key]['type'] == 'navs') {
                    $old_arr[$key]['config'] = array();
                } else {
                    // if (is_array($old_arr[$key]['value']) && count($old_arr[$key]['value']) > 0) {
                    //     foreach ($old_arr[$key]['value'] as &$v) {
                    //         $v = '';
                    //     }
                    // }
                }
            }
        }
        return $old_arr;
    }

    public function mobilePageList($page, $is_index = false)
    {
        $json_content = @file_get_contents(public_path("/templates/$this->themename/json/$page.json"));
        if ($json_content) {
            $json_content = json_decode($json_content, true);
            if ($is_index) {
                return $json_content;
            } else {
                $data = $json_content[$page];
            }
        } else {
            return null;
        }
        return $data;
    }

    private function getChildrenClassify($p_id, $show = null)
    {
        $id_str = $p_id;
        if ($show == null) {
            $ids = DB::table('classify')->where('p_id', '=', $p_id)->OrderBy('sort', 'asc')->lists('id');
        } else {
            $ids = DB::table('classify')->where('p_id', '=', $p_id)->where($this->type . '_show', $show)->OrderBy('sort', 'asc')->lists('id');
        }
        if (count($ids) > 0) {
            foreach ($ids as &$id) {
                $id_str .= ',' . $this->getChildrenClassify($id, $show);
            }
        }
        return $id_str;
    }

    /**
     * ===获取当前栏目===
     * 若c_id空，获取默认栏目导航
     * @param type $c_id 当前栏目id
     * @param type $posnavs 当前栏目导航内容
     * @return type
     */
    private function getPosNavs($c_id = 0, &$posnavs = array())
    {
//    private function getPosNavs($c_id, &$posnavs = array()) {
        if (!$c_id) {
            $webinfo = WebsiteConfig::where("cus_id", $this->cus_id)->where("key", "_pagenavs_sub3")->pluck("value");
            if (!$webinfo) {
                $webinfo = WebsiteConfig::where("cus_id", $this->cus_id)->where("key", "_aside")->pluck("value");
            }
            $webinfo = unserialize($webinfo);
            $c_id = $webinfo["pagenavs"]["value"]["id"];
        }
        $classify = Classify::where('id', $c_id)->first();
        $arr['name'] = $classify->name;
        $arr['en_name'] = $classify->en_name;
        if ($classify->type == 6) {
            if ($classify->open_page == 2) {
                $arr['link'] = $classify->url . '" target="_blank';
            } else {
                $arr['link'] = $classify->url;
            }
        } else {
            if ($this->showtype == 'preview') {
                //category链接位置(pc列表页面包屑)
                $arr['link'] = $this->domain . '/category/' . $c_id;
            } else {
                $arr['link'] = $this->domain . '/category/' . $c_id . '.html';
            }
        }
        $arr['icon'] = '<i class="iconfont">' . $classify->icon . '</i>';
        array_unshift($posnavs, $arr);
        if ($classify->p_id > 0) {
            $this->getPosNavs($classify->p_id, $posnavs);
        }
        return $posnavs;
    }

    /*
     * 推送模板信息 数据信息等
     */

    public function sendCusAllData($url = '')
    {
        $id = $this->cus_id;
        $type = $this->type;
        $string = $this->searchPreview();
        if ($string) {
            $file_arr = $this->getFile(app_path('views/templates/' . $this->themename));
            $file_name = "";
            $file_content = "";
            foreach ($file_arr as &$file) {
                if (preg_match('/^_.*/i', $file) || $file == 'searchresult.html') {
                    $file_name .= $file . '@';
                    $file_content .= file_get_contents(app_path('views/templates/' . $this->themename . '/' . $file)) . '@#@';
                }
            }
            $file_name = rtrim($file_name, '@');
            $file_content = rtrim($file_content, '@#@');
            $postFun = new CommonController;
            $postFun->postsend($url, array("id" => $id, 'type' => $type, 'string' => $string, 'file_name' => $file_name, 'file_content' => $file_content));
        }
    }

    //获取文件列表
    public function getFile($dir)
    {
        $fileArray[] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".." && strpos($file, ".")) {
                    $fileArray[$i] = $file;
                    if ($i == 100) {
                        break;
                    }
                    $i++;
                }
            }
            //关闭句柄
            closedir($handle);
        }
        return $fileArray;
    }

    /**
     * ===更换样式===
     */
    public function changeCss()
    {
        $colorArr = websiteInfo::where('cus_id', $this->cus_id)->select('pc_color_id', 'mobile_color_id')->first();
        $color['pc'] = DB::table('color')->where('id', $colorArr->pc_color_id)->pluck('color_en');
        $color['mobile'] = DB::table('color')->where('id', $colorArr->mobile_color_id)->pluck('color_en');
        return $color;
    }

}
