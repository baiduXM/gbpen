
/* jQuery.ScrollTo */
;(function($){var o=$.scrollTo=function(a,b,c){o.window().scrollTo(a,b,c)};o.defaults={axis:'y',duration:1};o.window=function(){return $($.browser.safari?'body':'html')};$.fn.scrollTo=function(l,m,n){if(typeof m=='object'){n=m;m=0}n=$.extend({},o.defaults,n);m=m||n.speed||n.duration;n.queue=n.queue&&n.axis.length>1;if(n.queue)m/=2;n.offset=j(n.offset);n.over=j(n.over);return this.each(function(){var a=this,b=$(a),t=l,c,d={},w=b.is('html,body');switch(typeof t){case'number':case'string':if(/^([+-]=)?\d+(px)?$/.test(t)){t=j(t);break}t=$(t,this);case'object':if(t.is||t.style)c=(t=$(t)).offset()}$.each(n.axis.split(''),function(i,f){var P=f=='x'?'Left':'Top',p=P.toLowerCase(),k='scroll'+P,e=a[k],D=f=='x'?'Width':'Height';if(c){d[k]=c[p]+(w?0:e-b.offset()[p]);if(n.margin){d[k]-=parseInt(t.css('margin'+P))||0;d[k]-=parseInt(t.css('border'+P+'Width'))||0}d[k]+=n.offset[p]||0;if(n.over[p])d[k]+=t[D.toLowerCase()]()*n.over[p]}else d[k]=t[p];if(/^\d+$/.test(d[k]))d[k]=d[k]<=0?0:Math.min(d[k],h(D));if(!i&&n.queue){if(e!=d[k])g(n.onAfterFirst);delete d[k]}});g(n.onAfter);function g(a){b.animate(d,m,n.easing,a&&function(){a.call(this,l)})};function h(D){var b=w?$.browser.opera?document.body:document.documentElement:a;return b['scroll'+D]-b['client'+D]}})};function j(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/* jQuery.LocalScroll */
;(function($){var g=location.href.replace(/#.*/,''),h=$.localScroll=function(a){$('body').localScroll(a)};h.defaults={duration:1e3,axis:'y',event:'click',stop:1};h.hash=function(a){a=$.extend({},h.defaults,a);a.hash=0;if(location.hash)setTimeout(function(){i(0,location,a)},0)};$.fn.localScroll=function(b){b=$.extend({},h.defaults,b);return(b.persistent||b.lazy)?this.bind(b.event,function(e){var a=$([e.target,e.target.parentNode]).filter(c)[0];a&&i(e,a,b)}):this.find('a,area').filter(c).bind(b.event,function(e){i(e,this,b)}).end().end();function c(){var a=this;return!!a.href&&!!a.hash&&a.href.replace(a.hash,'')==g&&(!b.filter||$(a).is(b.filter))}};function i(e,a,b){var c=a.hash.slice(1),d=document.getElementById(c)||document.getElementsByName(c)[0],f;if(d){e&&e.preventDefault();f=$(b.target||$.scrollTo.window());if(b.lock&&f.is(':animated')||b.onBefore&&b.onBefore.call(a,e,d,f)===!1)return;if(b.stop)f.queue('fx',[]).stop();f.scrollTo(d,b).trigger('notify.serialScroll',[d]);if(b.hash)f.queue(function(){location=a.hash;$(this).dequeue()})}}})(jQuery);

/* jQuery[a] */
;(function($){var a='serialScroll',b='.'+a,c='bind',C=$[a]=function(b){$.scrollTo.window()[a](b)};C.defaults={duration:1e3,axis:'x',event:'click',start:0,step:1,lock:1,cycle:1,constant:1};$.fn[a]=function(y){y=$.extend({},C.defaults,y);var z=y.event,A=y.step,B=y.lazy;return this.each(function(){var j=y.target?this:document,k=$(y.target||this,j),l=k[0],m=y.items,o=y.start,p=y.interval,q=y.navigation,r;if(!B)m=w();if(y.force)t({},o);$(y.prev||[],j)[c](z,-A,s);$(y.next||[],j)[c](z,A,s);if(!l.ssbound)k[c]('prev'+b,-A,s)[c]('next'+b,A,s)[c]('goto'+b,t);if(p)k[c]('start'+b,function(e){if(!p){v();p=1;u()}})[c]('stop'+b,function(){v();p=0});k[c]('notify'+b,function(e,a){var i=x(a);if(i>-1)o=i});l.ssbound=1;if(y.jump)(B?k:w())[c](z,function(e){t(e,x(e.target))});if(q)q=$(q,j)[c](z,function(e){e.data=Math.round(w().length/q.length)*q.index(this);t(e,this)});function s(e){e.data+=o;t(e,this)};function t(e,a){if(!isNaN(a)){e.data=a;a=l}var c=e.data,n,d=e.type,f=y.exclude?w().slice(0,-y.exclude):w(),g=f.length,h=f[c],i=y.duration;if(d)e.preventDefault();if(p){v();r=setTimeout(u,y.interval)}if(!h){n=c<0?0:n=g-1;if(o!=n)c=n;else if(!y.cycle)return;else c=g-n-1;h=f[c]}if(!h||d&&o==c||y.lock&&k.is(':animated')||d&&y.onBefore&&y.onBefore.call(a,e,h,k,w(),c)===!1)return;if(y.stop)k.queue('fx',[]).stop();if(y.constant)i=Math.abs(i/A*(o-c));k.scrollTo(h,i,y).trigger('notify'+b,[c])};function u(){k.trigger('next'+b)};function v(){clearTimeout(r)};function w(){return $(m,l)};function x(a){if(!isNaN(a))return a;var b=w(),i;while((i=b.index(a))==-1&&a!=l)a=a.parentNode;return i}})}})(jQuery);

/* Better Coda Slider : modified */
$(document).ready(function(){var $panels=$('#slider .scrollContainer > div');var $container=$('#slider .scrollContainer');var horizontal=true;if(horizontal){$panels.css({'float':'left','position':'relative'});$container.css('width',$panels[0].offsetWidth*$panels.length)}var $scroll=$('#slider .scroll').css('overflow','hidden');function selectNav(){$(this).parents('ul:first').find('a').removeClass('selected').end().end().addClass('selected')}$('#slider .navigation').find('a').click(selectNav);function trigger(data){var el=$('#slider .navigation').find('a[href$="'+data.id+'"]').get(0);selectNav.call(el)}if(window.location.hash){trigger({id:window.location.hash.substr(1)})}else{$('ul.navigation a:first').click()}var offset=parseInt((horizontal?$container.css('paddingTop'):$container.css('paddingLeft'))||0)*-1;var scrollOptions={target:$scroll,items:$panels,navigation:'.navigation a',prev:'.scrollMeLeft',next:'.scrollMeRight',axis:'xy',onAfter:trigger,offset:offset,duration:500,easing:'swing'};$('#slider').serialScroll(scrollOptions);$.localScroll(scrollOptions);scrollOptions.duration=1;$.localScroll.hash(scrollOptions)});

/* Coda Popup Bubble : modified */
$(function(){$('#toolbar').each(function(){var distance=10;var time=250;var hideDelay=500;var hideDelayTimer=null;var beingShown=false;var shown=false;var trigger=$('#advisory',this);var info=$('.popup',this).css('opacity',0);$([trigger.get(0),info.get(0)]).mouseover(function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);if(beingShown||shown){return}else{beingShown=true;info.css({top:-90,left:-33,display:'block'}).animate({top:'-='+distance+'px',opacity:1},time,'swing',function(){beingShown=false;shown=true})}return false}).mouseout(function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);hideDelayTimer=setTimeout(function(){hideDelayTimer=null;info.animate({top:'-='+distance+'px',opacity:0},time,'swing',function(){shown=false;info.css('display','none')})},hideDelay);return false})})});

$(function(){$('#toolbar').each(function(){var distance=10;var time=250;var hideDelay=1500;var hideDelayTimer=null;var beingShown=false;var shown=false;var trigger=$('#reservations',this);var info=$('.popup2',this).css('opacity',0);$([trigger.get(0),info.get(0)]).mouseover(function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);if(beingShown||shown){return}else{beingShown=true;info.css({top:-90,left:-33,display:'block'}).animate({top:'-='+distance+'px',opacity:1},time,'swing',function(){beingShown=false;shown=true})}return false}).mouseout(function(){if(hideDelayTimer)clearTimeout(hideDelayTimer);hideDelayTimer=setTimeout(function(){hideDelayTimer=null;info.animate({top:'-='+distance+'px',opacity:0},time,'swing',function(){shown=false;info.css('display','none')})},hideDelay);return false})})});

/* FancyBox */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(j($){k g={},N=1i 1w,2u=[\'3u\',\'3p\',\'3g\',\'2Y\'],1d,1b=1;$.5.6=j(b){g.1F=$.1e({},$.5.6.2e,b);$.5.6.2a();s 1g.2Q(j(){k a=$(1g);k o=$.1c?$.1e({},g.1F,a.1c()):g.1F;a.M(\'z\').z(j(){$.5.6.1P(1g,o);s y})})};$.5.6.1P=j(a,o){h(g.V){s y}h(o.2s){$("#1r").1G(\'<7 l="17"></7>\');$("#17").Q({\'m\':$(19).m(),\'p\':$(q).p(),\'1h\':o.24});h($.23.29){$("#1r").1G(\'<W l="1m" 21="1Y" 1N="0"></W>\');$("#1m").Q({\'m\':$(19).m(),\'p\':$(q).p(),\'1h\':0})}$("#17").z($.5.6.O)}g.9=[];g.8=0;h(1v.2A(o.1u)){o.1u.2w(1g,[g]);k c=$(a).10("J:Z").D?$(a).10("J:Z"):$(a);k b={\'m\':c.m(),\'p\':c.p(),\'L\':$.5.6.1s(c)};2r(k i=0;i<g.9.D;i++){g.9[i].o=$.1e({},o,g.9[i].o);h(o.Y>0||o.X>0){g.9[i].v=b}}}t{h(!a.1p||a.1p===\'\'){k d={11:a.K,I:a.I,o:o};h(o.Y>0||o.X>0){k c=$(a).10("J:Z").D?$(a).10("J:Z"):$(a);d.v={\'m\':c.m(),\'p\':c.p(),\'L\':$.5.6.1s(c)}}g.9.2k(d)}t{k e=$("a[@1p="+a.1p+"]").3o();2r(k i=0;i<e.D;i++){k b=$.1c?$.1e({},o,$(e[i]).1c()):o;k d={11:e[i].K,I:e[i].I,o:b};h(o.Y>0||o.X>0){k c=$(e[i]).10("J:Z").D?$(e[i]).10("J:Z"):$(a);d.v={\'m\':c.m(),\'p\':c.p(),\'L\':$.5.6.1s(c)}}h(e[i].K==a.K){g.8=i}g.9.2k(d)}}}$.5.6.U(g.8)};$.5.6.U=j(n){$.5.6.2c();g.8=n;$("#15").T();$("#E").3c();$("#R").C();$(q).M("1A");1f=2u.2Z(\'|\');1f=1i 2X(\'\\.\'+1f+\'$\',\'i\');k a=g.9[n].11;h(a.22(/#/)){k b=19.2T.K.2R(\'#\')[0];b=a.2P(b,\'\');$.5.6.1j(\'<7 l="2M">\'+$(b).2g()+\'</7>\');$("#B").C()}t h(a.22(1f)){$(N).M(\'1x\').1X(\'1x\',j(){$("#B").C();g.9[n].o.1M=N.m;g.9[n].o.1H=N.p;$.5.6.1j(\'<J l="2I" P="\'+N.P+\'" />\')}).2F(\'P\',a+\'?2E=\'+x.2D(x.1T()*2C))}t{$.5.6.1j(\'<W l="1S" 2B="$.5.6.1R()" 2z="2y\'+x.13(x.1T()*2x)+\'" 1N="0" 2v="0" P="\'+a+\'"></W>\')}};$.5.6.1R=j(){$("#B").C();$("#1S").G()};$.5.6.1j=j(a){$.5.6.1O();k b=$.5.6.1D();k c=$.5.6.2t(b[0]-3w,b[1]-3v,g.9[g.8].o.1M,g.9[g.8].o.1H);k d=b[2]+x.13((b[0]-c[0])/2)-20;k e=b[3]+x.13((b[1]-c[1])/2)-1t;k f={\'F\':d,\'A\':e,\'m\':c[0]+\'1L\',\'p\':c[1]+\'1L\'};h(g.1K){$(\'#u\').2q("1J",j(){$("#u").T();$("#E").1I(f,"1J",j(){$("#u").1a($(a)).3t("1J");$.5.6.1q()})})}t{g.1K=1n;$("#u").T();h($("#u").2p(":2o")){3s.3r(\'2o!\')}h(g.9[g.8].o.Y>0){g.V=1n;f.1h="G";$("#E").Q({\'A\':g.9[g.8].v.L.A-18,\'F\':g.9[g.8].v.L.F-18,\'p\':g.9[g.8].v.p,\'m\':g.9[g.8].v.m});$("#u").1a($(a)).G();$("#E").1I(f,g.9[g.8].o.Y,j(){g.V=y;$.5.6.1q()})}t{$("#u").1a($(a)).G();$("#E").Q(f).G();$.5.6.1q()}}};$.5.6.1q=j(){$("#w,#1o").G();h(g.9[g.8].I!==3q&&g.9[g.8].I!==\'\'){$(\'#R 7\').2g(g.9[g.8].I);$(\'#R\').G()}h(g.9[g.8].o.2n){$("#u").z($.5.6.O)}t{$("#u").M(\'z\')}h(g.8!==0){$("#15").1a(\'<a l="2m" K="2l:;"></a>\');$(\'#2m\').z(j(){$.5.6.U(g.8-1);s y})}h(g.8!=(g.9.D-1)){$("#15").1a(\'<a l="1Q" K="2l:;"></a>\');$(\'#1Q\').z(j(){$.5.6.U(g.8+1);s y})}$(q).1A(j(a){h(a.1C==27){$.5.6.O()}t h(a.1C==37&&g.8!==0){$.5.6.U(g.8-1)}t h(a.1C==39&&g.8!=(g.9.D-1)){$.5.6.U(g.8+1)}})};$.5.6.1O=j(){h((g.9.D-1)>g.8){2j=1i 1w();2j.P=g.9[g.8+1].11}h(g.8>0){2i=1i 1w();2i.P=g.9[g.8-1].11}};$.5.6.O=j(){h(g.V){s y}$(N).M(\'1x\');$(q).M("1A");$("#B,#R,#1o,#w").C();$("#15").T();g.1K=y;h(g.9[g.8].o.X>0){k a={\'A\':g.9[g.8].v.L.A-18,\'F\':g.9[g.8].v.L.F-18,\'p\':g.9[g.8].v.p,\'m\':g.9[g.8].v.m,\'1h\':\'C\'};g.V=1n;$("#E").1I(a,g.9[g.8].o.X,j(){$("#u").C().T();$("#17,#1m").2h();g.V=y})}t{$("#E").C();$("#u").C().T();$("#17,#1m").2q("3n").2h()}};$.5.6.2c=j(){2f(1d);k a=$.5.6.1D();$("#B").Q({\'F\':((a[0]-1t)/2+a[2]),\'A\':((a[1]-1t)/2+a[3])}).G();$("#B").1X(\'z\',$.5.6.O);1d=3m($.5.6.1U,3l)};$.5.6.1U=j(a,o){h(!$("#B").2p(\':3j\')){2f(1d);s}$("#B > 7").Q(\'A\',(1b*-1t)+\'1L\');1b=(1b+1)%12};$.5.6.2a=j(){h(!$(\'#1r\').D){$(\'<7 l="1r"><7 l="B"><7></7></7><7 l="E"><7 l="1z"><7 l="15"></7><7 l="1o"></7><7 l="u"></7><7 l="R"></7></7></7></7>\').2b("1k");$(\'<7 l="w"><7 H="w 3f"></7><7 H="w 3d"></7><7 H="w 3b"></7><7 H="w 3a"></7><7 H="w 38"></7><7 H="w 36"></7><7 H="w 35"></7><7 H="w 33"></7></7>\').32("#1z");$(\'<26 31="0" 30="0" 34="0"><25><S l="2W"></S><S l="2V"><7></7></S><S l="2U"></S></25></26>\').2b(\'#R\')}h($.23.29){$("#1z").1G(\'<W l="2S" 21="1Y" 1N="0"></W>\')}h(1v.5.28){$(q).28()}$("#1o").z($.5.6.O)};$.5.6.1s=j(a){k b=a.3e();b.A+=$.5.6.14(a,\'2O\');b.A+=$.5.6.14(a,\'3h\');b.F+=$.5.6.14(a,\'2N\');b.F+=$.5.6.14(a,\'3i\');s b};$.5.6.14=j(a,b){s 2L($.3k(a.2K?a[0]:a,b,1n))||0};$.5.6.2d=j(){k a,16;h(1y.1Z){16=1y.1Z;a=1y.2J}t h(q.1l&&q.1l.1B){16=q.1l.1B;a=q.1l.1W}t h(q.1k){16=q.1k.1B;a=q.1k.1W}s[a,16]};$.5.6.1D=j(){k a=$.5.6.2d();s[$(19).m(),$(19).p(),a[0],a[1]]};$.5.6.2t=j(a,b,c,d){k r=x.1E(x.1E(a,c)/c,x.1E(b,d)/d);s[x.13(r*c),x.13(r*d)]};$.5.6.2e={2n:y,Y:1V,X:1V,1M:2H,1H:2G,2s:y,24:0.4,1u:3x}})(1v);',62,220,'|||||fn|fancybox|div|itemNum|itemArray||||||||if||function|var|id|width|||height|document||return|else|fancy_content|orig|fancy_bg|Math|false|click|top|fancy_loading|hide|length|fancy_outer|left|show|class|title|img|href|pos|unbind|imgPreloader|close|src|css|fancy_title|td|empty|changeItem|animating|iframe|zoomSpeedOut|zoomSpeedIn|first|children|url||round|num|fancy_nav|yScroll|fancy_overlay||window|append|loadingFrame|metadata|loadingTimer|extend|imgRegExp|this|opacity|new|showItem|body|documentElement|fancy_bigIframe|true|fancy_close|rel|updateDetails|fancy_wrap|getPosition|40|itemLoadCallback|jQuery|Image|load|self|fancy_inner|keydown|scrollTop|keyCode|getViewport|min|settings|prepend|frameHeight|animate|normal|active|px|frameWidth|frameborder|preloadNeighborImages|start|fancy_right|showIframe|fancy_frame|random|animateLoading|500|scrollLeft|bind|no|pageYOffset||scrolling|match|browser|overlayOpacity|tr|table||pngFix|msie|init|appendTo|showLoading|getPageScroll|defaults|clearInterval|html|remove|preloadPrevImage|preloadNextImage|push|javascript|fancy_left|hideOnContentClick|animated|is|fadeOut|for|overlayShow|getMaxSize|imgTypes|hspace|apply|1000|fancy_iframe|name|isFunction|onload|999999999|floor|rand|attr|400|600|fancy_img|pageXOffset|jquery|parseInt|fancy_div|paddingLeft|paddingTop|replace|each|split|fancy_freeIframe|location|fancy_title_right|fancy_title_main|fancy_title_left|RegExp|gif|join|cellpadding|cellspacing|prependTo|fancy_bg_nw|border|fancy_bg_w|fancy_bg_sw||fancy_bg_s||fancy_bg_se|fancy_bg_e|stop|fancy_bg_ne|offset|fancy_bg_n|jpeg|borderTopWidth|borderLeftWidth|visible|curCSS|66|setInterval|fast|get|jpg|undefined|info|console|fadeIn|png|100|50|null'.split('|'),0,{}))

/* jQuery-Plugin "preloadCssImages" */
jQuery.preloadCssImages=function(settings){var settings=jQuery.extend({statusTextEl:null,statusBarEl:null},settings);var allImgs=[];var k=0;var sheets=document.styleSheets;for(var i=0;i<sheets.length;i++){var cssPile='';var csshref=sheets[i].href?sheets[i].href:'window.location.href';var baseURLarr=csshref.split('/');baseURLarr.pop();var baseURL=baseURLarr.join('/');if(baseURL!==""){baseURL+='/'}if(document.styleSheets[i].cssRules){var thisSheetRules=document.styleSheets[i].cssRules;for(var j=0;j<thisSheetRules.length;j++){cssPile+=thisSheetRules[j].cssText}}else{cssPile+=document.styleSheets[i].cssText}var imgUrls=cssPile.match(/[^\(]+\.(gif|jpg|jpeg|png)/g);var loaded=0;if(imgUrls!==null&&imgUrls.length>0&&imgUrls!==''){var arr=jQuery.makeArray(imgUrls);jQuery(arr).each(function(){allImgs[k]=new Image();allImgs[k].src=(this.charAt(0)=='/'||this.match('http://'))?this:baseURL+this;$(allImgs[k]).load(function(){loaded++;if(settings.statusTextEl){$(settings.statusTextEl).html('<span class="numLoaded">'+loaded+'</span> of <span class="numTotal">'+allImgs.length+'</span> loaded (<span class="percentLoaded">'+(loaded/allImgs.length*100).toFixed(0)+'%</span>) <span class="currentImg">Now Loading: <span>'+allImgs[loaded-1].src.split('/')[allImgs[loaded-1].src.split('/').length-1]+'</span></span>')}if(settings.statusBarEl){var barWidth=$(settings.statusBarEl).width();$(settings.statusBarEl).css('background-position',-(barWidth-(barWidth*loaded/allImgs.length).toFixed(0))+'px 50%')}});k++})}}return allImgs};

// JavaScript Document
////////////////////Ê×Ò³Áª¶¯²Ëµ¥//////////////////////////////////////
//<![CDaTa[
function showsub_a(id){
	for (j = 0;j<3;j++){
		document.getElementById("tab_a"+j).className = "bj_02";
		document.getElementById("sub_a"+j).style.display = "none";
	}
		document.getElementById("tab_a"+id).className = "bj_01";
		document.getElementById("sub_a"+id).style.display = "";
}
function showsub_b(id){
	for (k = 0;k<2;k++){
		document.getElementById("tab_b"+k).className = "bj_2";
		document.getElementById("sub_b"+k).style.display = "none";
	}
		document.getElementById("tab_b"+id).className = "bj_1";
		document.getElementById("sub_b"+id).style.display = "";
}
function showsub_c(id){
	for (l = 0;l<8;l++){
		document.getElementById("tab_c"+l).className = "bj_2";
		document.getElementById("sub_c"+l).style.display = "none";
	}
		document.getElementById("tab_c"+id).className = "bj_1";
		document.getElementById("sub_c"+id).style.display = "";
}
function showsub_d(id){
	for (m = 0;m<8;m++){
		document.getElementById("tab_d"+m).className = "bj_2";
		document.getElementById("sub_d"+m).style.display = "none";
	}
		document.getElementById("tab_d"+id).className = "bj_1";
		document.getElementById("sub_d"+id).style.display = "";
}
function showsub_e(id){
	for (n = 0;n<2;n++){
		document.getElementById("tab_e"+n).className = "bj_002";
		document.getElementById("sub_e"+n).style.display = "none";
	}
		document.getElementById("tab_e"+id).className = "bj_001";
		document.getElementById("sub_e"+id).style.display = "";
}
function showsub_f(id){
	for (o = 0;o<3;o++){
		document.getElementById("tab_f"+o).className = "bj_02";
		document.getElementById("sub_f"+o).style.display = "none";
	}
		document.getElementById("tab_f"+id).className = "bj_01";
		document.getElementById("sub_f"+id).style.display = "";
}
//]]>
/////////////////»ÃµÆ//////////////////////////////////////////////////////
function showpic_hd(fileurl,linkarr,picarr,textarr){
var files = "";
var links = "";
var texts = "";
for(i=1;i<picarr.length;i++){
  if(files=="") files = picarr[i];
  else files += "|"+picarr[i];
}
for(i=1;i<linkarr.length;i++){
  if(links=="") links = linkarr[i];
  else links += "|"+linkarr[i];
}
for(i=1;i<textarr.length;i++){
  if(texts=="") texts = textarr[i];
  else texts += "|"+textarr[i];
}
document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ swf_width +'" height="'+ swf_height +'">');
document.write('<param name="movie" value="'+fileurl+'/images/bcastr3.swf"><param name="quality" value="high">');
document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
document.write('<param name="FlashVars" value="bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'">');
document.write('<embed src="'+fileurl+'/images/bcastr3.swf" wmode="opaque" FlashVars="bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'& menu="false" quality="high" width="'+ swf_width +'" height="'+ swf_height +'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />'); document.write('</object>'); 

}

//使用div时，请保证colee_left2与colee_left1是在同一行上./滚动图
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

