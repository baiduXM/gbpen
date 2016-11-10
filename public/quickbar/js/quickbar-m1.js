// jquery文件是否存在
if (typeof jQuery == 'undefined') { 
	var jsjQuery = document.createElement("script");
	jsjQuery.setAttribute("type", "text/javascript");
	jsjQuery.setAttribute("src", "http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js");
	jsjQuery.onload = jsjQuery.onreadystatechange = jqueryfunc;
	var headobj = document.getElementsByTagName("head")[0];headobj.appendChild(jsjQuery);
}else{
	jqueryfunc();
}
function jqueryfunc(){
	(function($){
		/* share.js */
		var shareid="fenxiang";(function(){var b={url:function(){return encodeURIComponent(window.location.href)},title:function(){return encodeURIComponent(window.document.title)},content:function(a){if(a){return encodeURIComponent($("#"+a).html())}else{return""}},setid:function(){if(typeof(shareid)=="undefined"){return null}else{return shareid}},kaixin:function(){window.open("http://www.kaixin001.com/repaste/share.php?rtitle="+this.title()+"&rurl="+this.url()+"&rcontent="+this.content(this.setid()))},renren:function(){window.open("http://share.renren.com/share/buttonshare.do?link="+this.url()+"&title="+this.title())},sinaminiblog:function(){window.open("http://v.t.sina.com.cn/share/share.php?url="+this.url()+"&title="+this.title()+"&content=utf-8&source=&sourceUrl=&pic=")},baidusoucang:function(){window.open("http://cang.baidu.com/do/add?it="+this.title()+"&iu="+this.url()+"&dc="+this.content(this.setid())+"&fr=ien#nw=1")},taojianghu:function(){window.open("http://share.jianghu.taobao.com/share/addShare.htm?title="+this.title()+"&url="+this.url()+"&content="+this.content(this.setid()))},wangyi:function(){window.open("http://t.163.com/article/user/checkLogin.do?source=%E7%BD%91%E6%98%93%E6%96%B0%E9%97%BB%20%20%20&link="+this.url()+"&info="+this.content(this.setid()))},qqzone:function(){window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url="+encodeURIComponent(location.href)+"&title="+encodeURIComponent(document.title))},qqweibo:function(){window.open("http://v.t.qq.com/share/share.php?url="+encodeURIComponent(location.href)+"&title="+encodeURIComponent(document.title))+"&appkey="+encodeURI("appkey")},pengyou:function(){window.open("http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url="+encodeURIComponent(location.href)+"&title="+encodeURIComponent(document.title))},douban:function(){window.open("http://www.douban.com/recommend/?url="+this.url()+"&title="+this.title()+"&v=1")}};window.share=b})();
		
		$(function(){
			// 底部导航样式
			var share_style = '<style>'
				+'body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td,hr,button,article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section {margin:0;padding:0;}'
				+'body{display: -webkit-box;-webkit-box-orient: vertical;-webkit-box-align: stretch; min-width:320px; max-width:640px; margin:0px auto;overflow-x: hidden; font-size:14px; font-family:"Microsoft Yahei";}'
				+'body>.body{ position: absolute; width:100%; height:100%; min-width:320px; max-width:640px;  overflow:hidden; }'
				+'.page-in {-webkit-transition:all 300ms cubic-bezier(0.42, 0, 0.58, 1);-ms-transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1);transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1);}'
				+'.page-out {-webkit-transition:all 300ms cubic-bezier(0.42, 0, 0.58, 1) 0.1s;-ms-transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1) 0.1s;transition: all 300ms cubic-bezier(0.42, 0, 0.58, 1) 0.1s;}'
				+'#quickbar-navs{width:240px;  height:100%; z-index:7; background:'+dataQuickbar.config.style.mainColor+'; position: absolute; top:0px; left:0px; color:#1e1c1a;}'
				+'#quickbar-navs.page-prev {transform:translate3d(-240px, 0, 0);-ms-transform:translate3d(-240px, 0, 0);-webkit-transform:translate3d(-240px, 0, 0);	-o-transform:translate3d(-240px, 0, 0);	-moz-transform:translate3d(-240px, 0, 0);}'
				+'#quickbar-navs .quickbar-navs-top{ position:relative;z-index:2; height:40px; overflow:hidden; line-height:40px; font-size:1.3em;  background:rgba(255,255,255,0.3); color:#fff; text-align:left; padding-left:10px;}'
				+'#quickbar-navs .quickbar-navs-close{ position:relative; z-index:99; width:20px; margin-top:5px; margin-right:5px; height:20px; line-height:20px; float:right; display:inline; text-align:center; font-size:1em; padding:1px; border-radius:100%; background:rgba(0,0,0,0.5);}'
				+'#quickbar-navs .quickbar-navs-m{ height: 100%; box-sizing:border-box; margin-top: -40px; padding-top: 40px; overflow-y:scroll;-webkit-overflow-scrolling:touch;}'
				+'#quickbar-navs ul.quickbar-navs-list{ padding:0px; margin:0px; height:auto; overflow:hidden;}'
				+'#quickbar-navs ul.quickbar-navs-list li{ height: auto; line-height:35px; overflow:hidden; border-bottom:1px solid rgba(0,0,0,0.3); font-size:1.15em;}'
				+'#quickbar-navs ul.quickbar-navs-list li.menu_body{ margin-bottom:0px;}'
				+'#quickbar-navs ul.quickbar-navs-list li .qbnav-icon{ color:'+dataQuickbar.config.style.secondColor+'; width:35px; height:35px; text-align:center; float:right; display:inline;text-indent:0px; font-size:1.2em;}'
				+'#quickbar-navs ul.quickbar-navs-list li a{ color:#ffffff; display:block;margin-left:10px;}'
				+'#quickbar-navs ul.quickbar-navs-list li .qbnav-icon1{ display:none;}'
				+'#quickbar-navs ul.quickbar-navs-list li.menu_body dl{ height:32px; line-height:32px; overflow:hidden; font-size:0.9em; text-indent:0px; padding-left:12px;}'
				+'#quickbar-navs ul.quickbar-navs-list li.menu_body dl span{ color:'+dataQuickbar.config.style.secondColor+'; font-weight:bold; }'
				+'#quickbar-navs ul.quickbar-navs-list li.menu_body dl a{ color:'+dataQuickbar.config.style.textColor+'; display:block;border-radius:0px; margin:0px;}'
				+'#quickbar-navs ul.quickbar-navs-list li.menu_body dl a:active{ background:rgba(0,0,0,0.1);}'
				+'#quickbar-navs ul.quickbar-navs-list li.cur{background:rgba(0,0,0,0);}'
				+'#quickbar-navs ul.quickbar-navs-list li.cur .qbnav-icon1{ display:block;}'
				+'#quickbar-navs ul.quickbar-navs-list li.cur .qbnav-icon2{ display:none;} '
				+'#quickbar-navs ul.quickbar-navs-list li.menu_body{  height:0px; overflow:hidden; border-bottom:0px; background:rgba(0,0,0,0.1);}'
				+'#quickbar-navs ul.quickbar-navs-list li.cu{ height:auto; overflow:hidden;}'
				+'#quickbar{ width:100%; height:58px; padding-top:2px; overflow:hidden; background:'+dataQuickbar.config.style.mainColor+';position:absolute; left:0px; bottom:0px; z-index:99; }'
				+'#quickbar a,#quickbar a:hover,#quickbar-navs a,#quickbar-navs a:hover,.quickbar-sharebox a,.quickbar-sharebox a:hover {text-decoration:none;}'
				+'#quickbar-wrap{ width:100%; height:100%; position: absolute; top:0px; left:0px; z-index:5; }'
				+'#quickbar-wrap.page-next {transform:translate3d(240px, 0, 0);-ms-transform:translate3d(240px, 0, 0);-webkit-transform:translate3d(240px, 0, 0);	-o-transform:translate3d(240px, 0, 0);	-moz-transform:translate3d(240px, 0, 0);}'
				+'#quickbar-wrap-body{width:100%; height:100%; overflow:hidden;overflow-y : auto; -webkit-overflow-scrolling:touch;overflow-scrolling: touch; position:absolute; top:0px; left:0px; }'
				+'#quickbar .iconfont{font-size:25px;line-height:30px;}'
				+'#quickbar > ul{margin:0;padding:0;display:-webkit-box;display:-moz-box;display:-o-box;display:-ms-box;display:box; width:100%; height:auto; overflow: hidden;}'
				+'#quickbar > ul li{list-style:none; height:58px; overflow:hidden; text-align:center; }'
				+'#quickbar > ul li{-webkit-box-flex:1;-moz-box-flex:1;-o-box-flex:1;-ms-box-flex:1;box-flex:1;}'
				+'#quickbar > ul li a{ color:'+dataQuickbar.config.style.textColor+'; display:block; }'
				+'#quickbar > ul li p{ height:23px; overflow:hidden;  line-height:32px;}'
				+'#quickbar > ul li .title{ height:23px; overflow:hidden;  line-height:23px;}'
				+'#quickbar > ul li .fix_icon{ height:33px; overflow:hidden;'+(dataQuickbar.config.style.iconColor?'color:'+dataQuickbar.config.style.iconColor+';':'')+'}'
				+'#quickbar .current{background:rgba(0,0,0,0.4);}'
				+'#quickbar .current1{background:rgba(255,255,255,0.2); }'
				+'.quickbar-tips{ display:none; height:35px; overflow:hidden; line-height:35px; position:absolute; bottom:58px; background-color:'+dataQuickbar.config.style.secondColor+'; left:0px; z-index:99; width:100%; color:'+dataQuickbar.config.style.textColor+'; font-size:1.13em; }'
				+'.quickbar-tips .tips-close{ width:35px; height:35px; overflow:hidden; line-height:35px;float:right; display: block; position:relative;  cursor:pointer; color:'+dataQuickbar.config.style.textColor+';  text-indent:0px; text-align:center; z-index: 10;}'
				+'.quickbar-tips:before{ width:25px; height:25px; content:""; position:absolute; background-color:'+dataQuickbar.config.style.mainColor+'; border-radius:100%; right:5px; top:5px;}'
				+'.quickbar-tips .tips-content{ background-color:rgba(255,255,255,0.2);}'
				+'.quickbar-tips .iconfont{ font-size:25px; margin: 0 5px;}'
				+'.quickbar-sharebox{ width:100%; height:130px; padding-top:15px; position:absolute; bottom:-145px; left:0px; z-index:9999; background:#f5f5f5;-webkit-transition: all 0.3s ease-in; -moz-transition: all 0.3s ease-in; -o-transition: all 0.3s ease-in; transition: all 0.3s ease-in; }\n'
				+'.quickbar-sharebox.show_share{transform: translate(0px,-145px);-ms-transform: translate(0px,-145px);-webkit-transform: translate(0px,-145px);-o-transform: translate(0px,-145px);-moz-transform: translate(0px,-145px);}'
				+'.quickbar-sharebox .bn-share-con {overflow-x:scroll;-webkit-overflow-scrolling:touch;}'
				+'.quickbar-sharebox .thumbs-cotnainer {width: 640px;}'
				+'.quickbar-sharebox .thumbs-cotnainer .share-icon {float: left;width: 80px; text-align:center}'
				+'.quickbar-sharebox .thumbs-cotnainer .share-icon .title {color: #AAA; white-space: nowrap;text-overflow: ellipsis;overflow: hidden;}'
				+'.quickbar-sharebox .share-cance{ height:35px; line-height:35px; overflow:hidden; background:rgba(0,0,0,0.05); text-align:center; margin-top:10px;}'
				+'.quickbar-sharebox .share-con{ width:100%; height:85px; overflow:hidden;}'
				+'.quickbar-opacity2{ width:100%; height:100%; position:fixed; top:0px; left:0px; background:rgba(0,0,0,0.2); z-index:2222; display:none;}'
				+'.quickbar_search{position:fixed;bottom:58px;margin:auto;right:0;height:35px;padding:3px 0;z-index:9999;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out;overflow:hidden;width:0;opacity:0}.quickbar_search:before{position:absolute;top:0;left:0;right:0;bottom:0;content:"";background:'
				+(dataQuickbar.config.style.secondColor?dataQuickbar.config.style.secondColor:dataQuickbar.config.style.mainColor)
				+';opacity:.7}.quickbar_search.search_show{width:100%;opacity:1}.quickbar_search .s_ipt_w,.quickbar_search .s_btn_wr{position:absolute;top:0;bottom:0;height:30px;margin:auto 0}.quickbar_search .s_ipt,.quickbar_search .s_btn{font-family:"Microsoft Yahei";font-size:14px;border-radius:5px;height:30px;line-height:30px;overflow:hidden;color:'
				+dataQuickbar.config.style.textColor+';background:'+(dataQuickbar.config.style.secondColor?dataQuickbar.config.style.secondColor:dataQuickbar.config.style.mainColor)
				+';width:100%;border:0}.quickbar_search .s_ipt_w{left:0;right:58px}.quickbar_search .s_ipt{text-indent:7px}.quickbar_search .s_btn_wr{width:48px;right:5px}.quickbar_search .s_btn{cursor:pointer}'
				+'.follow_img{display: none;position:fixed;z-index:9999;text-align:center;top: 50%;left: 50%;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);transform: translate(-50%,-50%);}.follow_img img{position:relative;padding-bottom:64px;}</style>';
			$('head').append(share_style);
			// 底部导航 
			var li_btn = '';
			var li_nav = '';
			var search_form = '';
			var follow_img = '';
			var fixedLang = dataQuickbar.config.language == undefined ? 'cn':dataQuickbar.config.language;
			// 获取底部导航图标数据
			$.each(dataQuickbar.quickbar, function(k,v) {
				if (typeof v.enable !== 'undefined' && !v.enable) return true;
				if (v.type == 'share') {
					var idAttr = 'id="share_btn"';
				}else if(v.type == 'search'){
					var idAttr = 'id="search_btn"';
					search_form = '<div class="quickbar_search">' + '<form id="quickbar_form" class="fm" action="' + v.data + '" method="GET" name="fm">' + '<span class="s_ipt_w">' + '<input type="text" id="quickbar_kw" name="s" class="s_ipt" placeholder="' + dataLang.searchPlaceholder[fixedLang] + '"/>' + '</span>' + '<span class="s_btn_wr">' + '<input type="submit" class="s_btn" id="quickbar_submit" value="' + dataLang.search[fixedLang] + '">' + '</span></form></div>';
				}else if (v.type == 'follow') {
					var idAttr = 'id="follow_btn"';
					follow_img = '<div class="follow_img"><img src="' + v.data + '" alt=""/></div>';
				}else if (v.type == 'link') {
					var idAttr = '';
					if (v.data.indexOf('<{}>') >= 0) {
						var linkArr = v.data.split("<{}>");
						if (fixedLang=='cn') {
							v.name = linkArr[0];
						}else{
							v.en_name = linkArr[0];
						}
						v.link = linkArr[1];
					}
				}else if (v.type == 'im') {
					var idAttr = '';
					if (v.data.indexOf('<{}>') >= 0) {
						var dataArr = v.data.split("<{}>");
						if (fixedLang=='cn') {
							v.name = dataArr[0];
						}else{
							v.en_name = dataArr[0];
						}
						v.data = dataArr[1];
					}
				}else{
					var idAttr = '';
				}
				li_btn += '<li><a href="'+(typeof v.link !== 'undefined' ? v.link : 'javascript:void(0)')+'" '+idAttr+'><p class="fix_icon">'+(v.icon == null ? '<img src="'+v.image+'" width="33" width="33">' : '<i class="iconfont">'+v.icon+'</i>' )+'</p><p class="title">'+(fixedLang=='cn'?v.name:v.en_name)+'</p></a></li>';
			});
			// 获取侧边导航数据
			$.each(dataQuickbar.catlist, function(k,v) {
				if(v.childmenu == null){
					li_nav += '<li><a href="'+v.url+'" style="color:'+(dataQuickbar.config.style.textColor?dataQuickbar.config.style.textColor:"#fff")+'">'+(fixedLang=='cn'?v.name:v.en_name)+'</a></li>';
				}else{
					li_nav += '<li class="menu_head">\n\
					<dl class="qbnav-icon"><span class="qbnav-icon1" style="color:'+(dataQuickbar.config.style.textColor?dataQuickbar.config.style.textColor:"#fff")+'">-</span><span class="icon2" style="color:'+(dataQuickbar.config.style.textColor?dataQuickbar.config.style.textColor:"#fff")+'">+</span></dl>\n\
					<a href="'+v.url+'" style="color:'+(dataQuickbar.config.style.textColor?dataQuickbar.config.style.textColor:"#fff")+'">'+(fixedLang=='cn'?v.name:v.en_name)+'</a></li>\n\
					<li class="menu_body">';
					!function(childmenu, deep) {
						deep++;
						for (var i = 0; i < childmenu.length; i++) {
							li_nav += '<dl style="padding-left:'+(deep*10)+'px"><a href="'+childmenu[i].url+'">>'+childmenu[i].name+'</a></dl>';
							if (childmenu[i].childmenu) {
								arguments.callee(childmenu[i].childmenu, deep);
							}
						}
					}(v.childmenu, 0);
					li_nav += '</li>';
				}
			});
			// 低版本jQuery的wrap方法会重复执行body根节点下的script标签
			if (parseFloat($().jquery) < 1.9) {
				$('body').find('script').remove()
			}
			// 数据填充
			var share_pic = {
				baidu : 'data:image/gif;base64,R0lGODlheAB4APf/AGy57A6N4pnN8eHt9sTh+O71+Xm+7f/8+dvq9SKV5ACD4AWJ4UWl6FKt6f7++wCC3+Dv+vj6/N3t+ZHJ8XW+7cHh9v76+JXK733B7vr5+IrG7ySY5Nrs+qHR8orF8Lrd9fz8+0mo6fL4/Dmh5/n7/ery973e9vX4+uLw+uXx+YbE7gCA39Tp+dTo9juj6LTa9Eyq6fr6+S6d5vb29tHo+R6V5BWR4wmI4RGQ4/D2+ujy/FSu6sXh9GW27BGO42Gy7F2y6qnU8a3X9Or0+kGl6DGd5v38/DWg5+Xx/Mjj+ASG4YvI763W8s7l9ima5anV9J/Q8vr6+oHC7luv60ul6GW16/z6+vD2/cPf+AmM4jWc5uny+LDZ9KXT8s7m+YXF8F6x7AKG4XG87fX19dHm9W627fb6/GSz7Gi17K/Z9Tyi55XM8Wm360Wn6GC064XB8Oz1/E6s6b7g9yuW5fz9/8vl+D6k5zid51mw68nk977e8nW67tfr+KnT9dHn+fT4/vT4/B+Q47LZ9hmS4+r0/eXw9m+67UWo54LA8Mni9MXg8wmG4QyL4huU5AOK4XO57pTL8bvd8/3+/wOI4e/09wB+3hiU47fc9ROK4vv8/Pj6+fX6/XC47qbV86LR9Cyb5hyU4xWO41ev6gCB3wCI4f///gCE4ACF4ACH4QCG4P///f///P7+/v/+/f79/QCH4HG67v79/P7+/f/+/P/9+f/++v37+QCG4f/8+QCE3wGJ4fv6+f36+P78+v79+wCF4fz6+P/+/vz7+QCK4QKF4P/9+/7+/P/9/IPE7wCI4P78+/36+fX6/mO17Cuc5v7//wKH4QGI4bDa9hOP4wSC3/38+ub0/BuQ47TZ8rrc98Xk9+Lw+3K67gGG4AGG4QKH4MPg8wKD31Gs61er6//+/6bS9LPb9nG77B2S5LDX8lqx62iz7XG57vz7//j5+3O47fj6+waL4vX5/I/H8fb5/vj6/s3j9ff39/v7+/z8/Pj4+Pn5+f39/f///wCJ4f///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RjEzQTI4NDYyNjdBMTFFNEJBNzBDN0I1RDgwNTU0QzEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RjEzQTI4NDcyNjdBMTFFNEJBNzBDN0I1RDgwNTU0QzEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGMTNBMjg0NDI2N0ExMUU0QkE3MEM3QjVEODA1NTRDMSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGMTNBMjg0NTI2N0ExMUU0QkE3MEM3QjVEODA1NTRDMSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8e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',
				Qzone : 'data:image/gif;base64,R0lGODlheAB4APf/ACJ50Ch0ufn05Et7k4aYdSmB2PzrthptwEOL1EOFuP7HHP3VWdjl9PTDJ/fSY7bR7LSsWezBLVeNqf7ccv3HINStNuPIef7LLP7HIBZtxnqWhCdrp6enY/7MM1aGmCJ4z5qia/ny3jd4qv7SSf/77d7JhN3BZ5S95myTldS2PJmeZf79+RpwyfHCKjuEzmOFg+OxGfvil9WrKxFitdSmGvz68n6UeTeAyhNmuxlwxv/OO//44lSb4FqCiv701R541c60Q+nBMxRqwDN+yNe6Vfr26PrGI+q5H4eVbRJovtSkFf/GGiV+2dOpJip8ysWxStu6OjF3s//HHB50yhxsvDqDw/zuxDuCvNm0Reu7ILmqTsGuSfXpw3ip2iR91jF+xGWd0ujTkx9nrCF61A9mvv/JG+W9MfPdmhRtyPfu0iyB1EOHxdaxPmSPl1GX3i17xiRutffFJdOnIPncgfTKRf/KHP/JJh51y/TjsDRxnk6Jr9ytHPvFH/bBHyN0xBtyyc2xPKilXKKjYK6qXebQjGmIff779P789k+V3PvgjvnDH//+/Cp+0f/56DWAwubp3RFrxty5NSaA2eK9Nn2y5uK4LyB2ze7z+itxrei3HZ7C5zuL2/7zzs3g8u+7HmyPi+m/Lua/NOCvGPvprvv58UB6odSzNvvnqR1wwvbryOO8M1me4tepHvO+H9i2NjCF2nCVkBZwy+C6NLCoVSJ81vjx2xtquEqAnvzmpCV6zfv47XKMfMewRfv8/h5yxEaO1v3+/+i/McmzSBdksBRrwjCCzyd8zxRrxP/////JHv/JIP/IH//IHv7//z6H0EmR2EyT2k2U2/7+/vz9/v7HH/7+/////o6cceKwF6Kogf/KHt/q9k6LuEyPzf79++fNavfCH8GwUMynKuLfwtKnJ9msI9asLvLDMC1tofjw1ufETWCPn/7HHh9wvdfVrxdtwxxzyiV80xNpvxtxyCd+1RhuxRVswiR70iZ91BVrwRNqwCh/1imA1yqC2f/IIP///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RUQ2MTczOEIyNjdBMTFFNEI2N0VDM0UwNUUyMzMyQkYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RUQ2MTczOEMyNjdBMTFFNEI2N0VDM0UwNUUyMzMyQkYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpFRDYxNzM4OTI2N0ExMUU0QjY3RUMzRTA1RTIzMzJCRiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpFRDYxNzM4QTI2N0ExMUU0QjY3RUMzRTA1RTIzMzJCRiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACH5BAEAAP8ALAAAAAB4AHgAAAj/AP8JFAhsmyZKq3goXMiwocOHECNKnEgRohs3FFdR0tRp2sCPAi+dcPPqVb+TKFOqXMmypcuXMGPKjPlqE6VLIP9N6xStn6QCBWYKHUq0qFGUQE8+6yRtILIHv34enUq1alVJmx70EthpkySrYMOKfYmVwb9Lbr6OXcv2aFKklKY98Nq2rl2YBfYFRSkJ0TZKr/beHUy4XwF+KQu8OrGqsOPBb08GpcRjJdDLmDNr3sy5M2dJTCT99Ey6tOnMKROu5Me6tevXsGPLnh272Joqamjr3s37teB+GBP3Hk4ctqRisICE05O7uPPevxUKf05997oUZYwMqsKkuvfYKaWj//xO3rWkGxziJKsTTIKk8uXfij8Jv/w+CZOS+UtmhEOV9/V5t5d4hwXoHRPFgNCAfvvJIsE+BlY3YGX9ROjdPnpAIYU/HDITBwguAGhhcSeJx88+KKao4oostujiiysepyCDHbrCTV4w5qjjjinyU4CJPAYpJIsZMsPhkck0QEAxTAzpZJA/8gDUk1TmiOA1Cx7J4TIYALGGJFWG6aJCh4lpZoqScJPChlpyqEwLGjB55plkzmnmcdecQ2ObGISzBi12iolRoGKmCQQ7e2pZRgQoMNIkoU9eRM+klFZq6aWYZqqppQhq0EKiWvIHQRVebGrqqahS6gYiqbbqKqfcAP/BZptaMmOGBK/m2qqkuvaqqReMaBBBHbS2mcyHN5Tq67KV8srss15cEQ4FoNLKjCsSKPvssqvi4+234IYr7rjklvstsLAEUS2tSRLAiBfmxivvvPggggi9+Ob7LS1VhLPMMsWySwEQCcCr78Hx2ovwwuR6YQwswRAbMLtwqkELwxh/q3DGGXtRBQRGrFssl+EkcDHHDG+MMsLArhOKyAHXwSg+Bq+sr8o258svBNROHLCoVZycM76IRBPP0UgnrfTSTDftdDz3rDOJxD4Xm0x79zyt9dZbR2M012CHfTQtjqQH88T8+UeL2Gxr7XXbcDNNizFSn+1zMg5mHffeR7//zffeZHMQctV3f/jF2n/DHQ009zTu+OOQRy755JOPkSHVhAfMTAoJ/ED556CHfg80jItu+uljDAGCepnf3YAGToxx+uygk0777ZVn2Drh7AjTOe7AO2578LiPMYYjq9ud+aIo3CM78bcPD33kxhv/w/WyG+PEF7C8vHvVyVBQsvPXY1/989NDLj3w5xt/j/bbO1LFFQnosQ4sNgyiCgbKt75MAxBAgR4ScIUqOMIRX3CCE4zhvPahD3jQeAYAJkjBClZwDOXLYC5y8YY3fMERV7AfLDRwjUGEQxhQCEUQItCCODADYN8j3DKMEIFQTCIF4RiEIK6hAVisQw9XOOAX/97ghA0CIIMZBMAYLMjEZ0iQiRX8AQBysb0PXiGEEsDfNUAwiCdAYRKhiAALjUABZpShDmisQ/9iuB80lqEMGDBCHBogRhtCAYc6vIYNYNGGAV4RgQksIhQr6MRBTjAXjhDhFgeBQhWysIVG2E8yJklJGLLxkm1aBiU3WQZmUECOLWhBBIJgQzzucI8//IIhCznIXKzjCWBkYRyMsIwzprGSmPTZvyyZS2NRMo1ujGMcRFnDSXDgCoNk5Qc+EMUvgIAPatxkL7fEDHZI4ZrXXII2tYlNdnhTAdnc5jaxKQV2vJCXMdTkJtcDinXkAoqF/IAlmDnBZTpBA6BYI63+xf+Mal6TGXYI6AUu0IGCdkAHCNXBCBa6gIYudKEJRahBO3CBgCpjGdg8Zy6XQQFedI6C9AQAKysoTwB8IAqBaADmdGlNKWiTHf6oKEE7MIIFTGACiYjBKUYxCitYwQc7CGojdtCIog51qEH1gQ98OopTxCARN13ACAo6UGVgAKZsTIYUXPECP4B0nhN04jJNusyyfoAFIlBBBPo3U4TaNKc95QRRG0ECQxxiBXiVRjWa0Qxk+PWvgPUrX/laDW+swBCGIAFSlzqKGExgATqwAzqrxlFTvAAOUzCrWZ/xC0t49rOgnUI9onCNYRGuAwuYQwwMwAmg7qAIuiDFXfG6CGv/BPa2uM3tba1hDWnglQQ+mMMFsJq5ZCwDCD1oxx9Ay9xfdJa5zJ1CBkgbjH9NDAM0rakDtrtdC5TAAoQILyHOgIfyciEVaUhHCARQBNjGtgYrsK1u57uCVDigA/6YbMBmKIzkLvcO0PWscwMc3RxEwQaqKIN+OWSEFmQiE6yIcISbQGFyWJgcMpCBOcxRAQ5X4MNEMIEJwBsGPKSiFgJ4712lMV9k6OIMbGBFJvigjOJiYAsDaMc8CPzZAfP4s3eoRwBsEAlqrIsC4OhDH1rhiSwc4clPhoGUpYwNbIhCCVjOMg3kIAcKN8HDbAixBc4QAhbj1horSIMFzEGDTCgC/wPFjYMWBkAFeFgCwDz28Y89O4UcwKEQriiD8jBAgUIb+tCF5oMiFq2IJLciC06W8pVpoAQa0MAcFgiBfG9bAzxggQai6AMFakw4/syiFHXes4Cfq+o7swAOPQAEH/TpJmXY+ta4tjWHCE0BPvgaHI4OxRxC4A3cLiIdhKhAm2ncumS04NQH+K+q9dzqO8zDFrGe5DQzZ+tklMEOCzDAIfp620NwwQRNEEUrKLC7YwmiFAeYQqtXPW8gs4AKHtgC67ZdNSN1YAKcMHNgFyGAMLBh2d9jRgsEIYJ419sS1K43PKgwAC0Mjt8Bk4IyRoCLRmwasCs4Nzlo4IlRt7sFKv8QQT7k/fCIV/sOf6BCKQLxKYxnUgr/5sS4cVsEGNMAG32IITNAQQBM2MPOD4c4Au7A9KY7/elQh8cBRJBSWvOOGh1IxA5ye2wLyIDk7P5eMoiOCWLAA+pohzoClp72tjv9D1NXQT4x/i9wG6AGZ9aFp+UAgz5ggNStY4YskGD0P7jd7Ws/vOKljonSWp1W7FBAzouN21oQgs0zjuEkK7ELTBzj7IpPe+JD7/aJN94M2sbkMuzA8UaQO7CkOHe61w34zC2DHZHYBRwyAHrSq53tvk/7FA6ACRvkc8E+Y4YULgDwFeS2CGFQduY1v9XLHj34aB899tH+B3vAgcgvTLj/P3Sgdb5yWvZgr73tKQCIF9jiHb3fftPXDo/62//++M9//ufhfUBLQXnLoAAXEG54x3PRRwN9d0n6gVxUkAF/oH8QGIHwQH8SWIH79w7YBgjsgHwd4g9Z5wOUB1jWYAhc4HUkxweYZAS8MAC2UA8PaIEwSIEwOIN/kAEZSAHIp3EcVwMf91cvdnB9B2dsNEM41oIzOIMyeIQWWINC0AOmYCT7hHMAt3MimGZrtmzq9z0pMAA4UA9KGIMI8IU0iAZwIAj7diQKwA5Zt3W4RQp7F2php4BxoAKYgAZiaIFJeIcRGAsBEAgXl1/LMIB3Z2whUALmoAQz1k+7lFVKEgCx/6CHEogAzgAPf1CJlniJmJiJmmiJsRAFT2BJkbeGAhdYhhAGWFAJ5zBQFWUHVqUA4rQE5HRN3mRO/5IMiiAIURALm7iLvGiJzuAM8xCMwjiMxFiMxjiMsSACUEAstwdup6ALr3db0lAEtZAOVtBUTxVVIxBRBqWKAfWNdnAkT+AIuviCfvAHx5iOxviL6tiO7ugBoVAHysd8nOB8LeZX1rAIeHUIiaVYi8VUTpWNNxVVDbUA3wAGv/AM3dAN3JAAfuCO7siOEDmRwsgCfiAs2sB6p9AI99iRf0VYhoVY/UgCJLlYnPAIjzAO7lAC2XALOUCR6SiRMAmRLBAAMzICBv9QBKRQA3fVgx7ZkfpoCKRACkWQBnhwBnOwAHSgDhDQBpigYzO5jsAYle5Yk1owanawlCM2XnhwXuolAOzlXrH1XjVQljUwlLqQlu0FliGQDqlwlIRQAiZABGzQYTJQDuKABD2ADsOAAxnAAlRJjDIZmMfIAlGQAtrgD+CwB1tGYRnWYR9WAUQQYiNmAd4lXpj5XSI2l3QZmRlGYVxWaaIAA0egBXkwDDNgDy9JmMM4mKw5jIB5C6qgH0j2aEcAA1aWZVhmaVzmZb75m01ADlxmabo5mqSZBa3QB+DgawMjApCQAzkAmK8ZjL8IndZ5ndiZndp5nb7wCWvlJv5AaHz/0GuM5miPBmmQdptTNmVHgJ7I2QrgwGjjOZ5CqGvJYAalEAvbuZ/8CZ3V2Z8Amp0Z4AdYAiq5pmu7hmi+tqDMaWj1eaBWEwFt4Av1EKAWmgP/eaEBigZ9+IeYBKGY1C5+kAEaCqAZWqL8iQZRAAhlYHPbxh+4iAYoyp/O4AL1cKM4mqM6uqM8iqO3IAsr5aKY9ARRgAY9eqRIWg8uYKNJ2qQ6mgGo8AlBEKRCGkNloArO6aRaqqRMuqVNmgHtcA01N02UxG8y4wH1kAFemqRLuqZNyqGBcIYfSgGzpEnTVAdwggpq6qY92qZ8eqQZEAC8kF+5VAfLEAG8EAizIAvq//F4HHIsIBAARvqnO+qnlLqjGaCMjmpcLbAFhZAHG7ABA3ANkaAeHIg2RgABUbCnl4qjltqqN0qiHqAuWXUsroAEpzkDOIADMyAGPRAIEcA/VndcqwqrrtqlxgqmnkJr/CELKjAAYjADSWAP7/AO+UAGM4AOhaAFEWBkmhcMpcCqsLqk1Vqu5nqu6Jqu78ChKhAHWYg2yRABs/ACGyCt+fAOsVqt+YADw4AONmAKIQOFxdUCbaCn6nqwB+sCN4CwDJuukBAAWsA/xUUNLcALu8CXOCAEB5sPSdCrA6ACpZp64KMk7XAMDXuy9nADN0CtJ3uykHCYLQo+dWAEt5qr0//asPs6Axvwq/lkNzAaACbbsgyrsiwrtAhrD7JJpVqiDBRgBoIArTNABhortELAq9q6BS1gpxMDMLwQBZBgtAh7A0NgD0ULtudqD6iwC6b1M0myBfTal/dqtu/QscOQB0ggC0agRhNTB7LgnHKbrmKbD2X7t9Uapi1Apf/SAkCAq30pD4MLthzrsSqgCh6qJXUACh7wuH8rtsRAtp77uaAbuqF7DBBbuevRtM+KmtMquqzbuu9gDzm7s1rwKaDibBqACq2bu6GbD0MwBPrQubobvPZADIeZKPwBCrPQA/WascArvMIbuf1qA4DQAFr7qA2gAkDrvK2bD/rQu/IgD8T/ILjay7pJeyTM0LaFgLFCIL7j67xC0LFiUArXIAsUoA0MYmoBoA/tG7pCIA/eC77su7/DewDeqR//EgemwLj2KsDjmw9CMAO9+qtmEDK7BATOycCeqw/+OwTfqw9CgMH2AAntYAOHWwdN+7TRKrUg3L75IA+8ugEvgLW2hKWQgMHE0L8b/L3y8ME2TAWfoApxgLz0qqvg27wr7Lz5AL15IL0t0ABdW8MMjMM5rMNCsL5JfMVYnMVaLASYgASCkL6qq8ViPMZkXMZiXLW9Wgo2oAIvQAVmTMZVrMNT3MFW/MZjjANisAGoSQYAbMd+/MdbDMF5LAZ1DMgOrME6/L90/2zIWezCulrIjBzJb/y+vJqxkiwEiPy9itzBkpzEVVzFnRzKZiwEN/zJkpzJmszBcrzDkCzKrvzKrxzHq7zJiwzLtnzLl4zK37sGN7DKq6wPwBzMwjzMxFzMxnzMyJzMyrzMx+zLSZAPYAAGB5AEvtzBzHzN2JzN2rzNznwAJ/AAfsDH1WzN21zO5nzOzOzLZOAHnXAJayDO44zO8jzP6EwMHlzNYLAVnRDO4/zL9PzPAG3M+VAPUqzD68wATVEND/AGZEDN/SwPAR3RAW3PsLvKSUAGQ6AJwDAQO/EGF93QDx3SIj3SJF3SJg3RwWwPO6zDH50Eb6AJyJAT29AFN0qACgdQ0Ced0zq90yLtwb9bzQeACn7QBdtQDTkhEL2wDScABmLbu0791L2rsk0N1VRd1VZ91Vid1Vot1WKrslZ9A2BwAp2wFR8REAA7',
				qqweibo : 'data:image/gif;base64,R0lGODlheAB4APf/AJrRy4XI0XXF1rHXwpXMzR6oUYHH0o7Kzkm63rXXvm3C12nC2dLmkzu232O/2aDJGJPN0XzF00673a7Z5pLMzqLRyUG54KXV2WbA2dXp6dDl0qvUwlm+3G/C11a5kbPa2Ua43jK24ajSxHzEzgGaPpnNyonI0aPRxxaw5PP5+q/Vw3zF1JrR2lS923jE1Lnf54nX8bTXwdnr7x6y5G7N7er09LbYwBiy5JfOy/7+/4jJzzCxlbjYwZnMw+fvxanUxPn9/ia044TIzV++3HnG0eLy82G4X1y+22vC1p7Pydvy+cvj4+Xy71C0LYTH0nXBtqXRx///+pbOzZzPynLD2oHFzZLKzlu83FLF65XNwKHPycfbcEq54v7//cLfyFq/6LrUE3HE1uv2+JzLOcLi4aTQxpzPx37I0o3MzVS73Pz//qbSyInG0o3J0JXKziW05LHVxIzHy4zL3WLA28Lj68rl6T654P/9/4DG1avVz2y/2ovIpHnF1za34fz++5zOz0e2wY3JynnC1pbLyPT45ZHKyV3B2PP491W83oXG1uT1+c7p7HXC1iq142XC13XCz8/p8my/00G236fQx5POyY3KxKbNi3zC0vn6/dvw72O9xN/u8Z7Qy/r9+1C831C53fD2+XTF0X3D1Ua34XLB0j234ITP35jLzXrB0IvNzuz4+/r894jJy23E1ojJ1E262Y/Ny/v8/XS+0nHE0oHF2FK724rMyuDv6lG92njAdpnLyky8327ByWW/1aPSx4bM0mXA3Eq/1+/29GjB02/C083o5KDSzK7WxPz9/Y7K14zM0WHA3WzG0P/9/P77/Pf7+/f4+2vE1ZHO1Ca352PA51m82pzNyEe83Ea62pzLyITE1Lve3le73GrE0E6826zVwyO04iy14Sy14y+14Di34B2x46fTxrnav7fXvy+245vNyy224y+24Tu954vI30C50Pv8+xu05l283IS+HoDE0oDE1WbE14XD0qfX0Cy554LHyJ/OyaDNzCKz5v///////yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjEzRTIxNUI3N0EyNkU0MTE4OTE3QzRDQTREMDUxNDRBIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkMwNTVCOTkyMjY3QTExRTQ4MDU2QjYyOUQwODZBMjRFIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkMwNTVCOTkxMjY3QTExRTQ4MDU2QjYyOUQwODZBMjRFIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzUgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjEzRTIxNUI3N0EyNkU0MTE4OTE3QzRDQTREMDUxNDRBIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjEzRTIxNUI3N0EyNkU0MTE4OTE3QzRDQTREMDUxNDRBIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Af/+/fz7+vn49/b19PPy8fDv7u3s6+rp6Ofm5eTj4uHg397d3Nva2djX1tXU09LR0M/OzczLysnIx8bFxMPCwcC/vr28u7q5uLe2tbSzsrGwr66trKuqqainpqWko6KhoJ+enZybmpmYl5aVlJOSkZCPjo2Mi4qJiIeGhYSDgoGAf359fHt6eXh3dnV0c3JxcG9ubWxramloZ2ZlZGNi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',
				weibo : 'data:image/gif;base64,R0lGODlheAB4APf/AEZGRvzeosfHx/qSFN8CEuuqOt8IF+vo6PzXivGMldIpM/zcnPvLavCMVfz8/MtLTf3gqfqpJQQEBPvOcPKJF/vDU/L9/fvJY/miCv7tz/i3SPzYjuxrdeIYJvqcHfzYkfmsCepBTf3isPqVGvmaI/3irDg4OPm5LqysrPvFWPOYAfqjIu9wTfvQdY6OjmxsbPzalMQBDu10IOg1QegxPvScCvvIXek9SfqcC/ezaNYBDvzWhvHx8utrGfzSafmrHPmyIebX2fi7U/qxGPvReetSXeaYIvmxKcsbKe1RLv3ktu5zFfvUgPStNvqVDe6iFvmpE9FxdOpJVOgtOvqbFPW5NPvSffW9we2nKCMjI/m9MfSeEObExvqjGuk5Rf3gm+MlM9QqE9mJjPX09fa1u/qPD+hcD/OUAfquJ/qiE+pGUfSgE+16hPm2LM0BEP3lveucG9Q5QOYsOPKyM/WlSvnASvjFye6vOvC0OupNWepcZ/mzKvCZoPWpWfzgpvjAYuCRLvvKVexkDuWjP9uho+WdM/GKIPF7F+UoNeQzQfOUOPDw8OS3uvWjqsEyNb0ADe53CvW1LPH19eQeLPasE/Wts/zch/nMhPOTFPSpDfjCatxEFPSiGPGrLfDv8O/t7u+DQ/CkIPGLA+QzGPKODOY4RPqlLP7038saEMxeYPrBPfOVDPWxIeo9LPL4+e5dSPnSkva7NvObF/nLevbHWvGFC//68vOQHf3ae/GEA+E/SfeyPvvWhfnGcN+aRPq4I91KUvGoH/rU1/3PXfivG+EPHumlqvmvDPmyMf3ls/mmDvvTfvzbl+cpN/R+EfBjI/jFT/rXnfCDG/OaWuvO0OYkMvvTevmlCfnDd8oQEvfIWfPy8/rQge9/IfzXfvi+QMBFSPDz8++AB/u2J/n5+d1QWvzal/zWcfvgsuCvsfmoCvWmJuBGTuUiN+crP/vdrP3js/zVe+Xf3+g4TPzhtvf29/nMz/3fjsU+QfnQi/zShPvUguBXO/aFFP///////yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RjQ2MTY0QjgyNjdBMTFFNEI3ODRDREVCQTA0QzFBMzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RjQ2MTY0QjkyNjdBMTFFNEI3ODRDREVCQTA0QzFBMzIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpGNDYxNjRCNjI2N0ExMUU0Qjc4NENERUJBMDRDMUEzMiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpGNDYxNjRCNzI2N0ExMUU0Qjc4NENERUJBMDRDMUEzMiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8e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'
			};
			$('body').wrapInner('<div id="quickbar-wrap-body"></div>').wrapInner('<div id="quickbar-wrap" class="page-active">\n\
				<div class="quickbar-opacity2"></div>\n\
				<div class="quickbar-sharebox">\n\
					<div class="bn-share-con">\n\
						<div class="thumbs-cotnainer">\n\
							<div class="share-icon"> <a title="' + dataLang.share.shareQzone.shareTitle[fixedLang] + '" href="javascript:void(0);" class="qqzone"><img src="'+share_pic.Qzone+'" width="60" height="60"><dt class=" title">' + dataLang.share.shareQzone.shareCon[fixedLang] + '</dt></a></div> \n\
							<div class="share-icon"><a title="' + dataLang.share.shareTen.shareTitle[fixedLang] + '" href="javascript:void(0);"  class="qqweibo"><img src="'+share_pic.qqweibo+'" width="60" height="60"><dt class=" title">' + dataLang.share.shareTen.shareCon[fixedLang] + '</dt></a></div> \n\
							<div class="share-icon"> <a title="' + dataLang.share.shareBaidu.shareTitle[fixedLang] + '" href="javascript:void(0);" class="baidusoucang"><img src="'+share_pic.baidu+'" width="60" height="60"><dt class=" title">' + dataLang.share.shareBaidu.shareCon[fixedLang] + '</dt></a></div>\n\
							<div class="share-icon"><a title="' + dataLang.share.shareSina.shareTitle[fixedLang] + '" href="javascript:void(0)" class="xinlang"><img src="'+share_pic.weibo+'" width="60" height="60"><dt class=" title">' + dataLang.share.shareSina.shareCon[fixedLang] + '</dt></a></div>  \n\
						</div>\n\
					</div>\n\
					<div class="share-cance">' + dataLang.share.shareClose[fixedLang] + '</div>     \n\
				</div>\n\
				<div class="quickbar-tips"><span class="tips-close">×</span><div class="tips-content"></div></div>\n\
				<div id="quickbar" class="fixed public-bg1" style="background:'+dataQuickbar.config.style.mainColor+'">\n\
					<ul>\n\
					<li><a href="#" id="quickbar-navs-btn"><p class="fix_icon"><i class="iconfont">&#xe603;</i></p><p class="title">' + dataLang.quickbar.quickbarColumen.nav[fixedLang] + '</p></a></li>\n\
					'+li_btn+'\n\
					</ul>\n\
				</div>\n\
				'+search_form+follow_img+'\n\
			').wrapInner('<div class="body public-bg2">\n\
				<div id="quickbar-navs" class="page-prev public-bg1" style="background:'+dataQuickbar.config.style.mainColor+'">\n\
					<h1 class="quickbar-navs-top public-color1" style="'+(dataQuickbar.config.style.secondColor ? 'background:'+dataQuickbar.config.style.secondColor : '')+';color:'+(dataQuickbar.config.style.textColor?dataQuickbar.config.style.textColor:"#fff")+'"><span class="quickbar-navs-close" style="background:'+(dataQuickbar.config.style.iconColor!=dataQuickbar.config.style.textColor ? dataQuickbar.config.style.iconColor : dataQuickbar.config.style.secondColor)+'">×</span>' + dataLang.quickbar.quickbarTitle[fixedLang] + '</h1>\n\
					<div class="quickbar-navs-m">\n\
						<ul class="quickbar-navs-list">\n\
							<!--<li><a href="index.html">' + dataLang.quickbar.quickbarColumen.index[fixedLang] + '</a></li>-->\n\
							'+li_nav+'\n\
						</ul>\n\
					</div>\n\
				</div>\n\
			');
			// 模块开启
			if (dataQuickbar.config.module) {
				if (typeof dataQuickbar.config.module === 'string') {
					$('#quickbar-wrap > .quickbar-tips .tips-content').append($(dataQuickbar.config.module));
				}else if (typeof dataQuickbar.config.module === 'object') {
					if (dataQuickbar.config.module.tel) {
						$('#quickbar-wrap > .quickbar-tips .tips-content').append('<i class="iconfont">&#xe609;</i>' + dataLang.quickbar.quickbarColumen.advisory[fixedLang] + '：' + dataQuickbar.config.module.tel);
					}
				}
				if ($('#quickbar-wrap > .quickbar-tips .tips-content').html()) {
					$('#quickbar-wrap > .quickbar-tips').show();
					$("#quickbar-wrap > .quickbar-tips .tips-close").click(function(){
						$('#quickbar-wrap > .quickbar-tips').hide();
						$(window).resize();
						return false;
					})
				}
			}
			// 主体高度计算
			$(window).resize(function() {
				var qbTipsHeight = $(".quickbar-tips").is(':hidden') ? 0 : $(".quickbar-tips").height();
				$("#quickbar-wrap-body").height($(window).height()-$("#quickbar").height()-qbTipsHeight);
			}).resize();
			// 隐藏导航跟wrap的切换
			$("#quickbar #quickbar-navs-btn").on('click',function(){
				$("#quickbar-navs").removeClass("page-prev").addClass("page-in");
				$("#quickbar-wrap").removeClass("page-active").addClass("page-next page-in")
				$(".quickbar-opacity2").show()
				pageSlideOver();
			});
			$("#quickbar-navs .quickbar-navs-close,.quickbar-opacity2").on('click',function(){ 
				$("#quickbar-navs").removeClass("page-in").addClass("page-prev page-out")
				$("#quickbar-wrap").removeClass("page-next page-in").addClass("page-out")
				$(".quickbar-opacity2").hide()
				$(".quickbar-sharebox").removeClass("show_share")
				pageSlideOver();
				if ($('.quickbar_search').hasClass('search_show')) {
					$('.quickbar_search').removeClass('search_show');
				}
				$('.follow_img').css('display', 'none');
			});
			function pageSlideOver(){
				$('.page-out').on('transitionend', function(){
						$(this).removeClass('page-out');
				});
				$('.page-in').on('transitionend', function(){
						$(this).removeClass('page-in');
				});
			}
			// 幻灯片元素与类"menu_body"段与类"menu_head"时点击
			$("#quickbar-navs .menu_head .qbnav-icon").click(function(){
				$(this).parent().toggleClass("cur").next(".menu_body").toggleClass("cu").siblings(".menu_body").removeClass("cu");
				$(this).parent().siblings().removeClass("cur");
			});
			// 分享按钮
			var _shareIcon = $('.quickbar-sharebox .thumbs-cotnainer .share-icon');
			$('.quickbar-sharebox .thumbs-cotnainer').width(_shareIcon.outerWidth() * _shareIcon.length);
			$("#quickbar #share_btn").on('click',function(){
				$(".quickbar-sharebox").addClass("show_share");
				$(".quickbar-opacity2").show();
				return false;
			});
			$(".quickbar-sharebox .share-cance").on('click',function(){
				$(".quickbar-sharebox").removeClass("show_share");
				$(".quickbar-opacity2").hide();
			});
			// 分享操作
			$(function() {
				$(".renren").click(function() {
					share.renren()
				});
				$(".xinlang").click(function() {
					share.sinaminiblog()
				});
				$(".douban").click(function() {
					share.douban()
				});
				$(".kaixin").click(function() {
					share.kaixin()
				});
				$(".taojianghu").click(function() {
					share.taojianghu()
				});
				$(".wangyi").click(function() {
					share.wangyi()
				});
				$(".qqzone").click(function() {
					share.qqzone()
				});
				$(".baidusoucang").click(function() {
					share.baidusoucang()
				});
				$(".qqweibo").click(function() {
					share.qqweibo()
				});
				$(".qqpengyou").click(function() {
					share.pengyou()
				})
			});

			//搜索按钮事件
			$('#search_btn').click(function(event) {
				$('.quickbar_search').addClass('search_show');
				$(".quickbar-opacity2").show()
			});

			// 微信关注按钮事件
			$('#follow_btn').click(function(event) {
				$('.follow_img').css('display', 'block');
				$(".quickbar-opacity2").show()
			});

			// 覆盖初始化事件
			window.Quickbar_backtoTop = function() {
				$("#quickbar-wrap-body").animate({scrollTop :0}, 800);
			};
			window.Quickbar_showCategories = function() {
				$("#quickbar-navs").removeClass("page-prev").addClass("page-in");
				$("#quickbar-wrap").removeClass("page-active").addClass("page-next page-in")
				$(".quickbar-opacity2").show()
				pageSlideOver();
			};
			window.Quickbar_share = function() {
				$(".quickbar-sharebox").addClass("show_share");
				$(".quickbar-opacity2").show();
			};
		});
	})(jQuery);
}
