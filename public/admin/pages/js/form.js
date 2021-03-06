/*
 * ===表单列表js控制器===
 * 对象：表
 * 增/删操作：表
 * 排序：
 */
function formController($scope, $http, $location) {
    $scope.$parent.showbox = "main"; //===主页
    $scope.$parent.homepreview = true; //===右边导航
    $scope.$parent.menu = []; //===？不知道
    getFormList();
    $('.add_form').click(function () {
        $('#bomb-box').show();
    });
    $('.cancel').click(function () {
        cancelForm();
    });
    $('.close').click(function () {
        cancelForm();
    });
    $('.submit_add').click(function () {
        createForm();
    });
    //===表单列表===
    function getFormList() {
        $http.get('../form-list').success(function (json) {
            checkJSON(json, function (json) {
                var _div = '<tr>\n\
                            <th>表单名<div class="fr">|</div></th>\n\
                            <th>标题<div class="fr">|</div></th>\n\
                            <th>描述<div class="fr">|</div></th>\n\
                            <th>显示模式<div class="fr">|</div></th>\n\
                            <th>表单状态<div class="fr">|</div></th>\n\
                            <th style="min-width: 15%;">操作</th>\n\
                    </tr>';
                _div += '<tr class="sapces"></tr>';
                if (json.data != null) {
                    var form_list_data = json.data;//表单列表数据资料
                    $.each(form_list_data, function (k, v) {
                        _div += '<tr class="form-check" data-id="' + v.id + '">\n\
                                <td>\n\
                                        <div class="tit_info"><span class="sap_tit">' + v.name + '</span></div>\n\
                                </td>\n\
                                <td>' + v.title + '</td>\n\
                                <td>' + v.description + '</td>';
                        switch (v.showmodel) {
                            case 0:
                                _div += '<td>单页显示</td>';
                                break;
                            case 1:
                                _div += '<td>嵌入显示</td>';
                                break;
                            case 2:
                                _div += '<td>悬浮显示</td>';
                                break;
                            default:
                                _div += '<td>-</td>';
                                break;
                        }
                        if (v.status == 1) {
                            _div += '<td>启用</td>';
                        } else {
                            _div += '<td>禁用</td>';
                        }
                        _div += '<td>\n\
                                <a style="margin:0 10px; cursor: pointer" class="form_edit" title="编辑"><i class="fa iconfont icon-bianji"></i></a>\n\
                                <a style="margin:0 10px; cursor: pointer" class="form_view" title="浏览"><i class="fa iconfont icon-dengpao1"></i></a>\n\
                                <a class="delv" title="删除"><i class="fa iconfont icon-delete mr5"></i></a>\n\
                            </td>\n\
                        </tr>';
                    });
                } else {
                    _div += "<tr><td colspan='8'>" + json.msg + "</td></tr>";
                }
                $('.a-table').html(_div);
            });
            $('.delv').click(function () {
                deleteForm($(this));
            });
            //===编辑表单===
            $('.form_edit').click(function () {
                var form_id = $(this).parents('tr').attr('data-id');
                location.href = "#/addform?form_id=" + form_id;
            });
            //===浏览表单===
            $('.form_view').click(function () {
                var form_id = $(this).parents('tr').attr('data-id');
                location.href = "#/viewform?form_id=" + form_id;
            });
            //===填写表单(不依赖后台)===
            $('.form_write').click(function () {
                var form_id = $(this).parents('tr').attr('data-id');
//				location.href = "#/writeform?form_id=" + form_id;
                window.open("#/writeform?form_id=" + form_id);
            });
        });
    }
    //===创建表单===
    function createForm() {
        var form_name = $('[name="form_name"]').val();
        var platform = '';
        $('[name="platform"]:checked').each(function () {
            if (platform === '') {
                platform += $(this).val();
            } else {
                platform += ',' + $(this).val();
            }
        });
        var showmodel = $('[name="showmodel"]:checked').val();
        //验证值
        if (form_name == '') {
            alert('表单名不能为空');
            return false;
        }
        $.post('../form-create', {name: form_name, platform: platform, showmodel: showmodel}, function (json) {
            console.log(json);
            console.log('===form-create===');
            //===跳转到表单编辑页面===
            if (json.err === 0) {
                Hint_box(json.msg);
                location.href = "#/addform?form_id=" + json.data;
            } else {
                alert(json.msg);
            }
        });
        $('#bomb-box').hide();
    }
    //===取消创建===
    function cancelForm() {
//		$('[name="form_name"]').val('');
//		$("[name='platform']").removeAttr("checked");
        $('#bomb-box').hide();
    }

    /**
     * ===删除表单===
     * @param {type} _this
     * @returns {undefined}
     */
    function deleteForm(_this) {
        var form_id = _this.parents('tr').attr('data-id');
        //===弹窗确认===
        (function column_delete(del_num) {
            if (del_num === undefined) {
                var warningbox = new WarningBox(column_delete);
                warningbox.ng_fuc();
            } else {
                if (del_num) {
                    //===数据库删除===
                    $http.post('../form-delete', {form_id: form_id}).success(function (json) {
                        checkJSON(json, function (json) {
                            _this.parents('tr').remove();
                            Hint_box(json.msg);
                        });
                    });
                }
            }
        })();
    }
}