//小程序首页所使用的快捷导航文件
// 配置项
window.CustomerID = 0;
window.CustomerTYPE = 'pc';
window.configQuickbar = {
    dataurl: './quickbar/json/quickbar.json',
    langurl: './quickbar/json/quickbar-lang.json',
    viewcounturl: 'http://swap.5067.org/Viewcount/0000/pc.html',
    fonts: {
        eot: './quickbar/fonts/iconfont.eot',
        woff: './quickbar/fonts/iconfont.woff',
        ttf: './quickbar/fonts/iconfont.ttf',
        svg: './quickbar/fonts/iconfont.svg'
    }
};
// 初始化事件
// 返回顶部
window.Quickbar_backtoTop = function () {
    document.body.scrollTop = 0;
};
document.getElementById("quickbar-backtotop") !== null ? document.getElementById("quickbar-backtotop").addEventListener('click', function () {
    window.Quickbar_backtoTop();
}, false) : null;
// 展示栏目导航
window.Quickbar_showCategories = function () {
};
document.getElementById("quickbar-showcats") !== null ? document.getElementById("quickbar-showcats").addEventListener('click', function () {
    window.Quickbar_showCategories();
}, false) : null;
// 分享
window.Quickbar_share = function () {
};
document.getElementById("quickbar-share") !== null ? document.getElementById("quickbar-share").addEventListener('click', function () {
    window.Quickbar_share();
}, false) : null;

// 判断调试
(function () {
    var scripts = document.getElementsByTagName("script");
    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src.indexOf('quickbar-windex.js') >= 0) {
            target = scripts[i];
        }
    }
    if (target.src.indexOf('?') == -1 || target.src.substring(target.src.indexOf('?') + 1).indexOf('debug') == -1) {
        configQuickbar.dataurl = './quickbar.json';
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
var jssData;
window.quickbarCallback = function (dataQuickbar) {
    if (jsData.parentNode)
        jsData.parentNode.removeChild(jsData);
    else
        jssData.parentNode.removeChild(jssData);
    // 获取配置信息
    window.winLoca = window.location.href;
    winLoca = winLoca.replace(/(^http\:\/\/*)/g, "");
    winLoca = winLoca.split('/');
    if (winLoca[1] == 'mobile') {
        if (dataQuickbar.config.type != 'm1' && dataQuickbar.config.type != 'custom') {
            configQuickbar.dataurl = '/mobile/quickbar.json';
            jssData = document.createElement('script');
            jssData.setAttribute('type', 'text/javascript');
            jssData.setAttribute('src', configQuickbar.dataurl + '?callback=quickbarCallback');
            jssData.onload = jsData.onreadystatechange = function () {
                jssData.parentNode.removeChild(jssData);
            };
            (document.head || document.getElementsByTagName('head')[0]).appendChild(jssData);
            return false;
        }
    }
    var color = null;
    var colormain = null;
    var colorsec = null;
    var colortext = null;
    var coloricon = null;
    var colorurl = window.location.href;
    if(colorurl.indexOf('?')>0) {
        var parm = colorurl.substr(colorurl.indexOf('?')+1);
        var arr = parm.split('&');//参数数组
        for (var i=0;i<arr.length; i++) {
            var n = arr[i].indexOf('=');
            if(n > 0) {
                var name = arr[i].substring(0, n);
                var value = arr[i].substr(n+1);
                if(name == 'color')
                {
                    color = value.replace('#', '');
                    if(typeof(dataQuickbar.config.mdemo.demo) != 'undefine') {
                        if(dataQuickbar.config.mdemo.demo == 1) {
                            var num = parseInt(color, 16);
                            var demo_style = "dataQuickbar.config.mdemo.style[" + num + "]";                           
                            colormain = eval(demo_style).mainColor;
                            colorsec = eval(demo_style).secondColor;
                            colortext = eval(demo_style).textColor;
                            coloricon = eval(demo_style).iconColor;
                        }
                    }
                }
            }
        }
    }
    if (previewJSQuickbar.enable === false || !dataQuickbar.config.enable)
        return false;
    dataQuickbar.config.style.mainColor = previewJSQuickbar.style.mainColor || dataQuickbar.config.style.mainColor || '#AAA';
    dataQuickbar.config.style.secondColor = previewJSQuickbar.style.secondColor || dataQuickbar.config.style.secondColor || '#CCC';
    dataQuickbar.config.style.textColor = previewJSQuickbar.style.textColor || dataQuickbar.config.style.textColor || '#FFF';
    dataQuickbar.config.style.iconColor = previewJSQuickbar.style.iconColor || dataQuickbar.config.style.iconColor || '';
    dataQuickbar.config.module = previewJSQuickbar.module || dataQuickbar.config.module || {};
    dataQuickbar.config.type = previewJSQuickbar.type || dataQuickbar.config.type;
    dataQuickbar.config.color = color || '';
    dataQuickbar.config.colormain = colormain || '';
    dataQuickbar.config.colorsec = colorsec || '';
    dataQuickbar.config.colortext = colortext || '';
    dataQuickbar.config.coloricon = coloricon || '';
    window.dataQuickbar = dataQuickbar;
    // 根据不同type载入不同quickbar
    var quickbarMain = document.createElement('script');
    quickbarMain.setAttribute('type', 'text/javascript');
    quickbarMain.setAttribute('src', './quickbar/js/quickbar-' + dataQuickbar.config.type + '.js');
    (document.head || document.getElementsByTagName('head')[0]).appendChild(quickbarMain);
};
var jsData = document.createElement('script'),
        jsDataLoad = true;
jsData.setAttribute('type', 'text/javascript');
jsData.setAttribute('src', configQuickbar.dataurl + '?callback=quickbarCallback');
(document.head || document.getElementsByTagName('head')[0]).appendChild(jsData);
// 加载quickbar语言包
window.langCallback = function (dataLang) {
    window.dataLang = dataLang;
    lanData.parentNode.removeChild(lanData);
};
var lanData = document.createElement('script'),
        lanDataLoad = true;
lanData.setAttribute('type', 'text/javascript');
lanData.setAttribute('src', configQuickbar.langurl + '?callback=langCallback');
(document.head || document.getElementsByTagName('head')[0]).appendChild(lanData);

// 加载字体
//var iconfontStyle = document.createElement('style');
//iconfontStyle.setAttribute('type', 'text/css');
//iconfontStyle.setAttribute('rel', 'stylesheet');
//iconfontStyle.setAttribute('rev', 'stylesheet');
//iconfontStyle.innerHTML = '@font-face {font-family: "iconfont"; src: url("'+configQuickbar.fonts.eot+'");/* IE9*/ src: url("'+configQuickbar.fonts.eot+'?#iefix") format("embedded-opentype"), /* IE6-IE8 */ url("'+configQuickbar.fonts.woff+'") format("woff"),/* chrome、firefox */ url("'+configQuickbar.fonts.ttf+'") format("truetype"),/* chrome、firefox、opera、Safari, Android, iOS 4.2+*/ url("'+configQuickbar.fonts.svg+'#iconfont") format("svg");/* iOS 4.1- */}'
//	+'.iconfont{font-family:"iconfont" !important;font-size:16px;font-style:normal;-webkit-font-smoothing: antialiased;-webkit-text-stroke-width: 0.2px;-moz-osx-font-smoothing: grayscale;}';
//(document.head || document.getElementsByTagName('head')[0]).appendChild(iconfontStyle);
var iconfontStyle = document.createElement('div');
iconfontStyle.innerHTML = '_<style type="text/css" rel="stylesheet" rev="stylesheet">@font-face {font-family: "iconfont"; src: url("' + configQuickbar.fonts.eot + '");/* IE9*/ src: url("' + configQuickbar.fonts.eot + '?#iefix") format("embedded-opentype"), /* IE6-IE8 */ url("' + configQuickbar.fonts.woff + '") format("woff"),/* chrome、firefox */ url("' + configQuickbar.fonts.ttf + '") format("truetype"),/* chrome、firefox、opera、Safari, Android, iOS 4.2+*/ url("' + configQuickbar.fonts.svg + '#iconfont") format("svg");/* iOS 4.1- */}' + '.iconfont{font-family:"iconfont" !important;font-size:16px;font-style:normal;-webkit-font-smoothing: antialiased;-webkit-text-stroke-width: 0.2px;-moz-osx-font-smoothing: grayscale;}</style>';
iconfontStyle.removeChild(iconfontStyle.firstChild);
(document.head || document.getElementsByTagName('head')[0]).appendChild(iconfontStyle.firstChild);

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