function viewformController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    var form_id = getUrlParam('form_id');
    $('[name="form_id"]').val(form_id);
    init();

    function init() {
        getFormData();
        getFormUserdataList();
    }

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
     * ===表单列表===
     * @returns {undefined}
     */
    function getFormUserdataList() {
        $.get('../form-userdata-list', {form_id: form_id}, function (json) {
            console.log(json);
            console.log('getFormUserdataList');
            checkJSON(json, function (json) {
                var form_list_data = json.data;//表单列表数据资料
                var _div = '<tr>\n\
                                <th>编号<div class="fr">|</div></th>\n\
                                <th>用户<div class="fr">|</div></th>\n\
                                <th>填表时间<div class="fr">|</div></th>\n\
                                <th>操作</th>\n\
                        </tr>\n\
                        <tr class="sapces"></tr>';
                if (form_list_data != null) {
                    $.each(form_list_data, function (k, v) {
                        _div += '<tr class="form-check" data-id="' + v.id + '">\n\
                                    <td style="text-align: left">\n\
                                            <dl class="fl checkclass">\n\
                                                    <input type="checkbox" name="checks" value="Bike1" style=" display:none;">\n\
                                                    <label class="label"></label>\n\
                                            </dl>\n\
                                            <div class="tit_info"><span class="sap_tit">#' + v.id + '</span></div>\n\
                                    </td>\n\
                                    <td>' + v.cus_id + '</td>\n\
                                    <td>' + v.created_at + '</td>\n\
                                    <td>\n\
                                            <a style="margin:0 10px; cursor: pointer" class="form_edit" title="查看"><i class="fa iconfont icon-bianji"></i></a>\n\
                                            <a class="delv" title="删除"><i class="fa iconfont icon-delete mr5"></i></a>\n\
                                    </td>\n\
                                </tr>';
                    });
                } else {
                    _div += "<tr><td colspan='4'>" + json.msg + "</td></tr>";
                }
                $('.a-table').html(_div);
            });
            //===删除===
            $('.delv').unbind('click').click(function () {
                deleteFormUserdata($(this));
            });
            //===查看表单===
            $('.form_edit').unbind('click').click(function () {
                var id = $(this).parents('tr').attr('data-id');
                getFormUserdata(id);
            });
        });
    }

    function getFormUserdata(id) {
        $.get('../form-userdata', {form_id: form_id, id: id}, function (json) {
            checkJSON(json, function (json) {
                var udata = json.data;
                var _div = '';
                $.each(udata, function (k, v) {
                    _div += '<li class="data-li clearfix">';
                    _div += '<div class="data-litem">' + k + '</div>'
                    _div += '<div class="data-ritem">' + v + '</div>'
                    _div += '</li>';
                });
                $('.element-show').html(_div);
            });

        });
    }
    /**
     * ===删除表单===
     * @param {type} _this
     * @returns {undefined}
     */
    function deleteFormUserdata(_this) {
        var id = _this.parents('tr').attr('data-id');
        //===弹窗确认===
        (function column_delete(del_num) {
            if (del_num === undefined) {
                var warningbox = new WarningBox(column_delete);
                warningbox.ng_fuc();
            } else {
                if (del_num) {
                    //===数据库删除===
                    $http.post('../form-userdata-delete', {form_id: form_id, id: id}).success(function (json) {
                        console.log(json);
                        console.log('form-userdata-delete');
                        checkJSON(json, function (json) {
                            _this.parents('tr').remove();
                            Hint_box('删除成功');
                        });
                    });
                }
            }
        })();
    }

    function _div_info(data) {
        $('.as-title').html(data.title);
        $('.as-description').html(data.description);
    }
}

