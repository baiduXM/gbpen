/*
 * ===编辑表单js控制器===
 * 对象：表单、元素、组件、表单列
 * 初始化信息：（表单、元素、表单列）
 * 增/删/改操作：（表单、组件）
 * 排序：（表单列）
 * 
 * 1、验证用户是否登录，修改的表单是否是自己的表单
 * 2、初始化，加载左边（表单数据）、右边（表单列）
 * 3、根据操作（click,hover...）响应对应事件
 */
function addformController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    var form_id = getUrlParam('form_id');
    var is_go = false;//用作判断是否继续执行代码
    //===切换选项卡===
    $('.tab-head-item').click(function () {
        var _this = $(this);
        var _id = $(this).attr('id');
        $('.tab-head-item').removeClass('tab-head-item-active');
        _this.addClass('tab-head-item-active');
        $('.tab-content-item').hide();
        $('#con_' + _id).show();
    });
    //===预览表单===
    $('#writeform').click(function () {
        window.open('#/writeform?form_id=' + form_id);
    });
    //===数据初始化===
    init();
//    $('[name="form_id"]').val(form_id);
//    operate();//操作
//    sort();//表单列排序
    /**
     * 初始化
     */
    function init() {
        getFormData();
        if (is_go) {
            getFormElement();
            getFormColumn();
        }
    }
    /**
     * 验证表单是否是自己的、并且获取表单信息
     * @returns {undefined}
     */
    function getFormData() {
        $.ajax({
            type: 'get',
            url: '../form-data',
            async: false, //同步
            data: {form_id: form_id},
            success: function (json) {
                checkJSON(json, function (json) {
                    _div_info(json.data);
                    is_go = true;
                }, function () {
                    location.href = "#/form";
                });
            }
        });
    }
    /**
     * 获取组件元素
     * @returns {undefined}
     */
    function getFormElement() {
        $http.get('../form-element-list').success(function (json) {
//            console.log(json);
//            console.log('---/form-element-list---');
            checkJSON(json, function (json) {
                var _data = json.data;
                if (_data != null) {
                    _div_element(_data);
                }
            });

        });
    }

    /**
     * 获取表单列
     * @returns {undefined}
     */
    function getFormColumn() {
        $.get('../form-column-list', {form_id: form_id}, function (json) {
//            console.log(json);
//            console.log('---/form-column-list---');
            checkJSON(json, function (json) {
                var _data = json.data;
                if (_data != null) {
                    $.each(_data, function (k, v) {
                        console.log('===show_' + v.type + '===');
                        _html('show', v);
                    });
                }
            });
        });
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


    //==========================================================================









    /**
     * 显示表单信息
     * @param {type} data
     * @returns {undefined}
     */
    function _div_info(data) {
        var _data = data;
        //===表单数据加载===
        $('[name="name"]').val(_data.name);
        $('[name="title"]').val(_data.title);
        $('[name="description"]').val(_data.description);
        $('[name="action_type"]:eq(' + _data.action_type + ')').attr('checked', true);
        if (_data.action_type == 0) {
            $('[name="action_text"]').val(_data.action_text);
        } else {
            $('[name="action_text"]').val(_data.action_url);
        }
        if (_data.status == 1) {
            $('[name="status"]:eq(0)').attr('checked', true);
        } else {
            $('[name="status"]:eq(1)').attr('checked', true);
        }
        $('.as-title').html(_data.title);
        $('.as-description').html(_data.description);
        //===绑定动作===
        $('[name="title"]').blur(function () {
            $('.as-title').html($(this).val());
        });
        $('[name="description"]').blur(function () {
            $('.as-description').html($(this).val());
        });
        //===编辑表单信息===
        $('.as-title,.as-description').click(function () {
            $('.tab-head-item').removeClass('tab-head-item-active');
            $('#item_0').addClass('tab-head-item-active');
            $('.tab-content-item').hide();
            $('#con_item_0').show();
        });
        $('[name="action_type"]').click(function () {
            var _this = $(this);
            if (_this.val() == 0) {
                $('[name="action_text"]').val(_data.action_text);
            }
            if (_this.val() == 1) {
                $('[name="action_text"]').val(_data.action_url);
            }
        });
        //===保存表单信息（form数据）===
        $('#save_form').click(function () {
            var box_info = $('form[name="box_info"]').serializeArray();
            $.post('../form-edit', {form_id: form_id, box_info: box_info}, function (json) {
                checkJSON(json, function (json) {
                    Hint_box(json.msg);
                });
            });
        });
    }
    /**
     * 显示组件元素
     * @param {type} data
     * @returns {undefined}
     */
    function _div_element(data) {
        var _data = data;
        var _div = '';
        $.each(_data, function (k, v) {
            _div += '<li class="utility" data-id="' + v.id + '">\
                        <span class="title">' + v.title + '</span>\
                        <i class="iconfont icon-liebiao grey tpl_info"></i>\
                    </li>';
        });
        $('#element-box').html(_div);
        //===绑定动作===
        $('.unit-list>li').click(function () {
            var _this = $(this);
            bindElementEvent(_this);
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
            var _data = json.data;
            $('.tab-head-item').removeClass('tab-head-item-active');
            $('#item_2').addClass('tab-head-item-active');
            $('.tab-content-item').hide();
            $('#con_item_2').show();

            _html('show', _data);

//            _html('edit', _data);
//            _div_show(_data);
//            _div_edit(_data);
        });
    }




    /**
     * 显示输出内容
     * @param {string} operate 操作类型：show, edit, update
     * @param {array} data 传入数据
     * _show_{type} 显示/更新表单组件
     * 绑定点击编辑动作
     * 添加排序移动功能
     * _edit_{type} 编辑表单组件
     * 
     * type={text, textarea, radio, checkbox, select, date, address, image, file}
     * @returns {undefined}
     */
    function _html(operate, data) {
        var _operate = operate;
        var _data = data;
//        var _config = _data.config;
//        var _temp = _config.option_default;
        var _div = '';
        //显示布局数据
        //设置默认值
        if (_operate == 'show') {
            _div += eval('_' + _operate + '_' + _data.type + '(_data)');//调用方法
            $('.element-show').append(_div);
//            <li>
//                <p>标题*描述</p>
//                <input type="text"/>各类型input
//            </li>

            //===绑定元素点击响应事件===
            $(".element-show>li").unbind('click').on('click', function () {
                $('.tab-head-item').removeClass('tab-head-item-active');
                $('#item_2').addClass('tab-head-item-active');
                $('.tab-content-item').hide();
                $('#con_item_2').show();
                var _this_column = $(this);
                _this_column.siblings().removeClass("click");
                _this_column.addClass("click");
                $.get('../form-column', {form_id: form_id, column_id: _this_column.attr('data-id')}, function (json) {
                    _div_edit(json.data);
                }, 'json');
            });
            //===绑定元素鼠标滑动事件===
            $(".element-show>li").unbind('hover').hover(function () {
                $(this).addClass("hover");
            }, function () {
                $(this).removeClass("hover");
            });
        }
        if (_operate == 'edit') {
            alert(1)
        }
    }

    function _show_text(data) {
        var _data = data;




        var _div = '<li class="list-item" data-type="' + _data.type + '" data-id="' + _data.column_id + '" data-order="' + _data.order + '">';
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        _div += '</p>';
        if (_data.description == null && _data.description == '') {
            _div += '<i class="content-d">' + data.description + '</i>';
        }
        _div += '<input type="text" value="" disabled="disabled" placeholder="' + _data.description + '"/>';// name="col_' + _data.column_id + '"
        _div += '</li>';
        return _div;
    }


    function _show_textarea(data) {
        var _data = data;
        var _div = '<li class="list-item" data-type="' + _data.type + '" data-id="' + _data.column_id + '" data-order="' + _data.order + '">';
        _div += '<p class="content-l">' + _data.title + '：</p>';
        _div += '<textarea name="col_' + _data.column_id + '" disabled="disabled" placeholder=' + _data.description + '></textarea>';
        _div += '</li>';
        return _div;
    }


    function _show_radio(data) {
        var _data = data;
        var _config = _data.config;
        var _option_key = _config.option_key.split(',');
        var _div = '<li class="list-item" data-type="' + _data.type + '" data-id="' + _data.column_id + '" data-order="' + _data.order + '">';
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
                _div += '<input type = "radio" disabled="disabled" checked />';
            } else {
                _div += '<input type = "radio" disabled="disabled"/>';
            }
            _div += '<label>' + _config[_to] + '</label>';
            _div += '</span>';
        });
        _div += '</li>';
        return _div;
    }

    function _show_checkbox(data) {
        var _data = data;
        var _config = _data.config;
        var _option_key = _config.option_key.split(',');
        var _div = '<li class="list-item" data-type="' + _data.type + '" data-id="' + _data.column_id + '" data-order="' + _data.order + '">';
        var _to = '';
        var _option_default = _config.option_default.split(',');
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        if (_data.description != null || _data.description != '') {
            _div += '(' + data.description + ')';
        }
        _div += '</p>';
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<span class="option-item">';
//            $.each(_option_default, function (k, v) {
//                if (v == tv) {
//                    _div += '<input type = "checkbox" disabled="disabled" checked />';
//                } else {
            _div += '<input type = "checkbox" disabled="disabled"/>';
//                }
//            });
            _div += '<label>' + _config[_to] + '</label>';
            _div += '</span>';
        });
        _div += '</li>';
        return _div;
    }

    function _show_select(data) {
        var _data = data;
        var _config = _data.config;
        var _option_key = _config.option_key.split(',');
        var _div = '<li class="list-item" data-type="' + _data.type + '" data-id="' + _data.column_id + '" data-order="' + _data.order + '">';
        var _to = '';
        var _option_default = _config.option_default;
        _div += '<p class="content-l">' + _data.title;
        if (_data.required == 1) {
            _div += '<i class="red-asterisk">*</i>';
        }
        if (_data.description != null || _data.description != '') {
            _div += '(' + data.description + ')';
        }
        _div += '</p>';
        _div += '<select name="col_' + data.column_id + '" disabled="disabled">';
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<option value=' + tv + '>' + _config[_to] + '</option>';
        });
        _div += '</select>';
    }

    function _show_date(data) {
        var _data = data;
        _div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label>';
        _div += '<input  type="text" name="col_' + data.column_id + '" value=""  disabled="disabled" />';
    }

    function _show_address(data) {
        var _data = data;
        _div += '<label class="content-l">' + data.title + '(' + data.description + ')：</label>';
        _div += '<input  type="text" name="col_' + data.column_id + '" value=""  disabled="disabled" />';
    }

    function _show_image(data) {
        var _data = data;
        alert('_show_image');
    }

    function _show_file(data) {
        var _data = data;
        alert(_data.text);
    }
    function _edit_text(data) {
        var _data = data;
        var _config = _data.config;
    }
    function _edit_textarea(data) {
        var _data = data;
    }
    function _edit_radio(data) {
        var _data = data;
    }
    function _edit_checkbox(data) {
        var _data = data;
    }
    function _edit_select(data) {
        var _data = data;
    }
    function _edit_date(data) {
        var _data = data;
    }
    function _edit_address(data) {
        var _data = data;
    }
    function _edit_image(data) {
        var _data = data;
    }
    function _edit_file(data) {
        var _data = data;
    }

    /**
     * 检测必填
     * @returns {undefined}
     */
    function check_val(name, val) {
        var _name = name;
        var _val = val;
        if ($('[' + _name + '="' + _val + '"]').val() == '') {
            return false;
        } else {
            return true;
        }

    }



}