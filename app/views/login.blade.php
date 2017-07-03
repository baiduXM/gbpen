<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>统一平台登录</title>
        {{HTML::script('admin/js/jquery.min.js')}}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSS -->
        {{HTML::style('admin/css/login.css')}}
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script type="text/javascript" src="js/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <div class="page-container">
            <h1>统一平台登录</h1>
            {{Form::open(['url' => 'login-post'])}}
            {{Form::text('name','',['placeholder'=>'请输入您的用户名！'])}}
            {{Form::password('password',['placeholder'=>'请输入您的用户密码！'])}}
            {{Form::submit('登录',['class'=>'submit_button'])}}
            <div class="error"><span>+</span></div>
            {{Form::close()}}
            <div class="connect">
                <div class="connect">
{{--                    <p>{{HTML::link('get-remind','忘记密码?')}}</p>--}}
                    <p>
                        <a class="facebook" href=""></a>
                        <a class="twitter" href=""></a>
                    </p>
                </div>
            </div>
    </body>
</html>
