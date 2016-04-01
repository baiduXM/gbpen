function writeformController($scope, $http, $location) {
	$scope.$parent.showbox = "main";
//	$scope.$parent.homepreview = true;
	$scope.$parent.menu = [];
	var form_id = 6; //表单ID
	getFormData();

	/**
	 * ===获取表单数据===
	 * @returns {undefined}
	 */
	function getFormData() {
		$.post('../form-data-write', {form_id: form_id}, function (json) {
			var column_data = json.data.column_data;
			var form_data = json.data.form_data;
			$.each(column_data, function (k, v) {
				console.log(v);
//				$('[name="config_option_default"]:eq(' + v + ')').attr('checked', true);
				_div_show(v);
			});
		});
	}

	/**
	 * ===设置默认选项===
	 * @returns {undefined}
	 */
	function setDefaultOption() {

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
		_div_li += '<li class="list-item" data-type="' + data.type + '" data-id="' + data.column_id + '" name="li_' + data.column_id + '">';
		switch (data.type) {
			case 'text':
				_div += '<p class="content-l">' + data.title + '：</p>\n\
					<input  type="text" name="col_' + data.column_id + '" value=""  disabled="disabled" placeholder=' + data.description + '/>';
				break;
			case 'textarea':
				_div += '<p class="content-l">' + data.title + '：</p>\n\
					<textarea name="col_' + data.column_id + '" disabled="disabled" placeholder=' + data.description + '></textarea>';
				break;
			case 'radio':

				_div += '<p class="content-l">' + data.title + '：(' + data.description + ')</p>';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<span class="option-item">';
					_div += '<input type = "radio" name = "col_' + data.column_id + '" value = ' + i + '  disabled="disabled"/>\n\
						<label>' + _config[to] + '</label>';
					_div += '</span>';
				}
				temp = _config.option_default;
				_div += "<br>---radio-+++" + temp + "---<br>";
				break;
			case 'checkbox':
				_div += data.title + '：(' + data.description + ')<br />';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<span class="option-item">';
					_div += '<input type = "checkbox" name = "col_' + data.column_id + '" value = ' + i + '  disabled="disabled"/><label>' + _config[to] + '</label>';
					_div += '</span>';

				}
				temp = _config.option_default.split(',');
				_div += "<br>---checkbox-" + temp + "---<br>";
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
				_div += "<br>---select-" + temp + "---<br>";
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
		console.log(temp);
		if (temp != '') {
			console.log(12345654321);
			switch (data.type) {
				case 'radio':
					$('[name="col_' + data.column_id + '"]:eq(' + temp + ')').attr('checked', true);
					break;
				case 'checkbox':
					$.each(temp, function (tk, tv) {
						$('[name="col_' + data.column_id + '"]:eq(' + tv + ')').attr('checked', true);
					});
					break;
				case 'select':
//					alert($('option').change().val());
					$.each(temp, function (tk, tv) {
						$('[name="col_' + data.column_id + '"] option:eq(' + tv + ')').attr('selected', true);
					});
					break;
				default:
					break;
			}
		}

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

}