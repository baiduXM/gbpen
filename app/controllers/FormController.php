<?php

/**
 * 万用表单控制器
 * @author xieqixiang
 * createForm
 */
class FormController extends BaseController {

	/**
	 * 表单列表
	 * @param type $status
	 * @return type
	 */
	public function getFormList($status = null) {
		$cus_id = Auth::id();
		$status = Input::get('status') ? Input::get('status') : null;
		//===根据用户id查找用户表单列表===
		if (empty($status)) {
			$data = DB::table('form')->where('cus_id', $cus_id)->get();
		} else {
			$data = DB::table('form')->where('cus_id', $cus_id)->where('status', $status)->get();
		}
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
		$res2 = DB::table('form_data_' . $form_id % 10)->where('form_id', $form_id)->delete();
		$res3 = DB::table('form')->where('id', $form_id)->delete();
		//返回数据
		if ($res3) {
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
		foreach ($form_data as $v) {
			if (!isset($data[$v['name']])) {
				$data[$v['name']] = $v['value'];
			} else {
				$data[$v['name']] = $data[$v['name']] . ',' . $v['value']; //合并checkbox选项值
			}
		}
		$data['is_once'] = isset($data['is_once']) ? 1 : 0;
		$data['updated_at'] = $time;
		$res = DB::table('form')->where('id', $form_id)->update($data);
		//===返回数据===
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '更新表单信息成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '更新表单信息失败', 'data' => null]);
		}
		return $json;
	}

	/**
	 * 获取表单信息	 
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
		//===返回数据===
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

		//===获取元素===
		$element_data = DB::table('form_element')->where('id', $element_id)->first();
//		var_dump($element_data);
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
		$input = Input::all();
		$form_id = Input::get('form_id');
		$data = $input['data'];
		$redata = array();
		$config = array();
		foreach ($data as $v) {
			if (!isset($redata[$v['name']])) {
				$redata[$v['name']] = $v['value'];
			} else {
				$redata[$v['name']] = $redata[$v['name']] . ',' . $v['value']; //合并checkbox选项值
			}
			if (preg_match('/^option_/', $v['name'])) {
				$option_key[] = preg_replace('/^option_/', '', $v['name']);
			}
		}
		//===赋值config===
		/*
		 * [text]
		 * text_type 文本类型 text-文本 password-密码
		 * text_rules_regex 正则规则 
		 * text_rules_hint 正则提示
		 * [textarea]
		 * -
		 * [select]
		 * option_default 选项默认值 0 or 0,1...
		 * option_$i 选项值 i项
		 * option_count 选项个数		 * 
		 * [+radio]
		 * option_layout 选项排版分布 1-单列 2-两列 3-三列 4-四列
		 * option_type 选项类型 1-文字 2-图片
		 * option_img_$i 选项图片
		 * [+checkbox]
		 * option_limit 选项是否限制
		 * option_type 选项限制 >=至少 <=最多 =恰好
		 * option_num 选项限制数
		 * [date]
		 * -
		 * [image]
		 * img_type 图片类型 1-本地图片 2-外链图片
		 * img_src 图片http地址
		 * img_file 图片路径
		 * img_href 图片点击跳转链接 http地址
		 * img_align 图片显示方式 1-拉伸 2-居中
		 * [file]
		 * file_type 文件类型 1,2...  1-文档 2-图片 3-视频 4-音频 5-其他(rar...)
		 * [?]
		 * align 对齐方式？ left-左对齐 center-居中 right-右对齐
		 * 
		 */

		if ($redata['type'] == 'text') {
			$config['text_type'] = $redata['config_text_type'];
			$config['text_rules'] = isset($redata['config_rules']) ? $redata['config_rules'] : 'no';
			switch ($redata['config_rules']) {
				case 'mail':
					$regex = '/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i';
					$hint = '';

					break;
				case 'mobile':
					$regex = '/^((13[0-9])|147|(15[0-35-9])|180|182|(18[45-9]))[0-9]{8}$/';
					$hint = '';
					break;
				case 'number':
					$regex = '/^\d+$/';
					$hint = '';

					break;
				case 'defined':
					$regex = $redata['config_regex'];
					$hint = $redata['config_hint'];
					break;
				default:
					$regex = '';
					$hint = '';
					break;
			}
			$config['text_rules_regex'] = $regex;
			$config['text_rules_hint'] = isset($hint) ? $hint : '';
		}
		if ($redata['type'] == 'textarea') {
			$config = array();
		}
		//===下拉菜单、单选、多选===
		//===option_default 选项默认值 0 or 0,1...
		//===option_count 选项个数
		//===option_type 选项类型 1-文字 2-图片
		//===option_$i 选项值 i项
		if ($redata['type'] == 'select' || $redata['type'] == 'radio' || $redata['type'] == 'checkbox') {
			$config['option_key'] = implode(',', $option_key);
			$config['option_default'] = isset($redata['config_option_default']) ? $redata['config_option_default'] : '';
			$config['option_count'] = intval($redata['config_option_count']);
//			$config['option_type'] = isset($redata['config_option_type']) ? $redata['config_option_type'] : 0;
			foreach ($option_key as $key => $value) {
				$config['option_' . $value] = $redata['option_' . $value];
			}
//			for ($i = 0; $i < $config['option_count']; $i++) {
//				$config['option_' . $i] = $redata['option_' . $i];
////				if ($config['option_type'] == 2) {//===1-文字、2-图片===
////					$config['option_img_' . $i] = $redata['option_img' . $i];
////				}
//			}
		}
		//===单选、多选===
		if ($redata['type'] == 'radio' || $redata['type'] == 'checkbox') {
			$config['option_layout'] = $redata['config_option_layout'];
		}
		//===多选===
		if ($redata['type'] == 'checkbox') {
			$config['option_limit'] = 0;
			if (isset($redata['config_control'])) {
				$config['option_limit'] = 1;
				$config['option_num'] = intval($redata['config_control_num']);
				switch ($redata['config_control_type']) {
					case 0://===至少===
						$config['option_type'] = 0;
						break;
					case 1://===最多===
						$config['option_type'] = 1;
						break;
					case 2://===恰好===
						$config['option_type'] = 2;
						break;
					default:
						break;
				}
			}
		}
		if ($redata['type'] == 'date') {
			//
		}
		//===图片===
		if ($redata['type'] == 'image') {
			$config['img_type'] = isset($redata['config_img_type']) ? $redata['config_img_type'] : '';
			$config['img_file'] = isset($redata['config_img_file']) ? $redata['config_img_file'] : '';
			$config['img_src'] = isset($redata['config_img_src']) ? $redata['config_img_src'] : '';
			$config['img_href'] = isset($redata['config_img_href']) ? $redata['config_img_href'] : '';
			$config['img_align'] = isset($redata['config_img_align']) ? $redata['config_img_align'] : '';
		}
		//====文件===
		if ($redata['type'] == 'file') {
			$config['file_type'] = isset($redata['config_file_type']) ? $redata['config_file_type'] : '';
		}
		//===?===
		//$config['align'] = $redata['config_align'];
		$time = date('Y-m-d H:i:s');

		$column_data = array(
			'title' => $redata['title'],
			'description' => $redata['description'],
			'required' => isset($redata['required']) ? 1 : 0,
			'config' => serialize($config),
			'order' => $redata['order'],
			'updated_at' => $time
		);
		$column_id = DB::table('form_column_' . $form_id % 10)->where('id', $redata['column_id'])->update($column_data);
		$column_data['column_id'] = $redata['column_id'];
		$column_data['type'] = $redata['type'];
		$column_data['config'] = $config;

		if ($column_id != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '保存组件信息成功', 'data' => $column_data]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '保存组件信息失败', 'data' => null]);
		}
		return $json;
	}

	/**
	 * 删除组件信息
	 */
	public function deleteFormColumn() {
		$form_id = Input::get('form_id');
		$column_id = Input::get('column_id');
		$res = DB::table('form_column_' . $form_id % 10)->where('id', $column_id)->delete();
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '删除组件失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * ===用户表单提交===
	 * 提交数据到交互服务器180.76.148.39
	 * 1、获取表单信息，看是否只允许用户提交一次
	 * 2、查看用户是否已经提交
	 * 3、提交
	 */
	public function submitFormUserdata() {
		$Url = $_SERVER['HTTP_REFERER'];
		$cus_id = Auth::id();
		$form_id = Input::get('form_id');
		$input = Input::get('data');
		$form_data = DB::table('form')->where('id', $form_id)->first();
		$userdata = DB::table('form_data_' . $form_id % 10)->where('form_id', $form_id)->where('cus_id', $cus_id)->first();
		$column_data = DB::table('form_column_' . $form_id % 10)->where('form_id', $form_id)->orderBy('order', 'asc')->get();
		if ($form_data->is_once == 1 && !empty($userdata)) {
			return '请勿重复填写';
//			header("Location:$Url");
		}

//		return $column_data;
		//redata重置表单提交信息
		foreach ($input as $k => $v) {
			if (!isset($redata[$v['name']])) {
				$redata[$v['name']] = $v['value'];
			} else {
				$redata[$v['name']] = $redata[$v['name']] . ',' . $v['value']; //合并checkbox选项值
			}
		}
		$tdata = array();
		//赋值。title->value
		foreach ($column_data as $k => $v) {
			$config = unserialize($v->config);
			$tte = 'col_' . $v->id;
//			return $tte;
			if (!isset($redata['col_' . $v->id])) {
				$tdata[$k]['name'] = $v->title;
				$tdata[$k]['value'] = '';
			} else {
				$tdata[$k]['name'] = $v->title;
				switch ($v->type) {
					case 'radio':
					case 'select':
						$temp = 'option_' . $redata[$tte];
						$tdata[$k]['value'] = $config[$temp];
						break;
					case 'checkbox':
						$tc = explode(',', $redata[$tte]);
//						return $tc;

						foreach ($tc as $childkv) {
							$temp = 'option_' . $childkv;
							if (!isset($tdata[$v->title])) {
								$tdata[$k]['value'] = $config[$temp];
							} else {
								$tdata[$k]['value'] = ',' . $config[$temp];
							}
						}
						break;
					default:
						$tdata[$k]['value'] = $redata[$tte];
						break;
				}
//				$data['col_' . $v->id] = $redata['col_' . $v->id];
			}
		}
		$time = date('Y-m-d H:i:s');
		$data = array(
			'form_id' => $form_id,
			'cus_id' => $cus_id,
			'data' => serialize($tdata),
			'created_at' => $time,
			'updated_at' => $time
		);
		$postFun = new CommonController;
		$flag = $postFun->postsend("http://swap.5067.org/admin/form_userdata_submit.php", $data);
		if ($flag == 1) {
			$json = Response::json(['err' => 0, 'msg' => '用户数据获取成功', 'data' => $form_data]);
		} else {
			$json = Response::json(['err' => 0, 'msg' => '用户数据为空', 'data' => null]);
		}
		return $json;
	}

	/**
	 * 获取用户数据列表
	 * 从交互服务器180.76.148.39中获取数据
	 */
	public function getFormUserdataList() {
		$form_id = Input::get('form_id');
		$data['form_id'] = $form_id;
//		$data['cus_id'] = Auth::id();
		$postFun = new CommonController;
		$res = $postFun->postsend("http://swap.5067.org/admin/form_userdata_list.php", $data);
		$res = json_decode($res);
		foreach ($res as $key => &$value) {
			$value->data = unserialize($value->data);
		}
//		$res = DB::table('form_data_' . $form_id % 10)->where('form_id', $form_id)->get();
		if ($res != NULL) {
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
//		$res = DB::table('form_data_' . $form_id % 10)->where('id', $id)->delete();
		$postFun = new CommonController;
		$res = $postFun->postsend("http://swap.5067.org/admin/form_userdata_delete.php", $param);
		if ($res == 1) {
			$json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '删除失败', 'data' => '']);
		}
		return $json;
	}

}

//error_reporting(0);
//header('Content-type: text/html; charset=utf-8');
//include("conn.php");
//
//$sql = "insert into form_data_" . $_POST[form_id] % 10 . " (form_id,cus_id,data,created_at,updated_at) " .
//	"values ($_POST[form_id]','$_POST[cus_id]','$_POST[data]','$_POST[created_at]','$_POST[updated_at]')";
//if (mysql_query($sql)) {
//	$url = $_SERVER["HTTP_REFERER"];
//	echo '<script language="javascript" type="text/javascript">';
//	echo 'alert(\'提交成功\');';
//	echo 'window.location.href="' . $url . '";';
//	echo '</script>';
//} else {
//	echo "<script language=\"javascript\">alert('添加失败');history.go(-1)</script>";
//}
//if ($_POST['submit']) {
//	$sql = "insert into message (form_id,cus_id,data,created_at,updated_at) " .
//		"values ($_POST[form_id]','$_POST[cus_id]','$_POST[data]','$_POST[created_at]','$_POST[updated_at]')";
//	if (mysql_query($sql)) {
//		$url = $_SERVER["HTTP_REFERER"];
//		echo '<script language="javascript" type="text/javascript">';
//		echo 'alert(\'提交成功\');';
//		echo 'window.location.href="' . $url . '";';
//		echo '</script>';
//	} else {
//		echo "<script language=\"javascript\">alert('添加失败');history.go(-1)</script>";
//	}
//} elseif ($_GET['ajax']) {
//	header('Access-Control-Allow-Origin: *');
//	header('Content-type: application/json');
//	$result = array();
//	$sql = "insert into message (id,cus_id,name,content,telephone,email,creat_time,status) " .
//		"values ('','$_GET[id]','$_GET[name]','$_GET[content]','$_GET[telephone]','$_GET[email]',now(),'')";
//	if (mysql_query($sql)) {
//		$result['err'] = 0;
//		$result['msg'] = '';
//	} else {
//		$result['err'] = 1;
//		$result['msg'] = '数据入库失败';
//	}
//	$result = json_encode($result);
//	if ($_GET['callback']) {
//		echo $_GET['callback'] . "($result)";
//	} else {
//		echo $result;
//	}
//} elseif ($_POST['ajax']) {
//	header('Access-Control-Allow-Origin: *');
//	header('Content-type: application/json');
//	$result = array();
//	$sql = "insert into message (id,cus_id,name,content,telephone,email,creat_time,status) " .
//		"values ('','$_GET[id]','$_POST[name]','$_POST[content]','$_POST[telephone]','$_POST[email]',now(),'')";
//	if (mysql_query($sql)) {
//		$result['err'] = 0;
//		$result['msg'] = '';
//	} else {
//		$result['err'] = 1;
//		$result['msg'] = '数据入库失败';
//	}
//	$result = json_encode($result);
//	if ($_GET['callback']) {
//		echo $_GET['callback'] . "($result)";
//	} else {
//		echo $result;
//	}
//} else {
//	header('Content-type: application/json');
//	$result['err'] = 2;
//	$result['msg'] = '错误的判断信息';
//	echo json_encode($result);
//}
?>
