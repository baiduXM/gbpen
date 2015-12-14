function addarticleController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
	$scope.$parent.menu = [];
	$_GET = $location.search(); 
    var G_id = $_GET['id'];
    var G_c_id = $_GET['c_id'];
    G_id ? G_id : '';
    G_c_id ? G_c_id : '';

    // 图片上传
    function AddarticleUpload(proportion){
        $('.up_pic').on('click',function(event) {
            var warningbox = new WarningBox();
            warningbox._upImage({
                aspectRatio: proportion,
                ajaxurl    : '../file-upload?target=articles',
                IsMultiple : true,
                oncallback : function(json){
                    var addpic = function(idx, ele){
                        var _newpic = '<div class="template-download fade fr in">\n\
                                        <div>\n\
                                            <span class="preview">\n\
                                            <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                                <img src="'+ele.url+'" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                                            </span>\n\
                                        </div>\n\
                                    </div>';
                        $('.up_pic').before(_newpic);
                    }
                    if(json.data.length == undefined){
                        addpic(0, json.data);
                    }else{
                        $.each(json.data,function(idx, ele) {
                            addpic(idx, ele);
                        });    
                    } 
                }
            });
        });
        //删除图片
        $('.home-list').on('click','.preview-close',function(){
            $(this).parents('.template-download').remove();
            return false;
        });
    }
    // 栏目显示
    function column_show(){
        checkjs(location.hash.match(/[a-z]+?$/));
        $http.get('../classify-list').success(function(json) {
        // $http.get('json/column.json').success(function(json) {
            checkJSON(json, function(json){
                var d = json.data;
                var option1 = '',pid,pname='',id;
                var PageId = [];
                if(json.err == 0){
                    var option1 = '';
                    $.each(d,function(idx,ele){
                        if(ele.type < 5){
                            if(G_c_id == ele.id){
                                pid = ele.p_id;
                                pname = ele.name;
                                id = ele.id;
                            }
                            if(ele.type == '4'){
                                PageId.push(ele.id);
                            }
                            option1 += '<li><a class="parents'+(ele.childmenu != null?' not-allowed':'')+'" data-id="'+ele.id+'" data-size="'+ele.img_width+','+ele.img_height+','+ele.img_forcesize+'">'+ele.name+'</a></li>';
                            var NextChild = ele;
                            var num = 2;
                            var LoopChlid = function(NextChild,num){
                                if(NextChild.childmenu != null){
                                    $.each(NextChild.childmenu,function(k,v){
                                        if(v.type < 5){
                                            if(G_c_id == v.id){
                                                pid = v.p_id;
                                                pname = v.name;
                                                id = v.id;
                                            }
                                            if(v.type == '4'){
                                                PageId.push(v.id);
                                            }
                                            option1 += '<li><a class="LevelChild'+num+(v.childmenu != null?' not-allowed':'')+'" data-pid="'+v.p_id+'" data-id="'+v.id+'" data-size="'+v.img_width+','+v.img_height+','+v.img_forcesize+'">├'+v.name+'</a></li>'; 
                                            if(v.childmenu != null){
                                                NextChild = v;
                                                num++;
                                                LoopChlid(NextChild,num);
                                                num--;
                                            }  
                                        }
                                    });
                                }
                            }
                            LoopChlid(NextChild,num);
                        }
                    });
                    _op = '<div class="dropdown fl">\
                            <div class="selectBox" type="text">'+pname+'</div><span class="arrow"></span>\
                            <input class="selectBox_val" name="column_name" type="hidden" value="'+id+'"/>\
                            <ul>'+option1+'</ul></div>';
                }
                $('.f_column').append(_op);
                // 下拉框模拟事件
                DropdownEvent(PageId);
                // 图片尺寸限制
                // AddarticlePicLimit();
                $('.not-allowed').MoveBox({context:'此为含有子级的父级栏目或为单页内容页，不支持选择！'});
            });
        });
        // 图片上传
        AddarticleUpload()
    }// 栏目显示End 
    // 实例化编辑器 
    if (typeof addarticleEditor !== 'undefined') UE.getEditor('container').destroy();
    var editor = UE.getEditor('container',{ 
        initialFrameHeight:300,
        autoHeightEnabled: false
    });
    editor.addListener( 'ready', function( editor ) {
        addarticleEditor = true;
    } );
    var AddarticleData = function(){
        if(G_id){
            //文章编辑
            $http.get('../article-info?id='+G_id+'').success(function(json) {
                // 编辑内容填充
                checkJSON(json, function(json){
                    // 实例化编辑器
                    if (typeof addarticleEditor !== 'undefined') UE.getEditor('container').destroy();
                    var editor = UE.getEditor('container',{ 
                        initialFrameHeight:300,
                        autoHeightEnabled: false
                    });
                    editor.addListener( 'ready', function( editor ) {
                        addarticleEditor = true;
                    } );
                    var id = G_id;
                    var _newpic = '',
                        d = json.data;
                    $('.creat_time').val(d.updated_at);
                    $('.visit').val(d.viewcount);
                    if(d.pc_show == 1){
                        $('.is_show input[value=pc_show]').attr('checked','true');
                        $('.is_show input[value=pc_show]').next().addClass('chirdchecked');
                    }
                    if(d.mobile_show == 1){
                        $('.is_show input[value=mobile_show]').attr('checked','true');
                        $('.is_show input[value=mobile_show]').next().addClass('chirdchecked');
                    }
                    if(d.wechat_show == 1){
                        $('.is_show input[value=wechat_show]').attr('checked','true');
                        $('.is_show input[value=wechat_show]').next().addClass('chirdchecked');
                    }
                    $.each(d.img,function(k,v){
                        _newpic += '<div class="template-download fade fr in">\n\
                                <div>\n\
                                    <span class="preview">\n\
                                    <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                        <img src="'+v+'" style="width:80px;height:64px;padding:5px;" data-preimg="preimg">\n\
                                    </span>\n\
                                </div>\n\
                            </div>';
                    });
                    $('.up_pic').before(_newpic);
                    $('.keyword').val(d.keywords);
                    $('.txts').val(d.introduction);
                    $('.art_tit').val(d.title);
                    if(d.title_bold){
                        $('.art_tit').css('font-weight','bold');
                        $('.add-r .set_blod').text('取消加粗');
                    }
                    $('.art_tit').css('color',d.title_color);
                    editor.addListener("ready", function () {
                            editor.setContent(d.content);
                    });
                });// checkJSON结束
            });// 文章保存结束
        }// 判断是否是编辑操作
        // 栏目加载
        column_show(); 
        // 编辑文章标题
        AddarticleTitleEditor();
        // 保存
        AddarticleSave();
    }();
    function AddarticleTitleEditor(){
        //编辑文章标题
        $('.add-r .set_blod').click(function(){
            if($('#addarticle-con .art_tit').css('font-weight') == '600' || $('#addarticle-con .art_tit').css('font-weight') == 'bold'){
                $('#addarticle-con .art_tit').css('font-weight','');
                $(this).text('加粗');
            }else{
                $('#addarticle-con .art_tit').css('font-weight','600');
                $(this).text('取消加粗');
            }
        });
        $('.add-r .set_color').click(function(){
            if($('#color-picker').css('display') == 'none'){
                $('#color-picker').show();
                $(this).text('确认修改');
            }else{
                $('#color-picker').hide();
                $(this).text('修改颜色');
            } 
        });
    }
    function AddarticlePicLimit(){
        $('.f_column li a').on('click',function(event) {
            if($(this).data('size') != null){
                var limitSize = $(this).data('size').split(','),
                    forces = limitSize[2],
                    imgErr;
                if(limitSize[0] && limitSize[1]){
                    $('.article_description_txt').html('<div '+(forces == 'true' ? 'class="fb"' : '')+'>('+limitSize[0]+'*'+limitSize[1]+')</div>'+(imgErr == "1" ? '<div class="warning"><i class="iconfont icon-gantanhao"></i></div>' : ''));
                    // 图片上传
                    proportion = limitSize[0]/limitSize[1];
                    AddarticleUpload(proportion);
                }else{
                    AddarticleUpload('');
                    $('.colum_description_txt').html('(建议'+(limitSize[0] == '' ? (limitSize[1] == '' ? '' : '高为:'+limitSize[1]) : '宽为:'+limitSize[0])+')')
                }
            }
        });
    }
    function AddarticleSave(){
        $('.addsave').click(function(){
            var editor = UE.getEditor('container');
            var art_info = editor.getContent();
            var id = G_id?G_id:'';
            var f_c = $('.selectBox_val').val();
            var c_t = $('.creat_time').val();
            var vt = $('.visit').val();
            var key = $('.keyword').val();
            var txts = $('.txts').val();
            var art_tit = $('.art_tit').val();
            var title_bold;
            if($('#addarticle-con .art_tit').css('font-weight') == '600' || 'bold'){    
                title_bold = 1;
            }else{
                title_bold = 0;
            }
            var title_color = $('#addarticle-con .art_tit').css('color');
            var s_t = new Array();
            var j = 0 ;
            $('.home-list .is_show input[type="checkbox"]').each(function(i) {
                if($(this).next().hasClass('chirdchecked')) {
                    s_t[j] = $(this).val();              
                    j ++ ;
                }
            });
            var upload_picname = [];
            $('.up_pic_feild>.template-download .preview>img').each(function(i, v) {
                var len = $(this).attr('src').split('/').length;
                upload_picname.push($(this).attr('src').split('/')[len-1]);
            });
            $http.post('../article-create', 
                {id       : id,
                c_id      : f_c,  
                pubdate   : c_t,
                title_bold: title_bold,
                title_color: title_color,
                viewcount : vt,
                is_show   : s_t,
                src       : upload_picname,
                keywords   : key,
                introduction   : txts,
                title     : art_tit,
                content   : art_info}).success(function(json) {
                checkJSON(json, function(json){
                    alert('修改成功！');
                    location.href='#/article';
                });
            });
        });
    }
}