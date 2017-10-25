
$(function () {
    //弹框时表单重置
    document.getElementById('feedback_form').reset();     
    //生成验证码
    var code;
    var code2;
    createCode();  
    //提交申请
    $('.tj').click(function(){        
        var res = check();
        if(res==false){
            return false;
        }

        var formdata=new FormData($("#feedback_form")[0]);
        for (var [key, value] of formdata.entries()) { 
          console.log(key, value);
        }

        $('.tj').val('正在提交...');
        $('.subb').css('backgroud' , '#999999');
        $('.tj').attr('disabled',true);        
        $('.wait').css('display','block');

        $.ajax({
            url: '../weicard-apply',
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            dataType: 'json',
            success: function (json) {                
                if(json.err == 1000){
                    $('.gsqbox').hide();
                    $('.zhez').hide();
                } else {
                    $('.tj').val('提交');
                    $('.tj').attr('disabled',false);
                    $('.subb').css('backgroud' , '#fdb900');
                    $('.wait').css('display','none');
                }
                alert(json.msg);
            },
            error: function (json) {
                $('.tj').val('提交');
                $('.tj').attr('disabled',false);
                $('.subb').css('backgroud' , '#fdb900');
                $('.wait').css('display','none');
                alert('提交失败');
            }
        });
    });
    //G名片弹框
    $(document).on('click','.G-apply',function(){
        $(this).parents(".model").hide();
        $('.gsqbox').show();
        $('.zhez').show();
    });

    //G名片弹框关闭
    $(".cha").click(function(){
        $(this).parent().parent().hide();
        $(".zhez").hide()
    }); 


});



// 验证码
function createCode() {
    code = "";
    var codeLength = 6; //验证码的长度
    var checkCode = document.getElementById("checkCode");
    var codeChars = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
         'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
         'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'); //所有候选组成验证码的字符，当然也可以用中文的
    for (var i = 0; i < codeLength; i++) {
        var charNum = Math.floor(Math.random() * 52);
        code += codeChars[charNum];
    }
    if (checkCode) {
        checkCode.className = "code";
        checkCode.innerHTML = code;
    }
    code2 = "";
    var codeLength2 = 6; //验证码的长度
    var checkCode2 = document.getElementById("checkCode2");
    var codeChars2 = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
         'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
         'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'); //所有候选组成验证码的字符，当然也可以用中文的
    for (var i = 0; i < codeLength2; i++) {
        var charNum2 = Math.floor(Math.random() * 52);
        code2 += codeChars2[charNum2];
    }
    if (checkCode2) {
        checkCode2.className = "code";
        checkCode2.innerHTML = code2;
    }
}

//验证信息
function check() {
    var comname = $("#txtCompanyName").val();
    if (comname == "") {
        alert("请填写公司！");
        return false;
    }

    var name = $("#txtName").val();
    if (name == "") {
        alert("请填写姓名！");
        return false;
    }

    var contact = $("#txtContact").val();
    if (!(/^1[3|4|5|7|8][0-9]\d{4,8}$/.test(contact))) {
        alert("不是完整的11位手机号或者正确的手机号前七位");
        $("#txtContact").focus();
        $("#txtContact").select();
        return false;
    }

    var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
    if (!reg.test($("#txtContent").val())) {
        alert('邮箱格式不正确，请重新填写!');
        $("#txtContent").focus();
        $("#txtContent").select();
        return false;
    }

    var f=document.getElementById("fileId").value;
    if(f=="") { 
        alert("请上传营业执照");
        return false;
    } else {
        if(!/\.(gif|jpg|png|GIF|JPG|PNG)$/.test(f)) {
            alert("图片类型必须是.gif,jpg,png中的一种")
            return false;
        }
    }

    var inputCode = $("#inputCode").val();
    if (inputCode.length <= 0) {
        alert("请输入验证码！");
        return false;
    } else if (inputCode.toUpperCase() != code.toUpperCase()) {
        alert("验证码输入有误！");
        createCode();
        return false;
    }
    return true;
}

//图片预览
function readFile(file) {  
    var prevDiv = document.getElementById('preview');  
    if (file.files && file.files[0]) {  
        var reader = new FileReader();  
        reader.onload = function(evt){  
            prevDiv.innerHTML = '<img src="' + evt.target.result + '"width="128" height="128" />';  
        }    
        reader.readAsDataURL(file.files[0]);  
    } else {  
        prevDiv.innerHTML = '<div class="img" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';  
    } 
    $("#preview").show(); 
}  

//预览关闭
function cimg(){
    $("#preview").hide();
}

  