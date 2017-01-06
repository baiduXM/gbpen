<?php

/*
 * 万用表单控制器
 * @author xieqixiang
 * 
 * 数据库存储原理
 * form 表单名
 * form_element 表单元素
 * form_column_n (n=0,..,9) 表单列名，根据表单id尾数存储在对于表中，例：form_id=5,添加的列名存储在form_column_5表中
 * form_data_n (n=0,...,9) 表单数据 原理同上，存储在交互服务器上
 */

class FormController extends BaseController {

    /**
     * 表单列表
     * @param type $param 参数可能是数组
     * @return type
     */
    public function getFormList($type = null) {
        $where = Input::all();
        $where['cus_id'] = Auth::id();
        $data = DB::table('form')->where($where)->get();
        //===返回数据===
        if ($data != NULL) {
            $res = Response::json(['err' => 0, 'msg' => '获取表单列表成功', 'data' => $data]);
        } else {
            $res = Response::json(['err' => 0, 'msg' => '表单列表为空', 'data' => null]);
        }
        return $res;
    }

    /**
     * 创建表单
     */
    public function createForm() {
        $name = Input::get('name');
        $platform = Input::get('platform');
        $showmodel = Input::get('showmodel');

        $time = date('Y-m-d H:i:s');
        $cus_id = Auth::id();
        $data = array(
            'cus_id' => $cus_id,
            'name' => $name,
            'platform' => $platform,
            'created_at' => $time,
            'updated_at' => $time,
            'showmodel' => $showmodel
        );
        $id = DB::table('form')->insertGetId($data);
        //===返回数据===
        if ($id != NULL) {
            $this->logsAdd("form",__FUNCTION__,__CLASS__,1,"创建万用表单",0,$id);
            $res = Response::json(['err' => 0, 'msg' => '创建表单成功', 'data' => $id]);
        } else {
            $res = Response::json(['err' => 1, 'msg' => '创建表单失败', 'data' => null]);
        }
        return $res;
    }

    /**
     * 删除表单
     */
    public function deleteForm() {
        $form_id = Input::get('form_id');
        //判断是否有栏目关联表单
        $classify = DB::table('classify')->where('form_id', $form_id)->get();
        if (!empty($classify)) {
            return Response::json(['err' => 1, 'msg' => '请先解除关联栏目', 'data' => $classify]);
        }
        //表单删除连同表单组件和用户数据一起删除
        $res1 = DB::table('form_column_' . $form_id % 10)->where('form_id', $form_id)->delete();
        $param['form_id'] = $form_id;
        $param['id'] = $form_id;
        $param['flag'] = 2;
        $param['host'] = $_SERVER['HTTP_HOST'];
        $postFun = new CommonController;
        $res2 = $postFun->postsend("http://swap.5067.org/admin/form_userdata_delete.php", $param);
//		$res2 = DB::table('form_data_' . $form_id % 10)->where('form_id', $form_id)->delete();
        $res3 = DB::table('form')->where('id', $form_id)->delete();
        //返回数据
        if ($res3) {
            $this->logsAdd('form',__FUNCTION__,__CLASS__,2,"删除万用表单及其所属组件",0,$form_id);
            $json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res3]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '删除失败', 'data' => '']);
        }
        return $json;
    }

    /**
     * 保存表单信息
     */
    public function editForm() {
        $form_id = Input::get('form_id');
        $form_data = Input::get('box_info');
        $time = date('Y-m-d H:i:s');
        $data = array();
        $tag = 'text';
        foreach ($form_data as $v) {
            if (!isset($data[$v['name']])) {
                if ($v['name'] == 'action_type') {
                    if ($v['value'] == 0) {
                        $tag = 'text';
                    }
                    if ($v['value'] == 1) {
                        $tag = 'url';
                    }
                    $data[$v['name']] = $v['value'];
                } else if ($v['name'] == 'action_text') {
                    if ($tag == 'text') {
                        $data['action_text'] = $v['value'];
                    }
                    if ($tag == 'url') {
                        $data['action_url'] = $v['value'];
                    }
                } else {
                    $data[$v['name']] = $v['value'];
                }
            } else {
                $data[$v['name']] = $data[$v['name']] . ',' . $v['value']; //合并checkbox选项值
            }
        }
        $data['is_once'] = isset($data['is_once']) ? 1 : 0;
        $data['updated_at'] = $time;

        $res = DB::table('form')->where('id', $form_id)->update($data);
        //===返回数据===
        if ($res != NULL) {
            $this->logsAdd('form',__FUNCTION__,__CLASS__,3,"修改万用表单信息",0,$form_id);
            $json = Response::json(['err' => 0, 'msg' => '更新表单信息成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '更新表单信息失败', 'data' => null]);
        }
        return $json;
    }

    /**
     * 获取表单信息
     * @return type
     */
    public function getFormData() {
        $cus_id = Auth::id();
        $form_id = Input::get('form_id');
        $res = DB::table('form')->where('id', $form_id)->where('cus_id', $cus_id)->first();
        if ($res != NULL) {
            $json = Response::json(['err' => 0, 'msg' => '获取表单信息成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '获取表单信息失败', 'data' => null]);
        }
        return $json;
    }

    /**
     * 根据表单ids查找表单信息
     * @param array $ids
     */
    public function getFormInfoByIds($ids) {
        $formArray = array();
        $formInfo = DB::table('form')->whereIn('id', $ids)->where('status', 1)->get();
        foreach ($formInfo as $value) {
            $value->data = DB::table('form_column_' . $value->id % 10)->where('form_id', $value->id)->orderBy('order', 'asc')->get();
            foreach ($value->data as $vk => &$vv) {
                $vv->config = unserialize($vv->config);
            }
            $formArray[$value->id] = $value;
        }
        return $formArray;
    }

    /**
     * 浏览表单信息（不需要是自己的表单）
     */
    public function getFormView() {
        $form_id = Input::get('form_id');
        $res = DB::table('form')->where('id', $form_id)->first();
        if ($res != NULL) {
            $json = Response::json(['err' => 0, 'msg' => '获取表单信息成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '获取表单信息失败', 'data' => null]);
        }
        return $json;
    }

    /**
     * 获取组件元素
     */
    public function getFormElementList() {
        $data = DB::table('form_element')->where('status', 1)->get();
        if ($data != NULL) {
            $res = Response::json(['err' => 0, 'msg' => '组件元素列表获取成功', 'data' => $data]);
        } else {
            $res = Response::json(['err' => 0, 'msg' => '组件元素列表为空', 'data' => null]);
        }
        return $res;
    }

    /**
     * 获取表单详细信息
     */
    public function getFormColumnList() {
        $form_id = Input::get('form_id');
        $res = DB::table('form_column_' . $form_id % 10)->where('form_id', $form_id)->orderBy('order', 'asc')->get();
        foreach ($res as &$v) {
            $v->config = unserialize($v->config);
            $v->column_id = $v->id;
        }
        if ($res != NULL) {
            $json = Response::json(['err' => 0, 'msg' => '获取表单组件数据列表成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 0, 'msg' => '获取表单组件数据列表为空', 'data' => null]);
        }
        return $json;
    }

    /**
     * 编辑组件元素（获取信息）
     */
    public function getFormColumn() {
        //===获取参数===
        $form_id = Input::get('form_id');
        $column_id = Input::get('column_id');
        //===获取数据、处理数据===
        $column_data = DB::table('form_column_' . $form_id % 10)->orderBy('order', 'asc')->where('id', $column_id)->first();
        $column_data->column_id = $column_data->id;
        $column_data->config = unserialize($column_data->config);
        //===返回数据===
        if ($column_data != NULL) {
            $res = Response::json(['err' => 0, 'msg' => '获取组件元素成功', 'data' => $column_data]);
        } else {
            $res = Response::json(['err' => 1, 'msg' => '获取组件元素失败', 'data' => '']);
        }
        return $res;
    }

    /**
     * 添加组件元素
     */
    public function addFormColumn() {

        //===获取参数===
        $form_id = Input::get('form_id');
        $element_id = Input::get('element_id');
        Classify::where('form_id', $form_id)->update(['pushed' => 1]);
        //===获取元素===
        $element_data = DB::table('form_element')->where('id', $element_id)->first();
        //===添加数据===
        $time = date('Y-m-d H:i:s');
        $column_data = array(
            'form_id' => $form_id,
            'title' => $element_data->title,
            'description' => $element_data->description,
            'type' => $element_data->type,
            'required' => '0',
            'config' => $element_data->config,
            'order' => '100', //默认排序100
            'created_at' => $time,
            'updated_at' => $time
        );
        $column_id = DB::table('form_column_' . $form_id % 10)->insertGetId($column_data);
        $column_data['config'] = unserialize($element_data->config);
        $column_data['column_id'] = $column_id;
        //===返回数据===
        if ($column_id != NULL) {
            $this->logsAdd('form_column_' . $form_id % 10,__FUNCTION__,__CLASS__,1,"添加万用表单组件,所属表单id：".$form_id.",组件类型：".$element_data->type,0,$column_id);
            $json = Response::json(['err' => 0, 'msg' => '添加组件成功', 'data' => $column_data]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '添加组件失败', 'data' => null]);
        }
        return $json;
    }

    /**
     * 保存组件信息
     */
    public function editFormColumn() {
        //===获取参数===
        $form_id = Input::get('form_id');
        $data = Input::get('data');
        $column_id = Input::get('column_id');
        $type = Input::get('type');
        Classify::where('form_id', $form_id)->update(['pushed' => 1]);
        $redata = array();
        $config = array();
        foreach ($data as $v) {
            if (preg_match('/^config_option_/', $v['name'])) {
                $option_key[] = preg_replace('/^config_option_/', '', $v['name']);
            }
            if (strstr($v['name'], 'config_')) {
                $ckey = substr($v['name'], 7);
                if (!isset($config[$ckey])) {
                    $config[$ckey] = $v['value'];
                } else {
                    $config[$ckey] .= ',' . $v['value']; //合并checkbox选项值
                }
            } else {
                $redata[$v['name']] = $v['value'];
            }
        }
//        $fun_name = 'edit_' . $type;
//        $config = $this->$fun_name('data'); //动态调用方法
        if ($type == 'select' || $type == 'radio' || $type == 'checkbox') {
            $config['option_key'] = implode(',', $option_key);
        }
        $time = date('Y-m-d H:i:s');
        $column_data = array(
            'title' => $redata['title'],
            'description' => $redata['description'],
            'required' => isset($redata['required']) ? 1 : 0,
            'config' => serialize($config),
            'updated_at' => $time
        );
        $res = DB::table('form_column_' . $form_id % 10)->where('id', $column_id)->update($column_data);
        $column_data['column_id'] = $column_id;
        $column_data['type'] = $type;
        $column_data['config'] = $config;

        if ($res != NULL) {
            $this->logsAdd('form_column_' . $form_id % 10,__FUNCTION__,__CLASS__,3,"修改万用表单组件信息,所属表单id：".$form_id.",组件类型：".$type,0,$column_id);
            $json = Response::json(['err' => 0, 'msg' => '保存组件信息成功', 'data' => $column_data]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '保存组件信息失败', 'data' => null]);
        }
        return $json;
    }

    /**
     * text_type 文本类型 text-文本 password-密码
     * text_rules_regex 正则规则 
     * text_rules_hint 正则提示
     */
    public function edit_text($data) {
        $config = array();
        return $config;
    }

    /**
     * 
     */
    function edit_textarea($data) {
        $config = array();
        return $config;
    }

    /**
     * option_layout 选项排版分布 1-单列 2-两列 3-三列 4-四列
     * option_type 选项类型 1-文字 2-图片
     * option_img_$i 选项图片 
     */
    function edit_radio($data) {
        $config = array();
        return $config;
    }

    /**
     * option_limit 选项是否限制
     * option_type 选项限制 >=至少 <=最多 =恰好
     * option_num 选项限制数
     */
    function edit_checkbox($data) {
        $config = array();
        return $config;
    }

    /**
     * option_default 选项默认值 0 or 0,1...
     * option_$i 选项值 i项
     * option_count 选项个数	
     */
    function edit_select($data) {
        $config = array();
        return $config;
    }

    /**
     * 
     */
    function edit_date($data) {
        $config = array();
        return $config;
    }

    /**
     * img_type 图片类型 1-本地图片 2-外链图片
     * img_src 图片http地址
     * img_file 图片路径
     * img_href 图片点击跳转链接 http地址
     * img_align 图片显示方式 1-拉伸 2-居中
     */
    function edit_image($data) {
        $config = array();
        return $config;
    }

    /**
     * 
     */
    function edit_file($data) {
        $config = array();
        return $config;
    }

    /**
     * 
     */
    function edit_address($data) {
        $config = array();
        return $config;
    }

    /**
     * 删除组件信息
     */
    public function deleteFormColumn() {
        $form_id = Input::get('form_id');
        $column_id = Input::get('column_id');
        Classify::where('form_id', $form_id)->update(['pushed' => 1]);
        $res = DB::table('form_column_' . $form_id % 10)->where('id', $column_id)->delete();
        if ($res != NULL) {
            $this->logsAdd('form_column_' . $form_id % 10,__FUNCTION__,__CLASS__,2,"删除万用表单组件,所属表单id：".$form_id,0,$column_id);
            $json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '删除组件失败', 'data' => '']);
        }
        return $json;
    }

    /**
     * 获取用户数据列表
     * 从交互服务器180.76.148.39中获取数据
     */
    public function getFormUserdataList() {
        $form_id = Input::get('form_id');
        $param['form_id'] = $form_id;
        $param['host'] = $_SERVER['HTTP_HOST'];
        $postFun = new CommonController;
        $res = $postFun->postsend("http://swap.5067.org/admin/form_userdata_list.php", $param);
        if (!empty($res)) {
            $res = json_decode($res);
            foreach ($res as $key => &$value) {
                $value->data = unserialize($value->data);
            }
            $json = Response::json(['err' => 0, 'msg' => '用户数据获取成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 0, 'msg' => '用户数据为空', 'data' => null]);
        }
        return $json;
    }

    /**
     * 获取用户数据
     * 从交互服务器180.76.148.39中获取数据
     */
    public function getFormUserdata() {
        $form_id = Input::get('form_id');
        $id = Input::get('id');
        $param['form_id'] = $form_id;
        $param['id'] = $id;
        $param['host'] = $_SERVER['HTTP_HOST'];
        $postFun = new CommonController;
        $res = $postFun->postsend("http://swap.5067.org/admin/form_userdata.php", $param);
        $res = json_decode($res);

        if ($res != NULL) {
            $json = Response::json(['err' => 0, 'msg' => '用户详细数据获取成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '用户详细数据获取失败', 'data' => '']);
        }
        return $json;
    }

    /**
     * 删除用户数据
     */
    public function deleteFormUserdata() {
        $form_id = Input::get('form_id');
        $id = Input::get('id');
        $param['form_id'] = $form_id;
        $param['id'] = $id;
        $param['flag'] = 1;
        $param['host'] = $_SERVER['HTTP_HOST'];
        Classify::where('form_id', $form_id)->update(['pushed' => 1]);
//		$res = DB::table('form_data_' . $form_id % 10)->where('id', $id)->delete();
        $postFun = new CommonController;
        $res = $postFun->postsend("http://swap.5067.org/admin/form_userdata_delete.php", $param);
        if ($res == 1) {
            $this->logsAdd('null',__FUNCTION__,__CLASS__,2,"删除用户表单数据,所属表单id：".$form_id,0,$column_id);
            $json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
        } else {
            $json = Response::json(['err' => 1, 'msg' => '删除失败', 'data' => '']);
        }
        return $json;
    }

    /**
     * 获取表单信息 for PrintContorller
     */
    public function getFormdataForPrint($form_id = 0) {
        $form_data = DB::table('form')->where('id', $form_id)->where('status', 1)->first();
        if (empty($form_data)) {
            $data = array();
        } else {
            $column_data = DB::table('form_column_' . $form_id % 10)->where('form_id', $form_id)->orderBy('order', 'asc')->get();
            foreach ($column_data as &$v) {
                $v->config = unserialize($v->config);
            }
            $data['form'] = $form_data;
            $data['column'] = $column_data;
        }
        return $data;
    }

    /**
     * 显示表单前端
     * @param type $data
     * @param type $site
     * @return string
     */
    public function showFormHtmlForPrint($data = null, $site = 'page') {
        $_form = '';
        if (empty($data)) {
            $_form .= "<div class='fv-add-show'>
                        <div class='fv-as-description'>
                                表单已停用
                        </div>
                    <hr></div>";
        } else {
            $form_data = $data['form'];
            $tempform['action_type'] = $form_data->action_type;
            if ($form_data->action_type == 1) {
                $tempform['action_text'] = $form_data->action_url;
            } else if ($form_data->action_type == 0) {
                $tempform['action_text'] = $form_data->action_text;
            }
            $form_id = $form_data->id;
            $column_data = $data['column'];
            $_div = '';
            if (empty($site) || $site == 'page') {//===普通表单===
                $_form.="<div class='fv-add-show add-show' >";
            } elseif ($site == 'float') {//===悬浮表单===
                $_form.="<div class='adv-add-show add-show' >";
            }
            $_form .= "<div class='fv-as-title'>
                            $form_data->title
                    </div>
                    <div class='fv-as-description'>
                            $form_data->description
                    </div>
                    <hr>";
            foreach ($column_data as $item) {
                $_div .= "<li class='list-item' data-type=$item->type data-id=$item->id >";
                $func = 'show_html_' . $item->type;
                $_div .= $this->$func($item);
                $_div.="</li>";
            }
            $_div .= "</ul><div style='text-align:center;'>"
                    . "<input type='submit' value='提交' class='button form-btn' name='submit' />"
//                    . "<button id='sbok'>提交</button>"
                    . "<input type='reset' value='重置' class='button form-btn' /></div>"
                    . "<input type='hidden' name='form_id' value='$form_id' />"
                    . "<input type='hidden' name='action_type' value='$form_data->action_type' />"
                    . "<input type='hidden' name='action_text' value=" . $tempform['action_text'] . " />";

            if (empty($site) || $site == 'page') {//===普通表单===
                $_form.="<form class='fv-unit-preview unit-preview' id='box_show' action='http://swap.5067.org/userdata/' method='post' >"
                        . "<ul class='fv-element-show'>";
            } elseif ($site == 'float') {//===悬浮表单===
                $_form.="<form class='adv-unit-preview unit-preview' id='box_show' action='http://swap.5067.org/userdata/' method='post' style='width:100%;'>"
                        . "<ul class='fv-element-show'>";
            }
            $_form.=$_div . "</form></div>";
//            $_form.=$js;
        }
        return $_form;
    }

    /**
     * 验证表单项
     * @param type $item
     * @return type
     */
    function validata($item) {
        $config = $item->config;
        $_vali = '';
        if ($item->required) {
            $_vali['required'] = true;
        }
        if (!empty($config[rules])) {
            $_vali[$config[rules]] = true;
        }
        return $_vali;
    }

    /**
     * 赋值表单前端css/js
     */
    public function assignFormCSSandJSForPrint() {
        $css = '<link rel="stylesheet" href="http://swap.5067.org/css/universal-form.css">';
//        $css .= '<link rel="stylesheet" href="http://swap.5067.org/js/laydate/need/laydate.css">';
        $js = '<script src="http://swap.5067.org/js/universal-form.js"></script>';
        $js .= '<script src="http://swap.5067.org/js/laydate/laydate.js"></script>';
        return $css . $js;
    }

    function show_html_text($data) {
        $item = $data;
        $config = $data->config;
        $_div = '';
        $_div .= "<p class='content-l'>$item->title";
        if ($item->required == 1) {
            $_div .= "<span style='color:red;'>*</span>";
        }
        $_div .= "</p>";
        $_div .= "<input type='$config[rules]' name='$item->title' placeholder='$item->description'";
        $_div .= $item->required == 1 ? "required" : '';
        $_div .= "/>";
        return $_div;
    }

    function show_html_textarea($data) {
        $item = $data;
        $config = $data->config;
        $_div = '';
        $_div .= "<p class='content-l'>$item->title";
        if ($item->required == 1) {
            $_div .= "<span style='color:red;'>*</span>";
        }
        $_div .= "</p>";
        $_div .= "<textarea name = '$item->title' placeholder = '$item->description'";
        $_div .= $item->required == 1 ? "required='required'" : '';
        $_div .= "></textarea>";
        return $_div;
    }

    function show_html_radio($data) {
        $item = $data;
        $config = $data->config;
        $_div = '';
        $_div .= "<p class='content-l'>$item->title";
        if ($item->required == 1) {
            $_div .= "<span style='color:red;'>*</span>";
        }
        if ($item->description != '' && $item->description != null) {
            $_div .= '<i class="content-d">' . $item->description . '</i>';
        }
        $_div .="</p>";
        $option_key = explode(',', $config['option_key']);
        foreach ($option_key as $key => $value) {
            $to = "option_$value";
            $_div .= '<span class="option-item">';
            $_div .= "<input type = 'radio' name = '$item->title' value = '$config[$to]' data-value='$value'";
            $_div .= $item->required == 1 ? "required='required'" : '';
            $_div .= " /><label>" . $config[$to] . " </label>";
            $_div .= '</span>';
        }
        return $_div;
    }

    function show_html_checkbox($data) {
        $item = $data;
        $config = $data->config;
        $_div = '';
        $_div .= "<p class='content-l'>$item->title";
        if ($item->required == 1) {
            $_div .= "<span style='color:red;'>*</span>";
        }
        if ($item->description != '' && $item->description != null) {
            $_div .= '<i class="content-d">' . $item->description . '</i>';
        }
        $_div .="</p>";
        $option_key = explode(',', $config['option_key']);
        $_div .= "<input type='hidden' name = '$item->title' value = '' data-value='' />";
        foreach ($option_key as $key => $value) {
            $to = "option_$value";
            $_div .= '<span class="option-item">';
            $_div .= "<input type = 'checkbox' name = '$item->title[]' value = '$config[$to]' data-value='$value' ";
            $_div .= " /><label>" . $config[$to] . " </label>";
            $_div .= '</span>';
        }
        return $_div;
    }

    function show_html_select($data) {
        $item = $data;
        $config = $data->config;
        $_div = '';
        $_div .= "<p class='content-l'>$item->title";
        if ($item->required == 1) {
            $_div .= "<span style='color:red;'>*</span>";
        }
        if ($item->description != '' && $item->description != null) {
            $_div .= '<i class="content-d">' . $item->description . '</i>';
        }
        $_div .="</p>";
        $_div .= "<select name='$item->title'";
        $_div .= $item->required == 1 ? "required='required'" : '';
        $_div .=" >";
        $_div .= "<option value='' selected >===请选择===</option>";
        $option_key = explode(',', $config['option_key']);
        foreach ($option_key as $key => $value) {
            $to = "option_$value";
            $_div .= "<option  value='$config[$to]' data-value='$value'>" . $config[$to] . "</option>";
        }
        $_div .= '</select>';
        return $_div;
    }

    function show_html_date($data) {
        $item = $data;
        $config = $data->config;
        $_div = '';
        $_div .= "<p class='content-l'>$item->title";
        if ($item->required == 1) {
            $_div .= "<span style='color:red;'>*</span>";
        }
        if ($item->description != '' && $item->description != null) {
            $_div .= '<i class="content-d">' . $item->description . '</i>';
        }
        $_div .="</p>";
        $_div .= '<input type="text" name="' . $item->title . '" onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})" ';
        $_div .= $item->required == 1 ? "required='required'" : '';
        $_div .= ' />';
        return $_div;
    }

    function test() {
        $data = json_decode($_POST['data']);
        $css = '<link rel="stylesheet" type="text/css" href="http://swap.5067.org/css/floatadv.css">';
        $div = '';
        $js = '';
        foreach ((array) $data as $k => $v) {
            $div .= '<div class="popContent float floatAdv' . $k . '" style="';
            if ($v->position == '4') {
                $div.='right';
            } else {
                $div.='left';
            }
            $div.=':' . $v->posx . 'px;width:' . $v->posw . 'px;';
            if ($v->position == '3') {
                $div.='bottom';
            } else {
                $div.='top';
            }
            $div.=':' . $v->posy . 'px;">';
            $div.='<a class="popClose" title="关闭" >关闭</a>';
            if ($v->type == "form") {
                $div .= $v->content;
                $js .= $v->cssjs;
            }
            if ($v->type == "adv") {
                $div.='<a href="' . $v->href . '" target="_blank"><img class="float_adv" src="' . $v->url . '"></a>';
            }
            $div.='</div>';
        }
        $js .= '<script>';
        $js.='$(".popClose").click(function(){
                $(this).parent(".float").stop();
                $(this).parent(".float").slideUp();
            });';
        $js.='</script>';
        echo $css . $div . $js;
    }

}
