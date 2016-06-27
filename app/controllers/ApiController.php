<?php

class ApiController extends BaseController {

	//接口验证    authData
	#参数    timemap	操作时间戳
	#参数    taget    加密结果
	#返回值   boole    TURE/FALSE
	public function authData() {
		$timemap = Input::get('timemap');
		$data = md5(md5($timemap));
		$url = Config::get('url.DL_domain');
		$token = file_get_contents('http://dl2.5067.org/?module=ApiModel&action=GetHandShake&num=' . $data);
		$taget = Input::get('taget');
		$string = $token . $data;
		if (md5($string) == $taget) {
			return true;
		} else {
			return false;
		}
	}

    //用户登录    login
    #参数    id    用户id
    #返回值   无    跳转至管理页(标识为通过代理平台登录)
    public function login() {

        if ($this->authData()) {

            $name = Input::get('name');
            $id = Customer::where('name', $name)->pluck('id');
            $user = Customer::find($id);

            Auth::login($user);
            if (Auth::check()) {
                Session::put('isAdmin', TRUE);
                return Redirect::to('admin/index.html');
            } else {
                $result = ['err' => 1001, 'msg' => '登录失败'];
            }
        } else {
            $result = ['err' => 1001, 'msg' => '验证不通过'];
        }
        return Response::json($result);
    }

    //创建用户    modifyCustomer
    #参数    name    用户名
    #参数    email   用户邮箱
    #参数    ftp_address   ftp地址
    #参数    ftp_user    ftp帐号
    #参数    ftp_pwd    ftp密码
    #参数    ended_at    用户终止时间
    #参数    status    用户状态
    #返回值   data    用户id

    public function modifyCustomer() {
        if ($this->authData()) {
            $update['name'] = trim(Input::get('name'));
            $update['email'] = trim(Input::get('email'));
            $update['weburl'] = trim(Input::get('weburl'));
            $update['pc_domain'] = trim(Input::get('pc_domain'));
            $update['mobile_domain'] = trim(Input::get('mobile_domain'));
            if (trim(Input::get('pc_tpl_id')) == '0') {
                $update['pc_tpl_num'] = 1;
            } else {

                $update['pc_tpl_num'] = trim(Input::get('pc_tpl_id'));
            }
            if (trim(Input::get('mobile_tpl_id')) == '0') {
                $update['mobile_tpl_num'] = 1;
            } else {
                $update['mobile_tpl_num'] = trim(Input::get('mobile_tpl_id'));
            }
            $update['stage'] = trim(Input::get('stage'));
            $update['ftp'] = trim(Input::get('ftp'));
            $update['ftp_port'] = trim(Input::get('ftp_port'));
            $update['ftp_dir'] = trim(Input::get('ftp_dir'));
            $update['ftp_address'] = trim(Input::get('ftp_address'));
            $update['ftp_user'] = trim(Input::get('ftp_user'));
            $update['ftp_pwd'] = trim(Input::get('ftp_pwd'));
            $update['ended_at'] = trim(Input::get('ended_at'));
            $update['status'] = Input::get('status');
            $update['customization'] = Input::get('customization');
            //===不能用update数组，因为Customer表中没有capacity/capacity_use字段===
            $capacity = Input::get('capacity') ? trim(Input::get('capacity')) : 100 * 1024 * 1024; //默认100MB
            $cus_id = Customer::where('name', $update['name'])->pluck('id');
            if ($cus_id) {
                //修改操作
                $coustomer_old = Customer::where('id', $cus_id)->first();
                $save = Customer::where('id', $cus_id)->update($update);
                $pc_id = Template::where('tpl_num', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                $mobile_id = Template::where('tpl_num', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                $pc_templateid = Template::where('cus_id', $cus_id)->where('type', 1)->pluck('id');
                $mobile_templateid = Template::where('cus_id', $cus_id)->where('type', 2)->pluck('id');
                if ($pc_templateid != NULL) {
                    WebsiteInfo::where('cus_id', $cus_id)->update(['pc_tpl_id' => $pc_templateid]);
                } else {
                    WebsiteInfo::where('cus_id', $cus_id)->update(['pc_tpl_id' => $pc_id]);
                }
                if ($mobile_templateid != NULL) {
                    WebsiteInfo::where('cus_id', $cus_id)->update(['mobile_tpl_id' => $mobile_templateid]);
                } else {
                    WebsiteInfo::where('cus_id', $cus_id)->update(['mobile_tpl_id' => $mobile_id]);
                }
                //WebsiteInfo::where('cus_id',$cus_id)->update(['pc_tpl_id'=>$pc_id,'mobile_tpl_id'=>$mobile_id]);
                //===更新CustomerInfo时，更新capacity字段===
                CustomerInfo::where('cus_id', $cus_id)->update(['pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'], 'capacity' => $capacity]);

                if ($update['stage'] != $coustomer_old['stage'] or $update['pc_domain'] != $coustomer_old['pc_domain'] or $update['mobile_domain'] != $coustomer_old['mobile_domain']) {
                    $common = new CommonController();
                    @$common->postsend(trim($update['weburl'], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'], 'stage_old' => $coustomer_old['stage'], 'pc_domain_old' => $coustomer_old['pc_domain'], 'mobile_domain_old' => $coustomer_old['mobile_domain']));
                }

                if ($save) {
                    $result = ['err' => 1000, 'msg' => '更新用户成功'];
                } else {
                    $result = ['err' => 1002, 'msg' => '更新用户失败'];
                }
            } else {
                //增加操作
                $update['password'] = Hash::make($update['name']);
                $insert_id = Customer::insertGetId($update);
                if ($insert_id) {
                    $pc_id = Template::where('tpl_num', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                    $mobile_id = Template::where('tpl_num', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                    WebsiteInfo::insert(['cus_id' => $insert_id, 'pc_tpl_id' => $pc_id, 'mobile_tpl_id' => $mobile_id]);
                    CustomerInfo::insert(['cus_id' => $insert_id, 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'], 'capacity' => $capacity, 'capacity_use' => 0]);

                    //创建客户目录
                    mkdir(public_path('customers/' . $update['name']));
                    mkdir(public_path('customers/' . $update['name']) . '/detail');
                    mkdir(public_path('customers/' . $update['name']) . '/category');
                    mkdir(public_path('customers/' . $update['name']) . '/images');
                    mkdir(public_path('customers/' . $update['name']) . '/images/l');
                    mkdir(public_path('customers/' . $update['name']) . '/images/l/category');
                    mkdir(public_path('customers/' . $update['name']) . '/images/l/articles');
                    mkdir(public_path('customers/' . $update['name']) . '/images/l/common');
                    mkdir(public_path('customers/' . $update['name']) . '/images/l/page_index');
                    mkdir(public_path('customers/' . $update['name']) . '/images/s');
                    mkdir(public_path('customers/' . $update['name']) . '/images/s/category');
                    mkdir(public_path('customers/' . $update['name']) . '/images/s/articles');
                    mkdir(public_path('customers/' . $update['name']) . '/images/s/common');
                    mkdir(public_path('customers/' . $update['name']) . '/images/s/page_index');
                    mkdir(public_path('customers/' . $update['name']) . '/images/ueditor');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/detail');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/category');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/l');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/l/category');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/l/articles');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/l/common');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/l/page_index');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/s');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/s/category');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/s/articles');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/s/common');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/s/page_index');
                    mkdir(public_path('customers/' . $update['name']) . '/mobile/images/ueditor');

                    $ftp_array = explode(':', $update['ftp_address']);
                    $port = $update['ftp_port'];
                    $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
                    $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
                    if ($conn) {
                        if (trim(Input::get('ftp')) == '1') {
                            ftp_login($conn, $update['ftp_user'], $update['ftp_pwd']);
                            ftp_mkdir($conn, $update['name']);
                            ftp_mkdir($conn, $update['name'] . '/images');
                            ftp_mkdir($conn, $update['name'] . '/detail');
                            ftp_mkdir($conn, $update['name'] . '/category');
                            ftp_mkdir($conn, $update['name'] . '/images/ueditor');
                            ftp_mkdir($conn, $update['name'] . '/images/l');
                            ftp_mkdir($conn, $update['name'] . '/images/l/category');
                            ftp_mkdir($conn, $update['name'] . '/images/l/articles');
                            ftp_mkdir($conn, $update['name'] . '/images/l/common');
                            ftp_mkdir($conn, $update['name'] . '/images/l/page_index');
                            ftp_mkdir($conn, $update['name'] . '/images/s');
                            ftp_mkdir($conn, $update['name'] . '/images/s/category');
                            ftp_mkdir($conn, $update['name'] . '/images/s/articles');
                            ftp_mkdir($conn, $update['name'] . '/images/s/common');
                            ftp_mkdir($conn, $update['name'] . '/images/s/page_index');
                            ftp_mkdir($conn, $update['name'] . '/mobile');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images');
                            ftp_mkdir($conn, $update['name'] . '/mobile/detail');
                            ftp_mkdir($conn, $update['name'] . '/mobile/category');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/ueditor');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/l');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/l/category');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/l/articles');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/l/common');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/l/page_index');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/s');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/s/category');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/s/articles');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/s/common');
                            ftp_mkdir($conn, $update['name'] . '/mobile/images/s/page_index');

                            ftp_close($conn);
                        } else {
                            ftp_login($conn, $update['ftp_user'], $update['ftp_pwd']);
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/detail');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/category');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/ueditor');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/l');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/l/category');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/l/articles');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/l/common');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/l/page_index');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/s');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/s/category');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/s/articles');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/s/common');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/images/s/page_index');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/detail');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/category');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/ueditor');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/l');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/l/category');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/l/articles');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/l/common');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/l/page_index');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/s');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/s/category');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/s/articles');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/s/common');
                            ftp_mkdir($conn, $update['ftp_dir'] . '/mobile/images/s/page_index');
                            ftp_close($conn);
                        }
                    }
                    $common = new CommonController();
                    @$common->postsend(trim($update['weburl'], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain']));
                    $result = ['err' => 1000, 'msg' => '创建用户成功'];
                } else {
                    $result = ['err' => 1001, 'msg' => '创建用户失败'];
                }
            }
        } else {
            $result = ['err' => 1003, 'msg' => '验证信息不正确'];
        }
        return Response::json($result);
    }

    //删除用户
    #参数    name    用户名
    #返回值   boole    TURE/FALSE
    public function deleteCustomer() {
        if ($this->authData()) {

            $name = Input::get('name');
            $cus_id = Customer::where('name', $name)->pluck('id');
            $delete = Customer::where('name', $name)->delete();
            if ($delete) {
                $result = ['err' => 1000, 'msg' => '删除用户成功'];
                WebsiteInfo::where('cus_id', $cus_id)->delete();
                CustomerInfo::where('cus_id', $cus_id)->delete();
            } else {
                $result = ['err' => 1001, 'msg' => '删除用户失败'];
            }
        } else {
            $result = ['err' => 1002, 'msg' => '验证不通过'];
        }
        return Response::json($result);
    }

}
