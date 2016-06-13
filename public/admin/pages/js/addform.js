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
            checkJSON(json, function (json) {
                var _data = json.data;
                if (_data != null) {
                    $.each(_data, function (k, v) {
                        _html_show(v);
                    });
                }
            });
        });
    }
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
        if (_data.showmodel == 0) {
            $('[name="showmodel"]:eq(0)').attr('checked', true);
        } else {
            $('[name="showmodel"]:eq(1)').attr('checked', true);
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
            _html_show(_data);//添加预览组件
            _html_edit(_data);//编辑组件
        });
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
            $('.add-show').append(_div_li);
        }
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
                _html_edit(json.data);
            }, 'json');
        });
        //===绑定元素鼠标滑动事件===
        $(".element-show>li").unbind('hover').hover(function () {
            $(this).addClass("hover");
        }, function () {
            $(this).removeClass("hover");
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
            _div += '<input type="text" disabled="disabled" placeholder="' + _data.description + '"/>';// name="col_' + _data.column_id + '"
        } else {
            _div += '<input type="text" disabled="disabled" />';// name="col_' + _data.column_id + '"
        }
//        _div += '<input type="text" disabled="disabled" placeholder="' + _data.description + '"/>';// name="col_' + _data.column_id + '"
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
            _div += '<textarea disabled="disabled" placeholder=' + _data.description + '></textarea>';
        } else {
            _div += '<textarea disabled="disabled"></textarea>';
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
                _div += '<input type = "radio" disabled="disabled" checked />';
            } else {
                _div += '<input type = "radio" disabled="disabled"/>';
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
            _div += '<input type = "checkbox" disabled="disabled"/>';
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
        _div += '<select name="col_' + data.column_id + '" disabled="disabled">';
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
        _div += '<input onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})" name="col_' + data.column_id + '" disabled="disabled" />';
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


    function _html_edit(data) {
        var _data = data;
        var _div = '';
        _div += '<li class="list-item"><p class="content-l">标题：</p><input name="title" type="text" value="' + _data.title + '" /></li>';
        _div += '<li class="list-item"><p class="content-l">描述：</p><input name="description" type="text" value="' + _data.description + '" /></li>';
        _div += '<li class="list-item"><p class="content-l"><span class="option-item">';
        if (_data.required == 1) {
            _div += '<input type="checkbox" name="required" value="1" checked="checked"/>是否必填</span></p></li>';
        } else {
            _div += '<input type="checkbox" name="required" value="1"/>是否必填</span></p></li>';
        }
//            _div += '<li class="list-item"><p class="content-l">排序：<span class="option-item"><input type="text" name="order" value="' + _data.order + '"/></span></p></li>';
        _div += '<hr />';
        _div += eval('_edit_' + _data.type + '(_data)');//调用方法
        _div += '<li class="list-item">';
        _div += '<span class="option-item"><input type="button" class="save_column" value="保存"/></span>';
        _div += '<span class="option-item"><input type="button" class="delete_column" value="删除"/></span>';
        _div += '</li>';
        $('#element-edit').html(_div);

        $(".element-show>li").siblings().removeClass("click");
        $('li[data-id="' + _data.column_id + '"]').addClass("click");
        //===加载操作===
        save_column(_data);
        del_column(_data);
        if (_data.type == 'radio' || _data.type == 'select' || _data.type == 'checkbox') {
            add_option(_data);
            del_option();
        }


    }
    //===保存组件===
    function save_column(data) {
        var _data = data;
        $('.save_column').unbind('click').click(function () {
            var form_data = $('form[name="box_column"]').serializeArray();
            $.post('../form-column-edit', {form_id: form_id, data: form_data, column_id: _data.column_id, type: _data.type}, function (json) {
                checkJSON(json, function (json) {
                    _html_show(json.data);
                    Hint_box(json.msg);
                });
            });
        });
    }

    //===删除组件===
    function del_column(data) {

        $('.delete_column').unbind('click').click(function () {
            //===弹窗确认===
            (function column_delete(del_num) {
                if (del_num === undefined) {
                    var warningbox = new WarningBox(column_delete);
                    warningbox.ng_fuc();
                } else {
                    if (del_num) {
                        //===数据库删除===
                        $.post('../form-column-delete', {form_id: form_id, column_id: data.column_id}, function (json) {
                            checkJSON(json, function (json) {
                                $('li[data-id=' + data.column_id + ']').remove();
                                $('#element-edit').html('');
                                Hint_box('删除成功');
                                $('.tab-head-item').removeClass('tab-head-item-active');
                                $('#item_1').addClass('tab-head-item-active');
                                $('.tab-content-item').hide();
                                $('#con_item_1').show();
                            });
                        });
                    }
                }
            })();
        });
    }

    //===添加选项===
    function add_option(data) {
        var _data = data;
        var _config = _data.config;
        var _option_key = _config.option_key.split(',');
        var flag_num = _option_key[_option_key.length - 1];
        $('.option_add').unbind('click').click(function () {
            ++flag_num;
            var _option = '';
            var _this = $(this).parents('.list-item');//===选项
            switch (_data.type) {
                case 'radio':
                case 'select':
                    _option += '<p class="option-item" data-num=' + flag_num + '><input type = "radio" name="config_default" value = "' + flag_num + '" />';
                    _option += '<input type="text" name="config_option_' + flag_num + '" value="选项' + flag_num + '" /><button class="square option_del">-</button></p>';
                    break;
                case 'checkbox':
                    _option += '<p class="option-item" data-num=' + flag_num + '><input type = "checkbox" name="config_default" value = "' + flag_num + '" />';
                    _option += '<input type="text" name="config_option_' + flag_num + '" value="选项' + flag_num + '" /><button class="square option_del">-</button></p>';
                    break;
                default:
                    break;
            }
            _this.append(_option);
            del_option();
            //都不用算数量
        });
    }
    //===删除选项===
    function del_option() {
        $('.option_del').unbind('click').click(function () {
            var _this = $(this).parent();
            _this.remove();
        });

    }
    function _edit_text(data) {
        var _data = data;
        var _config = _data.config;
        var _div = '';
        _div += '<li class="list-item"><p class="content-l">规则</p>\n\
                    <span class="option-item"><input type = "radio" name="config_rules" value = "no" checked />无</span>\n\
                    <span class="option-item"><input type = "radio" name="config_rules" value = "mail" />邮箱</span>\n\
                    <span class="option-item"><input type = "radio" name="config_rules" value = "number" />数字</span>\n\
                </li>';
        return _div;
    }
    function _edit_textarea(data) {
        var _data = data;
        var _div = '';
        return _div;
    }
    function _edit_radio(data) {
        var _data = data;
        var _div = '';
        _div += '<li class="list-item"><p class="content-l">选项设置<button class="square option_add">+</button></p>';
        var _config = _data.config;
        var _option_key = _config.option_key.split(',');
        var _to;
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<p class="option-item" data-num=' + tv + '><input type = "radio" name="config_default" value = "' + tv + '" />';
            _div += '<input type="text" name="config_option_' + tv + '" value="' + _config[_to] + '" /><button class="square option_del">-</button></p>';

        });
        _div += '</li>';
//        _div += '<input type = "hidden" name="config_option_count" value = "' + _config.option_count + '" />';
        return _div;
    }
    function _edit_checkbox(data) {
        var _data = data;
        var _div = '';
        var _config = _data.config;
        _div += '<li class="list-item"><p class="content-l">选项设置<button class="square option_add">+</button></p>';
        var _option_key = _config.option_key.split(',');
        var _to;
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<p class="option-item" data-num=' + tv + '><input type = "checkbox" name="config_default" value = "' + tv + '" />';
            _div += '<input type="text" name="config_option_' + tv + '" value="' + _config[_to] + '" /><button class="square option_del">-</button></p>';
        });
        _div += '</li>';
//        _div += '<input type = "hidden" name="config_option_count" value = "" />';
        return _div;
    }
    function _edit_select(data) {
        var _data = data;
        var _div = '';
        var _config = _data.config;
        _div += '<li class="list-item"><p class="content-l">选项设置<button class="square option_add">+</button></p>';
        var _option_key = _config.option_key.split(',');
        var _to;
        $.each(_option_key, function (tk, tv) {
            _to = "option_" + tv;
            _div += '<p class="option-item" data-num=' + tv + '><input type = "radio" name="config_default" value = "' + tv + '" />';
            _div += '<input type="text" name="config_option_' + tv + '" value="' + _config[_to] + '" /><button class="square option_del">-</button></p>';
        });
        _div += '</li>';
//        _div += '<input type = "hidden" name="config_option_count" value = "" />';
        return _div;
    }
    function _edit_date(data) {
        var _data = data;
        var _div = '';
        return _div;
    }
    function _edit_address(data) {
        var _data = data;
        var _div = '';
        return _div;
    }
    function _edit_image(data) {
        var _data = data;
        var _div = '';
        return _div;
    }
    function _edit_file(data) {
        var _data = data;
        var _div = '';
        return _div;
    }

    /**
     * 检测必填
     * @returns {undefined}
     */
//    function check_val(name, val) {
//        var _name = name;
//        var _val = val;
//        if ($('[' + _name + '="' + _val + '"]').val() == '') {
//            return false;
//        } else {
//            return true;
//        }
//    }

}
