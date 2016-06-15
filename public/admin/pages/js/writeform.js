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
                        _html_show(v);
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
     * 显示输出内容
     * _show_{type} 显示/更新表单组件
     * 绑定点击编辑动作
     * 添加排序移动功能
     * _edit_{type} 编辑表单组件
     * 
     * type={text, textarea, radio, checkbox, select, date, address, image, file}
     * @returns {undefined}
     */
    function _html_show(data) {
        var _data = data;
        var _div_li = '<li class="list-item" data-type="' + _data.type + '" data-id="' + _data.column_id + '" data-order="' + _data.order + '">';
        var _div = eval('_show_' + _data.type + '(_data)');//eval调用方法
        _div_li += _div + '</li>';
        if ($('li[data-id="' + data.column_id + '"').length > 0) {
            $('li[data-id="' + data.column_id + '"').html(_div);
        } else {
            $('.element-show').append(_div_li);
        }
        //===绑定元素点击响应事件===
        $(".element-show>li").unbind('click').on('click', function () {
            $('.tab-head-item').removeClass('tab-head-item-active');
            $('#item_2').addClass('tab-head-item-active');
            $('.tab-content-item').hide();
            $('#con_item_2').show();
            var _this_column = $(this);
            $.get('../form-column', {form_id: form_id, column_id: _this_column.attr('data-id')}, function (json) {
                _html_edit(json.data);
            }, 'json');
        });
    }

    function _show_text(data) {
        var _data = data;
        var _div = '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        _div += '</p>';
        if (_data.description != null && _data.description != '') {
            _div += '<input type="text"  placeholder="' + _data.description + '"/>';// name="col_' + _data.column_id + '"
        } else {
            _div += '<input type="text"  />';// name="col_' + _data.column_id + '"
        }
//        _div += '<input type="text"  placeholder="' + _data.description + '"/>';// name="col_' + _data.column_id + '"
        return _div;
    }

    function _show_textarea(data) {
        var _data = data;
        var _div = '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        _div += '</p>';
        if (_data.description != null && _data.description != '') {
            _div += '<textarea  placeholder=' + _data.description + '></textarea>';
        } else {
            _div += '<textarea ></textarea>';
        }
        return _div;
    }

    function _show_radio(data) {
        var _data = data;
        var _config = _data.config;//直到世界尽头
        var _option_key = _config.option_key.split(',');
        var _div = '';
        var _to = '';
        var _option_default = _config.option_default;
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        if (_data.description != null && _data.description != '') {
            _div += '<i class="content-d">' + data.description + '</i>';
        }
        _div += '</p>';
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<span class="option-item">';
            if (_option_default == tv) {
                _div += '<input type = "radio"  checked />';
            } else {
                _div += '<input type = "radio" />';
            }
            _div += '<label>' + _config[_to] + '</label>';
            _div += '</span>';
        });
        return _div;
    }


    function _show_checkbox(data) {
        var _data = data;
        var _config = _data.config;
        var _div = '';
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        if (_data.description != null && _data.description != '') {
            _div += '<i class="content-d">' + data.description + '</i>';
        }
        _div += '</p>';
        var _option_key = _config.option_key.split(',');
        var _to = '';
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<span class="option-item">';
            _div += '<input type = "checkbox" />';
            _div += '<label>' + _config[_to] + '</label>';
            _div += '</span>';
        });
        return _div;
    }

    function _show_select(data) {
        var _data = data;
        var _config = _data.config;
        var _div = '';
//        var _option_default = _config.option_default;
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        if (_data.description != null && _data.description != '') {
            _div += '<i class="content-d">' + data.description + '</i>';
        }
        _div += '</p>';
        _div += '<select name="col_' + data.column_id + '" >';
        var _option_key = _config.option_key.split(',');
        var _to = '';
        _div += '<option value="">===请选择===</option>';
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<option value=' + tv + '>' + _config[_to] + '</option>';
        });
        _div += '</select>';
        return _div;
    }

    function _show_date(data) {
        var _data = data;
        var _div = '';
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        if (_data.description != null && _data.description != '') {
            _div += '<i class="content-d">' + data.description + '</i>';
        }
        _div += '</p>';
        _div += '<input onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})" name="col_' + data.column_id + '"  />';
        return _div;
    }

    function _show_address(data) {
        var _data = data;
        var _div = '';
        return _div;
    }
    function _show_image(data) {
        var _data = data;
        var _div = '';
        return _div;
    }

    function _show_file(data) {
        var _data = data;
        var _div = '';
        return _div;
    }
    
}