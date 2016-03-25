function userController($scope,$http ) {
    $scope.$parent.showbox = 'main';
    $scope.$parent.homepreview = false;
    $scope.$parent.menu = [];
    var mainMarginLeft = ($(window).width() - 840)/2;
    $('#main').css({'width' : 840,'marginLeft' : mainMarginLeft});
    $scope.userInit = function(){
        this._init();
    };
    $scope.userInit.prototype = {
         _init : function(){
            this._modifyPassword();
        },
    _modifyPassword : function(){
        $("#modifyPassword .addsave").on("click",function(){
            
        });
        $("input[name=rewritepassword]").on("change",function(){
            if($("input[name=rewritepassword]").val()!=$("input[name=newpassword]").val()){
                $(".error").show();
                $("#modifyPassword .errmessage").text("两次输入密码不一致");
            }else{
                $(".error").hide();
            }
            
        });
        $("input[name=newpassword]").on("change",function(){
            if($("input[name=rewritepassword]").val()!=''){
                if($("input[name=rewritepassword]").val()!=$("input[name=newpassword]").val()){
                    $(".error").show();
                    $("#modifyPassword .errmessage").text("两次输入密码不一致");
                }else{
                    $(".error").hide();
                }
            }else{
                $(".error").hide();
            }
        });
        $('#modifyPassword .addsave').on('click',function(){
                var data = $('#password_info').serializeJson();
                $http.post('../modify-password',data).success(function(json){
                    checkJSON(json,function(json){
                        if(json.success == 1){
                            $(".error").hide();
                            var hint_box = new Hint_box();
                            hint_box;
                            alert('密码修改成功');
                            location.hash='login';
                        }else{
                            $(".error").show();
                            $("#modifyPassword .errmessage").text("原密码错误");
                        }
                    });
                });
            });
    }  
    };
    var init = new $scope.userInit();
 }