function addformController($scope, $http, $location) {
	$scope.$parent.showbox = "main";
	$scope.$parent.homepreview = true;
	$scope.$parent.menu = [];
	var form_id = 6; //表单ID

	$('[name="form_id"]').val(form_id);
	//===表单标题===
	$('[name="table_title"]').blur(function () {
		$('.title p').html($(this).val());
	});
	//===表单描述===
	$('[name="table_description"]').blur(function () {
		$('.description p').html($(this).val());
	});
	//===保存表单===
	$('.addsave').click(function () {
		var form_box_info = $('form[name="box_info"]').serializeArray(); //表单头信息
		var form_box_show = $('form[name="box_show"]').serializeArray(); //表单详细信息
		$.post('../form-submit', {form_id: form_id, form_box_info: form_box_info, form_box_show: form_box_show}, function (json) {
			checkJSON(json, function (json) {
				var hint_box = new Hint_box();
				hint_box;
				setTimeout('location.href = "#/form"', 2000);
			});
		}, 'json');
	});
	init();
	/**
	 * 初始化
	 * 1、验证表单是否是该用户的，并加载表单信息
	 * 2、加载组件元素
	 * @returns {undefined}
	 */
	function init() {
		$.post('../form-auth', {form_id: form_id}, function (json) {
			checkJSON(json, function (json) {
//				console.log(json);
				if (json.err !== 0) {
					alert(json.msg);
					location.href = "#/form";
				}
				var column_data = json.data.column;
				$.each(column_data, function (k, v) {
					_div_show(v);
				});
				//===获取表单信息===
				var data = json.data.form;
				$('[name="table_name"]').val(data.name);
				$('[name="table_title"]').val(data.title);
				$('[name="table_description"]').val(data.description);
				$('[name="action"]').val(data.action);
				if (data.is_once === 1) {
					$('[name="is_once"]').attr('checked', true);
				}
				if (data.status === 1) {
					$('[name="table_status"]:eq(0)').attr('checked', true);
				} else {
					$('[name="table_status"]:eq(1)').attr('checked', true);
				}
				//===显示区域===
				$('h2').html(data.title);
				$('h3').html(data.description);
			});
		}, 'json');
		getFormElement();
	}
	/**
	 * 获取组件元素
	 * @returns {undefined}
	 */
	function getFormElement() {
		$http.get('../form-element').success(function (json) {
			checkJSON(json, function (json) {
				var form_element = json.data;
				var _div = '';
				$.each(form_element, function (k, v) {
					_div += '<li class="utility" data-id="' + v.id + '">\
								<span class="title">' + v.title + '</span>\
								<i class="iconfont icon-liebiao grey tpl_info" name="1"></i>\
							</li>';
				});
				$('[name="element-box"]').append(_div);
			});
			$('.unit-list>li').click(function () {
				var _this = $(this);
				bindElementEvent(_this);
			});
		});
	}

	/**
	 * 绑定组件事件
	 * @param {type} _this 当前对象
	 * @returns {undefined}
	 */
	function bindElementEvent(_this) {
		var element_id = _this.data('id');
		//===添加组件===
		$.post('../form-add-column', {form_id: form_id, element_id: element_id}, function (json) {
			var data = json.data;
			_div_show(data);
			_div_edit(data);
		}, 'json');
	}
	/**
	 * 显示组件/更新组件信息
	 * @param {type} data
	 * @returns {String}
	 */
	function _div_show(data) {
		var _div = '';
		var _div_li = '';
		_div_li += '<li data-type="' + data.type + '" data-id="' + data.column_id + '" name="li_' + data.column_id + '">';
		switch (data.type) {
			case 'text':
				_div += '<label class="content-l">' + data.title + '：</label><input class="Input" placeholder='+data.description+' type="text" name="col_' + data.column_id + '" value="" />';
				break;
			case 'textarea':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：<br /></label><textarea name="col_' + data.column_id + '"></textarea>';
				break;
			case 'radio':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><input type="radio" name="col_' + data.column_id + '" value="" />\n\
						<input type = "radio" name="col_' + data.column_id + '" value = "" />\n\
						<input type = "radio" name="col_' + data.column_id + '" value = "" />';
				break;
			case 'checkbox':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><input type="checkbox" name="col_' + data.column_id + '" value="" />\n\
						<input type="checkbox" name="col_' + data.column_id + '" value="" />\n\
						<input type="checkbox" name="col_' + data.column_id + '" value="" />';
				break;
			case 'select':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><select name="col_' + data.column_id + '">';
//				$.each(column_data, function (k, v) {
//					_div_show(v);
//				});
				_div += '<option value="volvo">Volvo</option>\n\
						<option value="saab">Saab</option>\n\
						<option value="fiat">Fiat</option>\n\
						<option value="audi">Audi</option>';
				_div += '</select>';
				break;
			case 'date':
				_div += '日期date';
				break;
			case 'image':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><input type="image" src="submit.gif" alt="Submit" name="col_' + data.column_id + '" />';
				break;
			case 'file':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><input type="file" name="col_' + data.column_id + '"/>';
				break;
			default:
				break;
		}
		_div_li += _div;
		_div_li += '</li>';
		if ($('li[name="li_' + data.column_id + '"').length > 0) {
			$('li[name="li_' + data.column_id + '"').html(_div);
		} else {
			$('.element-show').append(_div_li);
		}
		//===绑定元素点击响应事件===
		$(".element-show>li").unbind('click').on('click', function () {
			var _this_column = $(this);
			$.post('../form-edit-column', {form_id: form_id, column_id: _this_column.attr('data-id')}, function (col_json) {
				var col_data = col_json.data;
				_div_edit(col_data);
			}, 'json');
		});
	}
	/**
	 * 编辑组件
	 * @param {type} data
	 * @returns {undefined}
	 */
	function _div_edit(data) {
		var _div = '';
		_div += '<li>标题：<input type="text" name="title" value="' + data.title + '"/></li>';
		_div += '<li>描述：<textarea  name="description">' + data.description + '</textarea></li>';
		_div += '<li>是否必填：<input type="checkbox" name="required" value="1"/></li>';
		if (data.required === 1) {
			$('[name="required"]').attr('checked', true);
		}
		if (data.config !== null) {
			//===TODO===
		}
		switch (data.type) {
			case 'text':
				_div += '<hr /><div>类型\n\
					<input type = "radio" name="config_text_type" value = "text" />文本\n\
					<input type = "radio" name="config_text_type" value = "password" />密码\n\
				</div>';
				_div += '<hr /><div>规则\n\
					<input type = "radio" name="config_rules" value = "mail" />邮箱　\n\
					<input type = "radio" name="config_rules" value = "mobile" />手机　\n\
					<input type = "radio" name="config_rules" value = "username" />用户名　\n\
					<input type = "radio" name="config_rules" value = "number" />数字　\n\
					<input type = "radio" name="config_rules" value = "defined" />自定义　\n\
						正则表达式<input type = "text" name="config_regex" value = "" />\n\
						（提示<input type = "text" name="config_hint" value = "" />）\n\
				</div>';
				break;
			case 'textarea':
				break;
			case 'select':
				_div += '<hr /><div>下拉菜单选项设置\n\
					<input type = "radio" name="config_option_default" value = "1" /><input type="type" name="option_1" value="下拉请选择" />\n\
					<input type = "radio" name="config_option_default" value = "2" /><input type="type" name="option_2" value="下拉选项一" />\n\
					<input type = "radio" name="config_option_default" value = "3" /><input type="type" name="option_3" value="下拉选项二" />\n\
					<input type = "radio" name="config_option_default" value = "4" /><input type="type" name="option_4" value="下拉选项三" />\n\
				</div>';
				_div += '<hr /><div>选项个数\n\
					<input type = "hidden" name="config_option_count" value = "" />\n\
				</div>';
				break;
			case 'radio':
				_div += '<hr /><div>单选\n\
					<input type = "radio" name="config_option_default" value = "1" /><img name="option_img_1" src="" /><input type="type" name="option_1" value="单选选项一" />\n\
					<input type = "radio" name="config_option_default" value = "2" /><img name="option_img_2" src="" /><input type="type" name="option_2" value="单选选项二" />\n\
					<input type = "radio" name="config_option_default" value = "3" /><img name="option_img_3" src="" /><input type="type" name="option_3" value="单选选项三" />\n\
					<input type = "radio" name="config_option_default" value = "4" /><img name="option_img_4" src="" /><input type="type" name="option_4" value="单选选项四" />\n\
				</div>';
				_div += '<hr /><div>选项个数\n\
					<input type = "hidden" name="config_option_count" value = "1" />\n\
				</div>';
				_div += '<hr /><div>排版分布\n\
					<input type = "radio" name="config_option_layout" value = "1" />单列\n\
					<input type = "radio" name="config_option_layout" value = "2" />两列\n\
					<input type = "radio" name="config_option_layout" value = "3" />三列\n\
					<input type = "radio" name="config_option_layout" value = "4" />四列\n\
				</div>';
				_div += '<hr /><div>选项类型\n\
					<input type = "radio" name="config_option_type" value = "1" />文字\n\
					<input type = "radio" name="config_option_type" value = "2" />图片\n\
				</div>';
				break;
			case 'checkbox':
				_div += '<hr /><div>多选\n\
					<input type = "checkbox" name="config_option_default" value = "1" /><img name="option_img_1" src="" /><input type="type" name="option_1" value="多选选项一" />\n\
					<input type = "checkbox" name="config_option_default" value = "2" /><img name="option_img_2" src="" /><input type="type" name="option_2" value="多选选项二" />\n\
					<input type = "checkbox" name="config_option_default" value = "3" /><img name="option_img_3" src="" /><input type="type" name="option_3" value="多选选项三" />\n\
					<input type = "checkbox" name="config_option_default" value = "4" /><img name="option_img_4" src="" /><input type="type" name="option_4" value="多选选项四" />\n\
				</div>';
				_div += '<hr /><div>选项个数\n\
					<input type = "hidden" name="config_option_count" value = "1" />\n\
				</div>';
				_div += '<hr /><div>排版分布\n\
					<input type = "radio" name="config_option_layout" value = "1" />单列\n\
					<input type = "radio" name="config_option_layout" value = "2" />两列\n\
					<input type = "radio" name="config_option_layout" value = "3" />三列\n\
					<input type = "radio" name="config_option_layout" value = "4" />四列\n\
				</div>';
				_div += '<hr /><div>选项类型\n\
					<input type = "radio" name="config_option_type" value = "1" />文字\n\
					<input type = "radio" name="config_option_type" value = "2" />图片\n\
				</div>';
				_div += '<hr /><div>选项控制\n\
					<input name="config_control" type="checkbox" value="1">\n\
					<select name="config_control_type" autocomplete="off">\n\
						<option value="0" selected="selected">至少</option>\n\
						<option value="1">最多</option>\n\
						<option value="2">恰好</option>\n\
					</select>\n\
					<label>选择</label>\n\
					<input name="config_control_num" type="text" value="">\n\
					<label>项</label>\n\
				</div>';
				break;

			case 'date':
				_div += '日期date';
				break;
			case 'image':
				_div += '<hr /><div>选择图片\n\
					<input type = "radio" name="config_img_type" value = "1" />本地图片\n\
					<input type = "file" name="config_img" value = "" />\n\
					<input type = "radio" name="config_img_type" value = "2" />外链图片\n\
					<input type = "text" name="config_img" value = "" />\n\
				</div>';
				_div += '<hr /><div>显示方式\n\
					<input type = "radio" name="config_img_align" value = "1" />拉伸\n\
					<input type = "radio" name="config_img_align" value = "2" />居中\n\
				</div>';
				_div += '<hr /><div>点击链接\n\
					<input type = "text" name="config_img_href" value = "" />\n\
				</div>';
				break;
			case 'file':
				_div += '<hr /><div>文件类型\n\
					<input type = "checkbox" name="config_file_type" value = "1" /><文档>\n\
					<input type = "checkbox" name="config_file_type" value = "2" /><图片>\n\
					<input type = "checkbox" name="config_file_type" value = "3" /><视频>\n\
					<input type = "checkbox" name="config_file_type" value = "4" /><音频>\n\
					<input type = "checkbox" name="config_file_type" value = "5" /><其他>\n\
				</div>';
				break;
			default:
				break;
		}
//		_div += '<hr /><div>对齐方式\n\
//				<input type = "radio" name="config_align" value = "left" />文字左对齐\n\
//				<input type = "radio" name="config_align" value = "center" />居中\n\
//				<input type = "radio" name="config_align" value = "right" />右对齐\n\
//			</div>';

		//===保存按钮===
		_div += '<li><input type="submit" name="save_column" value="保存"/>\n\
				<input type="submit" name="delete_column" value="删除"/>\n\
				<input type="hidden" name="column_id" value="' + data.column_id + '"/>\n\
				<input type="hidden" name="type" value="' + data.type + '"/></li>';
		$('[name="element-edit"]').html(_div);

		//===保存组件修改===
		$('[name="save_column"]').unbind('click').click(function () {
			var form_data = $('form[name="box_column"]').serializeArray();
			$.post('../form-save-column', {form_id: form_id, data: form_data}, function (json) {
				checkJSON(json, function (json) {
					console.log(json);
					_div_show(json.data);
				});
			}, 'json');
		});

		//===删除组件===
		$('[name="delete_column"]').unbind('click').click(function () {
			//===弹窗确认===
			(function column_delete(del_num) {
				if (del_num === undefined) {
					var warningbox = new WarningBox(column_delete);
					warningbox.ng_fuc();
				} else {
					if (del_num) {
						//===数据库删除===
						$.post('../form-delete-column', {form_id: form_id, column_id: data.column_id}, function (json) {
							checkJSON(json, function (json) {
								$('li[name=li_' + data.column_id + ']').remove();
								$('[name="element-edit"]').html('');
								var hint_box = new Hint_box('删除成功');
								hint_box;
							});
						}, 'json');
					} 
				}
			})();
		});
	}


}