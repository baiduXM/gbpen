// 单页面应用 ----------
// create the module and name it mainApp
// also include ngRoute for all our routing needs
var mainApp = angular.module('mainApp', ['ngRoute']);
// configure our routes
mainApp.config(function ($routeProvider, $httpProvider) {
    var pageLoading, timerPageLoading = 0, initRouter = location.hash;
    // route for the home page
    $routeProvider.when('/login', {
        template: 'login...',
        controller: function () {
            location.href = '/';
        }
    }).when('/', {
        template: 'empty...',
        controller: "indexController"
    }).when('/loading', {
        template: 'loading...',
        controller: function () {
            if (location.hash.indexOf('?') !== -1) {
                var loadingRoute = decodeURIComponent(location.hash.substring(location.hash.indexOf('?') + 1));
                location.hash = loadingRoute;
            } else if (!pageLoading) {
                pageLoading = function () {
                    location.hash = initRouter;
                };
                clearTimeout(timerPageLoading);
                timerPageLoading = setTimeout(pageLoading, 5000);
            }
        }
    }).otherwise({redirectTo: 'loading'});

    $.get('json/config.json', function (data) {
        var currentPage = false;
        angular.forEach(data, function (page) {
            $routeProvider.when(page.router, {
                templateUrl: page.templateUrl + (page.templateUrl.indexOf('?') === -1 ? ('?t=' + Math.random()) : ('&t=' + Math.random())),
                controller: function ($scope, $rootScope, $injector) {
                    $(window).resize();
                    // Load Controller JS
                    if (page.scriptUrl) {
                        var elem = document.createElement('script');
                        elem.setAttribute('type', 'text/javascript');
                        elem.setAttribute('src', page.scriptUrl);
                        elem.onload = elem.onreadystatechange = function () {
                            if (!elem.readyState || /loaded|complete/.test(elem.readyState)) {
                                elem.onload = elem.onreadystatechange = null;
                                if (eval('typeof ' + page.controller + ' == "function"')) {
                                    //var prevMenuShow = $scope.$parent.menu.length > 0;
                                    var pageController = eval(page.controller);
                                    // Read pageController Params
                                    var pageCtrlParamsNameArr = pageController.toString().match(/function.*\(([^(]*)\)/)[1].split(',');
                                    var pageCtrlParamsArr = [], pageCtrlParamsArrNameArr = [];
                                    for (var k in pageCtrlParamsNameArr) {
                                        pageCtrlParamsNameArr[k] = pageCtrlParamsNameArr[k].trim();
                                        if ($injector.has(pageCtrlParamsNameArr[k])) {
                                            pageCtrlParamsArr[k] = $injector.get(pageCtrlParamsNameArr[k]);
                                            pageCtrlParamsArrNameArr[k] = 'pageCtrlParamsArr[' + k + ']';
                                        } else {
                                            pageCtrlParamsArrNameArr[k] = eval("typeof " + pageCtrlParamsNameArr[k] + "!=='undefined'") ? pageCtrlParamsNameArr[k] : 'null';
                                        }
                                    }
                                    eval("pageController(" + pageCtrlParamsArrNameArr.join(',') + ");")
                                    $scope.$apply();
                                    //var nowMenuShow = $scope.$parent.menu.length > 0;
                                    //if (nowMenuShow !== prevMenuShow) $(window).resize();
                                }
                            }
                        };
                        (document.body || document.getElementsByTagName('body')[0] || document.head || document.getElementsByTagName('head')[0] || document.documentElement).appendChild(elem);
                    }
                }
            });

            //if (location.hash.replace('#','') == page.router) currentPage = page;
        });
        // route for the otherwise page
        $routeProvider.otherwise({redirectTo: '/login'});

        // Refresh page
        clearTimeout(timerPageLoading);
        if (location.hash == '#/loading') {
            typeof pageLoading === 'function' ? setTimeout(pageLoading, 100) : null;
        }
        // light recording
        setTimeout(function () {
            var locathash;
            $('nav .nav a').each(function () {
                var locat = $(this).attr('href').substr(2);
                var loca_than = location.hash.substr(2);
                if (locat === loca_than) {
                    locathash = $(this).attr('href');
                    $(this).parent().addClass('selected').siblings().removeClass('selected');
                }
            });
            //===获取统计数据===
            var ahref = $(".selected>a").attr("href")
            if (ahref == "#/visit") {
                $.get('../statis-get', function (json) {
                    var data = json.data;
                    if (data != null) {
                        $(".cxb_list_n2>.cxb_list_m").html(data.count_today);//今日访问数量
                        $(".cxb_list_n3>.cxb_list_m").html(data.count_all);//总访客数量
                        $(".cxb_list_n4>.cxb_list_m").html(data.count_mobile);//移动端访问量
                    }
                });
            }
            locathash ? $('#blob').animate({left: $('nav .nav a[href="' + locathash + '"]').parent().position().left}, 1000, 'easeOutExpo').addClass('rotation') : '';
        }, 200);
    });

    // change request payload method to form data
    $httpProvider.defaults.headers.post = {
        'Content-Type': 'application/x-www-form-urlencoded'
    };
    $httpProvider.defaults.transformRequest = function (obj) {
        var inside_obj = '';
        for (var p in obj) {
            for (var n in obj[p]) {
                if (typeof obj[p][n] == "object")
                    inside_obj = 1;
            }
        }
        if (obj != undefined) {
            if (obj instanceof Array || inside_obj == '') {
                var str = [];
                for (var p in obj)
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                return str.join("&");
            } else if (inside_obj == 1) {
                return $.param(obj);
            }
        }
    };

}).factory('pageLoadingStart', function () {
    return function () {
        $('.page-loading').fadeIn('slow');
        $('div[ng-view]').css('opacity', 0.5);
    };
}).factory('pageLoadingSuccess', function () {
    return function () {
        $('.page-loading').stop().fadeOut('fast');
        $('div[ng-view]').css('opacity', 0.5);
    };
}).run(function ($rootScope, $http, pageLoadingStart, pageLoadingSuccess) {
    $http.get('json/config.json').success(function (response) {
        $rootScope.pages = response;
    });

    var locationChangeStartOff = $rootScope.$on('$locationChangeStart', function (event, next, current) {
        if (next.substring(next.indexOf('#')) == '#') {
            event.preventDefault();
        }
        //    if (/^#article/g.test(next.substring(next.indexOf('#')))) {
        //        event.preventDefault();
        //    }
    });
    var routeChangeStartOff = $rootScope.$on('$routeChangeStart', function (event, next, current) {
        pageLoadingStart();
    });
    var routeChangeSuccessOff = $rootScope.$on('$routeChangeSuccess', function (event, next, current) {
        pageLoadingSuccess();
    });
});

// create the controller and inject Angular's $scope
// 主控制器\控制模块显隐
mainApp.controller('mainController', function ($scope) {
    $scope.showbox = "";
    $scope.menu = [];
    $scope.domain_pc = '';
    $scope.phonepreview = true;
});
// 登陆信息
mainApp.controller('memberController', function ($scope, $http) {
    $http.get('../customer-info').success(function (json) {
        checkJSON(json, function (json) { 
            //获取是否有微传单
            $http.get('http://dl2.5067.org/?module=Api&action=getGshowByname&name='+json.data.customer).success(function (d) {
                if(d==1){
                    $(".daohang ul").append('<li class="nav"><a href="/cdlogin" target="_blank">E推</a><em></em></li>');
                }else{
                    $(".daohang ul").append('<li class="nav"><a href="javascript:void(0)" style="color:#FF3333;" onclick=freeEt("'+json.data.customer+'")>E</a><em></em></li>');
                }
            });
            $scope.companyname = json.data.company_name;
            $scope.$parent.domain_pc = json.data.domain_pc;
            if (json.data.domain_m == '' || json.data.domain_m == null) {
                $scope.$parent.phonepreview = false;
            }
            ercodeDrop._init('.phone-content .notice', json.data.domain_m);
        });
    });
});
// 引导页
mainApp.controller('indexController', function ($scope) {
    $scope.$parent.showbox = "home";
    $scope.$parent.menu = [];
});

$(document).ready(function ($scope) {
    // 初始化 ----------
    var bgImg = new Image();
    bgImg.src = 'images/bg_top.jpg';
    bgImg.onload = function () {
        $('.background .bg_thumb').fadeOut('slow', function () {
            $(this).remove();
        });
    };
    setTimeout(function () {
        $('#phone').data('phoneClosed', true);
    }, $.support.leadingWhitespace ? 1200 : 1);

    $scope.MainInit = function () {
        this._init();
    };
    $scope.MainInit.prototype = {
        _init: function () {
            this.navanimation();
            this.otherPageSkip();
            this.phoneMousehover();
            this.phoneClick();
        },
        navanimation: function () {
            $('header nav ul').spasticNav({
                overlap: 0,
                reset: 1000,
                color: 'transparent',
                backToSelected_callback: function () {
                    $('header nav ul li.selected').find('em').fadeIn();
                    $('#blob').addClass('rotation');
                }
            }).find('li:not(#blob)').mouseenter(function () {
                if (!$(this).hasClass('selected')) {
                    $('header nav ul li.selected').find('em').hide();
                    $('#blob').removeClass('rotation');
                } else if (!$('#blob').hasClass('rotation')) {
                    $('#blob').addClass('rotation');
                    var _this = $(this);
                    setTimeout(function () {
                        _this.find('em').fadeIn('fast');
                    }, 200);
                }
            }).mouseleave(function () {
                if ($(this).hasClass('selected'))
                    return false;
                $(this).find('em').hide();
            }).click(function () {
                if ($(this).hasClass('selected'))
                    return false;
                $(this).find('em').fadeIn();
                $(this).siblings().find('em').hide();
                $('#blob').addClass('rotation');
                $(this).addClass('selected').siblings().removeClass('selected');
            });
        },
        otherPageSkip: function () {
            $('.member_buttons a').click(function () {
                $('header nav ul li').removeClass('selected').find('em').hide();
                $('#blob').removeClass('rotation').animate({left: 0}, 1000, 'easeOutExpo');
            });
        },
        leftMenu: function () {
            // 侧边菜单 ----------
            $('#menu-close').click(function () {
                $('body').toggleClass('closemenu');
                $(window).resize();
            });
        },
        phoneMousehover: function () {
            $('#preview-phone').mouseenter(function () {
                if ($('#phone').data('phoneClosed_index')) {
                    return false;
                } else {
                    if (!$('#phone').data('phoneClosed'))
                        return false;
                    $('.previews').addClass('phone-hover');
                    $('#phone').addClass('phone-hover').removeClass('phone-hoveroff');
                }
            });
            $('#phone').mouseleave(function () {
                if ($('#phone').data('phoneClosed_index')) {
                    return false;
                } else {
                    if (!$('#phone').data('phoneClosed'))
                        return false;
                    $(this).addClass('phone-hoveroff').removeClass('phone-hover');
                    $('.previews').removeClass('phone-hover');
                }
            });
        },
        phoneClick: function () {
            $('#phone-home, #preview-phone, #preview-wechat').click(function () {
                $('#phone').data('phoneClosed_index') == false ? $('#phone').data('phoneClosed', true) : $('#phone').data('phoneClosed_index') == undefined ? $('#phone').data('phoneClosed', true) : $('#phone').data('phoneClosed', false);
                if ($('#phone').data('phoneClosed')) {
                    $('#phone').data('phoneClosed_index', true);
                    $('#phone').removeClass('phone-hover').data('phoneClosed', false);
                    $('body').removeClass('closephone').addClass('closemenu');
                    $('.previews').removeClass('phone-hover');
                    if ($(this).attr('id') == 'preview-wechat') {
                        $('#phone #weixin_preview').show();
                        $('#phone #phone_preview').hide();
                    } else {
                        $('#phone #phone_preview').show();
                        $('#phone #weixin_preview').hide();
                    }
                } else {
                    $('#phone').data('phoneClosed_index', false);
                    $('body').addClass('closephone').removeClass('closemenu');
                    setTimeout(function () {
                        $('#phone').data('phoneClosed', true);
                    }, $.support.leadingWhitespace ? 1200 : 1);
                }
                $(window).resize();
            });
        }
    };
    var maininit = new $scope.MainInit();
});

var mainMarginLeft = parseInt($('#main').css('margin-left'));
var previewsWidth = $('.previews').outerWidth(true);
// 计算高宽值
function width_resize(data) {
    // #main
    var mainWidth = 840;
    var socrllWidth = parseInt(window.innerWidth) - parseInt(document.body.clientWidth);
    $('#menu').data('restwidth', $('#menu').data('restwidth') || 0)
    var menuWidth = $('body').hasClass('showmenu') ? (!$('body').hasClass('closemenu') ? $('#menu').outerWidth() + parseInt($('#menu').css('margin-right')) : parseInt($('#menu').data('restwidth'))) : 0;
    var phoneWidth = !$('body').hasClass('closephone') ? $('#phone').outerWidth(true) : 0;
    var mainExtraWidth = mainMarginLeft + parseInt($('#main').css('padding-left'));
    mainWidth = $(window).width() - phoneWidth - menuWidth - previewsWidth - mainExtraWidth - 37;
    // console.log('menuWidth:'+menuWidth,'phoneWidth:'+phoneWidth,'previewsWidth:'+previewsWidth,'mainExtraWidth:'+mainExtraWidth);
    location.hash.match(/[a-z]+?$/) == 'setting' ? null : $('#main').css({'width': mainWidth, 'marginLeft': mainMarginLeft});
    // .home-box
    var homeboxWidth = $(window).width() - parseInt($('#home-edit').css('margin-left')) - parseInt($('#home-edit').width()) - parseInt($('#home-edit').css('padding-left')) - parseInt($('.home-box').css('margin-right')) - 40;
    $('#diy .home-page-preview').css('width', homeboxWidth);
    $('.home-box .home-page-preview .iframs').css('width', homeboxWidth);
}
//窗口更改时计算高宽值
$(window).resize(function () {
    width_resize()
});

//免费试用E推
function freeEt(customer){
    // alert(customer);
    // location.reload();
    $http.get('http://dl2.5067.org/?module=Api&action=TyGshow&name='+customer).success(function(data){
        if(data==0){
            alert('开通成功');
            location.reload();
        }else if(data==1){
            alert('同步失败');
        }else if(data==2){
            alert('开通失败');
        }
    });
}

// 推送静态文件
var cache_num;
function pushtimer() {
    $.get('../isneedpush', function (json) {
        checkJSON(json, function (json) {
            cache_num = json.data.cache_num;
            if (json.data.cache_num > 0) {
                $('#preview-refresh').show();
                cache_num > 99 ? $('.red_dot').text('99+') : $('.red_dot').text(json.data.cache_num);
            } else {
                $('.previews').find('#preview-refresh').remove();
            }
        });
    });
}
pushtimer();
//setInterval(function(){
//	pushtimer();
//}, 60000);
window.onbeforeunload = function (event) {
    if (cache_num) {
        return "你更改的文件尚未推送！";
    }

}





