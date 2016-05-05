/**
 * ===编辑表单js控制器===
 * 1、验证用户是否登录，修改的表单是否是自己的表单
 * 2、初始化，加载左边（表单数据）、右边（表单列）
 * 3、根据操作（click,hover...）响应对应事件
 */
function addformController($scope, $http, $location) {
	$scope.$parent.showbox = "main";
	$scope.$parent.homepreview = true;
	$scope.$parent.menu = [];
	//表单ID
	var form_id = getUrlParam('form_id');
	$('[name="form_id"]').val(form_id);
	init();
	/**
	 * 初始化
	 * 1、加载表单数据
	 * 2、获取组件元素
	 * @returns {undefined}
	 */
	function init() {
		getFormData();
		getFormElement();
		getFormColumn();
	}


	//******************************
	//******操作事件（无ajax）******
	//******************************
	//===表单标题===
	$('[name="title"]').blur(function () {
		$('.as-title').html($(this).val());
	});
	//===表单描述===
	$('[name="description"]').blur(function () {
		$('.as-description').html($(this).val());
	});
	//===编辑表单信息===
	$('.as-title,.as-description').click(function () {
		$('.tab-head-item').removeClass('tab-head-item-active');
		$('.tab-head-item[name="item_0"]').addClass('tab-head-item-active');
		$('.tab-content-item').hide();
		$('div[name="item_0"]').show();
	});
	//===切换选项卡===
	$('.tab-head-item').click(function () {
		var name = $(this).attr('name');
		$('.tab-head-item').removeClass('tab-head-item-active');
		$(this).addClass('tab-head-item-active');
		$('.tab-content-item').hide();
		$('div[name=' + name + ']').show();
	});
	//===预览表单===
	$('[name="writeform"]').click(function () {
		window.open('#/writeform?form_id=' + form_id);
	});

	//===保存表单信息（form数据）===
	$('[name="save_form"]').click(function () {
		var box_info = $('form[name="box_info"]').serializeArray();
		$.post('../form-edit', {form_id: form_id, box_info: box_info}, function (json) {
			checkJSON(json, function (json) {
				Hint_box(json.msg);
			});
		});
	});
	//===保存表单（column数据）===
	$('.addsave').click(function () {
		Hint_box('保存成功');
		setTimeout('location.href = "#/form"', 2000);
	});

	/**
	 * 验证表单是否是自己的、并且获取表单信息
	 * @returns {undefined}
	 */
	function getFormData() {
		$.get('../form-data', {form_id: form_id}, function (json) {
			checkJSON(json, function (json) {
				_div_info(json.data);
			}, function () {
				location.href = "#/form";
			});
		});
	}
	/**
	 * 获取组件元素
	 * @returns {undefined}
	 */
	function getFormElement() {
		$http.get('../form-element-list').success(function (json) {
			checkJSON(json, function (json) {

				var form_element = json.data;
				var _div = '';
				if (form_element != null) {
					$.each(form_element, function (k, v) {
						_div += '<li class="utility" data-id="' + v.id + '">\
								<span class="title">' + v.title + '</span>\
								<i class="iconfont icon-liebiao grey tpl_info"></i>\
							</li>';
					});
				} else {
					_div = json.msg;
				}
				$('[name="element-box"]').append(_div);
			});
			$('.unit-list>li').click(function () {
				var _this = $(this);
				bindElementEvent(_this);
			});
		});
	}
	/**
	 * 获取表单列
	 * @returns {undefined}
	 */
	function getFormColumn() {
		$.get('../form-column-list', {form_id: form_id}, function (json) {
			checkJSON(json, function (json) {
				if (json.data != null) {
					$.each(json.data, function (k, v) {
						_div_show(v);
					});
				}
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
		$.post('../form-column-add', {form_id: form_id, element_id: element_id}, function (json) {
			console.log(json);
			var data = json.data;
			$('.tab-head-item').removeClass('tab-head-item-active');
			$('.tab-head-item[name="item_2"]').addClass('tab-head-item-active');
			$('.tab-content-item').hide();
			$('div[name="item_2"]').show();
			_div_show(data);
			_div_edit(data);
		});
	}
	/**
	 * 显示表单信息
	 * @param {type} data
	 * @returns {undefined}
	 */
	function _div_info(data) {
		$('[name="name"]').val(data.name);
		$('[name="title"]').val(data.title);
		$('[name="description"]').val(data.description);
		var temp = data.platform.split(',');
		$.each(temp, function (tk, tv) {
			$('[name="platform"]:eq(' + tv + ')').attr('checked', true);
		});
		$('[name="showmodel"]:eq(' + data.showmodel + ')').attr('checked', true);
		$('[name="action_type"]:eq(' + data.action_type + ')').attr('checked', true);
		$('[name="action_text"]').val(data.action_text);
		if (data.is_once == 1) {
			$('[name="is_once"]').attr('checked', true);
		}
		if (data.status == 1) {
			$('[name="status"]:eq(0)').attr('checked', true);
		} else {
			$('[name="status"]:eq(1)').attr('checked', true);
		}
		//===显示区域===
		$('.as-title').html(data.title);
		$('.as-description').html(data.description);
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
		var temp = '';//默认值
		var to = '';
		var option_key = '';//选项值

		_div_li += '<li class="list-item" data-type="' + data.type + '" data-id="' + data.column_id + '" name="li_' + data.column_id + '" data-order="' + data.order + '">';
		switch (data.type) {
			case 'text':
				_div += '<p class="content-l">' + data.title + '：</p>\n\
					<input  type="text" name="col_' + data.column_id + '" value=""  disabled="disabled" placeholder="' + data.description + '"/>';
				break;
			case 'textarea':
				_div += '<p class="content-l">' + data.title + '：</p>\n\
					<textarea name="col_' + data.column_id + '" disabled="disabled" placeholder=' + data.description + '></textarea>';
				break;
			case 'radio':
				option_key = _config.option_key.split(',');
				_div += '<p class="content-l">' + data.title + '：(' + data.description + ')';
				$.each(option_key, function (tk, tv) {
					to = "option_" + tv;
					_div += '<span class="option-item">';
					_div += '<input type = "radio" name = "col_' + data.column_id + '" value = ' + tv + '  disabled="disabled"/>\n\
						<label>' + _config[to] + '</label>';
					_div += '</span>';
				});
				temp = _config.option_default;
				break;
			case 'checkbox':
				option_key = _config.option_key.split(',');
				_div += '<p class="content-l">' + data.title + '：(' + data.description + ')</p>';
				$.each(option_key, function (tk, tv) {
					to = "option_" + tv;
					_div += '<span class="option-item">';
					_div += '<input type = "checkbox" name = "col_' + data.column_id + '" value = ' + tv + '  disabled="disabled"/>\n\
						<label>' + _config[to] + '</label>';
					_div += '</span>';
				});
//				for (var i = 0; i < _config.option_count; i++) {
//					to = "option_" + i;
//					_div += '<span class="option-item">';
//					_div += '<input type = "checkbox" name = "col_' + data.column_id + '" value = ' + i + '  disabled="disabled"/><label>' + _config[to] + '</label>';
//					_div += '</span>';
//
//				}
				temp = _config.option_default.split(',');
				break;
			case 'select':
				_div += '<p class="content-l">' + data.title + '：(' + data.description + ')</p>';
				_div += '<select name="col_' + data.column_id + '" disabled="disabled">';
				var to = '';
				option_key = _config.option_key.split(',');
				$.each(option_key, function (tk, tv) {
					to = "option_" + tv;
					_div += '<option value=' + tv + '>' + _config[to] + '</option>';
				});
//				for (var i = 0; i < _config.option_count; i++) {
//					to = "option_" + i;
//					_div += '<option value=' + i + '>' + _config[to] + '</option>';
//				}
				_div += '</select>';
				temp = _config.option_default;
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

		if (data.required == 1) {
			$("[data-id='" + data.column_id + "']>.content-l").append("<b>*</b>");
		} else {
			$("b").remove("[data-id='" + data.column_id + "']>.content-l");
		}
		//===改变选项默认选定div_show===
//		if (temp != '') {
//			switch (data.type) {
//				case 'radio':
//					$('[name="col_' + data.column_id + '"]:eq(' + temp + ')').attr('checked', true);
//					break;
//				case 'checkbox':
//					$.each(temp, function (tk, tv) {
//						$('[name="col_' + data.column_id + '"]:eq(' + tv + ')').attr('checked', true);
//					});
//					break;
//				case 'select':
//					$('[name="col_' + data.column_id + '"] option:eq(' + temp + ')').attr('selected', true);
//					break;
//				default:
//					break;
//			}
//		}


		//===绑定元素点击响应事件===
		$(".element-show>li").unbind('click').on('click', function () {
			$('.tab-head-item').removeClass('tab-head-item-active');
			$('.tab-head-item[name="item_2"]').addClass('tab-head-item-active');
			$('.tab-content-item').hide();
			$('div[name="item_2"]').show();
			var _this_column = $(this);
			_this_column.siblings().removeClass("click");
			_this_column.addClass("click");
			$.get('../form-column', {form_id: form_id, column_id: _this_column.attr('data-id')}, function (json) {
				_div_edit(json.data);
			}, 'json');
		});
		//===绑定元素鼠标滑动事件===
		$(".element-show>li").unbind('hover').hover(
				function () {
					$(this).addClass("hover");
				},
				function () {
					$(this).removeClass("hover");
				});
	}
	/**
	 * 编辑组件
	 * @param {type} data
	 * @returns {undefined}
	 */
	function _div_edit(data) {
		console.log(data);
		console.log('===_div_edit===');
		var _config = data.config;
		var _div = '';
		var temp = '';
		var option_key = (_config.option_key) ? _config.option_key.split(',') : '';
		var flag_num = (option_key) ? option_key[_config.option_count - 1] : '';//数量标记

		_div += '<li class="list-item"><p class="content-l">标题：</p><input name="title" type="text" value="' + data.title + '" /></li>';
		_div += '<li class="list-item"><p class="content-l">描述：</p><input name="description" type="text" value="' + data.description + '" /></li>';
		_div += '<li class="list-item"><p class="content-l"><span class="option-item">';
		if (data.required == 1) {
			_div += '<input type="checkbox" name="required" value="1" checked="checked"/>是否必填</span></p></li>';
		} else {
			_div += '<input type="checkbox" name="required" value="1"/>是否必填</span></p></li>';
		}
		_div += '<li class="list-item"><p class="content-l">排序：<span class="option-item"><input type="text" name="order" value="' + data.order + '"/></span></p></li>';

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
					<span class="option-item"><input type = "radio" name="config_rules" value = "no" />无</span>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "mail" />邮箱</span>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "mobile" />手机</span>\n\
					<span class="option-item"><input type = "radio" name="config_rules" value = "number" />数字</span>\n\
				</li>';
				break;
			case 'textarea':
				break;
			case 'select':
				_div += '<hr /><li class="list-item"><p class="content-l">下拉菜单选项设置<button class="square" name="option_add">+</button></p>';
				option_key = _config.option_key.split(',');
				$.each(option_key, function (tk, tv) {
					to = "option_" + tv;
					_div += '<p class="option-item" data-num=' + tv + '><input type = "radio" name="config_option_default" value = "' + tv + '" />';
					_div += '<input type="text" name="option_' + tv + '" value="' + _config[to] + '" /><button class="square" name="option_del">-</button></p>';
				});
//				for (var i = 0; i < _config.option_count; i++) {
//					to = 'option_' + i;
//					_div += '<p class="option-item"><input type = "radio" name="config_option_default" value = "' + i + '" />';
//					_div += '<input type="text" name="option_' + i + '" value="' + _config[to] + '" /><button class="square" name="option_del">-</button></p>';
//				}
				_div += '</li>';
				_div += '<input type = "hidden" name="config_option_count" value = "" />';
				break;
			case 'radio':
				var to = '';
				_div += '<hr /><li class="list-item"><p class="content-l">单选<button class="square" name="option_add">+</button></p>';
				option_key = _config.option_key.split(',');
				$.each(option_key, function (tk, tv) {
					to = "option_" + tv;
					_div += '<p class="option-item" data-num=' + tv + '><input type = "radio" name="config_option_default" value = "' + tv + '" />';
					_div += '<input type="text" data-option="" name="option_' + tv + '" value="' + _config[to] + '" /><button class="square" name="option_del">-</button></p>';
				});
//				for (var i = 0; i < _config.option_count; i++) {
//					to = 'option_' + i;
//					_div += '<p class="option-item" data-num=' + i + '><input type = "radio" name="config_option_default" value = "' + i + '" />';
//					_div += '<input type="text" data-option="" name="option_' + i + '" value="' + _config[to] + '" /><button class="square" name="option_del">-</button></p>';
//				}
				_div += '</li>';
				_div += '<input type = "hidden" name="config_option_count" value = "" />';
//				_div += '<hr /><li class="list-item"><p class="content-l">排版分布</p>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "0" />单列</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "1" />两列</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "2" />三列</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "3" />四列</span>\n\
//				</li>';
//				_div += '<hr /><li class="list-item"><p class="content-l">选项类型</p>\n\
//					<span class="option-item"><input type = "radio" name="config_option_type" value = "0" />文字</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_type" value = "1" />图片</span>\n\
//				</li>';
				break;
			case 'checkbox':
				_div += '<hr /><li class="list-item"><p class="content-l">多选<button class="square" name="option_add">+</button></p>';
				$.each(option_key, function (tk, tv) {
					to = "option_" + tv;
					_div += '<p class="option-item" data-num=' + tv + '><input type = "checkbox" name="config_option_default" value = "' + tv + '" />';
					_div += '<input type="text" name="option_' + tv + '" value="' + _config[to] + '" /><button class="square" name="option_del">-</button></p>';
				});
//				for (var i = 0; i < _config.option_count; i++) {
//					to = 'option_' + i;
//					_div += '<p class="option-item"><input type = "checkbox" name="config_option_default" value = "' + i + '" />';
//					if (_config.option_type == 1) {
//						_div += '<img name="option_img_' + i + '" src="" />';
//					}
//					_div += '<input type="text" name="option_' + i + '" value="' + _config[to] + '" /><button class="square" name="option_del">-</button></p>';
////					_div += '<input type="text" name="option_' + i + '" value="' + _config[to] + '" /></p>';
//				}
				_div += '</li>';
				_div += '<input type = "hidden" name="config_option_count" value = "" />';
//				_div += '<hr /><li class="list-item"><p class="content-l">排版分布</p>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "0" />单列</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "1" />两列</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "2" />三列</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_layout" value = "3" />四列</span>\n\
//				</li>';
//				_div += '<hr /><li class="list-item"><p class="content-l">选项类型</p>\n\
//					<span class="option-item"><input type = "radio" name="config_option_type" value = "0" />文字</span>\n\
//					<span class="option-item"><input type = "radio" name="config_option_type" value = "1" />图片</span>\n\
//				</li>';
//				_div += '<hr /><li class="list-item"><p class="content-l">选项控制</p>\n\
//					<p class="option-item"><input name="config_control" type="checkbox" value="1">\n\
//					<select name="config_control_type" autocomplete="off">\n\
//						<option value="0" selected="selected">至少</option>\n\
//						<option value="1">最多</option>\n\
//						<option value="2">恰好</option>\n\
//					</select>\n\
//					<label>选择</label>\n\
//					<input name="config_control_num" type="text" value="">\n\
//					<label>项</label></p>\n\
//				</li>';
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
				break;
			default:
				break;
		}

		//===保存按钮===
		_div += '<li class="list-item">\n\
				<span class="option-item"><input type="button" name="save_column" value="保存"/></span>\n\
				<span class="option-item"><input type="button" name="delete_column" value="删除"/></span>\n\
				<input type="hidden" name="column_id" value="' + data.column_id + '"/>\n\
				<input type="hidden" name="type" value="' + data.type + '"/></li>';
		$('[name="element-edit"]').html(_div);
		if (('[name="config_option_default"]').length > 0) {
			$('[name="config_option_count"]').val($('[name="config_option_default"]').length);
		}
		//===改变选项默认选定_div_edit===
		switch (data.type) {
			case 'text':
				switch (_config.text_type) {
					case 'text':
						$('[name="config_text_type"]:eq(0)').attr('checked', true);
						break;
					case 'password':
						$('[name="config_text_type"]:eq(1)').attr('checked', true);
						break;
					default:
						break;
				}
				switch (_config.text_rules) {
					case 'mail':
						$('[name="config_rules"]:eq(1)').attr('checked', true);
						break;
					case 'mobile':
						$('[name="config_rules"]:eq(2)').attr('checked', true);
						break;
					case 'number':
						$('[name="config_rules"]:eq(3)').attr('checked', true);
						break;
//					case 'defined':
//						$('[name="config_rules"]:eq(4)').attr('checked', true);
//						$('[name="config_regex"]').val(_config.text_rules_regex);
//						$('[name="config_hint"]').val(_config.text_rules_hint);
//						break;
					default:
						$('[name="config_rules"]:eq(0)').attr('checked', true);
						break;
				}
				break;
//			case 'radio':
//				if (_config.option_default != '') {
//					$('[name="config_option_default"]:eq(' + _config.option_default + ')').attr('checked', true);
//				}
//				$('[name="config_option_layout"]:eq(' + _config.option_layout + ')').attr('checked', true);
//				$('[name="config_option_type"]:eq(' + _config.option_type + ')').attr('checked', true);
//				break;
//			case 'checkbox':
//				$.each(temp, function (k, v) {
//					$('[name="config_option_default"]:eq(' + v + ')').attr('checked', true);
//				});
//				$('[name="config_option_layout"]:eq(' + _config.option_layout + ')').attr('checked', true);
//				$('[name="config_option_type"]:eq(' + _config.option_type + ')').attr('checked', true);
//				if (_config.option_limit == 1) {
//					$('[name="config_control"]').attr('checked', true);
//					$('[name="config_control_type"]>option:eq(' + (_config.option_type) + ')').attr('selected', true);
//					$('[name="config_control_num"]').val(_config.option_num);
//				}
//				break;
//			case 'select':
//				$('[name="config_option_default"]:eq(' + _config.option_default + ')').attr('checked', true);
//				break;
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
		//===添加选项===
		$('[name="option_add"]').unbind('click').click(function () {
			var _option = '';
			++flag_num;
			var option_count = $('[name="config_option_count"]').val();//===选项个数
			++option_count;
			var _this = $(this).parents('.list-item');//===选项
			switch (data.type) {
				case 'radio':
				case 'select':
					_option += '<p class="option-item" data-num=' + flag_num + '><input type = "radio" name="config_option_default" value = "' + flag_num + '" />';
					_option += '<input type="text" name="option_' + flag_num + '" value="选项' + flag_num + '" /><button class="square" name="option_del">-</button></p>';
					break;
				case 'checkbox':
					_option += '<p class="option-item" data-num=' + flag_num + '><input type = "checkbox" name="config_option_default" value = "' + flag_num + '" />';
					_option += '<input type="text" name="option_' + flag_num + '" value="选项' + flag_num + '" /><button class="square" name="option_del">-</button></p>';
					break;
				default:
					break;
			}
			_this.append(_option);
			_del_option();
			$('[name="config_option_count"]').val(option_count);
		});
		_del_option();
		//===保存组件修改===
		$('[name="save_column"]').unbind('click').click(function () {
			var form_data = $('form[name="box_column"]').serializeArray();
			$.post('../form-column-edit', {form_id: form_id, data: form_data}, function (json) {
				console.log(json);
				console.log('form-column-edit');
				checkJSON(json, function (json) {
					_div_show(json.data);
					Hint_box(json.msg);
				});
			});
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
						$.post('../form-column-delete', {form_id: form_id, column_id: data.column_id}, function (json) {
							console.log(json);
							checkJSON(json, function (json) {
								$('li[name=li_' + data.column_id + ']').remove();
								$('[name="element-edit"]').html('');
								Hint_box('删除成功');
								$('.tab-head-item').removeClass('tab-head-item-active');
								$('.tab-head-item[name="item_1"]').addClass('tab-head-item-active');
								$('.tab-content-item').hide();
								$('div[name="item_1"]').show();
							});
						}, 'json');
					}
				}
			})();
		});
		//===上移、下移===
//		$('[name="moveup"]').click(function () {
//			$.post('../form-column-move', {form_id: form_id, column_id: data.column_id, operate: 'up'}, function (json) {
//				checkJSON(json, function (json) {
//					Hint_box(json.msg);
//
//				});
//			});
//		});
//		$('[name="movedown"]').click(function () {
//			$.post('../form-column-move', {form_id: form_id, column_id: data.column_id, operate: 'down'}, function (json) {
//				checkJSON(json, function (json) {
//					Hint_box(json.msg);
//				});
//			});
//		});
	}

	function _del_option() {
		$('[name="option_del"]').unbind('click').click(function () {
			var option_count = $('[name="config_option_count"]').val();//===选项个数
			--option_count;
			$('[name="config_option_count"]').val(option_count);
			var _this = $(this).parent();
			_this.remove();
		});
	}
}