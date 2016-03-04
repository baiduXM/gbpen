 function settingController($scope,$http) {
    $scope.$parent.showbox = 'main';
    $scope.$parent.homepreview = false;
	$scope.$parent.menu = [];
    $scope.settingInit = function(){
        this._init();
    };
    $scope.settingInit.prototype = {
        _init : function(){
            this._settingGetInfo();
            this._settingSave();
            this._settingPicDel();
            this._settingPageNum();
            this._Switch();
            this._loadPageSize();
        },
        _settingGetInfo : function(){
            var _this = this;
            // 数据读取
            $http.get('../customer-info').success(function(json){  
                var set = json.data;
                $('.setting-content input[name=company_name]').val(set.company_name);
                $('.setting-content input[name=domain_pc]').val(set.domain_pc);
                $('.setting-content input[name=domain_m]').val(set.domain_m);
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
                $('#checkbox1').attr('checked',set.pc_page_count_switch?true:false);
                var openstatus = $('input.chk').is(':checked');
                if(openstatus){
                    $('input.chk').nextAll('.switch_list').find('input,button').attr('disabled',(openstatus?false:true));
                    $('input.chk').prevAll('input,button').attr('disabled',(openstatus?true:false));
                    $('input.chk').nextAll('.switch_list').slideToggle();
                }
                // $('.setting-content label[name=pcnum]').val(set.address);
                // $('.setting-content label[name=mobnum]').val(set.address);
                if(set.favicon != null){
                    _this._ModelAddPic(set.favicon,'favicon',1);
                }
                if(set.logo_large != null){
                    _this._ModelAddPic(set.logo_large,'logo_large',2);
                }
                if(set.logo_small != null){
                    _this._ModelAddPic(set.logo_small,'logo_small',3);
                }
                _this._settingUpload(set.pc_logo_size,set.m_logo_size);
                $('.pclogo_size').text('('+set.m_logo_size.replace('/','*')+')');
                $('.moblogo_size').text('('+set.pc_logo_size.replace('/','*')+')');
            });
        },
        _ModelAddPic : function(picurl,picname,num){
            var nameArry = picurl.split('/');
            var pic_name = nameArry[(nameArry.length-1)];
            var _newpic = '<div class="template-download fade fl in">\n\
                        <div>\n\
                            <span class="preview">\n\
                            <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                <img src="'+picurl+'" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                            </span>\n\
                        </div>\n\
                        <input type="hidden" name='+picname+' value="'+pic_name+'">\n\
                    </div>';
            $('.set_pic'+num+'').append(_newpic);
        },
        _settingSave : function(){
            $('input').val().toLowerCase();
            $('#setting .addsave').click(function(){
                var data = $('#setting_info').serializeJson();
                $http.post('../customer-setting',data).success(function(json){
                    checkJSON(json,function(json){
                        if(json.err == 0){
                            var hint_box = new Hint_box();
                            hint_box;
                        }
                    });
                });
            });
        },
        _settingUpload : function(pc_logo_size,m_logo_size){
            var pc_logo_size_arr = pc_logo_size ? pc_logo_size.indexOf('|') ? pc_logo_size.split('|') : pc_logo_size : null,
                m_logo_size_arr = m_logo_size ? m_logo_size.indexOf('|') ? m_logo_size.split('|') : m_logo_size : null;
            $('.set_up_name').on('click',function(event) {
                var _this =  $(this);
                var warningbox = new WarningBox(),role = '';
                if($(this).data('role') == 'favicon'){
                    role = 32/32;
                }else if($(this).data('role') == 'logo_large'){
                    if(eval(pc_logo_size_arr instanceof(Array)) && pc_logo_size_arr[1] == 'forcesize')
                        role = eval(pc_logo_size_arr[0]);
                }else if($(this).data('role') == 'logo_small'){
                    if(eval(m_logo_size_arr instanceof(Array)) && m_logo_size_arr[1] == 'forcesize')
                        role = eval(m_logo_size_arr[0]);
                }
                warningbox._upImage({
                    aspectRatio: role,
                    ajaxurl    : '../file-upload?target=common',
                    oncallback : function(json){
                        _this.closest('.feild-item').find('div[data-role='+_this.data('role')+']').children().remove();
                        $('.column_pic .template-download').remove();
                        _newpic = '<div class="template-download fade fl in">\n\
                                    <div>\n\
                                        <span class="preview">\n\
                                        <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                            <img src="'+json.data.url+'" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                                        </span>\n\
                                    </div><input type="hidden" name="'+_this.data('role')+'" value="'+json.data.name+'"></div>';
                        _this.closest('.feild-item').find('div[data-role='+_this.data('role')+']').append(_newpic);
                    }
                });
            });
        },
        _settingPicDel : function(){
            $('.feild-content .feild-item').on('click','.preview-close',function(){
                $(this).parents('.template-download').remove();
                return false;
            });
        },
        _settingPageNum : function(){
            $('button.add').click(function(event) {
                $(this).prevAll('input').val(parseInt($(this).prevAll('input').val())+1)
            });
            $('button.minus').click(function(event) {
                $(this).prevAll('input').val(parseInt($(this).prevAll('input').val())-1)
            });
        },
        _Switch : function(){
            $('label.valign').MoveBox({
                Trigger : 'mouseenter',
                context : '开启设置各个列表展示条数'
            });
            $('input.chk').click(function(event) {
                var _this = $(this),
                    openstatus = _this.is(':checked'); 
                _this.nextAll('.switch_list').find('input,button').prop('disabled',(_this.nextAll('.switch_list').is(":visible")?true:false));
                _this.prevAll('input,button').prop('disabled',(_this.prop("checked")?true:false));
                _this.nextAll('.switch_list').slideToggle();
                $('.setting-content input[name=pc_page_count_switch]').val(openstatus?'1':'0');
            });
        },
        _loadPageSize : function(){
            var mainMarginLeft = ($(window).width() - 840)/2;
            $('#main').css({'width' : 840,'marginLeft' : mainMarginLeft});
        }
    };
    var init = new $scope.settingInit();
 }