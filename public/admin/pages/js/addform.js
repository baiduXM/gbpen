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
	//===切换选项卡===
	$('.tab-head-item').click(function () {
		var name = $(this).attr('name');
		$('.tab-head-item').removeClass('tab-head-item-active');
		$(this).addClass('tab-head-item-active');
		$('.tab-content-item').hide();
		$('div[name=' + name + ']').show();
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
				if (json.err !== 0) {
					alert(json.msg);
					location.href = "#/form";
				}
				var column_data = json.data.column;


				console.log(column_data);
				$.each(column_data, function (k, v) {
					console.log(v.type);
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
				$('.title p').html(data.title);
				$('.description p').html(data.description);
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
			$('.tab-head-item').removeClass('tab-head-item-active');
			$('p[name="item_2"]').addClass('tab-head-item-active');
			$('.tab-content-item').hide();
			$('div[name="item_2"]').show();
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
		var _config = data.config;
		var _div = '';
		var _div_li = '';
		var temp = '';
		var to = '';
		_div_li += '<li data-type="' + data.type + '" data-id="' + data.column_id + '" name="li_' + data.column_id + '">';
		switch (data.type) {
			case 'text':
				_div += '<label class="content-l">' + data.title + '：</label><input class="Input" placeholder=' + data.description + ' type="text" name="col_' + data.column_id + '" value=""  disabled="disabled"/>';
				break;
			case 'textarea':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：<br /></label><textarea name="col_' + data.column_id + '" disabled="disabled"></textarea>';
				break;
			case 'radio':
				_div += data.title + '：(' + data.description + ')<br />';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<input type = "radio" name = "col_' + data.column_id + '" value = ' + i + '  disabled="disabled"/><label>' + _config[to] + '</label>';
				}
				temp = _config.option_default;
//				_div="<br>----radio---<br>";
				break;
			case 'checkbox':
				_div += data.title + '：(' + data.description + ')<br />';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<input type = "checkbox" name = "col_' + data.column_id + '" value = ' + i + '  disabled="disabled"/><label>' + _config[to] + '</label>';
				}
				temp = _config.option_default.split(',');
//				_div="<br>----checkbox---<br>";
				break;
			case 'select':
				_div += data.title + '：(' + data.description + ')<br />';
				_div += '<select name="col_' + data.column_id + '" disabled="disabled">';
				var to = '';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<option value=' + i + '>' + _config[to] + '</option>';
				}
				_div += '</select>';
				temp = _config.option_default.split(',');
				break;
			case 'date':
				_div += '日期date';
				break;
			case 'image':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label>';
				_div += '<a href="javascript:void(0);" ><img src=""  alt="上传图片" /></a>';
				break;
			case 'file':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><input type="file" name="col_' + data.column_id + '" disabled="disabled"/>';
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

		//===改变选项默认选定div_show===
//		if (temp !== '') {
//			switch (data.type) {
//				case 'radio':
//					$('input[name="col_' + data.column_id + '"]:eq(' + temp + ')').attr('checked', true);
//					break;
//				case 'checkbox':
//					$.each(temp, function (k, v) {
//						$('input[name="col_' + data.column_id + '"]:eq(' + v + ')').attr('checked', true);
//					});
//					break;
//				case 'select':
////					alert($('option').change().val());
//					$.each(temp, function (k, v) {
//						$('[name="col_' + data.column_id + '"] option:eq(' + v + ')').attr('selected', true);
//					});
//					break;
//				default:
//					break;
//			}
//		}

		//===绑定元素点击响应事件===
		$(".element-show>li").unbind('click').on('click', function () {
			$('.tab-head-item').removeClass('tab-head-item-active');
			$('p[name="item_2"]').addClass('tab-head-item-active');
			$('.tab-content-item').hide();
			$('div[name="item_2"]').show();
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
		var _config = data.config;
		var _div = '';
		var temp = '';
		_div += '<li class="list-item"><p class="content-l">标题：</p><input name="title" type="text" value="' + data.title + '" /></li>';
		_div += '<li class="list-item"><p class="content-l">描述：</p><input name="description" type="text" value="' + data.description + '" /></li>';
		_div += '<li class="list-item"><p class="content-l"><span class="option-item"><input type="checkbox" name="required" value="1"/>是否必填</span></p></li>';
		if (data.required === 1) {
			$('[name="required"]').attr('checked', true);
		}
		if (data.config !== null) {
			//===TODO===
		}
		switch (data.type) {
			case 'text':
				_div += '<hr /><li class="list-item"><p class="content-l">类型</p>\n\
					<span class="option-item"><input type = "radio" name="config_text_type" value = "text" />文本</span>\n\
					<span class="option-item"><input type = "radio" name="config_text_type" value = "password" />密码</span>\n\
				</li>';
				_div += '<hr /><li class="list-item"><p class="content-l">规则</p>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "mail" />邮箱</span>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "mobile" />手机</span>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "number" />数字</span>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "defined" />自定义</span>\n\
					<p class="content-l">正则表达式：<input type = "text" name="config_regex" value = "" /></p>\n\
					<p class="content-l">提示：<input type = "text" name="config_hint" value = "" /></p>\n\
				</li>';
				break;
			case 'textarea':
				break;
			case 'select':
				_div += '<hr /><li class="list-item"><p class="content-l">下拉菜单选项设置</p>';
				for (var i = 0; i < _config.option_count; i++) {
					to = 'option_' + i;
					_div += '<p class="option-item"><input type = "radio" name="config_option_default" value = "' + i + '" />';
					_div += '<input type="text" name="option_' + i + '" value="' + _config[to] + '" /></p>';
				}
				_div += '</li>';
				_div += '<input type = "hidden" name="config_option_count" value = "" />';
				break;
			case 'radio':
				var to = '';
				_div += '<hr /><li class="list-item"><p class="content-l">单选</p>';
				for (var i = 0; i < _config.option_count; i++) {
					to = 'option_' + i;
					_div += '<p class="option-item"><input type = "radio" name="config_option_default" value = "' + i + '" />';
					if (_config.option_type == 1) {
						_div += '<img name="option_img_' + i + '" src="" />';
					}
					_div += '<input type="text" name="option_' + i + '" value="' + _config[to] + '" /></p>';
				}
				_div += '</li>';
				_div += '<input type = "hidden" name="config_option_count" value = "" />';
				_div += '<hr /><li class="list-item"><p class="content-l">排版分布</p>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "0" />单列</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "1" />两列</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "2" />三列</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "3" />四列</span>\n\
				</li>';
				_div += '<hr /><li class="list-item"><p class="content-l">选项类型</p>\n\
					<span class="option-item"><input type = "radio" name="config_option_type" value = "0" />文字</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_type" value = "1" />图片</span>\n\
				</li>';
				break;
			case 'checkbox':
				_div += '<hr /><li class="list-item"><p class="content-l">多选</p>';
				for (var i = 0; i < _config.option_count; i++) {
					to = 'option_' + i;
					_div += '<p class="option-item"><input type = "checkbox" name="config_option_default" value = "' + i + '" />';
					if (_config.option_type == 1) {
						_div += '<img name="option_img_' + i + '" src="" />';
					}
					_div += '<input type="text" name="option_' + i + '" value="' + _config[to] + '" /></p>';
				}
				_div += '</li>';
				_div += '<input type = "hidden" name="config_option_count" value = "" />';
				_div += '<hr /><li class="list-item"><p class="content-l">排版分布</p>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "0" />单列</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "1" />两列</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "2" />三列</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_layout" value = "3" />四列</span>\n\
				</li>';
				_div += '<hr /><li class="list-item"><p class="content-l">选项类型</p>\n\
					<span class="option-item"><input type = "radio" name="config_option_type" value = "0" />文字</span>\n\
					<span class="option-item"><input type = "radio" name="config_option_type" value = "1" />图片</span>\n\
				</li>';
				_div += '<hr /><li class="list-item"><p class="content-l">选项控制</p>\n\
					<p class="option-item"><input name="config_control" type="checkbox" value="1">\n\
					<select name="config_control_type" autocomplete="off">\n\
						<option value="0" selected="selected">至少</option>\n\
						<option value="1">最多</option>\n\
						<option value="2">恰好</option>\n\
					</select>\n\
					<label>选择</label>\n\
					<input name="config_control_num" type="text" value="">\n\
					<label>项</label></p>\n\
				</li>';
				temp = _config.option_default.split(',');
				break;
			case 'image':
				_div += '<hr /><li class="list-item"><p class="content-l">选择图片</p>\n\
					<span class="option-item"><input type = "radio" name="config_img_type" value = "0" />本地图片</span>\n\
					<span class="option-item"><input type = "radio" name="config_img_type" value = "1" />外链图片</span>\n\
					<p class="option-item"><input type = "file" name="config_img_file" value = "" /></p>\n\
					<p class="option-item"><input type = "text" name="config_img_src" value = "" /></p>\n\
				</li>';
				_div += '<hr /><li class="list-item"><p class="content-l">显示方式</p>\n\
					<span class="option-item"><input type = "radio" name="config_img_align" value = "0" />拉伸</span>\n\
					<span class="option-item"><input type = "radio" name="config_img_align" value = "1" />居中</span>\n\
				</li>';
				_div += '<hr /><li class="list-item"><p class="content-l">点击链接</p>\n\
					<p class="option-item"><input type = "text" name="config_img_href" value = "" /></p>\n\
				</li>';
				break;
			case 'file':
				_div += '<hr /><li class="list-item"><p class="content-l">文件类型</p>\n\
					<span class="option-item"><input type = "checkbox" name="config_file_type" value = "0" /><文档></span>\n\
					<span class="option-item"><input type = "checkbox" name="config_file_type" value = "1" /><图片></span>\n\
					<span class="option-item"><input type = "checkbox" name="config_file_type" value = "2" /><视频></span>\n\
					<span class="option-item"><input type = "checkbox" name="config_file_type" value = "3" /><音频></span>\n\
					<span class="option-item"><input type = "checkbox" name="config_file_type" value = "4" /><其他></span>\n\
				</li>';
				temp = _config.file_type.split(',');
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
		_div += '<li class="list-item">\n\
				<span class="option-item"><input type="submit" name="save_column" value="保存"/></span>\n\
				<span class="option-item"><input type="submit" name="delete_column" value="删除"/></span>\n\
				<input type="hidden" name="column_id" value="' + data.column_id + '"/>\n\
				<input type="hidden" name="type" value="' + data.type + '"/></li>';
		$('[name="element-edit"]').html(_div);
		if (('[name="config_option_count"]').length > 0) {
			$('[name="config_option_count"]').val($('[name="config_option_default"]').length);
		}
		//===改变选项默认选定===
//		if (data.type) {
		console.log(_config);

		switch (data.type) {
			case 'text':
				if (_config.text_type === 'text') {
					$('[name="config_text_type"]:eq(0)').attr('checked', true);
				}
				if (_config.text_type === 'password') {
					$('[name="config_text_type"]:eq(1)').attr('checked', true);
				}
				if (_config.text_rules === 'mail') {
					$('[name="config_rules"]:eq(0)').attr('checked', true);
				}
				if (_config.text_rules === 'mobile') {
					$('[name="config_rules"]:eq(1)').attr('checked', true);
				}
				if (_config.text_rules === 'number') {
					$('[name="config_rules"]:eq(2)').attr('checked', true);
				}
				if (_config.text_rules === 'defined') {
					$('[name="config_rules"]:eq(3)').attr('checked', true);
					$('[name="config_regex"]').val(_config.text_rules_regex);
					$('[name="config_hint"]').val(_config.text_rules_hint);
				}
				break;
			case 'radio':
				if (_config.option_default !== '') {
					$('[name="config_option_default"]:eq(' + _config.option_default + ')').attr('checked', true);
				}
				$('[name="config_option_layout"]:eq(' + _config.option_layout + ')').attr('checked', true);
				$('[name="config_option_type"]:eq(' + _config.option_type + ')').attr('checked', true);
				break;
			case 'checkbox':
				$.each(temp, function (k, v) {
					$('[name="config_option_default"]:eq(' + v + ')').attr('checked', true);
				});
				$('[name="config_option_layout"]:eq(' + _config.option_layout + ')').attr('checked', true);
				$('[name="config_option_type"]:eq(' + _config.option_type + ')').attr('checked', true);
				if (_config.option_limit == 1) {
					$('[name="config_control"]').attr('checked', true);
					$('[name="config_control_type"]>option:eq(' + (_config.option_type) + ')').attr('selected', true);
					$('[name="config_control_num"]').val(_config.option_num);
				}
				break;
			case 'select':
				$('[name="config_option_default"]:eq(' + _config.option_default + ')').attr('checked', true);
				break;
			case 'image':
				$('[name="config_img_type"]:eq(' + _config.img_type + ')').attr('checked', true);
				$('[name="config_img_align"]:eq(' + _config.img_align + ')').attr('checked', true);
				break;
			case 'file':
				$.each(temp, function (k, v) {
					$('[name="config_file_type"]:eq(' + v + ')').attr('checked', true);
				});
				break;
			default:
				break;
		}
//		}

		//===保存组件修改===
		$('[name="save_column"]').unbind('click').click(function () {
			var option_default = '';
			var file_type = '';
			$('[name="config_option_default"]:checked').each(function () {
				if (option_default === '') {
					option_default += $(this).val();
				} else {
					option_default += ',' + $(this).val();
				}
			});
			$('[name="config_file_type"]:checked').each(function () {
				if (file_type === '') {
					file_type += $(this).val();
				} else {
					file_type += ',' + $(this).val();
				}
			});
			var form_data = $('form[name="box_column"]').serializeArray();
//			console.log(form_data);exit;
			$.post('../form-save-column', {form_id: form_id, data: form_data}, function (json) {
				checkJSON(json, function (json) {
					_div_show(json.data);
					Hint_box('修改成功');
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
								$('.tab-head-item').removeClass('tab-head-item-active');
								$('p[name="item_1"]').addClass('tab-head-item-active');
								$('.tab-content-item').hide();
								$('div[name="item_1"]').show();
							});
						}, 'json');
					}
				}
			})();
		});
	}
}