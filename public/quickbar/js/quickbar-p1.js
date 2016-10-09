// jquery文件是否存在
if (typeof jQuery == 'undefined') {
    var jsjQuery = document.createElement("script");
    jsjQuery.setAttribute("type", "text/javascript");
    jsjQuery.setAttribute("src", "http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js");
    jsjQuery.onload = jsjQuery.onreadystatechange = jqueryfunc;
    var headobj = document.getElementsByTagName("head")[0];headobj.appendChild(jsjQuery);
} else {
    jqueryfunc();
}


function jqueryfunc() {
    (function($) {
        $(function() {
            var maincolor = dataQuickbar.config.style.mainColor?dataQuickbar.config.style.mainColor:'#78c340';
            var seccolor = dataQuickbar.config.style.secondColor?dataQuickbar.config.style.secondColor:maincolor;
            var txtcolor = dataQuickbar.config.style.textColor?dataQuickbar.config.style.textColor:'#fff';
            var iconcolor = dataQuickbar.config.style.iconColor?dataQuickbar.config.style.iconColor:txtcolor;
            // 固定侧边导航样式
            var quickbar_style = '<style>' + '.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox:before,.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox:after,.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox:before,.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox:after,.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox:before,.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox:after{position:absolute;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);-moz-transform:translateY(-50%);transform:translateY(-50%)}.quickbar_nav ul li.quickbar_weixin .quickbar_hoverbox img,.quickbar_nav ul li:hover .quickbar_box,.quickbar_nav ul li:hover .quickbar_hoverbox,.quickbar_nav ul li:hover .quickbar_box .icon.iconfont{-webkit-transition:all 0.3s ease;-ms-transition:all 0.3s ease;-moz-transition:all 0.3s ease;transition:all 0.3s ease}*{margin:0;padding:0}* html body{background-image:url(about:blank);background-attachment:fixed}.quickbar_nav input{font-size:100%;outline:none}.quickbar_nav img{border:0;-ms-interpolation-mode:bicubic}.quickbar_nav ol,.quickbar_nav ul{list-style:none}.quickbar_nav a,.quickbar_nav a:hover{text-decoration:none}.quickbar_nav a:hover{clip:rect()}.quickbar_nav input::-webkit-input-placeholder{color:#fff}.quickbar_nav input:-moz-placeholder{color:#fff}.quickbar_nav input::-moz-placeholder{color:#fff}.quickbar_nav input:-ms-input-placeholder{color:#fff}.quickbar_nav input[placeholder]{color:' + txtcolor+ '}.quickbar_nav{position:fixed;width:59px;right:0;bottom:50%;transform:translateY(50%);font-size:14px;font-family:"Microsoft Yahei";z-index:99999}.quickbar_nav ul li{position:relative;margin-bottom:1px}.quickbar_nav ul li .quickbar_box,.quickbar_nav ul li .quickbar_hoverbox{height:59px;background-color:'+maincolor+';}.quickbar_nav ul li .quickbar_box{position:relative;width:59px;text-align:center}.quickbar_nav ul li .quickbar_box .icon{position:relative;width:36px;height:36px;left:0;right:0;top:0;margin:0 auto;font-size:36px;line-height:59px;color:'+iconcolor+'}.quickbar_nav ul li .quickbar_box .s_btn_wr .s_btn{position:absolute;border:none;height:100%;width:100%;top:0;left:0;z-index:2;background-color:transparent;background-image:none}.quickbar_nav ul li .quickbar_hoverbox{position:absolute;font-size:14px;color:'+txtcolor+'}.quickbar_nav ul li.quickbar_search .quickbar_hoverbox{width:0;top:0;right:59px;overflow:hidden}.quickbar_nav ul li.quickbar_search .quickbar_hoverbox #quickbar_kw{border:none;position:absolute;width:95%;height:39px;top:10px;left:0;right:0;margin:0 auto;background-color:'+maincolor+';padding:10px;box-sizing:border-box}.quickbar_nav ul li.quickbar_search:hover .quickbar_hoverbox{width:220px;background-color:'+seccolor+'}.quickbar_nav ul li.quickbar_qq:hover .quickbar_hoverbox{width:113px;opacity:1;padding:10px;border:1px solid #ccc;overflow:visible;-webkit-transition: width 0.3s ease, opacity 0.3s ease;-ms-transition: width 0.3s ease, opacity 0.3s ease;-moz-transition: width 0.3s ease, opacity 0.3s ease;transition: width 0.3s ease, opacity 0.3s ease}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox{width:0;opacity:0;right:69px;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);-moz-transform:translateY(-50%);transform:translateY(-50%);overflow:hidden;height:auto;background-color:#fff;padding:0}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox .hoverbox_title{color:#ff9000;font-weight:bold;line-height:2;height:28px;overflow:hidden;border-bottom:1px solid #ccc}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox .hoverbox_con>ul>li{overflow:hidden}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox .hoverbox_con>ul>li a{display:block;line-height:2;height:28px;overflow:hidden;color:#666}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox .hoverbox_con>ul>li a i.icon.iconfont{color:'+seccolor+'}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox:before,.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox:after{content:""}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox:before{right: -11px;width: 10px;height: 100%;background: transparent;}.quickbar_nav ul li.quickbar_qq .quickbar_hoverbox:after{border-left:9px solid #fff;border-top:6.5px solid transparent;border-bottom:6.5px solid transparent;right:-9px}.quickbar_nav ul li.quickbar_weixin .quickbar_hoverbox{top:50%;right:0;margin-right:69px;overflow:hidden;background:#fff;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);-moz-transform:translateY(-50%);transform:translateY(-50%)}.quickbar_nav ul li.quickbar_weixin .quickbar_hoverbox img{width:0;opacity:0}.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox{overflow:visible;border:1px solid #ccc}.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox img{width:auto;opacity:1;-webkit-transition: width 0.3s ease, opacity 0.3s ease;-ms-transition: width 0.3s ease, opacity 0.3s ease;-moz-transition: width 0.3s ease, opacity 0.3s ease;transition: width 0.3s ease, opacity 0.3s ease}.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox:before,.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox:after{content:""}.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox:before{border-left:5px solid #ccc;border-top:7.5px solid transparent;border-bottom:7.5px solid transparent;right:-5px}.quickbar_nav ul li.quickbar_weixin:hover .quickbar_hoverbox:after{border-left:5px solid #fff;border-top:6.5px solid transparent;border-bottom:6.5px solid transparent;right:-4px}.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox{top:0;right:0;width:0;margin-right:59px;font-size:24px;line-height:59px;text-align:center;overflow:hidden}.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox:before,.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox:after{content:""}.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox:before{border-left:5px solid #ccc;border-top:7.5px solid transparent;border-bottom:7.5px solid transparent;right:-5px}.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox:after{border-left:4px solid #fff;border-top:6.5px solid transparent;border-bottom:6.5px solid transparent;right:-4px}.quickbar_nav ul li.quickbar_phone:hover .quickbar_hoverbox{width:300px;height:auto;}.quickbar_nav ul li.quickbar_phone .quickbar_hoverbox li{width:300px;overflow: hidden;}.quickbar_nav ul li:hover .quickbar_box{background-color:'+seccolor+';}.quickbar_nav ul li:hover .quickbar_box .icon.iconfont{text-shadow:0 0 10px '+iconcolor+'}.quickbar_nav ul li.quickbar_top{cursor:default;display:none}.quickbar_nav ul li.quickbar_share:hover .quickbar_hoverbox{overflow:hidden;opacity:1;border:1px solid #ddd;box-shadow:1px 1px 0 rgba(221,221,221,0.2)}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox{width:0;opacity:0;height:57px;top:0;right:0;margin-right:59px;background:#fff;-webkit-transform:translateY(0);-ms-transform:translateY(0);-moz-transform:translateY(0);transform:translateY(0);overflow:hidden;-webkit-transition:all 0.25s ease-in;-ms-transition:all 0.25s ease-in;-moz-transition:all 0.25s ease-in;transition:all 0.25s ease-in}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox{padding:0}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a{display:block;float:left;width:32px;height:32px;line-height:32px;margin:12.5px 6px;padding:0;background-image:url(/quickbar/images/qb_bdshare_popup.png);background-repeat:no-repeat;cursor:pointer;text-indent:-100em}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_tqq{background-position:0 0}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_tqq:hover{background-position:0 -34px}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_weixin{background-position:-42px 0}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_weixin:hover{background-position:-42px -34px}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_tsina{background-position:-84px 0}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_tsina:hover{background-position:-84px -34px}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_qzone{background-position:-126px 0}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_qzone:hover{background-position:-126px -34px}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_ibaidu{background-position:-210px 0}.quickbar_nav ul li.quickbar_share .quickbar_hoverbox .bdsharebuttonbox a.bds_ibaidu:hover{background-position:-210px -34px}' + '</style>';
            $('head').append(quickbar_style);
            // 获取固定侧边导航数据
            var li_class = '';
            var li_btn = '';
            var li_btns = '';
            var quickbar_box = '';
            var quickbar_hoverbox = '';
            var shareable = 0; //分享按钮的个数
            var share_hoverbox_width = 0;
            var fixedLang = dataQuickbar.config.language == undefined ? 'cn':dataQuickbar.config.language;
            //遍历侧边导航数据
            $.each(dataQuickbar.quickbar, function(k, v) {
                li_class = '',
                    li_btn = '';
                quickbar_box = '';
                quickbar_hoverbox = '';
                // 若v.enable为0则continue 
                if (typeof v.enable !== 'undefined' && !v.enable) return true;
                if (v.type == 'search') { //搜索
                    li_btn = '<li class="quickbar_search">' + '<form id="quickbar_form" class="fm" action="' + v.data + '" method="GET" name="fm">' + '<div class="quickbar_box">' + '<span class="icon">' + (v.icon == null ? '<img src="' + v.image + '"/>' : '<i class="icon iconfont">' + v.icon + '</i>') + '</span>' + '<span class="s_btn_wr">' + '<input type="submit" class="s_btn" id="quickbar_submit" value="">' + '</span>' + '</div>' + '<div class="quickbar_hoverbox">' + '<span class="s_ipt_w">' + '<input type="text" id="quickbar_kw" name="s" class="s_ipt" placeholder="' + dataLang.searchPlaceholder[fixedLang] + '"/>' + '</span></div></form></li>';
                } else if (v.type == 'im') { //咨询                       
                    li_class = "quickbar_qq";
                    //im_li保存账号数据
                    var im_li = '';
                    //获取data数据
                    var data_arr = '';
                    if(v.data && (typeof v.data === 'string')){
                        data_arr = v.data.split('|');
                    }
                    if (data_arr.length == 1) {
                        im_li += '<li><a target="_blank" href="' + v.link + '"><i class="icon iconfont">&#xe64c;</i><span>' + data_arr[0].split(':')[0] + '</span></a></li>';
                    } else if (data_arr.length > 1) {
                        $.each(data_arr, function(im_k, im_v) {
                            var im_v_arr = im_v.split('@');
                            //获取咨询类型
                            var im_type = im_v_arr[1];
                            // 获取咨询的用户名和链接
                            var value = im_v_arr[0].split(':');
                            // 如果是qq咨询
                            if (im_type === "QQ" || im_type === "qq") {
                                im_li += '<li><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=' + value[1] + '&site=qq&amp;menu=yes"><i class="icon iconfont">&#xe64c;</i><span>' + value[0] + '</span></a></li>';
                            } else {
                                im_li += '<li><a target="_blank" href="' + value[1] + '"><i class="icon iconfont">&#xe64c;</i><span>' + value[0] + '</span></a></li>';
                            }
                        });
                    }

                    quickbar_hoverbox = '<div class="quickbar_hoverbox">' + '<div class="hoverbox_title">' + dataLang.quickbar.quickbarColumen.customerService[fixedLang] + '</div>' + '<div class="hoverbox_con"><ul>' + im_li + '</ul></div></div>';

                    quickbar_box = '<div class="quickbar_box">' + '<span class="icon">' + (v.icon == null ? '<img src="' + v.image + '"/>' : '<i class="icon iconfont">' + v.icon + '</i>') + '</span></div>';
                    li_btn = '<li class="' + li_class + '">' + quickbar_box + quickbar_hoverbox + '</li>';
                } else {
                    var li_target = '';
                    if (v.type == 'share') { //分享
                        var data_arr = '';
                        if (v.data && (typeof v.data === 'string')) {
                            data_arr = v.data.split(',');
                        }  
                        v.link = v.link?v.link:'javascript:;';
                        var bdsharebtns = "";
                        $.each(data_arr, function(index, val) {
                            switch (val) {
                                case 'qzone':
                                    shareable++;
                                    bdsharebtns += '<a href="#" class="bds_qzone" data-cmd="qzone" title="' + dataLang.share.shareQzone.shareTitle[fixedLang] + '"></a>';
                                    break;
                                case 'tqq':
                                    shareable++;
                                    bdsharebtns += '<a class="bds_tqq" title="' + dataLang.share.shareTen.shareTitle[fixedLang] + '"></a>';
                                    break;
                                case 'tsina':
                                    shareable++;
                                    bdsharebtns += '<a href="#" class="bds_tsina" data-cmd="tsina" title="' + dataLang.share.shareSina.shareTitle[fixedLang] + '"></a>';
                                    break;
                                case 'baidu':
                                    shareable++;
                                    bdsharebtns += '<a href="#" class="bds_ibaidu" data-cmd="ibaidu" title="' + dataLang.share.shareBaiduCenter.shareTitle[fixedLang] + '"></a>';
                                    break;
                                case 'weixin':
                                    shareable++;
                                    bdsharebtns += '<a href="#" class="bds_weixin" data-cmd="weixin" title="' + dataLang.share.shareWeChat.shareTitle[fixedLang] + '"></a>';
                                    break;
                                default:
                                    break;
                            }
                        });
                        share_hoverbox_width = shareable * 44;
                        // 添加微信按钮
                        li_class = "quickbar_share";
                        quickbar_hoverbox = '<div class="quickbar_hoverbox"><div class="bdsharebuttonbox" data-tag="share_2">' + bdsharebtns + '</div></div>';


                    } else if (v.type == 'map') { //地图
                        li_class = "quickbar_map";
                        li_target = 'target="_blank"';
                    } else if (v.type == 'link') { //外链
                    	li_class = "quickbar_link";
                        if(v.link.indexOf('<{}>') >= 0){
                            var linkArr = v.data.split("<{}>");
                            if (fixedLang=='cn') {
                                v.name = linkArr[0];
                            }else{
                                v.en_name = linkArr[0];
                            }
                            v.link = linkArr[1];
                        }
                        li_target = 'target="_blank"';
                    } else if (v.type == 'sms') { //短信
                        return true;﻿
                        // li_class = "quickbar_sms";
                        // quickbar_hoverbox = '<div class="quickbar_hoverbox">'+v.data+'</div>';
                    } else if (v.type == 'tel') { // 电话
                        li_class = "quickbar_phone";
                        var li_phonedata = '';
                        if (v.data && (typeof v.data === 'string')) {
                            li_phonedata = v.data.split('|');
                        }
                        
                        var li_phone = "";
                        $.each(li_phonedata, function(index, el) {
                            if(el.indexOf(':')>=0){
                                var v_data = el.split(':');
                                li_phone += '<li>'+'<span class="lable">'+v_data[0]+'</span>:<span class="num">'+v_data[1]+'</span>'+'</li>';
                            }else{
                                li_phone += '<li>'+'<span class="num">'+el+'</span>'+'</li>';
                            }
                        });
                        quickbar_hoverbox = '<div class="quickbar_hoverbox"><ul>' + li_phone + '</ul></div>';
                    } else if (v.type == 'follow') { //微信
                        li_class = "quickbar_weixin";
                        quickbar_hoverbox = '<div class="quickbar_hoverbox"><img src="' + v.data + '" alt=""/></div>';
                    }
                    quickbar_box = '<div class="quickbar_box">' + '<span class="icon">' + (v.icon == null ? '<img src="' + v.image + '"/>' : '<i class="icon iconfont">' + v.icon + '</i>') + '</span></div>';
                    li_btn = '<li class="' + li_class + '"><a ' + li_target + ' href="' + (v.link?v.link:"javascript:;") + '">' + quickbar_box + '</a>' + quickbar_hoverbox + '</li>';
                }
                li_btns += li_btn;
            });
            // 模块开启
            if (dataQuickbar.config.module) {
                if (typeof dataQuickbar.config.module === 'string') {
                    li_btns += $(dataQuickbar.config.module)[0].outerHTML;
                }else if (typeof dataQuickbar.config.module === 'object') {
                    if (dataQuickbar.config.module.totop) {
                        //添加返回顶部导航
                        li_btn = '<li class="quickbar_top" id="quickbar_top"><div class="quickbar_box">' + '<span class="icon"><i class="icon iconfont">&#xe617;</i></span>' + '</div></li>';
                        li_btns += li_btn;
                    }
                }
            }

            // 低版本jQuery的wrap方法会重复执行body根节点下的script标签
            if (parseFloat($().jquery) < 1.9) {
                $('body').find('script').remove()
            }

            var $quickbar_nav = $('<div class="quickbar_nav"><ul>' + li_btns + '</ul></div>');       
            
            //分享按钮的hover事件，计算长度
            $quickbar_nav.find('ul li.quickbar_share').hover(function() {
                $(this).find('.quickbar_hoverbox').css('width', share_hoverbox_width + 'px');
            }, function() {
                $(this).find('.quickbar_hoverbox').css('width', 0);
            });

            // 添加返回顶部事件
            /*$quickbar_nav.bind('click', '#quickbar_top', function(event) {
                var speed = 300; //滑动的速度
                $('html,body').animate({
                    scrollTop: 0
                }, speed);
                return false;
            });*/
            //将数据插入页面
            $('body').prepend($quickbar_nav);
            if(navigator.userAgent.indexOf("MSIE 8.0")>0) {
				$quickbar_nav.css("top","10%");
			}
            //隐藏和显示返回顶部按钮
            $(window).scroll(function() {
                if ($(document).scrollTop() <= 300) {
                    // $("#quickbar_top").animate({display: 'none'}, "slow");
                    $("#quickbar_top").animate({
                        opacity: 'hide'
                    }, "slow");
                } else {
                    $("#quickbar_top").animate({
                        opacity: 'show'
                    }, "slow");
                };
            });

            if (shareable) {
                if (!window._bd_share_config) {
                    var sharePic = $('img').attr('src');
                    window._bd_share_config = {
                        "common": {
                            "bdSnsKey": {},
                            "bdText": document.title || " ",
                            "bdPic": sharePic,
                            "bdMini": "2",
                            "bdMiniList": false,
                            "bdStyle": "0",
                            "bdSize": "32"
                        },
                        "share": {}
                    };
                    with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];

                }

            }
			$(".bds_tqq").click(function(){window.open("http://v.t.qq.com/share/share.php?url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title));});
            $('.hoverbox_con ul li a').click(function(){
                window.open (this.href);
            })

            $('.quickbar_map a').click(function(){
                window.open (this.href);
            })
            //外链失效问题添加点击事件
            $(".quickbar_nav .quickbar_link").click(function(){
            	var link = $('.quickbar_link a').attr("href");
            	window.open(link);
            });
            //分享按钮的hover事件，计算长度
            $('.quickbar_nav ul li.quickbar_share').hover(function() {
                $('.quickbar_nav ul li.quickbar_share .quickbar_hoverbox').css('width', share_hoverbox_width + 'px');
            }, function() {
                $('.quickbar_nav ul li.quickbar_share .quickbar_hoverbox').css('width', 0);
            });

            // 添加返回顶部事件
            if ($("#quickbar_top")) {
                $("#quickbar_top").bind('click', function(event) {
                    var speed = 300; //滑动的速度
                    $('html,body').animate({
                        scrollTop: 0
                    }, speed);
                    return false;
                });
            }
        });
    })(jQuery);
}
