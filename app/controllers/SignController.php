<?php

class SignController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| 登录控制器
	|--------------------------------------------------------------------------
	|方法：
    |
    |loginPost          用户登录
    |loginOut           用户登出
	|remindPost         重设密码
	*/

	public function loginPost()
	{
		$name = Input::get('name');
        $password = Input::get('password');
        if (Auth::attempt(array('name' => $name, 'password' => $password)))
        {   
            $customername =  Auth::user()->name;
            return Redirect::to('admin/index.html');
        }
        else{
        	echo '<script language="javascript">alert("登录失败!");window.history.back(-1);</script> ';
            exit();
            //$result = ['err'=>1001,'msg'=>'登录失败'];
            //return Response::json($result);
        }
        
	}

    public function isLogin(){
        if (Auth::check())
        {
            return Response::json(['err'=>0,'msg'=>'']);
        }
        else{
            return Response::json(['err'=>2001,'msg'=>'未登录']);
        }
    }

    public function login(){

        $id = Input::get('id');
        if(验证成功){
            $user = User::find(1);
            Auth::login($user);
        }
        else{
            
        }
    }
    
    public function logOut(){
        Auth::logout();
        return Redirect::to('/');
    }
    
    public function remindPost()
    {
        Password::remind(Input::only('email'), function($message)
        {
            $message->subject('Password Reminder');
        });
    }

    public function resetPost(){
        
    }

}
