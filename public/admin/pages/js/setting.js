function settingController($scope, $http) {
    $scope.$parent.showbox = 'main';
    $scope.$parent.homepreview = false;
    $scope.$parent.menu = [];
    $scope.settingInit = function () {
        this._init();
    };
    $scope.settingInit.prototype = {
        _init: function () {
            this._settingGetInfo();
            this._settingSave();
            this._settingPicDel();
            this._settingPageNum();
            this._Switch();
            this._loadPageSize();
            this._Addadv();
            this._Addform();
        },
        _settingGetInfo: function () {
            var _this = this;
            // 数据读取
            $http.get('../customer-info').success(function (json) {
                var set = json.data;
                $('.setting-content input[name=company_name]').val(set.company_name);
                $('.setting-content input[name=domain_pc]').val(set.domain_pc);
                $('.setting-content input[name=domain_m]').val(set.domain_m);
                $('.setting-content input[name=def_domain_pc]').val(set.def_domain_pc);
                $('.setting-content input[name=def_domain_m]').val(set.def_domain_m);
                $('.setting-content input[name=company_name]').val(set.company_name);
                $('.setting-content input[name=company_name]').val(set.company_name);
                $('.setting-content input[name=title]').val(set.title);
                $('.setting-content textarea[name=keywords]').val(set.keywords);
                $('.setting-content textarea[name=description]').val(set.description);
                $('.setting-content textarea[name=footer]').val(set.footer);
                $('.setting-content textarea[name=mobile_footer]').val(set.mobile_footer);
                $('.setting-content textarea[name=footer_script]').val(set.footer_script);
                $('.setting-content textarea[name=header_script]').val(set.header_script);
                $('.setting-content input[name=pc_num_per_page]').val(set.pc_num_per_page);
                $('.setting-content input[name=pc_num_pagenav]').val(set.pc_num_pagenav);
                $('.setting-content input[name=m_num_per_page]').val(set.m_num_per_page);
                $('.setting-content input[name=m_num_pagenav]').val(set.m_num_pagenav);
                $('.setting-content textarea[name=pc_header_script]').val(set.pc_header_script);
                $('.setting-content textarea[name=pc_footer_script]').val(set.pc_footer_script);
                $('.setting-content textarea[name=mobile_header_script]').val(set.mobile_header_script);
                $('.setting-content textarea[name=mobile_footer_script]').val(set.mobile_footer_script);
                $('.setting-content input[name=logo_large]').val(set.logo_large);
                $('.setting-content input[name=logo_small]').val(set.logo_small);
                $('.setting-content input[name=contactor]').val(set.contactor);
                $('.setting-content input[name=telephone]').val(set.telephone);
                $('.setting-content input[name=mobile]').val(set.mobile);
                $('.setting-content input[name=fax]').val(set.fax);
                $('.setting-content input[name=mail]').val(set.mail);
                $('.setting-content input[name=qq]').val(set.qq);
                $('.setting-content input[name=address]').val(set.address);
                $('.setting-content input[name=pc_imgtxt_per_page]').val(set.pc_imgtxt_per_page);
                $('.setting-content input[name=pc_txt_per_page]').val(set.pc_txt_per_page);
                $('.setting-content input[name=pc_img_per_page]').val(set.pc_img_per_page);
                $('.setting-content input[name=pc_page_count_switch]').val(set.pc_page_count_switch);
                $('#domain_pc').attr('href', 'http://' + set.domain_pc);
                $('#def_domain_pc').attr('href', 'http://' + set.def_domain_pc);
                $('#lang option[value=' + set.lang + ']').attr('selected', true);
                $('#def_domain_m').MoveBox({
                    Trigger: 'mouseenter',
                    context: '<img src="http://s.jiathis.com/qrcode.php?url=http://' + set.def_domain_m + '" />'
                });
                $('#domain_m').MoveBox({
                    Trigger: 'mouseenter',
                    context: '<img src="http://s.jiathis.com/qrcode.php?url=http://' + set.domain_m + '" />'
                });
                if ($.isArray(set.floatadv)) {
                    $.each(set.floatadv, function (k, v) {
                        var _div = '';
                        if (v.type == 'form') {
                            var html = '<li class="floatadv">\n\
                                        <div>\n\
                                            关联表单：<select id="form_select' + k + '" name="float_adv[' + k + ']">\n\
                                                <option selected="selected" value="0">请选择</option>\n\
                                            </select>\n\
                                            <input type="hidden" name="float_type[' + k + ']" value="' + v.type + '">\n\
                                        </div>\n\
                                        <div>\n\
                                            <label class="floatxy" style="height: 20px;">X:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posx[' + k + ']" value="0">PX</label>\n\
                                            <label class="floatxy" style="height: 20px;">Y:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posy[' + k + ']" value="0">PX</label>\n\
                                            <label class="floatxy" style="height: 20px;">图宽:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posw[' + k + ']" value="0">PX</label>\n\
                                            <label class="floatxy" style="height: 20px;">位置:<select name="position[' + k + ']"><option value ="1">上</option><option value ="3">下</option><option value ="2">左</option><option value ="4">右</option></select></label>\n\
                                        </div>\n\
                                        <a><i class="fa iconfont icon-delete"></i></a>\n\
                                        </li>';
                            $.get('../form-list', {status: '1', showmodel: '2'}, function (json) {
                                checkJSON(json, function (json) {
                                    if (json.data != null) {
                                        $.each(json.data, function (kt, vt) {
                                            if (v.adv == vt.id) {
                                                _div += '<option value="' + vt.id + '" selected>' + vt.name + '</option>';
                                            } else {
                                                _div += '<option value="' + vt.id + '">' + vt.name + '</option>';
                                            }
                                        });
                                        $('#form_select' + k).append(_div);
                                    }
                                });
                            });
                        }
                        if (v.type == 'adv') {
                            var html = '<li class="floatadv">\n\
                                        <div class="template-download fade fl in">\n\
                                            <div>\n\
                                                <span class="preview">\n\
                                                    <img src="' + v.url + '" data-name="' + v.adv + '" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                                                </span>\n\
                                            </div><input type="hidden" name="float_adv[' + k + ']" value="' + v.adv + '">\n\
                                                 <input type="hidden" name="float_type[' + k + ']" value="' + v.type + '">\n\
                                        </div>\
                                        <label class="floatxy" style="height: 20px;">X:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posx[' + k + ']" value="' + v.posx + '">PX</label>\n\
                                        <label class="floatxy" style="height: 20px;">Y:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posy[' + k + ']" value="' + v.posy + '">PX</label>\n\
                                        <label class="floatxy" style="height: 20px;">宽:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posw[' + k + ']" value="' + v.posw + '">PX</label>\n\
                                        <label class="floatxy" style="height: 20px;">位置:<select name="position[' + k + ']"><option value ="1" ' + (v.position == '1' ? 'selected' : '') + '>上</option><option value ="3" ' + (v.position == '3' ? 'selected' : '') + '>下</option><option value ="2" ' + (v.position == '2' ? 'selected' : '') + '>左</option><option value ="4" ' + (v.position == '4' ? 'selected' : '') + '>右</option></select></label>\n\
                                        <label class="floatxy" style="position: relative;width:300px;height: 20px;">href:<input type="text" class="settingpos" name="href[' + k + ']" style="width: 250px;height: 20px;" value="' + v.href + '"></label>\n\
                                        <a><i class="fa iconfont icon-delete"></i></a>\n\
                                      </li>';
                        }

                        $('ul.adv').append(html);
                    });
                }
                $('#checkbox1').attr('checked', set.pc_page_count_switch ? true : false);
                var openstatus = $('input.chk').is(':checked');
                if (openstatus) {
                    $('input.chk').nextAll('.switch_list').find('input,button').attr('disabled', (openstatus ? false : true));
                    $('input.chk').prevAll('input,button').attr('disabled', (openstatus ? true : false));
                    $('input.chk').nextAll('.switch_list').slideToggle();
                }
                if (set.enlarge == '1') {
                    $("#enlargev").val("1");
                    $("input.enl").attr("checked", true);
                }
                // $('.setting-content label[name=pcnum]').val(set.address);
                // $('.setting-content label[name=mobnum]').val(set.address);
                if (set.favicon != null) {
                    _this._ModelAddPic(set.favicon, 'favicon', 1);
                }
                if (set.logo_large != null) {
                    _this._ModelAddPic(set.logo_large, 'logo_large', 2);
                }
                if (set.logo_small != null) {
                    _this._ModelAddPic(set.logo_small, 'logo_small', 3);
                }
                _this._settingUpload(set.pc_logo_size, set.m_logo_size);
                $('.pclogo_size').text('(' + (set.pc_logo_size && set.pc_logo_size.replace('/', '*')) + ')');
                $('.moblogo_size').text('(' + (set.m_logo_size && set.m_logo_size.replace('/', '*')) + ')');
            });
        },
        _Addadv: function () {
            var subscript = $('ul .floatadv').length;
            $(".addfloatadv").click(function () {
                subscript++;
                var warningbox = new WarningBox();
                warningbox._upImage({
                    ajaxurl: '../fileupload?target=common',
                    oncallback: function (json) {
                        var html = '<li class="floatadv">\n\
                                    <div class="template-download fade fl in">\n\
                                        <div>\n\
                                            <span class="preview">\n\
                                                <img src="' + json.data.url + '" class="img_upload" data-name="' + json.data.name + '" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                                            </span>\n\
                                        </div><input type="hidden" name="float_adv[' + subscript + ']" value="' + json.data.name + '">\n\
                                                <input type="hidden" name="float_type[' + subscript + ']" value="adv">\n\
                                    </div>\
                                    <label class="floatxy" style="height: 20px;">X:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posx[' + subscript + ']" value="0">PX</label>\n\
                                    <label class="floatxy" style="height: 20px;">Y:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posy[' + subscript + ']" value="0">PX</label>\n\
                                    <label class="floatxy" style="height: 20px;">图宽:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posw[' + subscript + ']" value="0">PX</label>\n\
                                    <label class="floatxy" style="height: 20px;">位置:<select name="position[' + subscript + ']"><option value ="1">上</option><option value ="3">下</option><option value ="2">左</option><option value ="4">右</option></select></label>\n\
                                    <label class="floatxy" style="position: relative;width:300px;height: 20px;">链接:<input type="text" class="settingpos" name="href[' + subscript + ']" style="width: 250px;height: 20px;" value=""></label>\n\
                                    <a><i class="fa iconfont icon-delete"></i></a>\n\
                                  </li>';
                        $('ul.adv').append(html);
                    }
                });
            });
            $('.adv').on('click', '.icon-delete', function () {
                $(this).parents('li').remove();
                return false;
            });

        },
        _Addform: function () {
            var subscript = $('ul .floatadv').length;
            $(".addfloatform").click(function () {
                subscript++;
                var _div = '';
                var html = '<li class="floatadv">\n\
                            <div>\n\
                                关联表单：<select id="form_select' + subscript + '" name="float_adv[' + subscript + ']">\n\
                                    <option selected="selected" value="0">请选择</option>\n\
                                </select>\n\
                                <input type="hidden" name="float_type[' + subscript + ']" value="form">\n\
                            </div>\n\
                            <div>\n\
                                <label class="floatxy" style="height: 20px;">X:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posx[' + subscript + ']" value="0">PX</label>\n\
                                <label class="floatxy" style="height: 20px;">Y:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posy[' + subscript + ']" value="0">PX</label>\n\
                                <label class="floatxy" style="height: 20px;">图宽:<input type="text" class="settingpos" style="width: 30px;height: 20px;" name="posw[' + subscript + ']" value="0">PX</label>\n\
                                <label class="floatxy" style="height: 20px;">位置:<select name="position[' + subscript + ']"><option value ="1">上</option><option value ="3">下</option><option value ="2">左</option><option value ="4">右</option></select></label>\n\
                            </div>\n\
                            <a><i class="fa iconfont icon-delete"></i></a>\n\
                            </li>';
                $.get('../form-list', {status: '1', showmodel: '2'}, function (json) {
                    checkJSON(json, function (json) {
                        if (json.data != null) {
                            $.each(json.data, function (k, v) {
                                _div += '<option value="' + v.id + '">' + v.name + '</option>';
                            });
                            $('#form_select' + subscript).append(_div);
                        }
                    });
                });
                $('ul.adv').append(html);
            });
        },
        _ModelAddPic: function (picurl, picname, num) {
            var nameArry = picurl.split('/');
            var pic_name = nameArry[(nameArry.length - 1)];
            var _newpic = '<div class="template-download fade fl in">\n\
                        <div>\n\
                            <span class="preview">\n\
                            <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                <img src="' + picurl + '" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                            </span>\n\
                        </div>\n\
                        <input type="hidden" name=' + picname + ' value="' + pic_name + '">\n\
                    </div>';
            $('.set_pic' + num + '').append(_newpic);
        },
        _settingSave: function () {
            $('input').val().toLowerCase();
            $('#setting .addsave').click(function () {
                var data = $('#setting_info').serializeJson();
                var img_upload = [];
                $('.preview>.img_upload').each(function () {
                    img_upload.push($(this).data('name'));
                });
                $http.post('../customer-setting', data).success(function (json) {
                    checkJSON(json, function (json) {
                        if (json.err == 0) {
                            if (img_upload.length) {
                                $http.post('../imgupload?target=common',
                                        {
                                            files: img_upload
                                        });
                            }
                            var hint_box = new Hint_box();
                            hint_box;
                        }
                    });
                });
            });
        },
        _settingUpload: function (pc_logo_size, m_logo_size) {
            var pc_logo_size_arr = pc_logo_size ? pc_logo_size.indexOf('|') ? pc_logo_size.split('|') : pc_logo_size : null,
                    m_logo_size_arr = m_logo_size ? m_logo_size.indexOf('|') ? m_logo_size.split('|') : m_logo_size : null;
            $('.set_up_name').on('click', function (event) {
                var _this = $(this);
                var warningbox = new WarningBox(), role = '';
                if ($(this).data('role') == 'favicon') {
                    role = 32 / 32;
                } else if ($(this).data('role') == 'logo_large') {
                    if (eval(pc_logo_size_arr instanceof(Array)) && pc_logo_size_arr[1] == 'forcesize')
                        role = eval(pc_logo_size_arr[0]);
                } else if ($(this).data('role') == 'logo_small') {
                    if (eval(m_logo_size_arr instanceof(Array)) && m_logo_size_arr[1] == 'forcesize')
                        role = eval(m_logo_size_arr[0]);
                }
                warningbox._upImage({
                    aspectRatio: role,
                    ajaxurl: '../fileupload?target=common',
                    oncallback: function (json) {
                        _this.closest('.feild-item').find('div[data-role=' + _this.data('role') + ']').children().remove();
                        $('.column_pic .template-download').remove();
                        _newpic = '<div class="template-download fade fl in">\n\
                                    <div>\n\
                                        <span class="preview">\n\
                                        <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                            <img src="' + json.data.url + '" class="img_upload" data-name="' + json.data.name + '" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                                        </span>\n\
                                    </div><input type="hidden" name="' + _this.data('role') + '" value="' + json.data.name + '"></div>';
                        _this.closest('.feild-item').find('div[data-role=' + _this.data('role') + ']').append(_newpic);
                    }
                });
            });
        },
        _settingPicDel: function () {
            $('.feild-content .feild-item').on('click', '.preview-close', function () {
                $(this).parents('.template-download').remove();
                return false;
            });
        },
        _settingPageNum: function () {
            $('button.add').click(function (event) {
                $(this).prevAll('input').val(parseInt($(this).prevAll('input').val()) + 1)
            });
            $('button.minus').click(function (event) {
                if ($(this).prevAll('input').val() > 1) {
                    $(this).prevAll('input').val(parseInt($(this).prevAll('input').val()) - 1);
                }
            });
        },
        _Switch: function () {
            $('label.valign').MoveBox({
                Trigger: 'mouseenter',
                context: '开启设置各个列表展示条数'
            });
            $('.enlarge').MoveBox({
                Trigger: 'mouseenter',
                context: '开启产品介绍图片放大功能'
            });
            $('.enlarge').click(function () {
                if ($("#enlargev").val() == '1') {
                    $("#enlargev").val("0");
                } else {
                    $("#enlargev").val("1");
                }
            });
            $('input.chk').click(function (event) {
                var _this = $(this),
                        openstatus = _this.is(':checked');
                _this.nextAll('.switch_list').find('input,button').prop('disabled', (_this.nextAll('.switch_list').is(":visible") ? true : false));
                _this.prevAll('input,button').prop('disabled', (_this.prop("checked") ? true : false));
                _this.nextAll('.switch_list').slideToggle();
                $('.setting-content input[name=pc_page_count_switch]').val(openstatus ? '1' : '0');
            });
        },
        _loadPageSize: function () {
            var mainMarginLeft = ($(window).width() - 840) / 2;
            $('#main').css({'width': 840, 'marginLeft': mainMarginLeft});
        }
    };
    var init = new $scope.settingInit();
}