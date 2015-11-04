<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	<style>

		body {
			margin:0;
			font: 14px/1.42857 "Microsoft YaHei","微软雅黑","Helvetica Neue",Helvetica,Arial,sans-serif;
			text-align:center;
			color: #999;
		}

		.welcome {
			width: 600px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -250px;
			margin-top: -150px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 36px 0;
		}
	</style>
</head>
<body>
    
	<div class="welcome">
        <h1>忘记密码</h1>
        {{Form::open(['url' => 'rest-post'])}}
        {{Form::label('username','邮箱')}}
        {{Form::hidden('token',$token)}}
        {{Form::email('email')}}
        {{Form::password('password',['placeholder'=>'请输入您的新密码！'])}}
        {{Form::password('password',['placeholder'=>'再次确认您的用户密码！'])}}
        {{Form::submit('确定')}}
        {{Form::close()}}
	</div>
</body>
</html>
