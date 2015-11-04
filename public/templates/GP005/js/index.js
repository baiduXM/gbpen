//使用div时，请保证colee_left2与colee_left1是在同一行上.
            var speed2=30//速度数值越大速度越慢
            var colee_l2=document.getElementById("colee_l2");
            var colee_l1=document.getElementById("colee_l1");
            var colee_l=document.getElementById("colee_l");
            colee_l2.innerHTML=colee_l1.innerHTML
            function Marquee4(){
            if(colee_l2.offsetWidth-colee_l.scrollLeft<=0)//offsetWidth 是对象的可见宽度
            colee_l.scrollLeft-=colee_l1.offsetWidth//scrollWidth 是对象的实际内容的宽，不包边线宽度
            else{
            colee_l.scrollLeft++
            }
            }
            var MyMar4=setInterval(Marquee4,speed2)
            colee_l.onmouseover=function() {clearInterval(MyMar4)}
            colee_l.onmouseout=function() {MyMar4=setInterval(Marquee4,speed2)}      

//设为首页
            function SetHome(obj, vrl) {
                try {
                    obj.style.behavior = 'url(#default#homepage)';
                    obj.setHomePage(vrl);
                }
                catch (e) {
                    if (window.netscape) {
                        try {
                            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                        }
                        catch (e) {
                            alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                        }
                        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                        prefs.setCharPref('browser.startup.homepage', vrl);
                    } else {
                        alert("您的浏览器不支持，请按照下面步骤操作：1.打开浏览器设置。2.点击设置网页。3.输入：" + vrl + "点击确定。");
                    }
                }
            }
            $(document).ready(function(){
    
    //实例一
    $("#menu li a").wrapInner('<span class="out"></span>' ).append('<span class="bg"></span>');
    $("#menu li a").each(function(){
        $('<span class="over">' +  $(this).text() + '</span>').appendTo(this);
    });

    $("#menu li a").hover(function(){
        $(".out",this).stop().animate({'top':'39px'},250);
        $(".over",this).stop().animate({'top':'0px'},250);
        $(".bg",this).stop().animate({'top':'0px'},120);
    },function(){
        $(".out",this).stop().animate({'top':'0px'},250);
        $(".over",this).stop().animate({'top':'-39px'},250);
        $(".bg",this).stop().animate({'top':'-39px'},120);
    });

});
/*鼠标移过，左右按钮显示*/
        jQuery(".focusBox").hover(function(){ jQuery(this).find(".prev,.next").stop(true,true).fadeTo("show",0.2) },function(){ jQuery(this).find(".prev,.next").fadeOut() });
        /*SuperSlide图片切换*/
        jQuery(".focusBox").slide({ mainCell:".pic",effect:"fold", autoPlay:true, delayTime:600, trigger:"click"});

    document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
//加入收藏
            function shoucang(sTitle, sURL)
            {
                try
                {
                    window.external.addFavorite(sURL, sTitle);
                }
                catch (e)
                {
                    try
                    {
                        window.sidebar.addPanel(sTitle, sURL, "");
                    }
                    catch (e)
                    {
                        alert("加入收藏失败，请使用Ctrl+D进行添加");
                    }
                }
            }

