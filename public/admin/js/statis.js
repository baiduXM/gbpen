// 配置项
window.CustomerID = 0;
window.CustomerTYPE = 'pc';

// 判断调试
(function () {
    var scripts = document.getElementsByTagName("script");
    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src.indexOf('quickbar.js') >= 0) {
            target = scripts[i];
        }
    }
    if (target.src.indexOf('?') == -1 || target.src.substring(target.src.indexOf('?') + 1).indexOf('debug') == -1) {
        configQuickbar.dataurl = '/quickbar.json';
        if (target.src.indexOf('?') !== -1) {
            var param = target.src.substring(target.src.indexOf('?') + 1);
            window.CustomerID = param.match(/(\d*)(.*)/)[1];
            window.CustomerTYPE = param.match(/(\d*)(.*)/)[2];
        }
    } else {
        if (CustomerTYPE == 'pc') {
            configQuickbar.dataurl = configQuickbar.dataurl.replace('quickbar.json', 'quickbar-pc.json');
        }
    }
    configQuickbar.viewcounturl = configQuickbar.viewcounturl.replace('/pc.', '/' + CustomerTYPE + '.');
})();
// 加载数据
previewJSQuickbar = window.previewJSQuickbar || {};
previewJSQuickbar.style = previewJSQuickbar.style || {};
window.quickbarCallback = function (dataQuickbar) {
    // 获取配置信息
    if (previewJSQuickbar.enable === false || !dataQuickbar.config.enable)
        return false;
    dataQuickbar.config.style.mainColor = previewJSQuickbar.style.mainColor || dataQuickbar.config.style.mainColor || '#AAA';
    dataQuickbar.config.style.secondColor = previewJSQuickbar.style.secondColor || dataQuickbar.config.style.secondColor || '#CCC';
    dataQuickbar.config.style.textColor = previewJSQuickbar.style.textColor || dataQuickbar.config.style.textColor || '#FFF';
    dataQuickbar.config.style.iconColor = previewJSQuickbar.style.iconColor || dataQuickbar.config.style.iconColor || '';
    dataQuickbar.config.module = previewJSQuickbar.module || dataQuickbar.config.module || {};
    dataQuickbar.config.type = previewJSQuickbar.type || dataQuickbar.config.type;
    window.dataQuickbar = dataQuickbar;
    // 根据不同type载入不同quickbar
    var quickbarMain = document.createElement('script');
    quickbarMain.setAttribute('type', 'text/javascript');
    quickbarMain.setAttribute('src', 'http://chanpin.xm12t.com.cn/js/quickbar-' + dataQuickbar.config.type + '.js');
    (document.head || document.getElementsByTagName('head')[0]).appendChild(quickbarMain);
};
var jsData = document.createElement('script');
jsData.setAttribute('type', 'text/javascript');
jsData.setAttribute('src', configQuickbar.dataurl + '?callback=quickbarCallback');
jsData.onload = jsData.onreadystatechange = function () {
    jsData.parentNode.removeChild(jsData);
};
(document.head || document.getElementsByTagName('head')[0]).appendChild(jsData);

// 加载字体
var iconfontStyle = document.createElement('style');
iconfontStyle.setAttribute('type', 'text/css');
iconfontStyle.setAttribute('rel', 'stylesheet');
iconfontStyle.setAttribute('rev', 'stylesheet');
iconfontStyle.innerHTML = '@font-face {font-family: "iconfont"; src: url("' + configQuickbar.fonts.eot + '");/* IE9*/ src: url("' + configQuickbar.fonts.eot + '?#iefix") format("embedded-opentype"), /* IE6-IE8 */ url("' + configQuickbar.fonts.woff + '") format("woff"),/* chrome、firefox */ url("' + configQuickbar.fonts.ttf + '") format("truetype"),/* chrome、firefox、opera、Safari, Android, iOS 4.2+*/ url("' + configQuickbar.fonts.svg + '#iconfont") format("svg");/* iOS 4.1- */}'
        + '.iconfont{font-family:"iconfont" !important;font-size:16px;font-style:normal;-webkit-font-smoothing: antialiased;-webkit-text-stroke-width: 0.2px;-moz-osx-font-smoothing: grayscale;}';
(document.head || document.getElementsByTagName('head')[0]).appendChild(iconfontStyle);

// 加载访问次数
if (document.getElementById("article-viewcount") !== null) {
    window.viewcountCallback = function (json) {
        if (json.err == 0) {
            document.getElementById("article-viewcount").innerHTML = json.data.viewcount;
        } else {
            console.log(json.msg);
        }
    };
    var viewcountData = document.createElement('script');
    viewcountData.setAttribute('type', 'text/javascript');
    viewcountData.setAttribute('src', configQuickbar.viewcounturl.replace('0000', CustomerID) + '?callback=viewcountCallback&url=' + location.href);
    viewcountData.onload = viewcountData.onreadystatechange = function () {
        viewcountData.parentNode.removeChild(viewcountData);
    };
    (document.head || document.getElementsByTagName('head')[0]).appendChild(viewcountData);
}

//配置项
window.CustomerID = 0;
window.CustomerTYPE = 'pc';
window.configQuickbar = {
	dataurl : 'http://chanpin.xm12t.com.cn/json/quickbar.json',
	viewcounturl : 'http://swap.5067.org/Viewcount/0000/pc.html',
	fonts : {
		eot : 'http://chanpin.xm12t.com.cn/fonts/iconfont.eot',
		woff : 'http://chanpin.xm12t.com.cn/fonts/iconfont.woff',
		ttf : 'http://chanpin.xm12t.com.cn/fonts/iconfont.ttf',
		svg : 'http://chanpin.xm12t.com.cn/fonts/iconfont.svg'
	}
};

// 初始化事件
// 返回顶部
window.Quickbar_backtoTop = function() {
	document.body.scrollTop = 0;
};
document.getElementById("quickbar-backtotop") !== null ? document.getElementById("quickbar-backtotop").addEventListener('click', function() {
	window.Quickbar_backtoTop();
}, false) : null;
// 展示栏目导航
window.Quickbar_showCategories = function() {
};
document.getElementById("quickbar-showcats") !== null ? document.getElementById("quickbar-showcats").addEventListener('click', function() {
	window.Quickbar_showCategories();
}, false) : null;
// 分享
window.Quickbar_share = function() {
};
document.getElementById("quickbar-share") !== null ? document.getElementById("quickbar-share").addEventListener('click', function() {
	window.Quickbar_share();
}, false) : null;

// 判断调试
(function(){
	var scripts = document.getElementsByTagName("script");
	for (var i = 0; i < scripts.length; i++) {
		if (scripts[i].src.indexOf('quickbar.js') >= 0) {
			target = scripts[i];
		}
	}
	if (target.src.indexOf('?') == -1 || target.src.substring(target.src.indexOf('?') + 1).indexOf('debug') == -1) {
		configQuickbar.dataurl = '/quickbar.json';
		if (target.src.indexOf('?') !== -1) {
			var param = target.src.substring(target.src.indexOf('?') + 1);
			window.CustomerID = param.match(/(\d*)(.*)/)[1];
			window.CustomerTYPE = param.match(/(\d*)(.*)/)[2];
		}
	}else{
		if (CustomerTYPE == 'pc') {
			configQuickbar.dataurl = configQuickbar.dataurl.replace('quickbar.json','quickbar-pc.json');
		}
	}
	configQuickbar.viewcounturl = configQuickbar.viewcounturl.replace('/pc.','/' + CustomerTYPE + '.');
})();
// 加载数据
previewJSQuickbar = window.previewJSQuickbar || {};
previewJSQuickbar.style = previewJSQuickbar.style || {};
window.quickbarCallback = function(dataQuickbar) {
	// 获取配置信息
	if (previewJSQuickbar.enable === false || !dataQuickbar.config.enable) return false;
	dataQuickbar.config.style.mainColor    =  previewJSQuickbar.style.mainColor || dataQuickbar.config.style.mainColor || '#AAA';
	dataQuickbar.config.style.secondColor    =  previewJSQuickbar.style.secondColor || dataQuickbar.config.style.secondColor || '#CCC';
	dataQuickbar.config.style.textColor    =  previewJSQuickbar.style.textColor || dataQuickbar.config.style.textColor || '#FFF';
	dataQuickbar.config.style.iconColor    =  previewJSQuickbar.style.iconColor || dataQuickbar.config.style.iconColor || '';
	dataQuickbar.config.module    =  previewJSQuickbar.module || dataQuickbar.config.module || {};
	dataQuickbar.config.type    =  previewJSQuickbar.type || dataQuickbar.config.type;
	window.dataQuickbar = dataQuickbar;
	// 根据不同type载入不同quickbar
	var quickbarMain = document.createElement('script');
	quickbarMain.setAttribute('type', 'text/javascript');
	quickbarMain.setAttribute('src', 'http://chanpin.xm12t.com.cn/js/quickbar-' + dataQuickbar.config.type + '.js');
	(document.head || document.getElementsByTagName('head')[0]).appendChild(quickbarMain);
};
var jsData = document.createElement('script');
jsData.setAttribute('type', 'text/javascript');
jsData.setAttribute('src', configQuickbar.dataurl + '?callback=quickbarCallback');
jsData.onload = jsData.onreadystatechange = function() {
	jsData.parentNode.removeChild(jsData);
};
(document.head || document.getElementsByTagName('head')[0]).appendChild(jsData);

// 加载字体
var iconfontStyle = document.createElement('style');
iconfontStyle.setAttribute('type', 'text/css');
iconfontStyle.setAttribute('rel', 'stylesheet');
iconfontStyle.setAttribute('rev', 'stylesheet');
iconfontStyle.innerHTML = '@font-face {font-family: "iconfont"; src: url("'+configQuickbar.fonts.eot+'");/* IE9*/ src: url("'+configQuickbar.fonts.eot+'?#iefix") format("embedded-opentype"), /* IE6-IE8 */ url("'+configQuickbar.fonts.woff+'") format("woff"),/* chrome、firefox */ url("'+configQuickbar.fonts.ttf+'") format("truetype"),/* chrome、firefox、opera、Safari, Android, iOS 4.2+*/ url("'+configQuickbar.fonts.svg+'#iconfont") format("svg");/* iOS 4.1- */}'
	+'.iconfont{font-family:"iconfont" !important;font-size:16px;font-style:normal;-webkit-font-smoothing: antialiased;-webkit-text-stroke-width: 0.2px;-moz-osx-font-smoothing: grayscale;}';
(document.head || document.getElementsByTagName('head')[0]).appendChild(iconfontStyle);

// 加载访问次数
if (document.getElementById("article-viewcount") !== null) {
	window.viewcountCallback = function(json) {
		if (json.err == 0) {
			document.getElementById("article-viewcount").innerHTML = json.data.viewcount;
		}else{
			console.log(json.msg);
		}
	};
	var viewcountData = document.createElement('script');
	viewcountData.setAttribute('type', 'text/javascript');
	viewcountData.setAttribute('src', configQuickbar.viewcounturl.replace('0000',CustomerID) + '?callback=viewcountCallback&url=' + location.href);
	viewcountData.onload = viewcountData.onreadystatechange = function() {
		viewcountData.parentNode.removeChild(viewcountData);
	};
	(document.head || document.getElementsByTagName('head')[0]).appendChild(viewcountData);
}
