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
            this.is_true = 0;
            this._modifyPassword();
        },
        _modifyPassword: function () {
            var _this = this;
            $("input[name=newpassword]").on("change", function () {
                if ($("input[name=newpassword]").val() == '') {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("请输入新密码");
                    _this.is_true = 0;
                } else if ($("input[name=rewritepassword]").val() != $("input[name=newpassword]").val()) {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("两次输入密码不一致");
                    _this.is_true = 0;
                } else {
                    $(".error").hide();
                    _this.is_true = 1;
                }
            });
            $("input[name=rewritepassword]").on("change", function () {
                if ($("input[name=rewritepassword]").val() == '') {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("请再次输入密码");
                    _this.is_true = 0;
                } else if ($("input[name=rewritepassword]").val() != $("input[name=newpassword]").val()) {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("两次输入密码不一致");
                    _this.is_true = 0;
                } else {
                    $(".error").hide();
                    _this.is_true = 1;
                }

            });
            $('#modifyPassword .addsave').on('click', function () {
                if ($("input[name=newpassword]").val() == '' || $("input[name=rewritepassword]").val() == '') {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("密码不能为空");
                    _this.is_true = 0;
                } else if ($("input[name=rewritepassword]").val() != $("input[name=newpassword]").val()) {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("两次输入密码不一致");
                    _this.is_true = 0;
                } else {
                    passwordStrong($("input[name=newpassword]").val());
                }
                if (_this.is_true == 0) {
                    return false;
                }
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
                            _this.is_true = 0;
                        }
                    });
                });
            });
            function passwordStrong(psw) {
                var reg = /^\w+$/;
                if (psw.length < 8) {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("密码太短，密码不得少于8位");
                    _this.is_true = 0;
                    return false;
                } else if (!reg.test(psw)) {
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("密码格式不对");
                    _this.is_true = 0;
                    return false;
                } else {
                    $(".error").hide();
                    _this.is_true = 1;
                }
            }
        }
    };
    var init = new $scope.userInit();

}