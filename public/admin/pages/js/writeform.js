function writeformController($scope, $http, $location) {
	$scope.$parent.showbox = "main";
//	$scope.$parent.homepreview = true;
	$scope.$parent.menu = [];
	var form_id = getUrlParam('form_id');
	$('[name="form_id"]').val(form_id);
	init();
//===初始化===
	function init() {
		getFormData();
		getFormColumn();
	}


	$('.addsave').click(function () {
		alert('浏览表单无法提交');
	});

	/**
	 * 
	 * @returns {undefined}
	 */
	function getFormData() {
		$.get('../form-view', {form_id: form_id}, function (json) {
			checkJSON(json, function (json) {
				_div_info(json.data);
			});
		});
	}

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
	 * 
	 * @param {type} data
	 * @returns {undefined}
	 */
	function _div_info(data) {
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
		var temp = '';
		var to = '';
		console.log(data)
		_div_li += '<li class="list-item" data-type="' + data.type + '" data-id="' + data.id + '" name="li_' + data.id + '">';
		switch (data.type) {
			case 'text':
				_div += '<p class="content-l">' + data.title + '：</p>\n\
					<input  type="text" name="col_' + data.id + '" value=""  placeholder="' + data.description + '"/>';
				break;
			case 'textarea':
				_div += '<p class="content-l">' + data.title + '：</p>\n\
					<textarea name="col_' + data.id + '" placeholder=' + data.description + '></textarea>';
				break;
			case 'radio':

				_div += '<p class="content-l">' + data.title + '：(' + data.description + ')</p>';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<span class="option-item">';
					_div += '<input type = "radio" name = "col_' + data.id + '" value = ' + i + '  />\n\
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
					_div += '<input type = "checkbox" name = "col_' + data.id + '" value = ' + i + '  /><label>' + _config[to] + '</label>';
					_div += '</span>';

				}
				temp = _config.option_default.split(',');
				break;
			case 'select':
				_div += data.title + '：(' + data.description + ')<br />';
				_div += '<select name="col_' + data.id + '" >';
				var to = '';
				for (var i = 0; i < _config.option_count; i++) {
					to = "option_" + i;
					_div += '<option value=' + i + '>' + _config[to] + '</option>';
				}
				_div += '</select>';
				temp = _config.option_default;
				break;
			case 'date':
				_div += '日期date';
				break;
			case 'image':
				_div += '<label class="content-l">' + data.title + '：</label>';
				_div += '<a href="javascript:void(0);" ><img src=""  title="' + data.description + '" /></a>';
				break;
			case 'file':
				_div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label><input type="file" name="col_' + data.id + '" />';
				break;
			default:
				break;
		}
		_div_li += _div;
		_div_li += '</li>';
		if ($('li[name="li_' + data.id + '"').length > 0) {
			$('li[name="li_' + data.id + '"').html(_div);
		} else {
			$('.element-show').append(_div_li);
		}

		//===改变选项默认选定div_show===
		if (temp != '') {
			switch (data.type) {
				case 'radio':
					$('[name="col_' + data.id + '"]:eq(' + temp + ')').attr('checked', true);
					break;
				case 'checkbox':
					$.each(temp, function (tk, tv) {
						$('[name="col_' + data.id + '"]:eq(' + tv + ')').attr('checked', true);
					});
					break;
				case 'select':
					$('[name="col_' + data.id + '"] option:eq(' + temp + ')').attr('selected', true);
					break;
				default:
					break;
			}
		}
	}
}