function userController($scope, $http) {
    $scope.$parent.showbox = 'main';
    $scope.$parent.homepreview = false;
    $scope.$parent.menu = [];
    var mainMarginLeft = ($(window).width() - 840) / 2;
    $('#main').css({'width': 840, 'marginLeft': mainMarginLeft});
    $scope.userInit = function () {
        this._init();
    };
    $scope.userInit.prototype = {
        _init: function () {
            this._modifyPassword();
        },
        _modifyPassword: function () {
            $("#modifyPassword .addsave").on("click", function () {

            });
            $("input[name=rewritepassword]").on("change", function () {
                if ($("input[name=rewritepassword]").val() != $("input[name=newpassword]").val()) {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("两次输入密码不一致");
                } else {
                    $(".error").hide();
                }

            });
            $("input[name=newpassword]").on("change", function () {
//                passwordStrong($("input[name=newpassword]").val());
                if ($("input[name=rewritepassword]").val() != '') {
                    if ($("input[name=rewritepassword]").val() != $("input[name=newpassword]").val()) {
                        $(".error").show();
                        $("#modifyPassword .errmessage").text("两次输入密码不一致");
                    } else {
                        $(".error").hide();
                    }
                } else {
                    $(".error").hide();
                }
            });
            $('#modifyPassword .addsave').on('click', function () {
                var data = $('#password_info').serializeJson();
                $http.post('../modify-password', data).success(function (json) {
                    checkJSON(json, function (json) {
                        if (json.success == 1) {
                            $(".error").hide();
                            var hint_box = new Hint_box();
                            hint_box;
                            alert('密码修改成功');
                            location.hash = 'login';
                        } else {
                            $(".error").show();
                            $("#modifyPassword .errmessage").text(json.msg);
                        }
                    });
                });
            });
        }
    };
    var init = new $scope.userInit();

//    function passwordStrong(psw) {
//        var reg = /^\w+$/;
//        if (!reg.test(psw)) {
//            alert('密码格式不对');
//            return;
//        }
//        alert(psw);
//        var rules = [{
//                reg: /\d+/,
//                weight: 2
//            }, {
//                reg: /[a-z]+/,
//                weight: 4
//            }, {
//                reg: /[A-Z]+/,
//                weight: 8
//            }, {
//                reg: /[~!@#\$%^&*\(\)\{\};,.\?\/'"]/,
//                weight: 16
//            }];
//
//        var strongLevel = {
//            '0-10': '弱',
//            '10-20': '中',
//            '20-30': '强'
//        };
//        var weight = 0;
//        for (var j = rules.length - 1; j >= 0; j--) {
//            if (rules[j].reg.test(testPasswords[i])) {
//                weight |= rules[j].weight;
//            }
//        }
//        var key = '20-30';
//        if (weight <= 10)
//            key = '0-10';
//        else if (weight <= 20)
//            key = '10-20';
//        console.log(testPasswords[i], strongLevel[key]);
//    }
}