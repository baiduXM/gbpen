
            //使用div时，请保证colee_left2与colee_left1是在同一行上.
            var speed=30//速度数值越大速度越慢
            var colee_left2=document.getElementById("colee_left2");
            var colee_left1=document.getElementById("colee_left1");
            var colee_left=document.getElementById("colee_left");
            colee_left2.innerHTML=colee_left1.innerHTML
            function Marquee3(){
            if(colee_left2.offsetWidth-colee_left.scrollLeft<=0)//offsetWidth 是对象的可见宽度
            colee_left.scrollLeft-=colee_left1.offsetWidth//scrollWidth 是对象的实际内容的宽，不包边线宽度
            else{
            colee_left.scrollLeft++
            }
            }
            var MyMar3=setInterval(Marquee3,speed)
            colee_left.onmouseover=function() {clearInterval(MyMar3)}
            colee_left.onmouseout=function() {MyMar3=setInterval(Marquee3,speed)}
            

 
            //使用div时，请保证colee_left2与colee_left1是在同一行上.
            var speed2=30//速度数值越大速度越慢
            var colee_5=document.getElementById("colee_5");
            var colee_4=document.getElementById("colee_4");
            var colee_3=document.getElementById("colee_3");
            colee_4.innerHTML=colee_3.innerHTML
            function Marquee5(){
            if(colee_4.offsetWidth-colee_3.scrollLeft<=0)//offsetWidth 是对象的可见宽度
            colee_3.scrollLeft-=colee_4.offsetWidth//scrollWidth 是对象的实际内容的宽，不包边线宽度
            else{
            colee_3.scrollLeft++
            }
            }
            var MyMar5=setInterval(Marquee5,speed2)
            colee_3.onmouseover=function() {clearInterval(MyMar5)}
            colee_3.onmouseout=function() {MyMar5=setInterval(Marquee5,speed2)}
         

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

