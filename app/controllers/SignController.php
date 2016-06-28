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

    public function loginPost() {
        $name = Input::get('name');
        $password = Input::get('password');
        $cus_id = Customer::where('name', $name)->where('status', '1')->pluck('id');
        if (!$cus_id) {
            echo '<meta charset="UTF-8"><script language="javascript">alert("用户不存在!");window.history.back(-1);</script> ';
            exit();
        }
        if (Auth::attempt(array('name' => $name, 'password' => $password))) {
            $customername = Auth::user()->name;
            return Redirect::to('admin/index.html');
        } else {
            echo '<meta charset="UTF-8"><script language="javascript">alert("登录失败!");window.history.back(-1);</script> ';
            exit();
            //$result = ['err'=>1001,'msg'=>'登录失败'];
            //return Response::json($result);
        }
    }

    public function isLogin() {
        if (Auth::check()) {
            return Response::json(['err' => 0, 'msg' => '']);
        } else {
            return Response::json(['err' => 2001, 'msg' => '未登录']);
        }
    }

    public function logOut() {
        Auth::logout();
        return Redirect::to('/');
    }

    public function remindPost() {
        Password::remind(Input::only('email'), function($message) {
            $message->subject('Password Reminder');
        });
    }

    public function resetPost() {
        
    }

    public function modifyPassword() {
        $oldpassword = Input::get('oldpassword');
        $newpassword = Input::get('newpassword');
        if (Auth::check()) {
            if (Hash::check($oldpassword, Auth::user()->password)) {
                $result = Customer::where('id', Auth::id())->update(['password' => Hash::make($newpassword)]);
            }
        }
        Auth::logout();
        if (isset($result) && ($result)) {
            return Response::json(['err' => 0, 'msg' => '修改成功', 'success' => 1]);
        } else {
            return Response::json(['err' => 0, 'msg' => '密码错误', 'success' => 0]);
        }
    }

}
