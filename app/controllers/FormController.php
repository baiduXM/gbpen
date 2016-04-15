<?php

/**
 * 万用表单控制器
 * @author xieqixiang
 * createForm
 */
class FormController extends BaseController {

	/**
	 * 表单列表
	 */
	public function getFormList() {
		$cus_id = Auth::id();
		//===根据用户id查找用户表单列表===
		$data = DB::table('form')->where('cus_id', $cus_id)->get();
		//===返回数据===
		if ($data != NULL) {
			$res = Response::json(['err' => 0, 'msg' => '获取表单列表成功', 'data' => $data]);
		} else {
			$res = Response::json(['err' => 1, 'msg' => '获取表单列表失败', 'data' => '']);
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
			$res = Response::json(['err' => 1, 'msg' => '创建表单失败', 'data' => '']);
		}
		return $res;
	}

	/**
	 * 删除表单
	 */
	public function deleteForm() {
		$form_id = Input::get('form_id');
		$res = DB::table('form')->where('id', $form_id)->delete();
		if ($res) {
			$json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '删除失败', 'data' => '']);
		}
		return $json;
	}

	//===addform.js---star===
	/**
	 * 获取表单信息
	 * 并验证表单是否是自己的、并且获取表单信息
	 */
	public function getFormData() {
		$cus_id = Auth::id();
		$form_id = Input::get('form_id');
		$res = DB::table('form')->where('id', $form_id)->where('cus_id', $cus_id)->first();
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '获取表单信息成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '获取表单信息失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * 获取表单详细信息
	 */
	public function getFormColumnList() {
		$form_id = Input::get('form_id');
		$res = DB::table('form_column_' . $form_id % 10)->where('form_id', $form_id)->orderBy('order', 'asc')->select('id as column_id', 'title', 'description', 'type', 'required', 'config', 'order')->get();
		foreach ($res as &$v) {
			$v->config = json_decode($v->config);
		}
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '获取表单列数据成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 0, 'msg' => '获取表单列数据为空', 'data' => '']);
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
			$res = Response::json(['err' => 0, 'msg' => '获取组件元素成功', 'data' => $data]);
		} else {
			$res = Response::json(['err' => 1, 'msg' => '获取组件元素失败', 'data' => '']);
		}
		return $res;
	}

	/**
	 * 编辑组件元素（获取信息）
	 */
	public function getFormColumn() {
		//===获取参数===
		$form_id = Input::get('form_id');
		$column_id = Input::get('column_id');

		//===获取数据===
		$num = $form_id % 10;
		$column_data = DB::table('form_column_' . $num)->orderBy('order', 'asc')->where('id', $column_id)->first();
		$column_data->column_id = $column_data->id;
		$column_data->config = json_decode($column_data->config);
		//===返回数据===
		if ($column_data != NULL) {
			$res = Response::json(['err' => 0, 'msg' => '获取组件元素成功', 'data' => $column_data]);
		} else {
			$res = Response::json(['err' => 1, 'msg' => '获取组件元素失败', 'data' => '']);
		}
		return $res;
	}

	/**
	 * 保存表单信息
	 */
	public function editForm() {
		$form_id = Input::get('form_id');
		$form_data = Input::get('box_info');
		$time = date('Y-m-d H:i:s');
		foreach ($form_data as $v) {
			$data[$v['name']] = $v['value'];
		}
		$data['updated_at'] = $time;

		$res = DB::table('form')->where('id', $form_id)->update($data);
		//===返回数据===
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '更新表单信息成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '更新表单信息失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * 添加组件元素
	 */
	public function addFormColumn() {
		//===获取参数===
		$form_id = Input::get('form_id');
		$element_id = Input::get('element_id');

		//===获取元素===
		$where = array(
			'id' => $element_id
		);
		$element_data = DB::table('form_element')->where($where)->first();
//		$element_data->config = ($element_data->config);
		//===添加数据===
		$time = date('Y-m-d H:i:s');
		$column_data = array(
			'form_id' => $form_id,
			'title' => $element_data->title,
			'description' => $element_data->description,
			'type' => $element_data->type,
			'required' => '0',
			'config' => $element_data->config,
			'order' => '',
			'created_at' => $time,
			'updated_at' => $time
		);
		$num = $form_id % 10;
		$column_id = DB::table('form_column_' . $num)->insertGetId($column_data);
		$element_data->column_id = $column_id;

		//===返回数据===
		if ($column_id != NULL) {
			$res = Response::json(['err' => 0, 'msg' => '添加组件成功', 'data' => $element_data]);
		} else {
			$res = Response::json(['err' => 1, 'msg' => '添加组件失败', 'data' => '']);
		}
		return $res;
	}

	/**
	 * 保存组件信息
	 */
	public function editFormColumn() {
		//===获取参数===
		$data = Input::get('data');
		$form_id = Input::get('form_id');
		$redata = array();
		$config = array();
		foreach ($data as $v) {
			if (!isset($redata[$v['name']])) {
				$redata[$v['name']] = $v['value'];
			} else {
				$redata[$v['name']] = $redata[$v['name']] . ',' . $v['value']; //合并checkbox选项值
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
			$config['text_rules'] = $redata['config_rules'];
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
			$config['option_default'] = isset($redata['config_option_default']) ? $redata['config_option_default'] : '';
			$config['option_count'] = intval($redata['config_option_count']);
			$config['option_type'] = isset($redata['config_option_type']) ? $redata['config_option_type'] : 1;
			for ($i = 0; $i < $config['option_count']; $i++) {
				$config['option_' . $i] = $redata['option_' . $i];
				if ($config['option_type'] == 2) {//===1-文字、2-图片===
					$config['option_img_' . $i] = $redata['option_img' . $i];
				}
			}
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
			'config' => json_encode($config),
			'order' => $redata['order'],
			'updated_at' => $time
		);
		$num = $form_id % 10;
		$column_id = DB::table('form_column_' . $num)->where('id', $redata['column_id'])->update($column_data);
		$column_data['column_id'] = $redata['column_id'];
		$column_data['type'] = $redata['type'];
		$column_data['config'] = $config;

		if ($column_id != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '保存组件信息成功', 'data' => $column_data]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '保存组件信息失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * 删除组件信息
	 */
	public function deleteFormColumn() {
		$form_id = Input::get('form_id');
		$column_id = Input::get('column_id');
		$num = $form_id % 10;
		$model = DB::table('form_column_' . $num); //分表
		$res = $model->where('id', $column_id)->delete();
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '删除组件失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * 保存表单
	 */
	public function saveForm() {
		$form_id = Input::get('form_id'); //表单ID
		$form_box_info = Input::get('form_box_info'); //表单头信息

		foreach ($form_box_info as $v) {
			$tn = str_replace('table_', '', $v['name']);
			$redata[$tn] = $v['value'];
		}
		$time = date('Y-m-d H:i:s');
		$data = array(
			'name' => $redata['name'],
			'title' => $redata['title'],
			'description' => $redata['description'],
			'action' => $redata['action'],
			'is_once' => isset($redata['is_once']) ? 1 : 0,
			'status' => $redata['status'],
			'updated_at' => $time
		);
//		return $data;
		$res = DB::table('form')->where('id', $form_id)->updata($data);
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '保存成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '保存失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * ===预览用户表单列表===
	 * form-view-list
	 */
	public function viewFormList() {
		$form_id = Input::get('form_id');
		$list = DB::table('form_data_' . $form_id % 10)->where('form_id', $form_id)->get();
		foreach ($list as &$value) {
			$value->data = json_decode($value->data);
		}
		if ($list != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '获取成功', 'data' => $list]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '获取失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * ===浏览表单===
	 * @return type
	 */
	public function viewForm($id) {
//		$form_id = Input::get('form_id');
		$form_id = $id;
		$form_data = DB::table('form')->where('id', $form_id)->first();
		$num = $form_id % 10;
		$column_data = DB::table('form_column_' . $num)->orderBy('order')->where('form_id', $form_id)->get();
		foreach ($column_data as &$v) {
			$v->config = json_decode($v->config);
		}
//		var_dump($form_data);
//		echo '<br />---form_data---<br />';
//		var_dump($form_info);
//		echo '<br />---form_info---<br />';
		return View::make('view')->with(array('form_data' => $form_data, 'column_data' => $column_data));
	}

	/**
	 * ===用户表单提交===
	 */
	public function submitViewForm() {
		$input = Input::all();
		$form_id = $input['form_id'];
		$num = $form_id % 10;
		$cus_id = Auth::id();
		$table = DB::table('form_data_' . $num);
		$time = date('Y-m-d H:i:s');
		foreach ($input as $key => $value) {
			if (strstr($key, 'col_')) {
				$config[$key] = $value;
			}
		}
		$data = array(
			'form_id' => $form_id,
			'cus_id' => $cus_id,
			'data' => json_encode($config),
			'created_at' => $time,
			'updated_at' => $time
		);
		$res = $table->insertGetId($data);
		if ($res) {
			echo '谢谢参与!';
		} else {
			echo '提交失败!';
		}
	}

	/**
	 * ===删除用户表单数据===
	 * form-data-delete
	 */
	public function deleteFormData() {
		$form_id = Input::get('form_id');
		$id = Input::get('id');
		$res = DB::table('form_data_' . $form_id % 10)->where('id', $id)->delete();
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '删除成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '删除失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * ===预览用户提交的表单详情===
	 * form-view-detail
	 */
	public function viewFormDetail($id) {
		$form_id = Input::get('form_id');
		$id = Input::get('id');
		$form_data = DB::table('form')->where('id', $form_id)->first();
		$column_data = DB::table('form_column_' . $form_id % 10)->orderBy('order')->where('form_id', $form_id)->get();
		foreach ($column_data as &$value) {
			$value->config = json_decode($value->config);
		}
		$user = DB::table('form_data_' . $form_id % 10)->where('id', $id)->first();
		$user_data = json_decode($user->data, TRUE);
		return View::make('view_detail')->with(array('form_data' => $form_data, 'column_data' => $column_data, 'user_data' => $user_data));
	}

	/**
	 * ===预览用户提交的表单删除===
	 * form-delete-detail
	 */
	public function deleteFormDetail() {
		//===判断是否有删除权限===
		//===TODO===
		$form_id = Input::get('form_id');
		$id = Input::get('id');
		$num = $form_id % 10;
		$table = DB::table('form_data_' . $num);
		$res = $table->where('id', $id)->delete();
		if ($res) {
			echo '删除成功';
		} else {
			echo '删除失败';
		}
	}

	/**
	 * 用户填写表单
	 */
	public function writeFormData() {
		$form_id = Input::get('form_id');
		$form_data = DB::table('form')->where('id', $form_id)->first();
		$num = $form_id % 10;
		$column_data = DB::table('form_column_' . $num)->orderBy('order')->where('form_id', $form_id)->get();
		foreach ($column_data as &$v) {
			$v->config = json_decode($v->config);
		}
//		$column_data['column_id'] = $column_data['id'];
		$res = array('form_data' => $form_data, 'column_data' => $column_data);
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '读取成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '读取失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * 用户表单提交
	 */
	public function writeFormSubmit() {

		$form_data = Input::get('form_data');
		$redata = array();
		foreach ($form_data as $v) {
			if (!isset($redata[$v['name']])) {
				$redata[$v['name']] = $v['value'];
			} else {
				$redata[$v['name']] = $redata[$v['name']] . ',' . $v['value']; //合并checkbox选项值
			}
		}
		return ($redata);

//		$cus_id = Auth::id();
//		$temp = Input::form('box_show');
//		var_dump($temp);
//		$res = array('form_data' => $form_data, 'column_data' => $column_data);
//		if ($res != NULL) {
//			$json = Response::json(['err' => 0, 'msg' => '读取成功', 'data' => $res]);
//		} else {
//			$json = Response::json(['err' => 1, 'msg' => '读取失败', 'data' => '']);
//		}
//		return $json;
	}

	/**
	 * 移动排序
	 */
	public function moveFormColumn() {
		$form_id = Input::get('form_id');
		$column_id = Input::get('column_id');
		$operate = Input::get('operate');
		switch ($operate) {
			case 'up':
				$res = DB::table('form_column_' . $form_id % 10)->where('id', $column_id)->decrement('order');
				break;
			case 'down':
				$res = DB::table('form_column_' . $form_id % 10)->where('id', $column_id)->increment('order');
				break;
			case 'add':
				break;
			case 'delete':
				break;
			default:
				break;
		}
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '排序成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '排序失败', 'data' => '']);
		}
		return $json;
	}

	/**
	 * 万用表单前端预览界面
	 * @param type $id
	 */
	public function formPreview($id) {
		$form_id = DB::table('classify')->where('id', $id)->pluck('form_id');
		$form_data = DB::table('form')->where('id', $form_id)->first();
		$column_data = DB::table('form_column_' . $form_id % 10)->where('form_id', $form_id)->get();
		foreach ($column_data as &$v) {
			$v->config = json_decode($v->config);
		}
		$res = array('form_data' => $form_data, 'column_data' => $column_data);
//		return $res;
	}

}

?>
