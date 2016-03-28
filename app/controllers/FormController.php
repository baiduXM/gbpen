<?php

/**
 * 万用表单控制器
 * @author xieqx
 * ===1、如何减少对数据库的访问，getFormElement和addFormColumn都有对表form_element进行访问===
 */
class FormController extends BaseController {
//	static private $element;

	/**
	 * 表单列表
	 */
	public function getFormList() {
		//===判断用户是否登录===
		$cus_id = Auth::id();
		//TODO
		//===根据用户id查找用户表单列表===
		$data = DB::table('form')->where('cus_id', $cus_id)->get();
		//===返回数据===
		if ($data != NULL) {
			$res = Response::json(['err' => 0, 'msg' => '获取成功', 'data' => $data]);
		} else {
			$res = Response::json(['err' => 3001, 'msg' => '没有表单数据', 'data' => '']);
		}
		return $res;
	}

	/**
	 * 创建表单
	 */
	public function createForm() {
		$name = Input::get('name');
		$platform = Input::get('platform');
		$time = date('Y-m-d H:i:s');
		$cus_id = Auth::id();
		$data = array(
			'cus_id' => $cus_id,
			'name' => $name,
			'platform' => $platform,
			'created_at' => $time,
			'updated_at' => $time
		);
		$id = DB::table('form')->insertGetId($data);
		//===返回数据===
		if ($id != NULL) {
			$res = Response::json(['err' => 0, 'msg' => '添加成功', 'data' => $id]);
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

	/**
	 * 验证表单是否是自己的，显示表单信息
	 */
	public function checkAuth() {
		$cus_id = Auth::id();
		$form_id = Input::get('form_id');
		$form_data = DB::table('form')->where('id', $form_id)->where('cus_id', $cus_id)->first();
		if (empty($form_data)) {
			return Response::json(['err' => 1, 'msg' => '没有权限', 'data' => '']);
		}
		//===获取表单详细信息===
		$num = $form_id % 10;
		$column_data = DB::table('form_column_' . $num)->where('form_id', $form_id)->select('id as column_id', 'title', 'description', 'type', 'required', 'config')->get();
		foreach ($column_data as &$v) {
			$v->config = json_decode($v->config);
		}
		$data['form'] = $form_data;
		$data['column'] = $column_data;
		return Response::json(['err' => 0, 'msg' => '验证成功，获取列表信息', 'data' => $data]);
	}

	/**
	 * 获取组件元素
	 */
	public function getFormElement() {
		$data = DB::table('form_element')->where('status', 1)->get();
		//===返回数据===
		if ($data != NULL) {
			$res = Response::json(['err' => 0, 'msg' => '获取成功', 'data' => $data]);
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
		$where = array(
			'id' => $element_id
		);
		$element_data = DB::table('form_element')->where($where)->first();
		$element_data->config = json_decode($element_data->config);

		//===添加数据===
		$time = date('Y-m-d H:i:s');
		$column_data = array(
			'form_id' => $form_id,
			'title' => $element_data->title,
			'description' => $element_data->description,
			'type' => $element_data->type,
			'required' => '0',
			'config' => '',
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
	 * 编辑组件元素（获取信息）
	 */
	public function editFormColumn() {
		//===获取参数===
		$form_id = Input::get('form_id');
		$column_id = Input::get('column_id');

		//===获取数据===
		$num = $form_id % 10;
		$model = DB::table('form_column_' . $num); //分表
		$column_data = $model->where('id', $column_id)->first();
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
	 * 保存组件信息
	 */
	public function saveFormColumn() {
		//===获取参数===
		$data = Input::get('data');
//		$file = fileRead();
//		$file = Input::file('config_img_file');
//		return $file;
		$form_id = Input::get('form_id');
		$redata = array();
		$config = array();
		foreach ($data as $v) {
			if (!isset($redata[$v['name']])) {
				$redata[$v['name']] = $v['value'];
			} else {
				$redata[$v['name']] = $redata[$v['name']] . ',' . $v['value'];
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
			if ($config['option_type'] == 2) {//===1-文字、2-图片===
				for ($i = 1; $i <= $config['option_count']; $i++) {
					$config['option_' . $i] = $redata['option_' . $i];
					$config['option_img_' . $i] = $redata['option_img' . $i];
				}
			} else {
				for ($i = 1; $i <= $config['option_count']; $i++) {
					$config['option_' . $i] = $redata['option_' . $i];
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
			$config['img_type'] = intval($redata['config_img_type']);
//			$config['img_file'] = $redata['config_img_file'];
			$config['img_src'] = $redata['config_img_src'];
			$config['img_href'] = $redata['config_img_href'];
			$config['img_align'] = intval($redata['config_img_align']);
		}
		//====文件===
		if ($redata['type'] == 'file') {
			$config['file_type'] = $redata['config_file_type'];
		}
		//===?===
		//$config['align'] = $redata['config_align'];

		$time = date('Y-m-d H:i:s');

		$column_data = array(
			'title' => $redata['title'],
			'description' => $redata['description'],
			'required' => isset($redata['required']) ? 1 : 0,
			'config' => json_encode($config),
			'order' => '',
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
	 * 提交表单
	 */
	public function submitForm() {
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
		$res = DB::table('form')->where('id', $form_id)->increment('version', 1, $data);
		if ($res != NULL) {
			$json = Response::json(['err' => 0, 'msg' => '保存成功', 'data' => $res]);
		} else {
			$json = Response::json(['err' => 1, 'msg' => '保存失败', 'data' => '']);
		}
		return $json;
	}

}

?>
