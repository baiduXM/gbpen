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

    /**
     * 用户登录
     * @param type $name
     * @param type $password
     * @return type
     */
    public function loginPost() {
        $name = Input::get('name');
        $password = Input::get('password');

        $id_del = Customer::where('name', $name)->where('status', '1')->where('is_del', '0')->pluck('id');
        if ($id_del) {
            echo '<meta charset="UTF-8"><script language="javascript">alert("用户已删除!");window.history.back(-1);</script> ';
            exit();
        }
        $cus_id = Customer::where('name', $name)->where('status', '1')->where('is_del', '1')->pluck('id');
        if (!$cus_id) {
            echo '<meta charset="UTF-8"><script language="javascript">alert("用户不存在!");window.history.back(-1);</script> ';
            exit();
        }
        if (Auth::attempt(array('name' => $name, 'password' => $password))) {
            if ($name == $password) {
                echo '<meta charset="UTF-8"><script language="javascript">alert("账号密码不能相同，请先修改密码!");</script> ';
                return Redirect::to('admin/index.html#/user');
            }
            $customername = Auth::user()->name;
            $this->logsAdd("customer",__FUNCTION__,__CLASS__,100,"用户登录",0);
            return Redirect::to('admin/index.html');
        } else {
            echo '<meta charset="UTF-8"><script language="javascript">alert("登录失败!");window.history.back(-1);</script> ';
            exit();
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
        $this->logsAdd("customer",__FUNCTION__,__CLASS__,101,"用户退出",0);
        Auth::logout();
        if (Session::has('isDaili')){
            Session::forget('isDaili');
        }
        return Redirect::to('/');
    }

    public function remindPost() {
        Password::remind(Input::only('email'), function($message) {
            $message->subject('Password Reminder');
        });
    }

    /**
     * 修改密码
     * @return type
     */
    public function modifyPassword() {
//        $oldpassword = Input::get('oldpassword');
        $newpassword = Input::get('newpassword');
        $msg = '密码修改失败';
        if (Auth::check()) {
            $name = Auth::user()->name;
//            if (Hash::check($oldpassword, Auth::user()->password)) {
            if ($name != $newpassword) {
                $msg = '修改成功';
                $result = Customer::where('id', Auth::id())->update(['password' => Hash::make($newpassword)]);
            } else {
                $msg = '密码与账号相同，请重新输入';
            }
//            }
        }
        if (isset($result) && ($result)) {
            $this->logsAdd("customer",__FUNCTION__,__CLASS__,3,"修改密码",0);
            Auth::logout();
            return Response::json(['err' => 0, 'msg' => '修改成功', 'success' => 1]);
        } else {
            return Response::json(['err' => 0, 'msg' => $msg, 'success' => 0]);
        }
    }

    /**
     * 自动登陆绑定账户
     */
    public function autoLogin() {
        $bind_id = Input::get('switch_cus_id');
        if (!empty($bind_id)) {
            $user = Customer::find($bind_id);
            Auth::login($user);
            $this->logsAdd("customer",__FUNCTION__,__CLASS__,100,"自动登陆绑定账户",0);
        } else {
            $result = ['err' => 1001, 'msg' => '未绑定'];
            return Response::json($result);
        }
        if (Auth::check()) {
            Session::put('isAdmin', TRUE);
            $result = ['err' => 0, 'msg' => '/admin/index.html'];
        } else {
            $result = ['err' => 1001, 'msg' => '登录失败'];
        }
        return Response::json($result);
    }

    /**
     * 临时修改密码
     * 1、读取全部用户
     * 2、实用用户名hash加密匹配密码，确认密码与账号是否相同
     * 3、修改密码，并记录明文密码
     * $2y$10$hJXVlXIyWePAso0awrGJMunciA1FWdtrjOi9kaSsvV5u.crI/bDqq
     */
    public function tempPsw() {
        $flag = false;
        $Customer = DB::table('customer')->where('status', '1')->where('is_del', '1')->select('id', 'name', 'password')->get();
        $num = array();
        foreach ($Customer as $key => $value) {
            if (Hash::check($value->name, $value->password)) {//===密码匹配，修改密码===
                $num = rand(1000, 9999);
                $password = $value->name . $num;
                $hashpassword = Hash::make($password);
                DB::table('customer')->where('id', $value->id)->update(array('password' => $hashpassword, 'password_temp' => $password));
                $flag = true;
            }
        }
        return 1;
    }

}
