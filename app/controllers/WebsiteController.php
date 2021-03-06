<?php

/**
  |--------------------------------------------------------------------------
  | 模版控制器
  |--------------------------------------------------------------------------
  |方法：
  |templatesPC        PC模版查询、增加、设为默认、删除
  |templatesQuery      查询模版
  |templatesList      列出当前已选的模版
  |copy               拷贝公共模版到用户目录用于定制
  |fileList           列出当前定制模版的可编辑文件
  |fileget            获取文件的内容
  |fileeidt           编辑保存文件
  |rcopy              拷贝一个目录
  |saveTemplate       模板入库
  |unpack             解包模板文件
  |
 */
class WebsiteController extends BaseController {

    /**
     * 模板列表
     * @param type $type        模板类型,1-PC,2-mobile
     * @param type $per_page    每页数据数
     * @param type $form        当前第几页
     * @param type $search      ？搜索条件
     * @param type $classify    ？栏目 
     * @param type $color       颜色
     * @return type 
     */
    public function templatesList($type = 1, $per_page = 8, $form = 1, $search = NULL, $classify = '', $color = NULL) {
        $cus_id = Auth::id();
        $without_tid = Template::where('cus_id', $cus_id)->lists('former_id'); //===定制模板===
        $where = " WHERE type=$type AND cus_id!=$cus_id";
        if (count($without_tid)) {
            $notin = implode(",", $without_tid);
            $where .= " AND id NOT IN($notin)";
        }
        if ($search) {
            $where .= " AND (name like '%$search%' OR name_bak like '%$search%') ";
        }
        if ($classify) {
            $where .= " AND classify='$classify'";
        }
        $join = '';
        $prefix = Config::get('database.connections.mysql.prefix');
        //搜索对应颜色的模板？
        // if ($color) {
        //     $join = ' LEFT JOIN ' . $prefix . 'template_to_color t_r ON t.id=t_r.template_id LEFT JOIN ' . $prefix . 'color c ON t_r.color_id=c.id';
        //     if (is_array($color)) {
        //         $in = implode("','", $color);
        //         $where .= " AND color IN('$in')";
        //     } else {
        //         $where .= " AND color='$color'";
        //     }
        // }
        $website_info = WebsiteInfo::where('cus_id', $cus_id)->first();
        $total = DB::select('SELECT count(*) FROM ' . $prefix . 'template t' . $join . $where . ' GROUP BY name');
        $result['total'] = count($total);
        $data = DB::select('SELECT t.id as tid,name,name_bak,tpl_name,classify,isColorful,color_style,created_at,updated_at  FROM ' . $prefix . 'template t' . $join . $where . ' GROUP BY name ORDER BY tid limit ' . $form . ',' . $per_page);
        $result['per_page'] = count($data);
        $result['current_page'] = $form + 1;
        $result['last_page'] = ceil($result['total'] / $per_page);
        $result['from'] = $form ? $form : 1;
        $result['to'] = $form + $result['per_page'];
        // $TemplateToColor = new TemplateToColor;//原换色模板？
        foreach ($data as $k => $v) {
            $std['id'] = $v->tid;
            $std['serial'] = $v->name;
            $std['serial_bak'] = $v->name_bak;
            $std['name'] = $v->tpl_name;
            $std['classify'] = $v->classify;
            $std['isColorful'] = $v->isColorful;
            if(!is_dir(public_path('templates/' . $v->name))){
                $std['img'] = asset('templates/' . $v->name_bak . '/screenshot.jpg');
            }else{
                $std['img'] = asset('templates/' . $v->name . '/screenshot.jpg');
            }
            // $std['img'] = asset('templates/' . $v->name . '/screenshot.jpg');
            // $std['colors'] = $TemplateToColor->getColorByTemplateId($v->tid);//原换色模板？
            $color_str = str_replace('#', '', $v->color_style);
            $std['colors'] = explode(',', $color_str);

            if ($type == 1) {
                if ($v->tid == $website_info->pc_tpl_id) {
                    $std['is_selected'] = 1;
                    // $std['selected_style'] = Color::where('id', $website_info->pc_color_id)->pluck('color');//原换色模板？
                    $std['selected_style'] = $website_info->pc_color;
                } else {
                    $std['is_selected'] = 0;
                    $std['selected_style'] = NULL;
                }
            }
            if ($type == 2) {
                if ($v->tid == $website_info->mobile_tpl_id) {
                    $std['is_selected'] = 1;
                    // $std['selected_style'] = Color::where('id', $website_info->mobile_color_id)->pluck('color');//原换色模板？
                    $std['selected_style'] = $website_info->mobile_color;
                } else {
                    $std['is_selected'] = 0;
                    $std['selected_style'] = NULL;
                }
            }
            $std['created_at'] = $v->created_at;
            $std['updated_at'] = $v->updated_at;
            $result['data'][$k] = $std;
        }
        return $result;
    }

    /**
     * 获取模板列表
     * @return type
     */
    public function templatesListGet() {
        $type = Input::get('type');
        $per_page = Input::get('per_page') ? Input::get('per_page') : 8;
        $form = Input::get('current_page') ? (Input::get('current_page') - 1) * $per_page : 0;
        $search = Input::get('search');
        $classify = Input::get('classify');
        $color = Input::get('color');
        $result = $this->templatesList($type, $per_page, $form, $search, $classify, $color);
        return Response::json(['err' => 0, 'msg' => '', 'data' => $result]);
    }

    /**
     * 我的定制列表
     * @return type
     */
    public function myTemplateList() {
        $cus_id = Auth::id();
        $type = Input::has('type') ? Input::get('type') : 1;
        //$type = 2;
        $per_page = Input::has('per_page') ? Input::get('per_page') : 8;
        $form = Input::has('current_page') ? (Input::get('current_page') - 1) * $per_page : 0;
        $data = Template::where('cus_id', $cus_id)->where('type', $type)
                        ->LeftJoin('template_to_color', 'template.id', '=', 'template_to_color.template_id')
                        ->LeftJoin('color', 'template_to_color.color_id', '=', 'color_id')->select('template.id as tid', 'name', 'tpl_name', 'created_at', 'updated_at', 'classify')->groupBy('name')->get()->toArray();
        $i = 1;
        $website_info = WebsiteInfo::where('cus_id', $cus_id)->first();
        $mytemplelist = array();
        $result = array();
        foreach ($data as $d) {
            $mytemplelist[$i]['id'] = $d['tid'];
            $mytemplelist[$i]['serial'] = $d['name'];
            $mytemplelist[$i]['name'] = $d['tpl_name'];
            $mytemplelist[$i]['img'] = asset('templates/' . $d['name'] . '/screenshot.jpg');
            if ($type == 1) {
                if ($d['tid'] == $website_info->pc_tpl_id) {
                    $mytemplelist[$i]['is_selected'] = 1;
                } else {
                    $mytemplelist[$i]['is_selected'] = 0;
                }
            }
            if ($type == 2) {
                if ($d['tid'] == $website_info->mobile_tpl_id) {
                    $mytemplelist[$i]['is_selected'] = 1;
                } else {
                    $mytemplelist[$i]['is_selected'] = 0;
                }
            }
            $mytemplelist[$i]['created_at'] = $d['created_at'];
            $mytemplelist[$i]['updated_at'] = $d['updated_at'];
            $mytemplelist[$i]['classify'] = $d['classify'];
            $i++;
        }
        $result = $this->templatesList($type, $per_page, $form);
        return Response::json(['err' => 0, 'msg' => '', 'data' => ['mytemplelist' => $mytemplelist, 'templelist' => $result]]);
    }

    /**
     * ===模板更换===
     * （4次查询，2次更新）
     * 1、选择颜色，查询模板
     * @return type
     */
    public function templateChage() {
        $cus_id = Auth::id();
        $type = Input::get('type'); //===模板类型：1-PC，2-手机===
        $id = Input::get('id'); //===所选模板id===
        $color = Input::get('color'); //===颜色名===
        //原换色模板？
        // if (!empty($color) || $color != "undefined") {
        //     $color_id = Color::where('color_en', $color)->pluck('id'); //===获取颜色id
        // } else {
        //     $color_id = 0;
        // }
        $template = Template::find($id); //查询模板
        $webinfo = websiteInfo::where('cus_id', $cus_id)->select('pushed', 'pc_color', 'mobile_color')->first()->toArray();
        if($type == 1) {
            $color_before = $webinfo['pc_color'];
        } else {
            $color_before = $webinfo['mobile_color'];
        }
        if($template->isColorful == 1) {
            if(!$color or $color == 'undefined') {
                if(!$color_before or !strpos($template->color_style, $color_before)) {
                    //之前的选色不存在或不属于所选模板时
                    $color_str = str_replace('#', '', $template->color_style);
                    $color_arr = explode(',', $color_str);
                    $color = $color_arr['0'];
                } else {
                    $color = $color_before;
                }                
            }
        } else {
            $color = $color_before;
        }
        $websiteconfig = WebsiteConfig::where('cus_id', $cus_id)->where('type', 2)->where('template_id', '0')->where('key', 'quickbar')->pluck('value'); //读取网站手机quickbar配置
        $websiteconfig = unserialize($websiteconfig);
        foreach ((array) $websiteconfig as $key => $val) {
            if ($val['type'] === 'colors') {//===如果quickbar有设置颜色，则移除quick的颜色配置===
                unset($websiteconfig[$key]);
                break;
            }
        }
        $websiteconfig = serialize($websiteconfig);
        // $pushed = websiteInfo::where('cus_id', $cus_id)->pluck('pushed'); //===获取是否推送===
        $pushed = $webinfo['pushed']; //===获取是否推送===
        if ($template->type == $type) {//===判断模板类型，1=PC，2=MOBILE===
            if ($type == 1) {
                if ($pushed == 1 || $pushed == '3') {//pushed：0-不需推送，1-pc+手机，2-pc，3-手机;
                    $pushed = 1;
                } else {
                    $pushed = 2;
                }
                // $update = ['pc_tpl_id' => $id, 'pc_color_id' => $color_id, 'pushed' => $pushed];
                $update = ['pc_tpl_id' => $id, 'pc_color' => $color, 'pushed' => $pushed];
            } else {
                if ($pushed == 1 || $pushed == '2') {
                    $pushed = 1;
                } else {
                    $pushed = 3;
                }
                $update = ['mobile_tpl_id' => $id, 'mobile_color' => $color, 'pushed' => $pushed];
            }
            $update_result = WebsiteInfo::where('cus_id', $cus_id)->update($update);
            if ($update_result) {
                WebsiteConfig::where('cus_id', $cus_id)->where('key', 'quickbar')->update(['value' => $websiteconfig, 'pushed' => 1]);
                if ($type == 1) {
                    $this->logsAdd('websiteinfo', __FUNCTION__, __CLASS__, 999, "切换PC模板，id：" . $id . "，颜色id：" . $color . "，cus_id", 0, $cus_id);
                } else {
                    $this->logsAdd('websiteinfo', __FUNCTION__, __CLASS__, 999, "切换手机模板，id：" . $id . "，颜色id：" . $color . "，cus_id", 0, $cus_id);
                }
                $result = ['err' => 0, 'msg' => 'success'];
            } else {
                $result = ['err' => 1001, 'msg' => '更换模版失败'];
            }
        } else {
            $result = ['err' => 1001, 'msg' => '选择模版存在问题'];
        }
        return Response::json($result);
    }

    //删除模板
    public function templateDelete() {
        $cus_id = Auth::id();
        $id = Input::get('id');
        $type = Input::get('type');
        $former_id = Template::where('cus_id', $cus_id)->where('id', $id)->where('type', $type)->pluck('former_id');
        $tpl_dir = Template::where('cus_id', $cus_id)->where('id', $id)->where('type', $type)->pluck('name');
        $del_result = Template::where('cus_id', $cus_id)->where('id', $id)->where('type', $type)->delete();
        if(empty($tpl_dir)){//找不到新编号找旧编号
            $tpl_dir = Template::where('cus_id', $cus_id)->where('id', $id)->where('type', $type)->pluck('name_bak');
        }
        if ($del_result) {
            if(!empty($tpl_dir)){//模板不为空才执行
                @$this->_remove_Dir(public_path("templates/$tpl_dir"));
                @$this->_remove_Dir(app_path("views/templates/$tpl_dir"));
            }            
            @$color_list = TemplateToColor::where('template_id', $former_id)->lists('color_id');
            if(empty($color_list)){
                $color_list[0] = 0;
            }
            if ($type == 1) {
                $update = ['pc_tpl_id' => $former_id, 'pc_color_id' => $color_list[0]];
            } else {
                $update = ['mobile_tpl_id' => $former_id, 'mobile_color_id' => $color_list[0]];
            }
            $update_result = WebsiteInfo::where('cus_id', $cus_id)->update($update);
            if ($update_result) {
                Template::where("name", $tpl_dir)->update(array("updated_at" => date("Y-m-d H:i:s", time())));
                if ($type == 1) {
                    $this->logsAdd('websiteinfo', __FUNCTION__, __CLASS__, 2, "删除PC模板，id：" . $former_id . "，颜色id：" . $color_list[0], 0, $id);
                } else {
                    $this->logsAdd('websiteinfo', __FUNCTION__, __CLASS__, 2, "删除手机模板，id：" . $former_id . "，颜色id：" . $color_list[0], 0, $id);
                }
                $result = ['err' => 0, 'msg' => ''];
            } else {
                $result = ['err' => 1001, 'msg' => '恢复原模板失败,需重新选择模板'];
            }
        } else {
            $result = ['err' => 1001, 'msg' => '删除模板失败'];
        }
        return Response::json($result);
    }

    public function copy() {
        $cus_id = Auth::id();
        $type = Input::get('type');
        $type_name = $type == 1 ? 'PC' : '手机';
        $customization = Customer::where('id', $cus_id)->pluck('customization');
        if ($customization <= 0)
            return Response::json(['err' => 1002, 'msg' => '您未开启' . $type_name . '高级定制服务!']);
        elseif ($type == 1) {
            if ($customization == 3 or $customization == 1) {
                $id = WebsiteInfo::where('cus_id', $cus_id)->pluck('pc_tpl_id');
                $had_name = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('template.cus_id', $cus_id)->pluck('name');
            } else
                return Response::json(['err' => 1002, 'msg' => '您未开启' . $type_name . '高级定制服务!']);
        }elseif ($type == 2) {
            if ($customization == 3 or $customization == 2) {
                $id = WebsiteInfo::where('cus_id', $cus_id)->pluck('mobile_tpl_id');
                $had_name = WebsiteInfo::leftJoin('template', 'mobile_tpl_id', '=', 'template.id')->where('template.cus_id', $cus_id)->pluck('name');
            } else
                return Response::json(['err' => 1002, 'msg' => '您未开启' . $type_name . '高级定制服务!']);
        }
        if (strstr($had_name, '_')) {
            return Response::json(['err' => 0, 'msg' => '高级定制模板已存在，载入成功！']);
        } else {
            $count = Template::where('type', $type)->where('cus_id', $cus_id)->count();
            if ($count >= 3) {
                return Response::json(['err' => 1001, 'msg' => '定制模版超过3个']);
            } else {
                $tpl_info = Template::where('type', $type)->where('id', $id)->where('cus_id', 0)->first();
                $name = $tpl_info->name;
                if(!is_dir(app_path('/views/templates/'.$name)) or !is_dir(public_path('/templates/'.$name))){
                    $name = $tpl_info->name_bak;
                }
                $new_name = $name . '_' . $cus_id;
                $template = new Template;
                $template->name = $new_name;
                $template->tpl_num = 0;
                $template->tpl_name = $tpl_info->tpl_name;
                $template->type = $type;
                $template->cus_id = $cus_id;
                $template->former_id = $id;
                $template->classify = $tpl_info->classify;
                $template->description = $tpl_info->description;
                $template->demo = $tpl_info->demo;
                $template->list1showtypetotal = $tpl_info->list1showtypetotal;
                $template->list2showtypetotal = $tpl_info->list2showtypetotal;
                $template->list3showtypetotal = $tpl_info->list3showtypetotal;
                $template->list4showtypetotal = $tpl_info->list4showtypetotal;
                $template->isColorful = $tpl_info->isColorful;
                $template->color_style = $tpl_info->color_style;
                $template->save();
                $insertedId = $template->id;
                $src = app_path('views/templates/' . $name);
                $dst = app_path('views/templates/' . $new_name);
                $src_public = public_path('templates/' . $name);
                $dst_public = public_path('templates/' . $new_name);
                $this->rcopy($src, $dst);
                $this->rcopy($src_public, $dst_public);
                //$img_src = public_path('admin/images/templates/'.$name.'.jpg');
                //$img_dst = public_path('admin/images/templates/'.$new_name.'.jpg');
                //$this->rcopy($img_src,$img_dst);
                $tpl = $type == 1 ? 'pc_tpl_id' : 'mobile_tpl_id';
                $update[$tpl] = $insertedId;
                $update = WebsiteInfo::where('cus_id', $cus_id)->update($update);
                return Response::json(['err' => 0, 'msg' => '']);
            }
        }
    }

    public function fileAdd() {
        $id = Auth::id();
        $type = Input::get('type');
        $filename = Input::get('filename');
        $filetype = Input::get('filetype');
        if ($type == 1)
            $name = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->pluck('name');
        else
            $name = WebsiteInfo::leftJoin('template', 'mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->pluck('name');
        switch ($filetype) {
            case 'html':
                $dst = app_path('views/templates/' . $name);
                file_put_contents($dst . '/' . $filename . '.html', '厦门易尔通携程为您服务');
                break;
            case 'css':
                $dst = public_path('templates/' . $name . '/css');
                file_put_contents($dst . '/' . $filename . '.css', '厦门易尔通携程为您服务');
                break;
            case 'js':
                $dst = public_path('templates/' . $name . '/js');
                file_put_contents($dst . '/' . $filename . '.js', '厦门易尔通携程为您服务');
                break;
            case 'json':
                $dst = public_path('templates/' . $name . '/json');
                file_put_contents($dst . '/' . $filename . '.json', '厦门易尔通携程为您服务');
                break;
            default:
                $error = 1;
                break;
        }
        if (isset($error))
            return Response::json(['err' => 1001, 'msg' => '错误的文件名后缀', 'data' => '']);
        else {
            Template::where("name", $name)->update(array("updated_at" => date("Y-m-d H:i:s", time())));
            return Response::json(['err' => 0, 'msg' => '新建文件成功', 'data' => '']);
        }
    }

    public function fileList() {
        $id = Auth::id();
        $type = Input::get('type');
        $customization = Customer::where('id', $id)->pluck('customization');
        if ($customization <= 0 or ( $customization != 3 && $customization != $type))
            return Response::json(['err' => 1001, 'msg' => '您未开启相应的高级定制，高级定制需要付费，如需要，请联系客服', 'data' => '您未开启高级定制，高级定制需要付费，如需要，请联系客服']);
        if ($type == 1) {
            $web_tpl = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->select('name' , 'isColorful' , 'pc_color')->first()->toArray();
            $name = $web_tpl['name'];
            $isColorful = $web_tpl['isColorful'];
            $color_dir = $web_tpl['pc_color'];
        } else {
            $web_tpl = WebsiteInfo::leftJoin('template', 'mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->select('name' , 'isColorful' , 'mobile_color')->first()->toArray();
            $name = $web_tpl['name'];
            $isColorful = $web_tpl['isColorful'];
            $color_dir = $web_tpl['mobile_color'];
        }            
        if (!strstr($name, "_"))
            return Response::json(['err' => 1002, 'msg' => '您已开启相应的高级定制，但未点亮相应的高级定制，请到页面编辑点亮', 'data' => '您已开启相应的高级定制，但未点亮相应的高级定制，请到页面编辑点亮']);
        $dst = app_path('views/templates/' . $name);
        // $dst_css = public_path('templates/' . $name . '/css');
        // $dst_js = public_path('templates/' . $name . '/js');
        $dst_json = public_path('templates/' . $name . '/json');
        if($isColorful == 1) {
            $dst_css = public_path('templates/' . $name . '/themes/' . $color_dir . '/css');
            $dst_js = public_path('templates/' . $name . '/themes/' . $color_dir . '/js');
        } else {
            $dst_css = public_path('templates/' . $name . '/css');
            $dst_js = public_path('templates/' . $name . '/js');
        }
        //css处理
        $css = $this->getFile($dst_css);
        $css_0['title'] = '颜色样式';
        $css_1['title'] = '整体样式';
        $css_0_file = [];
        $css_1_file = [];
        foreach ($css as $css_v) {
            if (preg_match('/style[_a-z]*.css/', $css_v)) {
                $css_0_file[] = $css_v;
            } else {
                $css_1_file[] = $css_v;
            }
        }
        $css_0['files'] = $css_0_file;
        $css_1['files'] = $css_1_file;
        //html和json
        $tpl = $this->getFile($dst);
        $json = $this->getFile($dst_json);
        $astrict = ['content-news' => 70,
            'content-product' => 80,
            '_footer' => 20,
            '_header' => 10,
            'index' => 0,
            'list-image' => 50,
            'list-imagetext' => 60,
            'list-page' => 30,
            'list-text' => 40];
        $tpl_0['title'] = '首页文件';
        $tpl_1['title'] = '头部文件';
        $tpl_2['title'] = '底部文件';
        $tpl_3['title'] = '内容单页';
        $tpl_4['title'] = '文字列表';
        $tpl_5['title'] = '图片列表';
        $tpl_6['title'] = '图文列表';
        $tpl_7['title'] = '新闻内容页';
        $tpl_8['title'] = '产品内容页';
        $tpl_file_0 = [];
        $tpl_file_1 = [];
        foreach ($tpl as $tpl_v) {
            $file_type = explode('.', $tpl_v);
            if (array_key_exists($file_type[0], $astrict)) {
                $tpl_file_0[$astrict[$file_type[0]]] = $tpl_v;
                if (array_search($file_type[0] . '.json', $json) !== false) {
                    $tpl_file_0[$astrict[$file_type[0]] + 1] = $file_type[0] . '.json';
                    $json_del = array_search($file_type[0] . '.json', $json);
                    unset($json[$json_del]);
                }
            } else {
                $tpl_file_1[] = $tpl_v;
                if (array_search($file_type[0] . '.json', $json) !== false) {
                    $tpl_file_1[] = $file_type[0] . '.json';
                    $json_del = array_search($file_type[0] . '.json', $json);
                    unset($json[$json_del]);
                }
            }
        }
        ksort($tpl_file_0);
        foreach ($tpl_file_0 as $key => $val) {
            if ($key)
                $num = intval($key / 10);
            else
                $num = $key;
            $title_num = 'tpl_' . $num;
            $filename['files'][$key] = $val;
            $$title_num = $this->superArrayMerge($$title_num, $filename);
            unset($filename);
        }
        $tpl_9['title'] = '模块文件';
        $tpl_9['files'] = array_merge($tpl_file_1, $json);
        //js
        $js['title'] = '脚本文件';
        $js['files'] = $this->getFile($dst_js);
        $files[0] = $css_0;
        $files[1] = $css_1;
        $files[2] = $js;
        for ($i = 0; $i < 9; $i++) {
            $title_num = 'tpl_' . $i;
            $files[$i + 3] = $$title_num;
            if (isset($files[$i + 3]['files'])) {
                $files[$i + 3]['files'] = array_merge($files[$i + 3]['files']);
            }
        }
        $files[12] = $tpl_9;
        $result = ['name' => $name, 'filenames' => $files];
        //dd($result);
        return Response::json(['err' => 0, 'msg' => '', 'data' => $result]);
    }

    public function fileget() {
        $id = Auth::id();
        $type = Input::get('type');
        $filename = Input::get('filename');
        $file_type = explode('.', $filename);
        $file_type = end($file_type);
        if ($type == 1) {
            $web_tpl = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->select('name' , 'isColorful' , 'pc_color')->first()->toArray();
            $name = $web_tpl['name'];
            $isColorful = $web_tpl['isColorful'];
            $color_dir = $web_tpl['pc_color'];
        } else {
            $web_tpl = WebsiteInfo::leftJoin('template', 'mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->select('name' , 'isColorful' , 'mobile_color')->first()->toArray();
            $name = $web_tpl['name'];
            $isColorful = $web_tpl['isColorful'];
            $color_dir = $web_tpl['mobile_color'];
        }
        if ($file_type == 'css') {
            if($isColorful == 1) {
                $dst = public_path('templates/' . $name . '/themes/' . $color_dir . '/css/' . $filename);
            } else {
                $dst = public_path('templates/' . $name . '/css/' . $filename);
            }            
        } elseif ($file_type == 'js') {
            if($isColorful == 1) {
                $dst = public_path('templates/' . $name . '/themes/' . $color_dir . '/js/' . $filename);
            } else {
                $dst = public_path('templates/' . $name . '/js/' . $filename);
            }            
        } elseif ($file_type == 'json') {
            $dst = public_path('templates/' . $name . '/json/' . $filename);
        } elseif ($file_type == 'html') {
            $dst = app_path('views/templates/' . $name . '/' . $filename);
        }
        $content = htmlentities(file_get_contents($dst));
        $result = ['filename' => $content, 'code' => $content];
        return Response::json(['err' => 0, 'msg' => '', 'data' => $result]);
    }

    public function fileedit() {
        $cus_id = Auth::id();
        $type = Input::get('type');
        $filename = Input::get('filename');
        $content = Input::get('code');
        $img_array = Input::get('fileimg');
        if ($img_array)
            $img_array = explode(',', $img_array);
        if ($type == 1) {
            $web_tpl = WebsiteInfo::join('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $cus_id)->select('name' , 'isColorful' , 'pc_color')->first()->toArray();
            $template = $web_tpl['name'];
            $isColorful = $web_tpl['isColorful'];
            $color_dir = $web_tpl['pc_color'];
        } else {
            $web_tpl = WebsiteInfo::join('template', 'mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', $cus_id)->select('name' , 'isColorful' , 'mobile_color')->first()->toArray();
            $template = $web_tpl['name'];
            $isColorful = $web_tpl['isColorful'];
            $color_dir = $web_tpl['mobile_color'];
        }
        $fail = [];
        if ($filename === NULL || $content === NULL) {
            $result = ['err' => 1001, 'msg' => '提交数据错误'];
        } else {
            if ($img_array) {
                foreach ($img_array as $val) {
                    if (strpos($content, $val)) {
                        if (file_exists(public_path('templates/' . $template . '/img_cache/' . $val))) {
                            rename(public_path('templates/' . $template . '/img_cache/' . $val), public_path('templates/' . $template . '/images/' . $val));
                        } else {
                            $fail[] = $val;
                        }
                    }
                }
            }
            $file_type = explode('.', $filename);
            $file_type = end($file_type);
            if ($file_type == 'css') {
                if($isColorful == 1) {
                    $dst = public_path('templates/' . $template . '/themes/' . $color_dir . '/css/' . $filename);
                } else {
                    $dst = public_path('templates/' . $template . '/css/' . $filename);
                }                
            } elseif ($file_type == 'js') {
                if($isColorful == 1) {
                    $dst = public_path('templates/' . $template . '/themes/' . $color_dir . '/js/' . $filename);
                } else {
                    $dst = public_path('templates/' . $template . '/js/' . $filename);
                }                
            } elseif ($file_type == 'json') {
                $dst = public_path('templates/' . $template . '/json/' . $filename);
            } else {
                if ($file_type == 'html') {
                    $dst = app_path('views/templates/' . $template . '/' . $filename);
                }
            }
            $pushed = WebsiteInfo::where("cus_id", $cus_id)->pluck("pushed");
            if ($type == 1) {
                if ($pushed == 0 || $pushed == 2) {
                    $pushed = 2;
                } else {
                    $pushed = 1;
                }
            } else {
                if ($pushed == 0 || $pushed == 3) {
                    $pushed = 3;
                } else {
                    $pushed = 1;
                }
            }
            WebsiteInfo::where("cus_id", $cus_id)->update(array("pushed" => $pushed));
            if (file_exists($dst)) {
                $edit = file_put_contents($dst, $content);
                if ($edit == FALSE) {
                    $result = ['err' => 1001, 'msg' => '无法编辑文件'];
                } else {
                    Template::where("name", $template)->update(array("updated_at" => date("Y-m-d H:i:s", time())));
                    $result = ['err' => 0, 'msg' => '', 'data' => $fail];
                }
            } else {
                $result = ['err' => 1001, 'msg' => '文件不存在'];
            }
        }
        return Response::json($result);
    }

    /*
     * 删除文件目录及其目录内的文件 
     */

    public function _remove_Dir($dirName) {
        if (!is_dir($dirName)) {
            return false;
        }
        $handle = @opendir($dirName);
        while (($file = @readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                $dir = $dirName . '/' . $file;
                is_dir($dir) ? $this->_remove_Dir($dir) : @unlink($dir);
            }
        }
        closedir($handle);
        return rmdir($dirName);
    }

    // 拷贝文件和非空目录
    public function rcopy($src, $dst) {
        if (is_dir($src)) {
            if (!file_exists($dst)) {
                mkdir($dst);
            }
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $this->rcopy("$src/$file", "$dst/$file");
                }
            }
        } else {
            if (file_exists($src)) {
                copy($src, $dst);
            }
        }
    }

    //高级定制中数组合并处理
    public function superArrayMerge($a, $b) {
        if (isset($a['files']))
            $a['files'] = array_merge($a['files'], $b['files']);
        else
            $a['files'] = $b['files'];
        return $a;
    }

    //获取文件列表
    public function getFile($dir) {
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

    //获取文件列表
    public function getDir($dir) {
        $fileArray[] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != "..") {
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

    /*
     * 上传模板 
     *
     */

    public function uploadTemplate() {
        set_time_limit(0);
        $file = Input::file('file');
        if ($file->isValid()) {
            //$truth_name=$file->getClientOriginalName();
            $type = $file->getClientOriginalExtension();
            $truth_name = date('ymd') . mt_rand(100, 999) . '.' . $type;
            if ($type == "zip") {
                if (file_exists(public_path("temp_templates/$truth_name"))) {
                    $result = ['err' => 1000, 'msg' => '模板上传成功'];
                } else {
                    $up_result = $file->move(public_path("temp_templates/"), $truth_name);
                    if ($up_result) {
                        $tpl = explode('.', $file->getClientOriginalName());
                        $tpl_name = $tpl[0];
                        //===判断命名方式===
                        // if(preg_match('/G\d{4}(P|M)(CN|EN|TW|JP)\d{2}/', $tpl_name)){
                        //     //查询对应的旧命名
                        //     $old_tpl_name = Template::where('name', $tpl_name)->pluck('name_bak');
                        //     if($old_tpl_name){
                        //         //删除旧命名的目录
                        //         $this->_remove_Dir(public_path("templates/$old_tpl_name"));
                        //         $this->_remove_Dir(app_path("views/templates/$old_tpl_name")); 
                        //     }                            
                        // }
                        //===end===
                        if (!empty($tpl_name)) {
                            $this->_remove_Dir(public_path("templates/$tpl_name"));
                            $this->_remove_Dir(app_path("views/templates/$tpl_name"));
                        }

                        $result = $this->saveTemplate($truth_name, $tpl_name);
                    } else {
                        $result = ['err' => 1001, 'msg' => '模板上传失败'];
                    }
                }
            } else {
                $result = ['err' => 1002, 'msg' => '模板上传失败，请上传正确的文件类型'];
            }
            return Response::json($result);
        }
    }

    //模板入库
    public function saveTemplate($truth_name, $tpl_name = '', $temptype = 0) {
        if ($tpl_name != '') {
            $tpl_exists = Template::where('name', $tpl_name)->first();
            if ($tpl_exists) {
                $unpack_resuslt = $this->unpack($truth_name, $tpl_name, true, $temptype);
            } else {
                $tpl_exists = false;
                $unpack_resuslt = $this->unpack($truth_name, $tpl_name, true, $temptype);
            }
        } else {
            $tpl_exists = false;
            $unpack_resuslt = $this->unpack($truth_name, $tpl_name, false, $temptype);
        }

        if ($unpack_resuslt) {
            $tpl_info = $unpack_resuslt['config'];
            if ($tpl_exists) {
                $template = Template::find($tpl_exists->id);
            } else {
                $template = new Template;
                $template->name = $unpack_resuslt['tpl_dir'];
                $template->name_bak = $unpack_resuslt['tpl_dir'];
                $template->tpl_num = $unpack_resuslt['tpl_num'];
            }
            $template->tpl_name = $tpl_info['template']['tpl_name'];
            $template->classify = $tpl_info['template']['classify'];
            $template->demo = $tpl_info['template']['demo'];
            $template->type = $tpl_info['template']['type'];
            $template->description = $tpl_info['template']['description'];
            $template->list1showtypetotal = $tpl_info['template']['list1showtypetotal'];
            $template->list2showtypetotal = $tpl_info['template']['list2showtypetotal'];
            $template->list3showtypetotal = $tpl_info['template']['list3showtypetotal'];
            $template->list4showtypetotal = $tpl_info['template']['list4showtypetotal'];
            $template->updated_at = date("Y-m-d H:i:s", time());
            $template->isColorful = $tpl_info['template']['isColorful'];
            $template->color_style = $tpl_info['template']['colorOption'];
            $insert_rst = $template->save();
//             if ($insert_rst) {
//                 $insert_id = $template->id;
//                 $color_arr = $tpl_info['tpl_color'];
//                 $tpl_color = array();
//                 $i = 0;
//                 TemplateToColor::where('template_id', $insert_id)->delete();
//                 if (count($color_arr) > 0) {
//                     foreach ($color_arr as $color) {
//                         $tpl_color[$i]['template_id'] = $insert_id;
//                         $tpl_color[$i]['color_code'] = Config::get('color.' . $color);
//                         $tpl_color[$i]['color_id'] = Color::where('color_en', $color)->pluck('id');
//                         $i++;
//                     }
// //                    TemplateToColor::insert($tpl_color); // bug插入数据库错误
//                 }
//             }
            $result = ['err' => 1000, 'msg' => '上传模板成功'];
            $this->logsAdd('n', __FUNCTION__, __CLASS__, 999, "模板上传", 0, $truth_name);
        } else {
            $result = ['err' => 1003, 'msg' => '解压文件失败'];
        }
        return $result;
    }

    //解包并分配模板文件
    private function unpack($tpl_pack, $tpl_name, $tpl_exists = false, $temptype = 0) {
        $zip = new ZipArchive;
        if ($zip->open(public_path("temp_templates/$tpl_pack")) === true) {
            $file_info = pathinfo($tpl_pack);
            @mkdir(public_path('temp_templates/' . $file_info['filename']));
//            for($i = 0; $i < $zip->numFiles; $i++) {
//                $filename = $zip->getNameIndex($i);
//                if(!mb_detect_encoding($filename,array('GBK'))){
//                    $filename= mb_convert_encoding($filename, 'GBK',mb_detect_encoding($filename));
//                }
//                dd(zip_read($filename));
//                $zip->extractTo(public_path('temp_templates/'.$file_info['filename']),$filename);
//            }

            $zip->extractTo(public_path('temp_templates/' . $file_info['filename']));
            $zip->close();
            if (file_exists(public_path('temp_templates/' . $file_info['filename'] . '/config.ini'))) {
                $dir_site = public_path('temp_templates/' . $file_info['filename']);
            } else {
                $child_dir = $this->getDir(public_path('temp_templates/' . $file_info['filename']) . '/');
                $dir_site = public_path('temp_templates/' . $file_info['filename'] . '/' . $child_dir[0]);
            }
            // 其他json文件验证
            $config_arr = parse_ini_file($dir_site . '/config.ini', true);
            if (!is_array($config_arr))
                dd('【config.ini】文件不存在！文件格式说明详见：http://pme.eexx.me/doku.php?id=ued:template:config');
            $type = $config_arr['Config']['Type'];
            if ($tpl_exists) {
                if (substr_count(strtolower($type), 'pc')) {
                    $type = 1;
                } else {
                    $type = 2;
                }
                if ($temptype != 0) {
                    if ($type != $temptype) {
                        return false;
                    }
                }
                $tpl_dir = $tpl_name;
                $new_num = '';
            } else {
                // if (substr_count(strtolower($type), 'pc')) {
                //     $type = 1;
                //     $tpl_dir = "GP";
                // } else {
                //     $type = 2;
                //     $tpl_dir = "GM";
                // }
                // $last_num = Template::where('type', $type)->max('tpl_num');
                // $new_num = $last_num + 1;
                // $tpl_dir = $tpl_dir . str_repeat('0', 4 - strlen($new_num)) . $new_num;
                return false;
            }

            //正则匹配tpl_num
            if (!isset($_SERVER['HTTP_REFERER'])) {
                // preg_match('/[A-Z]{2}[0]*(\d*)/', $tpl_name, $have);
                preg_match('/G[0]*(\d*)/', $tpl_name, $have);
                $new_num = $have[1];
            }

            //配置数据
            $data = array();
            $data['template'] = array(
                'tpl_name' => $config_arr['Template']['Name'],
                'classify' => $config_arr['Config']['Category'],
                'demo' => $config_arr['Template']['URL'],
                'type' => $type,
                'description' => $config_arr['Template']['Description'],
                'list1showtypetotal' => ($type == 1) ? 0 : $config_arr['Config']['List1ShowtypeTotal'],
                'list2showtypetotal' => ($type == 1) ? 0 : $config_arr['Config']['List2ShowtypeTotal'],
                'list3showtypetotal' => ($type == 1) ? 0 : $config_arr['Config']['List3ShowtypeTotal'],
                'list4showtypetotal' => ($type == 1) ? 0 : $config_arr['Config']['List4ShowtypeTotal'],
                'isColorful' => isset($config_arr['Config']['ColorOption']) ? ($config_arr['Config']['ColorOption'] ? 1 : 0) : 0,
                'colorOption' => isset($config_arr['Config']['ColorOption']) ? $config_arr['Config']['ColorOption'] : '',
            );
            if (trim($config_arr['Config']['StyleColors']) != "") {
                $color_arr = explode(',', $config_arr['Config']['StyleColors']);
            } else {
                $color_arr = array();
            }
            $data['tpl_color'] = $color_arr;
            @unlink(public_path("temp_templates/$tpl_pack"));
            @unlink($dir_site . '/preview.js');
            if (!file_exists(app_path("views/templates/$tpl_dir"))) {
                mkdir(app_path("views/templates/$tpl_dir"));
            }
            $this->rcopy($dir_site, public_path("templates/$tpl_dir"));
            if (isset($config_arr['Config']['ColorOption'])) {
                $this->imgUrls($tpl_name);
                // $this->imgUrl($tpl_name);
            } else {
                $this->imgUrl($tpl_name);
            }            
            @$this->_remove_Dir(public_path('temp_templates/' . $file_info['filename']));
            $file_list = $this->getFile(public_path("templates/$tpl_dir"));
            if (!file_exists(public_path("templates/$tpl_dir/json"))) {
                mkdir(public_path("templates/$tpl_dir/json"));
            }
            foreach ($file_list as $file_name) {
                $file_type = explode('.', $file_name);
                $file_type = end($file_type);
                switch ($file_type) {
                    case "json" :
                        rename(public_path("templates/$tpl_dir/$file_name"), public_path("templates/$tpl_dir/json/$file_name"));
                        break;
                    case "html" :
                        $pattern = array("/\<script(.*)preview\.js(.*)\<\/script\>/i","/\<script(.*)preview-url\.js(.*)\<\/script\>/i", "/\<!--(.*)\{(.*)\}(.*)--\>/i");
                        file_put_contents(public_path("templates/$tpl_dir/$file_name"), preg_replace($pattern, '', file_get_contents(public_path("templates/$tpl_dir/$file_name"))));
                        rename(public_path("templates/$tpl_dir/$file_name"), app_path("views/templates/$tpl_dir/$file_name"));
                        break;
                }
            }
            return array('tpl_dir' => $tpl_dir, 'tpl_num' => $new_num, 'config' => $data);
        } else {
            @unlink(public_path("temp_templates/$tpl_pack"));
            return false;
        }
    }

    ###############################################

    public function pushFile() {
        $cus_id = Auth::id();
        $customerinfo = CustomerInfo::where('cus_id', $cus_id)->first();
        $pushed_at = strtotime($customerinfo->pushed_at);
        $updated_at = strtotime($customerinfo->updated_at);
        if ($pushed_at == NULL || $pushed_at < $updated_at) {//整站重新生成html
            //只需推送$customer.'.zip',不生成$customer.'_update.zip'
        } else {
            $pc_classify_updated = FALSE;
            $mobile_classify_updated = FALSE;
            $pc_classify_updated_at = Classify::where('cus_id', $cus_id)->where('pc_show', 1)->lists('updated_at');
            $mobile_classify_updated_at = Classify::where('cus_id', $cus_id)->where('mobile_show', 1)->lists('updated_at');
            foreach ($pc_classify_updated_at as $v) {
                if ($pushed_at < strtotime($v)) {
                    $pc_classify_updated = TRUE;
                    break;
                }
            }
            foreach ($mobile_classify_updated_at as $v) {
                if ($pushed_at < strtotime($v)) {
                    $mobile_classify_updated = TRUE;
                    break;
                }
            }
            /*
              /*找出PC生成html的部分
             */
            if ($pc_classify_updated || 'PC模版更换') {//PC所有页面重新生成html
            } else {
                $pc_article_updated_at = Articles::where('cus_id', $cus_id)->where('pc_show', 1)->lists('updated_at');
                $cids = explode(',', $this->getChirldenCid($v['value']['id'])); //取得所有栏目id
                //PC文章修改--->其栏目、父级及长辈级栏目、文章页重新生成html
                //PC模版颜色更换
            }

            /*
              /*找出手机生成html的部分
             */
            if ($pc_classify_updated) {//手机所有页面重新生成html
            } else {
                //手机文章修改--->其栏目、父级及长辈级栏目、文章页重新生成html
                //手机模版颜色更换
            }
            //更新$customer.'.zip'，创建$customer.'_update.zip'，推送$customer.'_update.zip'，删除$customer.'_update.zip'
        }
    }

    public function templateUploadZip() {
        set_time_limit(0);
        $files = Input::file();
        $cus_id = Auth::id();
        $temptype = Input::get("type");
        $customization = Customer::where('id', $cus_id)->pluck('customization');
        if ($customization <= 0 || ($customization != 3 && $customization != $temptype))
            return Response::json(['err' => 1001, 'msg' => '您未开启相应的高级定制，高级定制需要付费，如需要，请联系客服', 'data' => '您未开启高级定制，高级定制需要付费，如需要，请联系客服']);
        $files = Input::file();
        $file = $files['upload_zip'];
        if ($file->isValid()) {
            $type = $file->getClientOriginalExtension();
            $truth_name = date('ymd') . mt_rand(100, 999) . '.' . $type;
            if ($type == "zip") {
                if (file_exists(public_path("temp_templates/$truth_name"))) {
                    $this->logsAdd('n', __FUNCTION__, __CLASS__, 999, "高级定制模板上传", 0, $truth_name);
                    $result = ['err' => 1000, 'msg' => '模板覆盖成功'];
                } else {
                    $up_result = $file->move(public_path("temp_templates/"), $truth_name);
                    if ($up_result) {
                        if ($temptype == 1)
                            $name = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $cus_id)->pluck('name');
                        else
                            $name = WebsiteInfo::leftJoin('template', 'mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', $cus_id)->pluck('name');
                        $tpl_name = $name;
                        $result = $this->saveTemplate($truth_name, $tpl_name, $temptype);
                        $pushed = WebsiteInfo::where("cus_id", $cus_id)->pluck("pushed");
                        if ($temptype == 1) {
                            if ($pushed == 0 || $pushed == 2) {
                                $pushed = 2;
                            } else {
                                $pushed = 1;
                            }
                        } else {
                            if ($pushed == 0 || $pushed == 3) {
                                $pushed = 3;
                            } else {
                                $pushed = 1;
                            }
                        }
                        WebsiteInfo::where("cus_id", $cus_id)->update(array("pushed" => $pushed));
                    } else {
                        $result = ['err' => 1001, 'msg' => '模板覆盖失败'];
                    }
                }
            } else {
                $result = ['err' => 1002, 'msg' => '模板覆盖失败，请上传正确的文件类型'];
            }
        } else {
            $result = ['err' => 1002, 'msg' => '模板覆盖失败，请上传正确的文件类型'];
        }
        return Response::json($result);
    }

    //模板官网图片上传
    public function imgUrl($tpl_name){
        $template = public_path("templates/".$tpl_name);
        $i_path = public_path('customers/gbpen/img_url');
        $destinationPath = 'gbpen/img_url/' . $tpl_name . '_screenshot.jpg';//大图在静态的路径
        $s_path = public_path('customers/gbpen/img_urls');
        $s_target = 'gbpen/img_urls/' . $tpl_name . '_screenshot.jpg';//缩略图在静态的路径
        if(!is_dir($i_path)){
            @mkdir($i_path);
        }
        if(!is_dir($s_path)){
            @mkdir($s_path);
        }
        if(is_dir($template)){
            $img = $template.'/screenshot.jpg';//大图源路径
            if(file_exists($img)){
                copy($img, $i_path . '/' . $tpl_name . '_screenshot.jpg');//大图在主控的路径
                $s_img = $s_path . '/' . $tpl_name . '_screenshot.jpg';//缩略图在主控的路径
                $this->resizeImgs($img,$s_img);//生成缩略图
                $conn = ftp_connect('172.16.0.4', 21);
                if($conn){
                    ftp_login($conn, 'tongYi', 'B164RLFh');
                    ftp_pasv($conn, 1);
                    ftp_put($conn, $destinationPath , $img, FTP_BINARY);
                    ftp_put($conn, $s_target , $s_img, FTP_BINARY);
                    ftp_close($conn);
                }
                $conn_b = ftp_connect('172.16.0.20', 21);
                if($conn_b){
                    ftp_login($conn_b, 'tongyi', 'B164RLFh');
                    ftp_pasv($conn_b, 1);
                    ftp_put($conn_b, $destinationPath , $img, FTP_BINARY);
                    ftp_put($conn_b, $s_target , $s_img, FTP_BINARY);
                    ftp_close($conn_b);
                }
            }
        }
    }
    //官网缩略图生成
    public function resizeImgs($src, $path){
        $image = @imagecreatefromjpeg($src);
        //如果不是jpg格式的话
        if(!$image) {
            $image = imagecreatefrompng($src);
        }
        $width = imagesx($image);
        $height = imagesy($image);
        if($height>1000){
            $height = 1000;
        }
        $canvas = imagecreatetruecolor($width, $height);
        $alpha = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $alpha);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        imagesavealpha($canvas, true);
        imagejpeg($canvas, $path, 10);
        imagedestroy($canvas);
    }
    //换色模板上传
    public function imgUrls($tpl_name) {
        $template = public_path("templates/".$tpl_name);
        $zip = new ZipArchive;
        $zname = 'model_' . str_random(4) . '.zip';
        $path = public_path('customers/gbpen/' . $zname);//压缩包名
        $destinationPath = '/gbpen/' . $zname;//压缩包上传至服务器路径
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            $handle = opendir($template);
            while (($filename = readdir($handle)) !== FALSE) {
                if ($filename == '.' || $filename == '..' || !strpos($filename, '_screenshot.jpg')) {
                    continue;
                } else {
                    $img = $template . '/' . $filename;//大图源路径
                    $name = $tpl_name . '_' . $filename;//图片文件名
                    $i_path = public_path('customers/gbpen/img_url') . '/' . $name;
                    copy($img, $i_path);
                    $s_path = public_path('customers/gbpen/img_urls') . '/' . $name;//缩略图在主控的路径
                    $this->resizeImgs($i_path,$s_path);//生成缩略图
                    $zip->addFile($i_path, 'img_url/' . $name);
                    $zip->addFile($s_path, 'img_urls/' . $name);
                }
            }
            closedir($handle);
            $zip->close();
            //上传到服务器
            if(file_exists($path)) {
                $conn = ftp_connect('172.16.0.4', 21);
                if($conn){
                    ftp_login($conn, 'tongYi', 'B164RLFh');
                    ftp_pasv($conn, 1);
                    ftp_put($conn, $destinationPath , $path, FTP_BINARY);
                    ftp_put($conn, "/gbpen/model_unzip.php", public_path("packages/model_unzip.php"), FTP_ASCII);
                    ftp_close($conn);
                }
                $conn_b = ftp_connect('172.16.0.20', 21);
                if($conn_b){
                    ftp_login($conn_b, 'tongyi', 'B164RLFh');
                    ftp_pasv($conn_b, 1);
                    ftp_put($conn_b, $destinationPath , $path, FTP_BINARY);
                    ftp_put($conn_b, "/gbpen/model_unzip.php", public_path("packages/model_unzip.php"), FTP_ASCII);
                    ftp_close($conn_b);
                }
                file_get_contents("http://172.16.0.4/gbpen/model_unzip.php?model=" . $zname);
                file_get_contents("http://172.16.0.20/gbpen/model_unzip.php?model=" . $zname);
            }            
        }        
    }
}
