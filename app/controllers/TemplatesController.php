<?php

/**
 * |--------------------------------------------------------------------------
 * | 模版首页控制器
 * |--------------------------------------------------------------------------
 * |方法：
 * |homepageInfo       首页详情
 * |homepageModify     首页修改
 * |homepagePreview    首页预览
 * |categoryPreview    列表页预览
 * |articlePreview     内容页预览
 * |homepageRewrite    首页配置重写
 */
class TemplatesController extends BaseController
{

    /**
     * 首页详情
     *
     * @param string $page index首页/_aside其他/global全局/form表单
     * @return array
     */
    public function homepageInfo($page = 'index')
    {
        $pagedata = new PrintController;
        $data = $pagedata->pagedata($page);
        $data_final = $data;
        $classify = new Classify;
        foreach ($data as $k => $v) {
            // ===不对form表单做操作===
            if ($v['type'] == 'list') {
                $type_arr = array(1, 2, 3);
                $list = Classify::where('cus_id', '=', $pagedata->cus_id)->whereIn('type', array(1, 2, 3, 4, 5, 6, 9))->where('pc_show', '=', 1)->get()->toArray();
                $value = array();
                $selected = false;
                foreach ($list as $key => $l) {
                    $value[$key]['id'] = $l['id'];
                    $value[$key]['name'] = $l['name'];
                    $value[$key]['p_id'] = $l['p_id'];
                    $value[$key]['type'] = $l['type'];
                    if (isset($v['config']['id']) && $l['id'] == $v['config']['id']) {
                        $value[$key]['selected'] = 1;
                        $selected = true;
                    } else {
                        $value[$key]['selected'] = 0;
                    }
                }
                if (isset($v['config']['filter'])) {
                    if ($v['config']['filter'] == 'page') {
                        $value = $classify->toTree($value);
                        $type_arr = array(4);
                    } elseif ($v['config']['filter'] == 'list') {
                        $value = $classify->toTree($value);
                        $type_arr = array(1, 2, 3);
                    } elseif ($v['config']['filter'] == 'feedback') {/* 20151021添加feeback filter */
                        $value = $classify->toTree($value);
                        $type_arr = array(5, 9);
                    } elseif ($v['config']['filter'] == 'ALL') {
                        $value = $classify->toTree($value);
                        $type_arr = array(1, 2, 3, 4, 5, 6);
                    } else {
                        $value = $classify->toTree($value);
                        $type_arr = array(1, 2, 3, 4, 9);
                    }
                }
                $this->unsetFalseClassify($value, $type_arr);
                if (!$selected && count($value)) {
                    foreach ($value as $key => $v) {
                        $value[$key]['selected'] = 1;
                        break;
                    }
                }
                if (isset($v['config']['mustchild']) && $v['config']['mustchild'] == true) {
                    $this->unsetLastClassify($value);
                }
                $data_final[$k]['config']['list'] = $value;
                if (isset($v['config']['star_only'])) {
                    $data_final[$k]['config']['star_only'] = $v['config']['star_only'];
                } else {
                    $data_final[$k]['config']['star_only'] = "0";
                }
            } elseif ($v['type'] == 'page') {
                $list = Classify::where('cus_id', '=', $pagedata->cus_id)->where('type', 4)->where('pc_show', '=', 1)->get()->toArray();
                $value = array();
                $selected = false;
                foreach ($list as $key => $l) {
                    $value[$key]['id'] = $l['id'];
                    $value[$key]['name'] = $l['name'];
                    $value[$key]['p_id'] = $l['p_id'];
                    $value[$key]['type'] = $l['type'];
                    if (isset($v['config']['id']) && $l['id'] == $v['config']['id']) {
                        $value[$key]['selected'] = "1";
                        $selected = true;
                    } else {
                        $value[$key]['selected'] = "0";
                    }
                }
                if (!$selected) {
                    $value[0]['selected'] = 1;
                }
                if (isset($v['config']['mustchild']) && $v['config']['mustchild'] == true) {
                    $this->unsetLastClassify($value);
                }
                $data_final[$k]['config']['list'] = $value;
                if (isset($v['config']['star_only'])) {
                    $data_final[$k]['config']['star_only'] = $v['config']['star_only'];
                } else {
                    $data_final[$k]['config']['star_only'] = "0";
                }
            } elseif ($v['type'] == 'navs') {
                $type_arr = array(1, 2, 3, 4, 6, 9);
                $list = Classify::where('cus_id', '=', $pagedata->cus_id)->whereIn('type', array(1, 2, 3, 4, 6, 9))->where('pc_show', '=', 1)->get()->toArray();
                $value = array();
                $selected = false;
                foreach ($list as $key => $l) {
                    $value[$key]['id'] = $l['id'];
                    $value[$key]['name'] = $l['name'];
                    $value[$key]['p_id'] = $l['p_id'];
                    $value[$key]['type'] = $l['type'];
                    if (isset($v['config']['id']) && $l['id'] == $v['config']['id']) {
                        $value[$key]['selected'] = 1;
                        $selected = true;
                    } else {
                        $value[$key]['selected'] = 0;
                    }
                }
                if (isset($v['config']['filter'])) {
                    if ($v['config']['filter'] == 'page') {
                        $value = $classify->toTree($value);
                        $type_arr = array(4);
                    } elseif ($v['config']['filter'] == 'list') {
                        $value = $classify->toTree($value);
                        $type_arr = array(1, 2, 3);
                    } elseif ($v['config']['filter'] == 'ALL') {
                        $value = $classify->toTree($value);
                        $type_arr = array(1, 2, 3, 4, 6);
                    } else {
                        $value = $classify->toTree($value);
                        $type_arr = array(1, 2, 3, 4);
                    }
                }
                $this->unsetFalseClassify($value, $type_arr);
                if (!$selected && count($value)) {
                    foreach ($value as $key => $v) {
                        $value[$key]['selected'] = 1;
                        break;
                    }
                }
                if (isset($v['config']['mustchild']) && $v['config']['mustchild'] == true) {
                    $this->unsetLastClassify($value);
                }
                $data_final[$k]['config']['list'] = $value;
                if (isset($v['config']['star_only'])) {
                    $data_final[$k]['config']['star_only'] = $v['config']['star_only'];
                } else {
                    $data_final[$k]['config']['star_only'] = "0";
                }
            }
        }
        return $data_final;
    }

    /*
     * 分类选择时剔除没用的分类
     *  
     */

    public function unsetLastClassify(&$arr)
    {
        if (!is_array($arr)) {
            return $arr;
        } else {
            foreach ($arr as $key => $val) {
                if (is_array($val) && isset($val['childmenu']) && is_array($val['childmenu']) && count($val['childmenu'])) {
                    $this->unsetLastClassify($arr[$key]['childmenu']);
                } else {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    /*
     * 分类选择时剔除没用的分类
     *  
     */

    public function unsetFalseClassify(&$arr, $type)
    {
        if (!is_array($arr)) {
            return $arr;
        } else {
            foreach ($arr as $key => $val) {
                if (is_array($arr[$key]) && isset($arr[$key]['childmenu']) && is_array($arr[$key]['childmenu']) && count($arr[$key]['childmenu'])) {
                    if (!count($this->unsetFalseClassify($arr[$key]['childmenu'], $type))) {
                        if (!in_array($arr[$key]['type'], $type)) {
                            unset($arr[$key]);
                        }
                    }
                } else {
                    if (!in_array($arr[$key]['type'], $type)) {
                        unset($arr[$key]);
                    }
                }
            }
        }
        return $arr;
    }

    public function homepageManage()
    {
        $page = Input::get('page') ? Input::get('page') : 'index';
        $pagelist = $this->buttonList();
        $templedata = $this->homepageInfo($page);
        $cus_id = Auth::id();
        //$template_user = websiteInfo::leftJoin('template','template.id','=','website_info.pc_tpl_id')->where('website_info.cus_id',$cus_id)->pluck('template.cus_id');
        $pc_tpl_id = WebsiteInfo::where('cus_id', $cus_id)->pluck('pc_tpl_id');
        $my_tpl_name = Template::where('former_id', $pc_tpl_id)->where('cus_id', $cus_id)->pluck('name');
        $is_my_tpl = Template::where('id', $pc_tpl_id)->where('cus_id', $cus_id)->pluck('name');
        $my_tpl_num = count(Template::where('cus_id', $cus_id)->lists('id'));
        //===模板调用===
        if(empty($my_tpl_name)){
            $my_tpl_name = Template::where('former_id', $pc_tpl_id)->where('cus_id', $cus_id)->pluck('name_bak');
        }
        if(empty($is_my_tpl)){
            $is_my_tpl = Template::where('id', $pc_tpl_id)->where('cus_id', $cus_id)->pluck('name_bak');
        }
        //===模板调用===
        if ($my_tpl_name || $is_my_tpl || $my_tpl_num >= 3) {
            $coded = 1;
        } else {
            $coded = 0;
        }
        $data_final = ['err' => 0, 'msg' => '', 'data' => ['pagelist' => $pagelist, 'templedata' => $templedata, 'coded' => $coded]];
        return Response::json($data_final);
    }

    /**
     * ===首页列表===
     * @return type
     */
    public function homepageList()
    {
        $page = Input::get('page') ? Input::get('page') : 'index'; //===index首页/_aside其他/global全局===
        $templedata = $this->homepageInfo($page);
        $data_final = ['err' => 0, 'msg' => '', 'data' => $templedata];
        return Response::json($data_final);
    }

    public function buttonList()
    {
        $result = [];
        $cus_id = Auth::id();
        $themename = DB::table('template')->leftJoin('website_info', 'website_info.pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $cus_id)->pluck('template.name');
        $directory = public_path('templates/' . $themename . '/json');
        if(!is_dir($directory)){
            $themename = DB::table('template')->leftJoin('website_info', 'website_info.pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', '=', $cus_id)->pluck('template.name_bak');
            $directory = public_path('templates/' . $themename . '/json');
        }
        $files = scandir($directory);
        if (array_search('!database.json', $files) !== false) {
            unset($files[array_search('!database.json', $files)]);
        }
        $files = array_slice($files, 2);
        unset($files[array_search('index.json', $files)]);
        array_unshift($files, 'index.json');
        foreach ($files as $k => $f) {
            $file_type = explode('.', $f);
            $result[$k]['page'] = $file_type[0];
            if (strpos($file_type[0], 'pagenavs') !== false) {
                $title = "侧边导航";
            } elseif (strpos($file_type[0], 'left') !== false) {
                $title = "左侧栏";
            } elseif (strpos($file_type[0], 'right') !== false) {
                $title = "右侧栏";
            } elseif (strpos($file_type[0], 'top') !== false || strpos($file_type[0], 'header') !== false) {
                $title = "顶部";
            } elseif (strpos($file_type[0], 'bottom') !== false || strpos($file_type[0], 'footer') !== false) {
                $title = "底部";
            } elseif (strpos($file_type[0], 'links') !== false) {
                $title = "友情链接";
            } else {
                $title = "其他";
            }
            $result[$k]['title'] = Config::get('file.' . $file_type[0], $title);
            if ($file_type[0] == 'index') {
                $result[$k]['url'] = asset('homepage-preview');
            } elseif ($file_type[0] == 'list-text') {
                $id = Classify::where('cus_id', $cus_id)->where('type', 1)->pluck('id');
                $result[$k]['url'] = asset('category/' . $id . '.html');
            } elseif ($file_type[0] == 'list-image') {
                $id = Classify::where('cus_id', $cus_id)->where('type', 2)->pluck('id');
                $result[$k]['url'] = asset('category/' . $id . '.html');
            } elseif ($file_type[0] == 'list-imagetext') {
                $id = Classify::where('cus_id', $cus_id)->where('type', 3)->pluck('id');
                $result[$k]['url'] = asset('category/' . $id . '.html');
            } else {
                $result[$k]['url'] = '';
            }
        }
        return $result;
    }

    public function getimage($arr, $type = 'image')
    {
        $org_img = array();
        foreach ((array)$arr as $k => $v) {
            if ($k === $type) {
                $org_img[] = $v;
            }
            if (is_array($v)) {
                $child_imgs = $this->getimage($v);
                $org_img = array_merge((array)$org_img, (array)$child_imgs);
            }
        }
        return $org_img;
    }

    public function homepageRewrite()
    {
        $cus_id = Auth::id();
        $website_config = new WebsiteConfig();
        websiteInfo::where('cus_id', $cus_id)->update(['pushed' => 3]);
        $webinfo = WebsiteInfo::where('cus_id', $cus_id)->first();
        $pc_tpl_id = $webinfo->pc_tpl_id;
        $mobile_tpl_id = $webinfo->mobile_tpl_id;
        $websiteconfigs = $website_config->where('cus_id', $cus_id)->where('template_id', $pc_tpl_id)->get()->toArray();
        foreach ($websiteconfigs as $val) {
            $org_imgs = $this->getimage(unserialize($val['value']));
            foreach ((array)$org_imgs as $v) {
                $imgdel = new ImgDel();
                $imgdel->mysave($v, 'page_index');
            }
        }
        $websiteconfigs = $website_config->where('cus_id', $cus_id)->where('template_id', $mobile_tpl_id)->get()->toArray();
        foreach ($websiteconfigs as $val) {
            $org_imgs = $this->getimage(unserialize($val['value']));
            foreach ((array)$org_imgs as $v) {
                $imgdel = new ImgDel();
                $imgdel->mysave($v, 'page_index');
            }
        }
        WebsiteConfig::where('cus_id', $cus_id)->where('template_id', $pc_tpl_id)->delete();
        WebsiteConfig::where('cus_id', $cus_id)->where('template_id', $mobile_tpl_id)->delete();
        echo '<meta http-equiv="Content-Type" content="text/html;charset=utf-8">';
        echo '清除首页配置';
        exit();
    }

    /**
     * 页面编辑修改数据
     *
     * @return mixed
     */
    public function homepageModify()
    {
        $org_imgs = array();
        $mod_imgs = array();
        $cus_id = Auth::id();
        $template_id = websiteInfo::where('cus_id', $cus_id)->pluck('pc_tpl_id');
        $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
        if ($pushed == 1 || $pushed == '3') {
            $pushed = 1;
        } else {
            $pushed = 2;
        }
        websiteInfo::where('cus_id', $cus_id)->update(['pushed' => $pushed]);
        $page = Input::get('page'); //===页面所属===index/form/...
        $data = Input::get('data');

        // ===处理数据===
        foreach ($data as $key => $val) {
            if (is_array($data[$key]) && count($data[$key])) {
                if (array_key_exists("href", $data[$key]) || array_key_exists("src", $data[$key])) {
                    $data[$key]['link'] = $data[$key]['href'];
                    $data[$key]['image'] = basename($data[$key]['src']);
                    $mod_imgs[] = $data[$key]['image'];
                    unset($data[$key]['href']);
                    unset($data[$key]['src']);
                } else {
                    foreach ($data[$key] as &$arr) {
                        if (is_array($arr)) {
                            if (array_key_exists("href", $arr) || array_key_exists("src", $arr)) {
                                $arr['link'] = $arr['href'];
                                $arr['image'] = basename($arr['src']);
                                $mod_imgs[] = $arr['image'];
                                unset($arr['href']);
                                unset($arr['src']);
                            }
                        }
                    }
                }
            }
            $temp_arr = $data[$key];
            unset($data[$key]);
            $data[$key]['value'] = $temp_arr;
        }
        $website_config = new WebsiteConfig();
        $website_config->value = serialize($data);
        $website_config->cus_id = $cus_id;
        $website_config->template_id = $template_id;
        $website_config->key = $page;
        if ($page == 'form') {
            $websiteFormArray = $data;
            $ids = array();
            $bind = array();
            foreach ($websiteFormArray as $key => $value) {
                $bind[substr($key, strlen($key) - 1)][substr($key, 0, -1)] = $value['value'];
                if (array_key_exists('form_id', $value['value'])) {
                    if (!in_array($value['value']['form_id'], $ids)) {
                        $ids[] = $value['value']['form_id'];
                    }
                }
            }
            $FormC = new FormController();
            $formInfo = $FormC->getFormInfoByIds($ids);
            $datajson = array();
            $datajson['forminfo'] = $formInfo;
            $datajson['website'] = $bind;
            file_put_contents(public_path("customers/" . Auth::user()->name . '/formdata.json'), json_encode($datajson));//===将表单数据写入.json中
        }
        $count = $website_config->where('cus_id', $cus_id)->where('template_id', $template_id)->where('key', $page)->first();//===判断是否存在数据===
        if ($count) {
            $websiteconfig = $count->value;
//            $websiteconfig = $website_config->where('cus_id', $cus_id)->where('template_id', $template_id)->where('key', $page)->pluck('value');
            $websitearray = unserialize($websiteconfig);
            $org_imgs = $this->getimage($websitearray);
            $result = $website_config->where('cus_id', $cus_id)->where('template_id', $template_id)->where('key', $page)->update(['value' => $website_config->value]);
        } else {
            $result = $website_config->save();
        }
        if ($result) {
            foreach ((array)$org_imgs as $v) {
                if (!in_array($v, (array)$mod_imgs)) {
                    $imgdel = new ImgDel();
                    $imgdel->mysave($v, 'page_index');
                }
            }
            return Response::json(['err' => 0, 'msg' => 'success', 'data' => null]);
        } else {
            return Response::json(['err' => 1001, 'msg' => '数据保存失败', 'data' => null]);
        }
    }

    public function getMobilePageData()
    {
        $cus_id = Auth::id();
        $mobile = new PrintController('preview', 'mobile');
        $mobile_tpl_id = WebsiteInfo::where('cus_id', $cus_id)->pluck('mobile_tpl_id');
        $my_tpl_name = Template::where('former_id', $mobile_tpl_id)->where('cus_id', $cus_id)->pluck('name');
        $is_my_tpl = Template::where('id', $mobile_tpl_id)->where('cus_id', $cus_id)->pluck('name');
        $my_tpl_num = count(Template::where('cus_id', $cus_id)->lists('id'));
        //===模板调用===
        if(empty($my_tpl_name)){
            $my_tpl_name = Template::where('former_id', $mobile_tpl_id)->where('cus_id', $cus_id)->pluck('name_bak');
        }
        if(empty($is_my_tpl)){
            $is_my_tpl = Template::where('id', $mobile_tpl_id)->where('cus_id', $cus_id)->pluck('name_bak');
        }
        //===模板调用===
        if ($my_tpl_name || $is_my_tpl || $my_tpl_num >= 3) {
            $coded = 1;
        } else {
            $coded = 0;
        }
        $m_catlist = Classify::where('mobile_show', 1)->where('cus_id', $mobile->cus_id)->whereIn('type', [1, 2, 3, 4])->orderBy('sort')->select('id', 'name', 'p_id', 'type')->get()->toArray();
        $showtypetotal = WebsiteInfo::where('website_info.cus_id', $cus_id)->LeftJoin('template', 'mobile_tpl_id', '=', 'template.id')->select('template.list1showtypetotal', 'template.list2showtypetotal', 'template.list3showtypetotal', 'template.list4showtypetotal')->first();
        if (count($m_catlist) > 0) {
            foreach ($m_catlist as &$m_arr) {
                $liststr = 'list' . $m_arr['type'] . 'showtypetotal';
                $mobilehome_config = MobileHomepage::where('c_id', $m_arr['id'])->first();
                if ($mobilehome_config) {
                    $m_arr['showtypetotal'] = $showtypetotal->$liststr;
                    if ($m_arr['showtypetotal']) {
                        $m_arr['index_show'] = $mobilehome_config->index_show;
                        if ($m_arr['showtypetotal'] < $mobilehome_config->m_index_showtype) {
                            $m_arr['showtype'] = 1;
                        } else {
                            $m_arr['showtype'] = $mobilehome_config->m_index_showtype;
                        }
                    } else {
                        $m_arr['showtype'] = 1;
                        $m_arr['index_show'] = 0;
                    }
                    MobileHomepage::where('id', $mobilehome_config->id)->update(array('m_index_showtype' => $m_arr['showtype'], 'index_show' => $m_arr['index_show']));
                    $m_arr['show_num'] = $mobilehome_config->show_num;
                    $m_arr['star_only'] = $mobilehome_config->star_only;
                    $m_arr['s_sort'] = $mobilehome_config->s_sort;
                } else {
                    $m_arr['index_show'] = 0;
                    $m_arr['show_num'] = 0;
                    $m_arr['star_only'] = 0;
                    $m_arr['s_sort'] = 0;
                    $m_arr['showtypetotal'] = $showtypetotal->$liststr;
                    $m_arr['showtype'] = 0;
                }
                $sort_key[] = $m_arr['s_sort'];
            }
        }
        if (!empty($sort_key) || !empty($m_catlist)) {
            array_multisort($sort_key, $m_catlist);
        }
        //$m_catlist=$this->toTree($m_catlist);
        $data = array();
        $pagelist[] = array('page' => 'index', 'title' => '栏目排序', 'type' => 'category');
        $template_id = WebsiteInfo::where('cus_id', $mobile->cus_id)->pluck('mobile_tpl_id');
        $config_str = WebsiteConfig::where('cus_id', $mobile->cus_id)->where('type', 2)->where('template_id', $template_id)->pluck('value');
        if ($config_str) {
            $config_arr = unserialize($config_str);
            if ($config_arr['slidepics']['value']) {
                //===大图排序===
                foreach ($config_arr['slidepics']['value'] as $k => &$v) {
                    if (is_array($v)) {
                        if (isset($v['sort'])) {
                            $sort[$k] = is_numeric($v['sort']) ? $v['sort'] : 100;
                            $v['sort'] = is_numeric($v['sort']) ? $v['sort'] : 100;
                        } else {
                            $sort[$k] = 100;
                            $v['sort'] = 100;
                        }
                    }
                }
                array_multisort($sort, $config_arr['slidepics']['value']);
                //===大图排序_end===
                ksort($config_arr['slidepics']['value'], SORT_NUMERIC);
            }
            if ($config_arr['slidepics']['value']) {
                $config_arr['slidepics']['value'] = array_merge($config_arr['slidepics']['value']);
            }
        } else {
            $config_arr = $mobile->mobilePageList('global', true);
        }
        //print_r($config_arr);exit;
        if (is_array($config_arr)) {
            foreach ($config_arr as $key => $val) {
                $pagelist[] = array('page' => $key, 'title' => $config_arr[$key]['description'], 'type' => $config_arr[$key]['type']);
                $data[$key] = $config_arr[$key];
                if ($data[$key]['type'] == 'image') {
                    if (!$data[$key]['value']['title'] and !$data[$key]['value']['image'] and !$data[$key]['value']['link'])
                        $data[$key]['value'] = array();
                }
            }
        }
        $templatedata['templatedata'] = $data;
        $templatedata['pagelist'] = $pagelist;
        $templatedata['coded'] = $coded;
        $templatedata['m_catlist'] = $m_catlist;
        $mobile->replaceUrl($templatedata);
        $return_msg = array('err' => 0, 'msg' => '', 'data' => $templatedata);
        return Response::json($return_msg);
    }

    public function mhomepageModify()
    {
        $cus_id = Auth::id();
        $org_imgs = array();
        $mod_imgs = array();
        $template_id = WebsiteInfo::where('cus_id', $cus_id)->pluck('mobile_tpl_id');
        $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed');
        if ($pushed == 1 || $pushed == '2') {
            $pushed = 1;
        } else {
            $pushed = 3;
        }
        websiteInfo::where('cus_id', $cus_id)->update(['pushed' => $pushed]);
        $data = Input::all();
        $input_keys = array_keys($data);
        $config_str = WebsiteConfig::where('cus_id', $cus_id)->where('type', 2)->where('template_id', $template_id)->pluck('value');
        $org_imgs = $this->getimage(unserialize($config_str));
        if ($config_str) {
            $config_arr = unserialize($config_str);
            $output_keys = array_keys($config_arr);
            if (in_array($input_keys[0], $output_keys)) {
                if ($config_arr[$input_keys[0]]['type'] != 'image') {
                    if ($data[$input_keys[0]] != '') {
                        foreach ($data[$input_keys[0]] as $key => $val) {
                            $data[$input_keys[0]][$key]['title'] = $data[$input_keys[0]][$key]['PC_name'];
                            $data[$input_keys[0]][$key]['image'] = basename($data[$input_keys[0]][$key]['phone_info_pic']);
                            $data[$input_keys[0]][$key]['link'] = $data[$input_keys[0]][$key]['PC_link'];
                            $data[$input_keys[0]][$key]['sort'] = $data[$input_keys[0]][$key]['PC_sort'];
                            unset($data[$input_keys[0]][$key]['PC_name']);
                            unset($data[$input_keys[0]][$key]['phone_info_pic']);
                            unset($data[$input_keys[0]][$key]['PC_link']);
                            unset($data[$input_keys[0]][$key]['PC_sort']);
                        }
                    } else {
                        $data[$input_keys[0]][0]['title'] = '';
                        $data[$input_keys[0]][0]['image'] = '';
                        $data[$input_keys[0]][0]['link'] = '';
                        $data[$input_keys[0]][0]['sort'] = 100;
                    }
                } else {
                    if ($data[$input_keys[0]] != '') {
                        foreach ($data[$input_keys[0]] as $key => $val) {
                            $data[$input_keys[0]]['title'] = $data[$input_keys[0]][$key]['PC_name'];
                            $data[$input_keys[0]]['image'] = basename($data[$input_keys[0]][$key]['phone_info_pic']);
                            $data[$input_keys[0]]['link'] = $data[$input_keys[0]][$key]['PC_link'];
                            $data[$input_keys[0]]['sort'] = $data[$input_keys[0]][$key]['PC_sort'];
                            unset($data[$input_keys[0]][$key]);
                        }
                    } else {
                        $data[$input_keys[0]]['title'] = '';
                        $data[$input_keys[0]]['image'] = '';
                        $data[$input_keys[0]]['link'] = '';
                        $data[$input_keys[0]]['sort'] = 100;
                    }
                }
                $config_arr[$input_keys[0]]['value'] = $data[$input_keys[0]];
            }
        } else {
            $mobile = new PrintController('preview', 'mobile');
            $config_arr = $mobile->mobilePageList('global', true);
            if (isset($data[$input_keys[0]])) {
                if ($config_arr[$input_keys[0]]['type'] != 'image') {
                    foreach ($data[$input_keys[0]] as $key => $v) {
                        $data[$input_keys[0]][$key]['title'] = $data[$input_keys[0]][$key]['PC_name'];
                        $data[$input_keys[0]][$key]['image'] = basename($data[$input_keys[0]][$key]['phone_info_pic']);
                        $data[$input_keys[0]][$key]['link'] = $data[$input_keys[0]][$key]['PC_link'];
                        $data[$input_keys[0]][$key]['sort'] = $data[$input_keys[0]][$key]['PC_sort'];
                        unset($data[$input_keys[0]][$key]['PC_name']);
                        unset($data[$input_keys[0]][$key]['phone_info_pic']);
                        unset($data[$input_keys[0]][$key]['PC_link']);
                        unset($data[$input_keys[0]][$key]['PC_sort']);
                    }
                } else {
                    foreach ($data[$input_keys[0]] as $key => $val) {
                        $data[$input_keys[0]]['title'] = $data[$input_keys[0]][$key]['PC_name'];
                        $data[$input_keys[0]]['image'] = basename($data[$input_keys[0]][$key]['phone_info_pic']);
                        $data[$input_keys[0]]['link'] = $data[$input_keys[0]][$key]['PC_link'];
                        $data[$input_keys[0]]['sort'] = $data[$input_keys[0]][$key]['PC_sort'];
                        unset($data[$input_keys[0]][$key]);
                    }
                }
                
            }
            $config_arr[$input_keys[0]]['value'] = $data[$input_keys[0]];
        }
        $mod_imgs = $this->getimage($config_arr);
        $new_config_str = serialize($config_arr);
        if ($config_str) {
            $result = DB::table('website_config')->where('cus_id', $cus_id)->where('type', 2)->where('template_id', $template_id)->update(array('value' => $new_config_str));
        } else {
            $result = DB::table('website_config')->insert(array('cus_id' => $cus_id, 'type' => 2, 'template_id' => $template_id, 'key' => 'global', 'value' => $new_config_str));
        }
        if ($result) {
            foreach ((array)$org_imgs as $v) {
                if (!in_array($v, (array)$mod_imgs)) {
                    $imgdel = new ImgDel();
                    $imgdel->mysave($v, 'page_index');
                }
            }
            $return_msg = array('err' => 0, 'msg' => '');
        } else {
            $return_msg = array('err' => 3001, 'msg' => '修改失败', 'data' => array());
        }
        return Response::json($return_msg);
    }

    public function homepageSortModify()
    {
        $indexlist = Input::get('indexlist');
        $data = array();
        $err = false;
        $cus_id = Auth::id();
        if (count($indexlist) > 0) {
            foreach ($indexlist as $list) {
                $classify = MobileHomepage::where('c_id', $list['id'])->first();
                if (!$classify) {
                    $classify = new MobileHomepage();
                    $classify->index_show = 0;
                    $classify->c_id = $list['id'];
                    $classify->cus_id = $cus_id;
                }
                $classify->s_sort = $list['indexs'];
                $classify->pushed = 1;
                $result = $classify->save();
                if (!$result) {
                    $data[] = $list['id'];
                    $err = true;
                }
            }
        }
        if ($err) {
            $return_msg = array('err' => 3001, 'msg' => '操作失败', 'data' => $data);
        } else {
            $return_msg = array('err' => 0, 'msg' => '操作成功');
        }
        return Response::json($return_msg);
    }

    public function homepageBatchModify()
    {
        $ids = Input::get('id');
        $index_show = Input::get('show');
        $show_num = Input::get('total');
        $show_type = Input::get('showtype');
        $star_only = Input::get('star_only');
        $cus_id = Auth::id();
        if (is_array($ids)) {
            $err = false;
            $data = array();
            foreach ($ids as $id) {
                $m_i_config = MobileHomepage::where('c_id', '=', $id)->first();
                if (!$m_i_config) {
                    $m_i_config = new MobileHomepage();
                    $m_i_config->c_id = $ids;
                    $m_i_config->cus_id = $cus_id;
                }
                if ($index_show != NULL) {
                    $m_i_config->index_show = $index_show;
                }
                if ($show_num != NULL) {
                    $m_i_config->show_num = $show_num;
                }
                if ($star_only != NULL) {
                    $m_i_config->star_only = $star_only;
                }
                if ($show_type != NULL) {
                    $m_i_config->m_index_showtype = $show_type;
                }
                $m_i_config->pushed = 1;
                $result = $m_i_config->save();
                if ($result) {
                    $data[] = $id;
                } else {
                    $err = true;
                }
            }
            if ($err) {
                $return_msg = array('err' => 3001, 'msg' => '操作失败', 'data' => $data);
            } else {
                $return_msg = array('err' => 0, 'msg' => '操作成功');
            }
        } else {
            $m_i_config = MobileHomepage::where('c_id', '=', $ids)->first();
            if (!$m_i_config) {
                $m_i_config = new MobileHomepage();
                $m_i_config->c_id = $ids;
                $m_i_config->cus_id = $cus_id;
            }
            if ($index_show != NULL) {
                $m_i_config->index_show = $index_show;
            }
            if ($show_num != NULL && is_numeric($show_num)) {
                $m_i_config->show_num = $show_num;
            }
            if ($show_type != NULL && is_numeric($show_type)) {
                $m_i_config->m_index_showtype = $show_type;
            }
            if ($star_only != NULL) {
                $m_i_config->star_only = $star_only;
            }
            $m_i_config->pushed = 1;
            $result = $m_i_config->save();
            if ($result) {
                $return_msg = array('err' => 0, 'msg' => '操作成功');
            } else {
                $return_msg = array('err' => 3001, 'msg' => '操作失败', 'data' => array());
            }
        }
        return Response::json($return_msg);
    }

    private function toTree($arr, $pid = 0)
    {
        $tree = array();
        foreach ($arr as $k => $v) {
            if ($v['p_id'] == $pid) {
                $tree[] = $v;
            }
        }
        if (empty($tree)) {
            return null;
        }
        foreach ($tree as $k => $v) {
            if ($v['type'] == 1 || $v['type'] == 2 || $v['type'] == 3 || $v['type'] == 4) {
                $tree[$k]['link'] = url('mcategory/' . $v['id'] . '.html');
            } else {
                $tree[$k]['link'] = $v['url'];
            }
            $tree[$k]['childmenu'] = $this->toTree($arr, $v['id']);
        }
        return $tree;
    }

    private function sendTemplate()
    {
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $cus_id = Auth::id();
            $customer = Auth::user()->name;
            $webinfo = WebsiteInfo::where("cus_id", $cus_id)->first();
            $pc_themename = Template::where("id", $webinfo->pc_tpl_id)->pluck("name");
            $mobile_themename = Template::where("id", $webinfo->mobile_tpl_id)->pluck("name");
            $pc_tpl = Template::where("id", $webinfo->pc_tpl_id)->first();
            $m_tpl = Template::where("id", $webinfo->mobile_tpl_id)->first();
            $conn = ftp_connect(TONGYI_TUISONG_JUYU_IP, TONGYI_TUISONG_FTP_PORT);
            $path = TONGYI_TUISONG_DIR;
            if ($conn) {
                ftp_login($conn, TONGYI_TUISONG_FTP_USER, TONGYI_TUISONG_FTP_PASSWORD);
                ftp_pasv($conn, true);
                if (ftp_nlist($conn, $path . "/" . $customer) === FALSE) {
                    ftp_mkdir($conn, $path . "/" . $customer);
                }
                $view_dir = app_path('views/templates/');
                $json_dir = public_path('templates/');
                //===PC调用模板===
                if(empty($pc_themename) or !is_dir($view_dir . $pc_themename) or !is_dir($json_dir . $pc_themename)){
                    $pc_themename = Template::where("id", $webinfo->pc_tpl_id)->pluck("name_bak");
                }
                //===PC调用模板===
                //===手机调用模板===
                if(empty($mobile_themename) or !is_dir($view_dir . $mobile_themename) or !is_dir($json_dir . $mobile_themename)){
                    $mobile_themename = Template::where("id", $webinfo->mobile_tpl_id)->pluck("name_bak");
                }
                //===手机调用模板===
                if ($pc_tpl->push_get_date == null || $pc_tpl->push_get_date == "" || $pc_tpl->push_get_date < $pc_tpl->updated_at) {
                    $pc_json = array();
                    $pc_json["themename"] = $pc_themename;
                    $pc_json["push_get_date"] = date("Y-m-d H:i:s", time());
                    file_put_contents(public_path('customers/' . $customer . '/pc_config.json'), json_encode($pc_json));
                    $pc_path = public_path('customers/' . $customer . '/pc.zip');
                    if (file_exists($pc_path)) {
                        @unlink($pc_path);
                    }
                    $pc_zip = new ZipArchive;
                    if ($pc_zip->open($pc_path, ZipArchive::CREATE) === TRUE) {
                        $this->addFileToZip($view_dir . $pc_themename, $pc_zip, $pc_themename . "/html/");
                        $this->addFileToZip($json_dir . $pc_themename, $pc_zip, $pc_themename . "/json/");
                        $pc_zip->addFile(public_path('customers/' . $customer . '/pc_config.json'), $pc_themename . "/config.json");
                        $pc_zip->close();
                        ftp_put($conn, $path . "/" . $customer . "/pc.zip", $pc_path, FTP_BINARY);
                    }
                }
                if ($m_tpl->push_get_date == null || $m_tpl->push_get_date == "" || $m_tpl->push_get_date < $m_tpl->updated_at) {
                    $m_json = array();
                    $m_json["themename"] = $pc_themename;
                    $m_json["push_get_date"] = date("Y-m-d H:i:s", time());
                    file_put_contents(public_path('customers/' . $customer . '/m_config.json'), json_encode($m_json));
                    $m_path = public_path('customers/' . $customer . '/mobile.zip');
                    if (file_exists($m_path)) {
                        @unlink($m_path);
                    }
                    $m_zip = new ZipArchive;
                    if ($m_zip->open($m_path, ZipArchive::CREATE) === TRUE) {
                        $this->addFileToZip($view_dir . $mobile_themename, $m_zip, $mobile_themename . "/html/");
                        $this->addFileToZip($json_dir . $mobile_themename, $m_zip, $mobile_themename . "/json/");
                        $m_zip->addFile(public_path('customers/' . $customer . '/m_config.json'), $mobile_themename . "/config.json");
                        $m_zip->close();
                        ftp_put($conn, $path . "/" . $customer . "/mobile.zip", $m_path, FTP_BINARY);
                    }
                }
                ftp_close($conn);
            }
            $remember_token = Auth::user()->remember_token;
            if ($remember_token == "" || $remember_token == null) {
                $remember_token = time() . str_random(4);
                Customer::where("id", Auth::id())->update(array("remember_token" => $remember_token));
            }
        }
    }

    private function unpackTemplate()
    {
        if ($_SERVER["HTTP_HOST"] == TONGYI_TUISONG_JUYU_IP) {
            $cus_id = Auth::id();
            $customer = Auth::user()->name;
            $pc_path = public_path('customers/' . $customer . '/pc.zip');
            $mobile_path = public_path('customers/' . $customer . '/mobile.zip');
            $view_dir = app_path('views/templates/');
            $json_dir = public_path('templates/');
            $webinfo = WebsiteInfo::where("cus_id", $cus_id)->first();
            $pc_themename = Template::where("id", $webinfo->pc_tpl_id)->pluck("name");
            $mobile_themename = Template::where("id", $webinfo->mobile_tpl_id)->pluck("name");
            $pc_tpl = Template::where("id", $webinfo->pc_tpl_id)->first();
            $m_tpl = Template::where("id", $webinfo->mobile_tpl_id)->first();
            //===手机模板调用===
            if(empty($pc_themename) or !is_dir(public_path('customers/' . $customer . "/temp/" . $pc_themename))){
                $pc_themename = Template::where("id", $webinfo->pc_tpl_id)->pluck("name_bak");
            }
            //===手机模板调用===
            //===PC模板调用===
            if(empty($mobile_themename) or !is_dir(public_path('customers/' . $customer . "/temp/" . $mobile_themename))){
                $mobile_themename = Template::where("id", $webinfo->mobile_tpl_id)->pluck("name_bak");
            }
            //===PC模板调用===
            if ($pc_tpl->push_get_date == null || $pc_tpl->push_get_date == "" || $pc_tpl->push_get_date < $pc_tpl->updated_at) {
                if (file_exists($pc_path)) {
                    $zip = new ZipArchive;
                    if ($zip->open($pc_path) === TRUE) {
                        $zip->extractTo(public_path('customers/' . $customer . "/temp"));
                        $zip->close();
                        $pc_config = json_decode(file_get_contents(public_path('customers/' . $customer . "/temp/" . $pc_themename . "/config.json")));
                        if ($pc_tpl->push_get_date == null || $pc_tpl->push_get_date == "" || $pc_tpl->push_get_date < $pc_config->push_get_date) {
                            $this->copydir(public_path('customers/' . $customer . "/temp/" . $pc_themename . "/html"), $view_dir . "/" . $pc_themename);
                            if (file_exists($view_dir . "/" . $pc_themename . "/searchresult_do.html")) {
                                @unlink($view_dir . "/" . $pc_themename . "/searchresult_do.html");
                            }
                            $this->copydir(public_path('customers/' . $customer . "/temp/" . $pc_themename . "/json"), $json_dir . "/" . $pc_themename);
                            if ($pc_config->push_get_date > $pc_tpl->updated_at) {
                                Template::where("id", $webinfo->pc_tpl_id)->update(array("push_get_date" => date("Y-m-d H:i:s", time())));
                            }
                        }
                    }
                }
            }
            if ($m_tpl->push_get_date == null || $m_tpl->push_get_date == "" || $m_tpl->push_get_date < $m_tpl->updated_at) {
                if (file_exists($mobile_path)) {
                    $zip = new ZipArchive;
                    if ($zip->open($mobile_path) === TRUE) {
                        $zip->extractTo(public_path('customers/' . $customer . "/temp"));
                        $zip->close();
                        $m_config = json_decode(file_get_contents(public_path('customers/' . $customer . "/temp/" . $mobile_themename . "/config.json")));
                        if ($m_tpl->push_get_date == null || $m_tpl->push_get_date == "" || $m_tpl->push_get_date < $m_config->push_get_date) {
                            $this->copydir(public_path('customers/' . $customer . "/temp/" . $mobile_themename . "/html"), $view_dir . "/" . $mobile_themename);
                            if (file_exists($view_dir . "/" . $mobile_themename . "/searchresult_do.html")) {
                                @unlink($view_dir . "/" . $mobile_themename . "/searchresult_do.html");
                            }
                            $this->copydir(public_path('customers/' . $customer . "/temp/" . $mobile_themename . "/json"), $json_dir . "/" . $mobile_themename);
                            if ($m_config->push_get_date > $m_tpl->updated_at) {
                                Template::where("id", $webinfo->mobile_tpl_id)->update(array("push_get_date" => date("Y-m-d H:i:s", time())));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * PC首页预览
     */
    public function homepagePreview()
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/homepage-preview-no-auth?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController();
        return $template->homepagePreview($result);
    }

    /**
     * PC栏目页预览
     */
    public function categoryPreview($id, $page = 1)
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/category-no-auth/" . $id . "_" . $page . "?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController;
        return $template->categoryPreview($id, $page, $result);
    }

    /**
     * ===PC栏目页预览(栏目别名)===
     */
    public function categoryPreviewV($view_name, $page = 1)
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/category-no-auth/" . $view_name . "_" . $page . "?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController;
        return $template->categoryPreview($view_name, $page, $result);
    }

    /**
     * PC内容页预览
     */
    public function articlePreview($id)
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/detail-no-auth/" . $id . "?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController;
        return $template->articlePreview($id, $result);
    }

    /**
     * 手机首页预览
     */
    public function mhomepagePreview()
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/mobile/homepage-preview-no-auth?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController('preview', 'mobile');
        return $template->mhomepagePreview($result);
    }

    /**
     * 手机栏目预览
     */
    public function mcategoryPreview($id, $page = 1)
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/mobile/category-no-auth/{$id}_{$page}?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController('preview', 'mobile');
        return $template->categoryPreview($id, $page, $result);
    }

    /**
     * ===手机栏目预览(栏目别名)===
     */
    public function mcategoryPreviewV($view_name, $page = 1)
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/mobile/category-no-auth/{$view_name}_{$page}?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController('preview', 'mobile');
        return $template->categoryPreview($view_name, $page, $result);
    }

    /**
     * 手机内容页预览
     */
    public function marticlePreview($id)
    {
        $result = array();
        if ($_SERVER["HTTP_HOST"] == TONGYI_DOMAIN) {
            $this->sendTemplate();
            $json = file_get_contents("http://" . TONGYI_TUISONG_JUYU_IP . "/mobile/detail-no-auth/{$id}?name=" . (Auth::user()->name) . "&remember_token=" . (Auth::user()->remember_token ? Auth::user()->remember_token : ""));
            $result = json_decode($json, true);
        }
        $template = new PrintController('preview', 'mobile');
        return $template->articlePreview($id, $result);
    }

    private function preview_login()
    {
        if ($_SERVER["HTTP_HOST"] == TONGYI_TUISONG_JUYU_IP && Input::has("name")) {
            if (Input::has("remember_token") && Input::get("remember_token") == "" && Input::get("remember_token") == null) {
                $user = Customer::where("name", Input::get("name"))->first();
            } else {
                $user = Customer::where("name", Input::get("name"))->where("remember_token", Input::get("remember_token"))->first();
            }
            Auth::login($user);
            Session::put('isAdmin', TRUE);
            $this->unpackTemplate();
        }
    }

    /**
     * PC首页预览
     */
    public function homepagePreview_no_auth()
    {
        $this->preview_login();
        $template = new PrintController();
        return $template->homepagePreview();
    }

    /**
     * PC栏目页预览
     */
    public function categoryPreview_no_auth($id, $page = 1)
    {
        $this->preview_login();
        $template = new PrintController;
        return $template->categoryPreview($id, $page);
    }

    /**
     * PC内容页预览
     */
    public function articlePreview_no_auth($id)
    {
        $this->preview_login();
        $template = new PrintController;
        return $template->articlePreview($id);
    }

    /**
     * 手机首页预览
     */
    public function mhomepagePreview_no_auth()
    {
        $this->preview_login();
        $template = new PrintController('preview', 'mobile');
        return $template->mhomepagePreview();
    }

    /**
     * 手机栏目预览
     */
    public function mcategoryPreview_no_auth($id, $page = 1)
    {
        $this->preview_login();
        $template = new PrintController('preview', 'mobile');
        return $template->categoryPreview($id, $page);
    }

    /**
     * 手机内容页预览
     */
    public function marticlePreview_no_auth($id)
    {
        $this->preview_login();
        $template = new PrintController('preview', 'mobile');
        return $template->articlePreview($id);
    }

    /**
     * 手机底部功能条
     */
    public function quickBarJson()
    {
        if (array_key_exists('HTTP_REFERER', $_SERVER) && (strpos(strtolower($_SERVER['HTTP_REFERER']), 'mobile') || strpos(strtolower($_SERVER['HTTP_REFERER']), 'templates/gm') || preg_match('/templates\/g[0-9]{4}m/', strtolower($_SERVER['HTTP_REFERER'])))) {
            $template = new PrintController('preview', 'mobile');
        } else {
            $template = new PrintController('preview', 'pc');
        }
        return $template->quickBarJson();
    }

    /**
     * 搜索页面预览
     */
    public function searchPreview($type = 'pc')
    {
        header("Content-type:text/html;charset=utf-8");
        echo '预览页面不支持搜索功能，请在网站中使用--- , 当前页面将在3s后自动跳转';
        ob_flush();
        flush();
        sleep(3);
        echo '<script language="javascript">
                history.go(-1);
            </script>';
        //$template = new PrintController('preview',$type);
        //return $template->searchPreview();
    }

    /**
     * 获取模板名
     */
    public function getTemplatesName($type = 'pc')
    {
        $cus_id = Auth::id();
        if ($type == 'pc') {
            $tpl_name = 'pc_tpl_id';
        } else {
            $tpl_name = 'mobile_tpl_id';
        }
        $tpl_id = WebsiteInfo::where('cus_id', $cus_id)->pluck($tpl_name);
        $my_tpl_name = Template::where('id', $tpl_id)->pluck('name');
        //===模板调用===
        if(empty($my_tpl_name) or !is_dir(public_path('/templates/'.$my_tpl_name)) or !is_dir(app_path('/views/templates/'.$my_tpl_name))){
            $my_tpl_name = Template::where('id', $tpl_id)->pluck('name_bak');
        }
        //===模板调用===
        return $my_tpl_name;
    }

    /**
     * 模板下载
     */
    public function downloadTemplate($tplname = "")
    {
        if ($tplname != "") {
            //===统一转换成新命名===
            if(preg_match('/G\d{4}(P|M)(CN|EN|TW|JP)\d{2}/', $tplname)){
                $tpl = Template::where('name', $tplname)->first();
            }else{
                $tpl = Template::where('name_bak', $tplname)->first();
                $tplname = $tpl['name'];
            }
            //===转换end===
            if ($tpl == null) {
                return json_encode(array("err" => 1, "msg" => "没有该模板！"));
            }
            if (!is_dir(public_path('temp_templates/tpl/'))) {
                @mkdir(public_path('temp_templates/tpl/'));
            } else {
                $dh = opendir(public_path('temp_templates/tpl'));
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != "..") {
                        $fullpath = public_path('temp_templates/tpl/' . $file);
                        if (is_file($fullpath)) {
                            unlink($fullpath);
                        }
                    }
                }
                closedir($dh);
            }            
            $view_dir = app_path('views/templates/');
            $json_dir = public_path('templates/');
            //===判断view_dir和json_dir是否存在，不存在，用旧命名去查===
            if(!is_dir($view_dir.$tplname) && !is_dir($json_dir.$tplname)){
                    $tplname = $tpl['name_bak'];
            }
            //===查找end===
            $path = public_path('temp_templates/tpl/' . $tplname . '.zip');
            if (file_exists($path)) {
                @unlink($path);
            }            
            $zip = new ZipArchive;
            if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
                $this->addFileToZip($json_dir . $tplname . "/css", $zip, "css/");
                $this->addFileToZip($json_dir . $tplname . "/js", $zip, "js/");
                $this->addFileToZip($json_dir . $tplname . "/images", $zip, "images/");
                $this->addFileToZip($json_dir . $tplname . "/json", $zip, "");
                $this->addFileToZip($view_dir . $tplname, $zip, "", "searchresult_do.html");
                $zip->addFile($json_dir . $tplname . "/config.ini", "config.ini");
                $zip->addFile($json_dir . $tplname . "/screenshot.jpg", "screenshot.jpg");
                $zip->close();
            }
            return json_encode(array("err" => 0, "msg" => 'http://' . $_SERVER['HTTP_HOST'] . '/temp_templates/tpl/' . $tplname . '.zip'));
        } else {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            if (Auth::user()->name != "test") {
                return false;
            }
            $tplname = Input::get("name");
            //===统一转换新命名===
            if(preg_match('/G\d{4}(P|M)(CN|EN|TW|JP)\d{2}/', $tplname)){
                $tpl = Template::where('name', $tplname)->first();
            }else{
                $tpl = Template::where('name_bak', $tplname)->first();
                $tplname = $tpl['name'];
            }
            //===转换end===
            if ($tpl == null) {
                echo '没有该模板！';
            }
            if (!is_dir(public_path('temp_templates/tpl/'))) {
                @mkdir(public_path('temp_templates/tpl/'));
            } else {
                $dh = opendir(public_path('temp_templates/tpl'));
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != "..") {
                        $fullpath = public_path('temp_templates/tpl/' . $file);
                        if (is_file($fullpath)) {
                            unlink($fullpath);
                        }
                    }
                }
                closedir($dh);
            }            
            $view_dir = app_path('views/templates/');
            $json_dir = public_path('templates/');
            //===判断view_dir和json_dir是否存在，不存在，用旧命名去查===
            if(!is_dir($view_dir.$tplname) && !is_dir($json_dir.$tplname)){
                    $tplname = $tpl['name_bak'];
            }
            //===查找end===
            $path = public_path('temp_templates/tpl/' . $tplname . '.zip');
            if (file_exists($path)) {
                @unlink($path);
            }            
            $zip = new ZipArchive;
            if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
                $this->addFileToZip($json_dir . $tplname . "/css", $zip, "css/");
                $this->addFileToZip($json_dir . $tplname . "/js", $zip, "js/");
                $this->addFileToZip($json_dir . $tplname . "/images", $zip, "images/");
                $this->addFileToZip($json_dir . $tplname . "/json", $zip, "");
                $this->addFileToZip($view_dir . $tplname, $zip, "", "searchresult_do.html");
                $zip->addFile($json_dir . $tplname . "/config.ini", "config.ini");
                $zip->addFile($json_dir . $tplname . "/screenshot.jpg", "screenshot.jpg");
                $zip->close();
            }
            echo '<a href="/temp_templates/tpl/' . $tplname . '.zip">下载' . $tplname . '模板</a>';
        }
    }

    /**
     * 将整个文件夹压缩
     * @param type $path文件夹路径
     * @param type $zip压缩变量
     * @param type $dir存储名称
     */
    private function addFileToZip($path, $zip, $dir, $notcopyfile = "")
    {//将整个文件夹压缩
        $handler = opendir($path);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                if (is_dir($path . "/" . $filename)) {
                    $this->addFileToZip($path . "/" . $filename, $zip, $dir . $filename . "/");
                } else {
                    if ($notcopyfile == "" || $filename != $notcopyfile) {
                        $zip->addFile($path . "/" . $filename, $dir . $filename);
                    }
                }
            }
        }
    }

    /**
     * 文件夹复制
     * @param type $source_dir 资源文件夹
     * @param type $dest_dir 目标文件夹
     */
    private function copydir($source_dir, $dest_dir)
    {
        $dir = opendir($source_dir);
        if (!is_dir($dest_dir)) {
            @mkdir($dest_dir);
        }
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source_dir . '/' . $file)) {
                    $this->copydir($source_dir . '/' . $file, $dest_dir . '/' . $file);
                } else {
                    copy($source_dir . '/' . $file, $dest_dir . '/' . $file);
                }
            }
        }
    }

    /*
      public function searchPreview($url,$type='pc'){
      $template = new PrintController('online',$type);
      return $template->sendCusAllData($url);
      }

      public function sendData(){
      $this->searchPreview('http://swap.gbpen.com/PostTemplates.php','pc');
      $this->searchPreview('http://swap.gbpen.com/PostTemplates.php','mobile');
      }
     * */
}
