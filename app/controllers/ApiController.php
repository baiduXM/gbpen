<?php

class ApiController extends BaseController
{

    /**
     * 接口验证    authData
     * @param type timemap      操作时间戳
     * @param type taget        加密结果
     * @return boolean
     */
    public function authData()
    {
        $timemap = Input::get('timemap');
        $data = md5(md5($timemap));
        $url = Config::get('url.DL_domain');
        $token = file_get_contents('http://' . DAILI_DOMAIN . '/?module=ApiModel&action=GetHandShake&num=' . $data);
        $taget = Input::get('taget');
        $string = $token . $data;
        if (md5($string) == $taget) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 用户登录    login
     * @param type id       用户id
     * @return type null    跳转至管理页(标识为通过代理平台登录)
     */
    public function login()
    {
        if ($this->authData()) {
            $name = Input::get('name');
            $id_del = Customer::where('name', $name)->where('is_del', '0')->pluck('id');
            if (!$id_del) {
                $id = Customer::where('name', $name)->where('is_del', '1')->pluck('id');
                $user = Customer::find($id);
                Auth::login($user);
                if (Auth::check()) {
                    $this->logsAdd("customer", __FUNCTION__, __CLASS__, 100, "用户登录", 1);
                    Session::put('isAdmin', TRUE);//统一双站切换也有该标识
                    Session::put('isDaili', TRUE);//由代理登录
                    return Redirect::to('admin/index.html');
                } else {
                    $result = ['err' => 1001, 'msg' => '登录失败'];
                }
            } else {
                $result = ['err' => 1001, 'msg' => '用户已删除'];
            }
        } else {
            $result = ['err' => 1001, 'msg' => '验证不通过'];
        }
        return Response::json($result);
    }

    /**
     * 创建用户    modifyCustomer
     * @param type name             用户名
     * @param type email            用户邮箱
     * @param type ftp_address      ftp地址
     * @param type ftp_user         ftp帐号
     * @param type ftp_pwd          ftp密码
     * @param type ended_at         用户终止时间
     * @param type status           用户状态
     * @return type data            用户id
     */
    public function modifyCustomer()
    {
        if ($this->authData()) {
            $update['name'] = trim(Input::get('name'));
            $update['email'] = trim(Input::get('email'));
            $update['weburl'] = trim(Input::get('weburl'));
            $update['pc_domain'] = trim(Input::get('pc_domain'));
            $update['mobile_domain'] = trim(Input::get('mobile_domain'));
            if (trim(Input::get('pc_tpl_id')) == '0') {
                // $update['pc_tpl_num'] = 1;
                $update['pc_tpl_num'] = 'GP0001';
            } else {

                $update['pc_tpl_num'] = trim(Input::get('pc_tpl_id'));
            }
            if (trim(Input::get('mobile_tpl_id')) == '0') {
                // $update['mobile_tpl_num'] = 1;
                $update['mobile_tpl_num'] = 'GM0001';
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

            //获取ftp_b信息
            $update['ftp_address_b'] = trim(Input::get('ftp_address_b'));
            $update['ftp_user_b'] = trim(Input::get('ftp_user_b'));
            $update['ftp_pwd_b'] = trim(Input::get('ftp_pwd_b'));

            //获取外域PC，手机域名
            $update['pc_out_domain'] = trim(Input::get('pc_out_domain'));
            $update['mobile_out_domain'] = trim(Input::get('mobile_out_domain'));

            //是否允许用户自定义栏目
            $update['column_on'] = trim(Input::get('column_on'));
            //其他域名
            $mobile_other = trim(Input::get('mobile_other'))?trim(Input::get('mobile_other')):'';

            //===绑定账户===
            $switch_cus_name = Input::get('switch_cus_name');
            if (!empty($switch_cus_name)) {
                $update['switch_cus_id'] = Customer::where('name', $switch_cus_name)->pluck('id');
            } else {
                $update['switch_cus_id'] = 0;
            }
            //===end===
            //===不能用update数组，因为Customer表中没有capacity/capacity_use字段===
            $capacity = Input::get('capacity') ? trim(Input::get('capacity')) : 300 * 1024 * 1024; //默认100MB
            $cus_id = Customer::where('name', $update['name'])->pluck('id');
            if ($cus_id) {
                //修改操作
                $coustomer_old = Customer::where('id', $cus_id)->first();
                $save = Customer::where('id', $cus_id)->update($update);
                //===新旧===
                if(preg_match('/G\d{4}P(CN|EN|TW|JP)\d{2}/',$update['pc_tpl_num'])){
                    $pc_id = Template::where('name', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                }elseif(preg_match('/GP\d{4}/',$update['pc_tpl_num'])){
                    $pc_id = Template::where('name_bak', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                }
                if(preg_match('/G\d{4}M(CN|EN|TW|JP)\d{2}/',$update['mobile_tpl_num'])){
                    $mobile_id = Template::where('name', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                }elseif(preg_match('/GM\d{4}/',$update['mobile_tpl_num'])){
                    $mobile_id = Template::where('name_bak', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                }
                //===新旧===
                // $pc_id = Template::where('tpl_num', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                // $mobile_id = Template::where('tpl_num', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
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
                CustomerInfo::where('cus_id', $cus_id)->update(['pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'],'pc_out_domain' => $update['pc_out_domain'],'mobile_out_domain' => $update['mobile_out_domain'], 'capacity' => $capacity]);
                if ($update['stage'] != $coustomer_old['stage'] or $update['pc_domain'] != $coustomer_old['pc_domain'] or $update['mobile_domain'] != $coustomer_old['mobile_domain']) {
                    //域名绑定
                    if($update['ftp'] == 2){//35开户的PC绑定由35处理
                        //生成手机站绑定文件
                        if($mobile_other){
                            $str = $this->webConfig($mobile_other);
                            @file_put_contents(public_path('customers/' . $update['name']) . '/web.config', $str);
                            //上传绑定文件到35空间
                            $ftp_array = explode(':', $update['ftp_address']);
                            $port = $update['ftp_port'];
                            $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
                            $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
                            ftp_login($conn, $update['ftp_user'], $update['ftp_pwd']);
                            ftp_pasv($conn, 1);
                            @ftp_put($conn,$update['ftp_dir'] . '/web.config',public_path('customers/' . $update['name']) . '/web.config', FTP_ASCII);
                            ftp_close($conn);
                        }
                        
                    }else{
                        //获取绑定ip地址
                        $ftp_array = explode(':', $update['ftp_address']);
                        $common = new CommonController();
                        @$common->postsend(trim($ftp_array[0], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'], 'stage_old' => $coustomer_old['stage'], 'pc_domain_old' => $coustomer_old['pc_domain'], 'mobile_domain_old' => $coustomer_old['mobile_domain']));
                    }
                    
                    //如果有ftp_b(非公司ftp不考虑)
                    if($update['ftp_address_b']){
                        $ftp_array_b = explode(':', $update['ftp_address_b']);
                        @$common->postsend(trim($ftp_array_b[0], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'], 'stage_old' => $coustomer_old['stage'], 'pc_domain_old' => $coustomer_old['pc_domain'], 'mobile_domain_old' => $coustomer_old['mobile_domain']));
                    }
                }
                //如果有ftp_b
                // if($update['ftp_address_b']){
                //         $ftp_array_b = explode(':', $update['ftp_address_b']);
                //         if ($update['stage'] != $coustomer_old['stage'] or $update['pc_domain'] != $coustomer_old['pc_domain'] or $update['mobile_domain'] != $coustomer_old['mobile_domain']) {
                //         $common = new CommonController();
                //         @$common->postsend(trim($ftp_array_b[0], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'], 'stage_old' => $coustomer_old['stage'], 'pc_domain_old' => $coustomer_old['pc_domain'], 'mobile_domain_old' => $coustomer_old['mobile_domain']));
                //     }
                // }
                if ($save) {
                    $result = ['err' => 1000, 'msg' => '更新用户成功'];
                } else {
                    $result = ['err' => 1002, 'msg' => '更新用户失败'];
                }
            } else {
                //增加操作
                $password = Input::get("password");
                if ($password != null) {
                    $update['password'] = Hash::make($password);
                } else {
                    $update['password'] = Hash::make($update['name']);
                }
                $insert_id = Customer::insertGetId($update);
                if ($insert_id) {
                    //===新旧===
                    if(preg_match('/G\d{4}P(CN|EN|TW|JP)\d{2}/',$update['pc_tpl_num'])){
                        $pc_id = Template::where('name', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                    }elseif(preg_match('/GP\d{4}/',$update['pc_tpl_num'])){
                        $pc_id = Template::where('name_bak', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                    }
                    if(preg_match('/G\d{4}M(CN|EN|TW|JP)\d{2}/',$update['mobile_tpl_num'])){
                        $mobile_id = Template::where('name', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                    }elseif(preg_match('/GM\d{4}/',$update['mobile_tpl_num'])){
                        $mobile_id = Template::where('name_bak', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                    }
                    //===新旧===
                    // $pc_id = Template::where('tpl_num', $update['pc_tpl_num'])->where('type', 1)->pluck('id');
                    // $mobile_id = Template::where('tpl_num', $update['mobile_tpl_num'])->where('type', 2)->pluck('id');
                    WebsiteInfo::insert(['cus_id' => $insert_id, 'pc_tpl_id' => $pc_id, 'mobile_tpl_id' => $mobile_id]);
                    CustomerInfo::insert(['cus_id' => $insert_id, 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain'],'pc_out_domain' => $update['pc_out_domain'],'mobile_out_domain' => $update['mobile_out_domain'], 'capacity' => $capacity, 'capacity_use' => 0]);

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
                            //35开户手机站绑定方式(PC站绑定由35处理)
                            if($update['ftp'] == 2){
                                //生成手机站绑定文件
                                if($mobile_other){
                                    $str = $this->webConfig($mobile_other);
                                    @file_put_contents(public_path('customers/' . $update['name']) . '/web.config', $str);
                                    //上传绑定文件到35空间
                                    ftp_pasv($conn, 1);
                                    @ftp_put($conn,$update['ftp_dir'] . '/web.config',public_path('customers/' . $update['name']) . '/web.config', FTP_ASCII);
                                }                                
                            }
                            ftp_close($conn);
                        }
                        //使用公司空间的绑定方式
                        if($update['ftp'] != 2){
                            $common = new CommonController();
                            @$common->postsend(trim($ftp_array[0], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain']));
                        }                                                    
                        $this->logsAdd("customer", __FUNCTION__, __CLASS__, 1, "创建用户", 1);
                        $result = ['err' => 1000, 'msg' => '创建用户成功'];
                    } else {
                        $result = ['err' => 1001, 'msg' => '创建用户失败,创建文件失败'];
                    }

                    //判断是否有ftp_b，有则在该ftp上再建目录
                    if($update['ftp_address_b']){
                        $ftp_array_b = explode(':', $update['ftp_address_b']);
                        $ftp_array_b[1] = isset($ftp_array_b[1]) ? $ftp_array_b[1] : $port;
                        $conn_b = ftp_connect($ftp_array_b[0], $ftp_array_b[1]);
                        if($conn_b){
                            if (trim(Input::get('ftp')) == '1'){
                                ftp_login($conn_b, $update['ftp_user_b'], $update['ftp_pwd_b']);
                                ftp_mkdir($conn_b, $update['name']);
                                ftp_mkdir($conn_b, $update['name'] . '/images');
                                ftp_mkdir($conn_b, $update['name'] . '/detail');
                                ftp_mkdir($conn_b, $update['name'] . '/category');
                                ftp_mkdir($conn_b, $update['name'] . '/images/ueditor');
                                ftp_mkdir($conn_b, $update['name'] . '/images/l');
                                ftp_mkdir($conn_b, $update['name'] . '/images/l/category');
                                ftp_mkdir($conn_b, $update['name'] . '/images/l/articles');
                                ftp_mkdir($conn_b, $update['name'] . '/images/l/common');
                                ftp_mkdir($conn_b, $update['name'] . '/images/l/page_index');
                                ftp_mkdir($conn_b, $update['name'] . '/images/s');
                                ftp_mkdir($conn_b, $update['name'] . '/images/s/category');
                                ftp_mkdir($conn_b, $update['name'] . '/images/s/articles');
                                ftp_mkdir($conn_b, $update['name'] . '/images/s/common');
                                ftp_mkdir($conn_b, $update['name'] . '/images/s/page_index');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/detail');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/category');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/ueditor');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/l');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/l/category');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/l/articles');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/l/common');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/l/page_index');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/s');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/s/category');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/s/articles');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/s/common');
                                ftp_mkdir($conn_b, $update['name'] . '/mobile/images/s/page_index');

                                ftp_close($conn_b);
                            }
                            $common = new CommonController();
                            @$common->postsend(trim($ftp_array_b[0], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain']));
                            $this->logsAdd("customer", __FUNCTION__, __CLASS__, 1, "备用ftp上创建用户", 1);
                            $result = ['err' => 1000, 'msg' => '备用ftp上创建用户成功'];
                        }
                    }
                } else {
                    $result = ['err' => 1001, 'msg' => '创建用户失败'];
                }
            }
        } else {
            $result = ['err' => 1003, 'msg' => '验证信息不正确'];
        }
        return Response::json($result);
    }

    /**
     * 删除用户
     * @param type name 用户名
     * @return boole TURE/FALSE
     */
//    public function deleteCustomer() {
//        if ($this->authData()) {
//            $name = Input::get('name');
//            $result = $this->deletemytest($name);
//            $cus_id = Customer::where('name', $name)->pluck('id');
//            $delete = Customer::where('name', $name)->delete();
//            if ($delete) {
//                $result = ['err' => 1000, 'msg' => '删除用户成功'];
//                WebsiteInfo::where('cus_id', $cus_id)->delete();
//                CustomerInfo::where('cus_id', $cus_id)->delete();
//            } else {
//                $result = ['err' => 1001, 'msg' => '删除用户失败'];
//            }
//        } else {
//            $result = ['err' => 1002, 'msg' => '验证不通过'];
//        }
//        return Response::json($result);
//    }

    /**
     * 删除文件的备份和数据库保存
     * @param type name 用户名
     * @return boole TURE/FALSE
     */
    public function deleteCustomer()
    {
        if ($this->authData()) {
            $result = '';
            $name = Input::get('username');
            header("Content-type: text/html; charset=utf-8");
//            $name = $_GET['username'];
            $Customer = Customer::where('name', $name)->get();
            $cus_id = $Customer[0]['id'];
            if (!$cus_id) {
                return Response::json(['err' => 1004, 'msg' => '用户不存在']);
                exit();
            }
            $WebsiteInfo = WebsiteInfo::where('cus_id', $cus_id)->get();
            $CustomerInfo = CustomerInfo::where('cus_id', $cus_id)->get();
            $handle_a = opendir(public_path("customers/" . $name . "/images"));
            $handle_b = opendir(public_path("customers/" . $name . "/mobile/images"));
            if ($handle_a) {
                closedir($handle_a);
            } else {
                return Response::json(['err' => 1004, 'msg' => '用户images文件夹不存在']);
                exit();
            }
            if ($handle_b) {
                closedir($handle_b);
            } else {
                return Response::json(['err' => 1004, 'msg' => '用户mobile/images文件夹不存在']);
                exit();
            }
            $Customer = $Customer[0];
            $db = new PDO('sqlite:sqlite_1.db');
            if ($db) {
                $conn = @ftp_connect($Customer['ftp_address'], $Customer['ftp_port']);
                if (!$conn) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
                    exit();
                }
                if (!@ftp_login($conn, $Customer['ftp_user'], $Customer['ftp_pwd'])) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
                    exit();
                }
                

                //保存数据库
                $sql = "INSERT INTO customer (id,name,email,password,password_temp,remember_token,weburl,serv_id,ftp,ftp_address,ftp_port,ftp_user,ftp_pwd,ftp_dir,pc_tpl_id,mobile_tpl_id,pc_domain,mobile_domain,ended_at,status,created_at,updated_at,pc_end_time,mobile_end_time,color_id,switch_cus_id,customization,del_time) "
                    . "values('" . $Customer['id'] . "','" . $Customer['name'] . "','" . $Customer['email'] . "','" . $Customer['password'] . "','" . $Customer['password_temp'] . "','" . $Customer['remember_token'] . "','"
                    . $Customer['weburl'] . "','" . $Customer['serv_id'] . "','" . $Customer['ftp'] . "','" . $Customer['ftp_address'] . "','" . $Customer['ftp_port'] . "','" . $Customer['ftp_user'] . "','" . $Customer['ftp_pwd'] . "','" . $Customer['ftp_dir'] . "','"
                    . $Customer['pc_tpl_id'] . "','" . $Customer['mobile_tpl_id'] . "','" . $Customer['pc_domain'] . "','" . $Customer['mobile_domain'] . "','" . $Customer['ended_at'] . "','" . $Customer['status'] . "','" . $Customer['created_at'] . "','" . $Customer['updated_at'] . "','" . $Customer['pc_end_time'] . "','" . $Customer['mobile_end_time'] . "','" . $Customer['color_id'] . "','" . $Customer['switch_cus_id'] . "','" . $Customer['customization'] . "','" . time() . "')";
                $ret = $db->exec($sql);
                if ($WebsiteInfo->count()) {
                    $WebsiteInfo = $WebsiteInfo[0];
                    $sql = "INSERT INTO website_info (id,cus_id,pc_tpl_id,mobile_tpl_id,pc_color_id,mobile_color_id,pc_htpl_id,mobile_htpl_id,pc_hcolor_id,mobile_hcolor_id,pushed,del_time) values('"
                        . $WebsiteInfo['id'] . "','" . $WebsiteInfo['cus_id'] . "','" . $WebsiteInfo['pc_tpl_id'] . "','" . $WebsiteInfo['mobile_tpl_id'] . "','" . $WebsiteInfo['pc_color_id'] . "','" . $WebsiteInfo['mobile_color_id']
                        . "','" . $WebsiteInfo['pc_htpl_id'] . "','" . $WebsiteInfo['mobile_htpl_id'] . "','" . $WebsiteInfo['pc_hcolor_id'] . "','" . $WebsiteInfo['mobile_hcolor_id'] . "','" . $WebsiteInfo['pushed'] . "','" . time() . "')";
                    $ret1 = $db->exec($sql);
                }
                if ($CustomerInfo->count()) {
                    $CustomerInfo = $CustomerInfo[0];
                    $sql = "INSERT INTO customer_info (id,cus_id,company,pc_domain,mobile_domain,favicon,logo,logo_small,title,keywords,description,pc_header_script,mobile_header_script,footer,mobile_footer,pc_footer_script,mobile_footer_script,pc_page_count,pc_page_links,mobile_page_count,mobile_page_links,telephone,mobile,address,fax,"
                        . "email,qq,contact_name,pushed_at,created_at,updated_at,pc_page_imgtxt_count,pc_page_img_count,pc_page_txt_count,pc_page_count_switch,enlarge,floatadv,pushed,lang,copyright,capacity_use,capacity,lastpushtime,init_capacity,is_openmember,background_music,talent_support,del_time) values('"
                        . $CustomerInfo['id'] . "','" . $CustomerInfo['cus_id'] . "','" . $CustomerInfo['company'] . "','" . $CustomerInfo['pc_domain'] . "','" . $CustomerInfo['mobile_domain'] . "','" . $CustomerInfo['favicon']
                        . "','" . $CustomerInfo['logo'] . "','" . $CustomerInfo['logo_small'] . "','" . $CustomerInfo['title'] . "','" . $CustomerInfo['keywords'] . "','" . $CustomerInfo['description']
                        . "','" . $CustomerInfo['pc_header_script'] . "','" . $CustomerInfo['mobile_header_script'] . "','" . $CustomerInfo['footer'] . "','" . $CustomerInfo['mobile_footer'] . "','" . $CustomerInfo['pc_footer_script'] . "','" . $CustomerInfo['mobile_footer_script'] . "','" . $CustomerInfo['pc_page_count']
                        . "','" . $CustomerInfo['pc_page_links'] . "','" . $CustomerInfo['mobile_page_count'] . "','" . $CustomerInfo['mobile_page_links'] . "','" . $CustomerInfo['telephone'] . "','" . $CustomerInfo['mobile'] . "','" . $CustomerInfo['address'] . "','" . $CustomerInfo['fax']
                        . "','" . $CustomerInfo['email'] . "','" . $CustomerInfo['qq'] . "','" . $CustomerInfo['contact_name'] . "','" . $CustomerInfo['pushed_at'] . "','" . $CustomerInfo['created_at'] . "','" . $CustomerInfo['updated_at'] . "','" . $CustomerInfo['pc_page_imgtxt_count']
                        . "','" . $CustomerInfo['pc_page_img_count'] . "','" . $CustomerInfo['pc_page_txt_count'] . "','" . $CustomerInfo['pc_page_count_switch'] . "','" . $CustomerInfo['enlarge'] . "','" . $CustomerInfo['floatadv'] . "','" . $CustomerInfo['pushed'] . "','" . $CustomerInfo['lang']
                        . "','" . $CustomerInfo['copyright'] . "','" . $CustomerInfo['capacity_use'] . "','" . $CustomerInfo['capacity'] . "','" . $CustomerInfo['lastpushtime'] . "','" . $CustomerInfo['init_capacity'] . "','" . $CustomerInfo['is_openmember'] . "','" . $CustomerInfo['background_music'] . "','" . $CustomerInfo['talent_support'] . "','" . time() . "')";

                    $ret2 = $db->exec($sql);
                }

                if ($ret && $ret1 && $ret2) {
//                    echo "数据库备份成功！<br/>";
                    $update['is_del'] = 0;
                    Customer::where('id', $cus_id)->update($update);
                    WebsiteInfo::where('cus_id', $cus_id)->update($update);
                    CustomerInfo::where('cus_id', $cus_id)->update($update);
//                    echo "开始备份文件<br/>";
                    $zip = new ZipArchive();
                    if ($zip->open(public_path("customers_backups/" . $name . '.zip'), ZipArchive::OVERWRITE) === TRUE) {
                        //备份文件
                        $this->addFileToZip(public_path("customers/" . $name . "/images"), $zip);
                        $zip->addEmptyDir("mobile");
                        $this->addFileToZip(public_path("customers/" . $name . "/mobile/images"), $zip, "mobile");
                        $zip->close();
                        $dir = "./customers/" . $name; // 文件夹的名称
                        if ($this->delUserFile($dir)) {
//                            echo "文件备份完成！<br/>";
//                            echo "开始删除文件<br/>";

                            @ftp_pasv($conn, 1); // 打开被动模拟
                            $this->userFtpDel($conn, './' . $name, $name);
                            @ftp_rename($conn, $name, "beifen_" . $name);
                            @ftp_close($conn);

                            //ftp_b是不是连接，是就同时删除ftp_b的文件
                            if($Customer['ftp_address_b']){
                                $conn_b = @ftp_connect($Customer['ftp_address_b'], $Customer['ftp_port']);
                                if (!$conn_b) {
                                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器连接失败']);
                                    exit();
                                }
                                if (!@ftp_login($conn_b, $Customer['ftp_user_b'], $Customer['ftp_pwd_b'])) {
                                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器登陆失败']);
                                    exit();
                                }
                                if($conn_b){
                                    @ftp_pasv($conn_b, 1); 
                                    $this->userFtpDel($conn_b, './'.$name, $name);
                                    @ftp_rename($conn_b,$name,"beifen_".$name);
                                    @ftp_close($conn_b); 
                                }
                            }
                            
                            
//                            echo "删除完成！";
                            $this->logsAdd("customer", __FUNCTION__, __CLASS__, 999, "删除用户(备份)", 1);
                            $result = ['err' => 1000, 'msg' => '删除用户成功'];
                        } else {
                            return Response::json(['err' => 1002, 'msg' => '文件备份失败']);
                        }
                    } else {
//                        echo "数据库备份失败";
                        return Response::json(['err' => 1004, 'msg' => '数据库备份失败1']);
                    }
                } else {
                    return Response::json(['err' => 1004, 'msg' => '数据库备份失败2']);
                }
            } else {
                return Response::json(['err' => 1004, 'msg' => '数据库备份失败3']);
            }
        } else {
            $result = ['err' => 1002, 'msg' => '验证不通过'];
        }
        return Response::json($result);
    }

    /**
     * 压缩保存用户文件
     * @param string $path 文件路径
     * @param ZipArchive $zip zip对象
     * @param string $array 要压缩在压缩包中的位置
     * @return null
     */
    protected function addFileToZip($path, $zip, $array = '')
    {
        $linshi = explode('/', $path);
        $linshi = end($linshi);
        if ($array) {
            $array = $array . '/' . $linshi;
        } else {
            $array = $linshi;
        }
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    if (count(@scandir($path . "/" . $filename)) == 2) {
                        $zip->addEmptyDir($array . '/' . $filename);
                    } else {
                        $this->addFileToZip($path . "/" . $filename, $zip, $array);
                    }
                } else { //将文件加入zip对象
                    $zip->addFile($path . "/" . $filename, $array . '/' . $filename);
                }
            }
//                else { //将文件加入zip对象
//                    $zip->addFile($path . "/" . $filename, $array . '/' . $filename);
//                }
        }
        @closedir($path);
    }

    /**
     * 代理平台下载模板
     * @param type name 用户名
     * @return string
     */
    public function DownloadTemplate()
    {
        if (Input::has("token") && Input::get("token") == md5("linshimingma")) {
            $template = new TemplatesController();
            return $template->downloadTemplate(Input::get("name"));
        } else {
            return json_encode(array("err" => 1, "msg" => "err！"));
        }
    }

    /**
     * 删除用户文件
     * @param string $path 文件路径
     * @return null
     */
    protected function delUserFile($dir)
    {
        if ($handle = opendir($dir)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir($dir . "/" . $item)) {
                        $this->delUserFile($dir . "/" . $item);
                    } else {
                        if (unlink($dir . "/" . $item)) {
//                                echo "成功删除文件： $dir/$item<br />";
                        }
                    }
                }
            }
            closedir($handle);
            rmdir($dir);
            return true;
        }
    }

    /**
     * 删除用户FTP文件和目录，保留images和mobile/images
     * @param ftp_connect $conn ftp对象
     * @param string $dir 路径
     * @param string $username 用户名
     * @return null
     */
    protected function userFtpDel($conn, $dir, $username)
    {
        if (!stripos($dir, $username . '/images') && !stripos($dir, 'mobile/images')) {
            $filelist = ftp_rawlist($conn, $dir);
            foreach ($filelist as $file) {
                $filename = preg_replace("/.+[:]*\\d+\\s/", "", $file);
                if ($filename !== '.' && $filename !== '..') {
                    if (stripos($filename, '.')) {
                        @ftp_delete($conn, $dir . '/' . $filename);
                    } else {
                        if (@ftp_rmdir($conn, $dir . '/' . $filename)) {
                            continue;
                        } else {
                            $this->userFtpDel($conn, $dir . '/' . $filename, $username);
                            @ftp_rmdir($conn, $dir . '/' . $filename);
                        }
                    }
                }
            }
        } else {
            return true;
        }
    }

    /**
     * 还原用户
     * @param ftp_connect $conn ftp对象
     * @param string $dir 路径
     * @param string $username 用户名
     * @return null
     */
    public function reductionCustomer()
    {
        if ($this->authData()) {
            $result = '';
            $name = Input::get('username');

//            $name = Input::get('name');
            $Customer = Customer::where('name', $name)->where('is_del', 0)->get();

            if (!isset($Customer[0])) {
                return Response::json(['err' => 1004, 'msg' => '用户不存在或未删除']);
                exit();
            }

            //修改静态服务器文件名
            $conn = @ftp_connect($Customer[0]['ftp_address'], $Customer[0]['ftp_port']);
            if (!$conn) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
                exit();
            }
            if (!@ftp_login($conn, $Customer[0]['ftp_user'], $Customer[0]['ftp_pwd'])) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
                exit();
            }
            @ftp_pasv($conn, 1); // 打开被动模拟
            if (!@ftp_rename($conn, "beifen_" . $name, $name)) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器静态没有备份']);
                exit();
            }
            @ftp_close($conn);

            //判断有无ftp_b，有就修改ftp_b文件名
            if($Customer[0]['ftp_address_b']){
                $conn_b = @ftp_connect($Customer[0]['ftp_address_b'], $Customer[0]['ftp_port']);
                if (!$conn_b) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器连接失败']);
                    exit();
                }
                if (!@ftp_login($conn_b, $Customer[0]['ftp_user_b'], $Customer[0]['ftp_pwd_b'])) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器登陆失败']);
                    exit();
                }
                @ftp_pasv($conn_b, 1); // 打开被动模拟
                if(!@ftp_rename($conn_b,"beifen_".$name,$name)){
                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器静态没有备份']);
                    exit();
                }
                @ftp_close($conn_b);
            }


            //解压备份的图片文件
            $zip = new ZipArchive();

            if (!is_dir((public_path("customers/" . $name)))) {
                mkdir("customers/" . $name);
            }
            if ($zip->open(public_path("customers_backups/" . $name . '.zip')) === TRUE) {
                $zip->extractTo(public_path("customers/" . $name));
                $zip->close();
            } else {
                return Response::json(['err' => 1004, 'msg' => '解压失败']);
                exit();
            }
            if ($zip->open(public_path("packages/customernull.zip")) === TRUE) {
                $zip->extractTo(public_path("customers/" . $name));
            } else {
                return Response::json(['err' => 1004, 'msg' => '解压失败2']);
                exit();
            }
            $zip->close();

            //更新数据库
            $update['is_del'] = 1;
            $cus_id = $Customer[0]['id'];
            $WebsiteInfo = WebsiteInfo::where('cus_id', $cus_id)->where('is_del', 0)->get();
            if ($WebsiteInfo) {
                WebsiteInfo::where('cus_id', $cus_id)->update($update);
            }
            $CustomerInfo = CustomerInfo::where('cus_id', $cus_id)->where('is_del', 0)->get();
            if ($CustomerInfo) {
                CustomerInfo::where('cus_id', $cus_id)->update($update);
            }
            Customer::where('id', $cus_id)->update(array('is_del' => 1, 'status' => 1));

            unlink(public_path("customers_backups/" . $name . ".zip"));
            $this->logsAdd("customer", __FUNCTION__, __CLASS__, 999, "还原用户", 1);
            $result = ['err' => 1000, 'msg' => '还原用户成功'];
        } else {
            $result = ['err' => 1002, 'msg' => '验证不通过'];
        }
        return Response::json($result);
    }

    /**
     * 模板文件制作者
     * @return json
     */
    public function getTplDevUser()
    {
        if (!file_exists(public_path('/templates/' . $tplname) . '/config.ini')) {
            return json_encode(array("err" => 1001));
        }
        $tplname = Input::get("tplname");
        $config_str = file_get_contents(public_path('/templates/' . $tplname) . '/config.ini');
        $search = "/Name=(.*)/i";
        $config_arr = array();
        $r = preg_match_all($search, $config_str, $config_arr);
        if ($r) {
            $config_arr[1][1] = str_replace(array("\r", "\r\n", "\n"), '', $config_arr[1][1]);
            return json_encode(array("err" => 1000, "name" => $config_arr[1][1]));
        } else {
            return json_encode(array("err" => 1001));
        }
    }

    /**
     * ===网站迁移(图片)===
     * 1、打包用户资料
     * 2、删除ftp原资料（所有不仅是图片）
     * 3、ftp传输资料，解压
     */
    public function webRemove()
    {
        if ($this->authData()) {
            set_time_limit(0);
            //获取用户名
            $username = Input::get('username');
            //获取新FTP数据
            $ftpAddr = Input::get('ftp_address'); //182.61.7.87
            $ftpPort = Input::get('ftp_port'); // '21';
            $ftpUser = Input::get('ftp_user'); //'tongYi'; 
            $ftpPwd = Input::get('ftp_pwd'); //'B164RLFh';
            $ftpDir = Input::get('ftp_dir'); //"./";
            $ftpFlag = Input::get('ftp_flag'); //1:women ,0:kehu//"1";
            $ftpUrl = Input::get('ftp_url'); //"http://test6.n01.5067.org/"; 

            //获取新ftp_b的信息
            $ftpAddr_B = Input::get('ftp_address_b');
            $ftpUser_B = Input::get('ftp_user_b'); 
            $ftpPwd_B = Input::get('ftp_pwd_b');
            $ftpUrl_B = Input::get('ftp_url_b');

            $m_url =  Input::get('m_url'); 

//            $ftpAddr = "182.61.7.87";
//            $ftpPort = '21';
//            $ftpUser = 'tongYi';
//            $ftpPwd = 'B164RLFh';
//            $ftpDir = "./";
//            $ftpFlag = "1"; //1:women ,0:kehu//
//            $ftpUrl = "http://test.n01.5067.org";
            //压缩文件
            $zip = new ZipArchive();
            $zip->open(public_path("customers/" . $username . "/img.zip"), ZipArchive::OVERWRITE);
            $this->addFileToZip(public_path("customers/" . $username . "/images"), $zip);
            $zip->close();
            $conn_new = @ftp_connect($ftpAddr, $ftpPort);
            if (!$conn_new) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
            }
            if (!@ftp_login($conn_new, $ftpUser, $ftpPwd)) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
            }
            ftp_pasv($conn_new, TRUE);
            $ftpDir = preg_replace("/^(\.)?\//", "", $ftpDir);
            if ($ftpFlag == 1) {
                $ftpDir = $ftpDir . "/" . $username;
            }
            //创建mobile文件夹
            @ftp_mkdir($conn_new, $ftpDir);
            @ftp_mkdir($conn_new, $ftpDir . "/mobile");
            $ftp_put = ftp_put($conn_new, $ftpDir . "/img.zip", public_path("customers/" . $username . "/img.zip"), FTP_BINARY);
            $ftp_put = $ftp_put && ftp_put($conn_new, $ftpDir . '/img_unzip.php', public_path("/packages/img_unzip.php"), FTP_ASCII);
            if (!$ftp_put) {
                return Response::json(array(['err' => 1003, 'msg' => '文件传输失败']));
            }
            //35绑定文件
            if($ftpFlag == 2){
                if($m_url){
                    $str = $this->webConfig($m_url);
                    @file_put_contents(public_path('customers/' . $username) . '/web.config', $str);
                    //上传绑定文件到35空间
                    $ftp_put = ftp_put($conn_new,$ftpDir . '/web.config',public_path('customers/' . $username) . '/web.config', FTP_ASCII);
                    if (!$ftp_put) {
                        return Response::json(array(['err' => 1003, 'msg' => '文件传输失败']));
                    } 
               }                
            }
            //解压文件
            @file_get_contents($ftpUrl . "/img_unzip.php");
            //删除压缩文件
            @ftp_delete($conn_new, $ftpDir . "/img_unzip.php");
            ftp_close($conn_new);

            //判断有无ftp_b，有则将文件也传一份过去
            if($ftpAddr_B){
                $conn_new_b = @ftp_connect($ftpAddr_B, $ftpPort);
                if (!$conn_new_b) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器连接失败']);
                }
                if (!@ftp_login($conn_new_b, $ftpUser_B, $ftpPwd_B)) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP_B服务器登陆失败']);
                }
                ftp_pasv($conn_new_b, TRUE);
                //创建mobile文件夹
                @ftp_mkdir($conn_new_b, $ftpDir);
                @ftp_mkdir($conn_new_b, $ftpDir . "/mobile");
                $ftp_put_b = ftp_put($conn_new_b, $ftpDir . "/img.zip", public_path("customers/" . $username . "/img.zip"), FTP_BINARY);
                $ftp_put_b = $ftp_put && ftp_put($conn_new_b, $ftpDir . '/img_unzip.php', public_path("/packages/img_unzip.php"), FTP_ASCII);
                if (!$ftp_put_b) {
                    return Response::json(array(['err' => 1003, 'msg' => 'FTP_B文件传输失败']));
                }
                //解压文件
                file_get_contents($ftpUrl_B . "/img_unzip.php");
                //删除压缩文件
                @ftp_delete($conn_new_b, $ftpDir . "/img_unzip.php");
                ftp_close($conn_new_b);
            }
            

            //删除原FTP的资料
            $CustomerInfo = Customer::where('name', $username)->first();
            if (empty($CustomerInfo)) {
                return Response::json(['err' => 1001, 'msg' => '用户不存在']);
            }
            $cus_ftp['addr'] = $CustomerInfo->ftp_address;
            $cus_ftp['port'] = $CustomerInfo->ftp_port;
            $cus_ftp['user'] = $CustomerInfo->ftp_user;
            $cus_ftp['pwd'] = $CustomerInfo->ftp_pwd;
            $cus_ftp['dir'] = $CustomerInfo->ftp_dir;
            $cus_ftp['ftp'] = $CustomerInfo->ftp; //1：我们，0：客户的
            $conn_old = @ftp_connect($cus_ftp['addr'], $cus_ftp['port']);
            if (!$conn_old) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
            }
            if (!@ftp_login($conn_old, $cus_ftp['user'], $cus_ftp['pwd'])) {
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
            }
            ftp_pasv($conn_old, TRUE);
            //删除文件夹
            $cus_ftp['dir'] = preg_replace("/^(\.)?\//", "", $cus_ftp['dir']);
            if ($cus_ftp['ftp'] == 1) {
                $cus_ftp['dir'] = $cus_ftp['dir'] . "/" . $CustomerInfo->name;
            }
            if (ftp_nlist($conn_old, $cus_ftp['dir'] . "/mobile") !== false) {
                $this->ftp_delete_file($conn_old, $cus_ftp['dir']);
            }
            ftp_close($conn_old);

            //如果原站也有ftp_b，则同样进行删除原文件
            if($CustomerInfo->ftp_address_b){
                $cus_ftp['addr_b'] = $CustomerInfo->ftp_address_b;
                $cus_ftp['user_b'] = $CustomerInfo->ftp_user_b;
                $cus_ftp['pwd_b'] = $CustomerInfo->ftp_pwd_b;
                $conn_old_b = @ftp_connect($cus_ftp['addr_b'], $cus_ftp['port']);
                if (!$conn_old_b) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
                }
                if (!@ftp_login($conn_old_b, $cus_ftp['user_b'], $cus_ftp['pwd_b'])) {
                    return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
                }
                ftp_pasv($conn_old_b, TRUE);
                //删除文件夹
                $cus_ftp['dir'] = preg_replace("/^(\.)?\//", "", $cus_ftp['dir']);
                if (ftp_nlist($conn_old_b, $cus_ftp['dir'] . "/mobile") !== false) {
                    $this->ftp_delete_file($conn_old_b, $cus_ftp['dir']);
                }
                ftp_close($conn_old_b);

            }
            return Response::json(array(['err' => 1000, 'msg' => '图片数据迁移成功']));
        }
    }

    /**
     * 删除FTP上的文件夹
     * @param type $conn ftp链接
     * @param type $dir 路径
     * @return boolean
     */
    public function ftp_delete_file($conn, $dir)
    {
        $dir = preg_replace("/^(\.)?\//", "", $dir);
        $filelist = ftp_rawlist($conn, $dir);
        foreach ($filelist as $filename) {
            $aa = explode(' ', $filename, 26);
            $aa = array_reverse($aa);
            $filename = $aa[0];
            $filename = str_replace($dir . "/", '', $filename);
            if ($filename !== '.' && $filename !== '..') {
                if (stripos($filename, '.')) {//===确认是否是文件===
                    @ftp_delete($conn, $dir . '/' . $filename);
                } else {
                    $this->ftp_delete_file($conn, $dir . '/' . $filename);
                }
            }
        }
        @ftp_rmdir($conn, $dir);
        return true;
    }

    /**
     * 跳转至微传单
     */
    public function cdLogin()
    {
        if (Auth::check()) {
            $url = "http://" . GSHOW_DOMAIN . "/index.php?c=user&a=autologin";
            $data = array();
            $data["remember"] = Auth::user()->remember_token;
            $data["username"] = Auth::user()->email;
            header('Location:' . $url . '&remember=' . $data["remember"] . '&username=' . $data["username"]);
            exit();
        }
    }

    /**
     * 跳转至微传单握手验证
     */
    public function cdShakeHands()
    {
        $remember = Input::get("remember");
        $username = Input::get("username");
        $cust = Customer::where("email", $username)->first();
        if (md5($cust["remember_token"] . $cust["email"]) == $remember) {
            return json_encode(array("err" => 0));
        } else {
            return json_encode(array("err" => 1));
        }
    }

    /**
     * 35开户手机站绑定文件
     */
    public function webConfig($mobile_other){
        $arr = explode(',', $mobile_other);
        $str = '<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>';
        foreach ($arr as $k => $v) {
            $rule = str_replace('.', '_', $v);
            $add = str_replace('.', '\\.', $v);

            $str .= '
                <rule name="mobile_'. $rule. '" stopProcessing="true">
                    <match url="(.*)" />
                    <conditions>
                        <add input="{HTTP_HOST}" pattern="^'. $add. '$" />
                    </conditions>
                    <action type="Rewrite" url="mobile/{R:0}" logRewrittenUrl="false" />
                </rule>';
        }
        $str .= '
            </rules>
        </rewrite>
    </system.webServer>
</configuration>';

        return $str;
    }

    /**
     * 公告发布
     */
    public function modifyNotice(){
        if ($this->authData()) {
            $update['uid'] = trim(Input::get('uid'));
            $update['title'] = trim(Input::get('title'));
            $update['content'] = trim(Input::get('content'));
            $update['updatetime'] = trim(Input::get('updatetime'));
            $update['is_on'] = trim(Input::get('is_on'));
            $update['synopsis'] = trim(Input::get('synopsis'));
            $update['type'] = trim(Input::get('type'));

            $id = Notice::where('uid',$update['uid'])->pluck('id');
            if($id){
                $res = Notice::where('id',$id)->update($update);
            } else {
                $res = Notice::insert($update);
            }

            if($res){
                return Response::json(array(['err' => 1000, 'msg' => '发布成功']));
            }else{
                return Response::json(array(['err' => 1001, 'msg' => '发布失败']));
            }
        }
    }

    /**
     * 公告删除
     */
    public function delNotice(){
        if ($this->authData()) {
            $uid = trim(Input::get('uid'));

            $res = Notice::where('uid',$uid)->delete();

            if($res){
                return Response::json(array(['err' => 1000, 'msg' => '删除成功']));
            }else{
                return Response::json(array(['err' => 1001, 'msg' => '删除失败']));
            }
        }
    }

    /**
     * 批量改用户密码
     */
    public function batchpwd(){
        set_time_limit(300);
        if ($this->authData()) {
            $name = Input::get('name');

            $name = json_decode($name , true);            

            foreach ($name as $k => $v) {
                $password = Hash::make($v['pwd']);
                $count = Customer::where('name', $v['G_name'])->count();
                //是否存在
                if($count){
                    $res = Customer::where('name', $v['G_name'])->update(['password' => $password]);
                    if(!$res) {                  
                        return Response::json(array(['err' => 1003, 'msg' => '批量修改失败']));
                    }
                }                
            }

            return Response::json(array(['err' => 0, 'msg' => '批量修改成功']));
        }
    }

    //自动处理过期用户
    public function deleteAuto() {
        set_time_limit(0);
        if ($this->authData()) {
            $site['name'] = Input::get('name');//删除站点名称
            $site['type'] = Input::get('type');//删除站点类型

            $date = date('Y-m-d' , time());
            $path = public_path('logs/delete_log');//日志目录

            if($site['type'] != 0) {
                $res = $this->delPart($site);//删除单站
            } else {
                $res = $this->delWhole($site['name']);//删除双站                
            }

            //记录文件日志
            if(!is_dir($path)) {
                @mkdir($path);
            }
            @file_put_contents($path . '/' .$date . '.txt', $site['name'] . ',' . $res['msg'] . ',' . date('H:i:s' , time()) . ';' . PHP_EOL, FILE_APPEND);

            return Response::json(array('err' => $res['err'], 'msg' => $res['msg']));
        }
    }

    //删除整站
    public function delWhole($name) {
        //防止用户为空造成误删
        if(!$name) {
            $res['err'] = 1004;
            $res['msg'] = '用户错误';
            return $res;
        }

        //用户图片目录
        $pc = public_path("customers/" . $name . "/images");
        $mobile = public_path("customers/" . $name . "/mobile/images");        

        //用户信息
        $Customer = Customer::where('name', $name)->get();
        $cus_id = $Customer[0]['id'];
        $cus = $Customer[0];

        //检测用户及目录是否存在
        if (!$cus_id) {
            $res['err'] = 1001;
            $res['msg'] = '用户不存在';
            return $res;
        }        
        if(!is_dir($pc)) {
            $res['err'] = 1002;
            $res['msg'] = '用户images文件夹不存在';
            return $res;
        }
        if(!is_dir($mobile)) {
            $res['err'] = 1003;
            $res['msg'] = '用户mobile/images文件夹不存在';
            return $res;
        }

        //压缩主控资料
        $zip = new ZipArchive();
        if ($zip->open(public_path("customers_backups/" . $name . '.zip'), ZipArchive::OVERWRITE) === TRUE) {
            //压缩指定目录
            $this->addFileToZip(public_path("customers/" . $name . "/images"), $zip);
            $zip->addEmptyDir("mobile");
            $this->addFileToZip(public_path("customers/" . $name . "/mobile/images"), $zip, "mobile");
            $zip->close();

            //删除主控客户目录
            $dir = "./customers/" . $name;
            //防止误删
            if($dir == './customers/') {
                $res['err'] = 1009;
                $res['msg'] = '非法操作删除';
                return $res;
            }
            // if ($this->delUserFile($dir)) {
                //ftp目录
                if($cus['ftp'] == 1) {//公司的ftp
                    $ftp_dir = './' . $name;
                } else {//非公司ftp
                    //去掉最后一条斜杠
                    $arr_ftp = explode('/', $cus['ftp_dir']);
                    $arr_end = end($arr_ftp);
                    if(!$arr_end) {
                        array_pop($arr_ftp);
                    }
                    $ftp_dir = implode('/', $arr_ftp);
                }
                //A服连接
                // $conn = @ftp_connect($cus['ftp_address'], $cus['ftp_port']);
                // if (!$conn) {
                //     $res['err'] = 1005;
                //     $res['msg'] = 'A服连接失败';
                //     return $res;
                // }
                // if (!@ftp_login($conn, $cus['ftp_user'], $cus['ftp_pwd'])) {
                //     $res['err'] = 1006;
                //     $res['msg'] = 'A服登录失败';
                //     return $res;
                // }
                // @ftp_pasv($conn, 1);
                // $this->delStatic($conn, $ftp_dir, $name);
                // @ftp_rename($conn, $name, "beifen_" . $name);
                // @ftp_close($conn);
                //B服连接
                if($cus['ftp_address_b']) {
                    $conn_b = @ftp_connect($cus['ftp_address_b'], $cus['ftp_port']);
                    if (!$conn_b) {
                        $res['err'] = 1005;
                        $res['msg'] = 'B服连接失败';
                        return $res;
                    }
                    if (!@ftp_login($conn_b, $cus['ftp_user_b'], $cus['ftp_pwd_b'])) {
                        $res['err'] = 1006;
                        $res['msg'] = 'B服登录失败';
                        return $res;
                    }
                    @ftp_pasv($conn_b, 1);
                    $this->delStatic($conn_b, $ftp_dir, $type);
                    @ftp_close($conn_b);
                }
            // } else {
            //     $res['err'] = 1007;
            //     $res['msg'] = '主控目录删除失败';
            //     return $res;
            // }
        } else {
            $res['err'] = 1007;
            $res['msg'] = '主控目录压缩失败';
            return $res;
        }

        //修改用户状态
        // $update['is_del'] = 0;
        // $res1 = Customer::where('id', $cus_id)->update($update);
        // $res2 = WebsiteInfo::where('cus_id', $cus_id)->update($update);
        // $res3 = CustomerInfo::where('cus_id', $cus_id)->update($update);

        // if($res1 && $res2 && $res3) {
        //     $result['err'] = 1000;
        //     $result['msg'] = '过期双站删除成功';
        // } else {
        //     $result['err'] = 1008;
        //     $result['msg'] = '统一平台过期双站删除失败';
        // }

        // return $result;
    }

    //删除单站
    public function delPart($data) {
        $type = $data['type'];//过期的站点类型，1-PC，2-手机
        $name = $data['name'];

        if(!$name) {
            $res['err'] = 1003;
            $res['msg'] = '用户错误';
            return $res;
        }

        //用户信息
        $Customer = Customer::where('name', $name)->get();
        $cus_id = $Customer[0]['id'];
        $cus = $Customer[0];

        //检测用户是否存在
        if (!$cus_id) {
            $res['err'] = 1001;
            $res['msg'] = '用户不存在';
            return $res;
        }

        //修改数据库信息
        if($type == 1) {
            $update['pc_out_domain'] = $cus['pc_domain'];
            $update['pc_domain'] = '';
            $update['stage'] = 2;
        } elseif ($type == 2) {
            $update['mobile_out_domain'] = $cus['mobile_domain'];
            $update['mobile_domain'] = '';
            $update['stage'] = 1;
        }
        // $res1 = Customer::where('id', $cus_id)->update($update);
        // unset($update['stage']);//customerinfo表无该字段
        // $res2 = CustomerInfo::where('cus_id', $cus_id)->update($update);

        // if($res1&&$res2) {
        //     $result['err'] = 1000;
        //     $result['msg'] = '过期单站删除成功';
        // } else {
        //     $result['err'] = 1002;
        //     $result['msg'] = '统一平台过期单站删除失败';
        // }

        //ftp目录
        if($cus['ftp'] == 1) {//公司的ftp
            $ftp_dir = './' . $name;
            if($type == 2) {
                $ftp_dir = './' . $name . '/mobile';
            }
        } else {//非公司ftp
            //去掉最后一条斜杠
            $arr_ftp = explode('/', $cus['ftp_dir']);
            $arr_end = end($arr_ftp);
            if(!$arr_end) {
                array_pop($arr_ftp);
            }
            $ftp_dir = implode('/', $arr_ftp);
            if($type == 2) {
                $ftp_dir = $ftp_dir . '/mobile';
            }
        }
        //A服连接
        // $conn = @ftp_connect($cus['ftp_address'], $cus['ftp_port']);
        // if (!$conn) {
        //     $res['err'] = 1005;
        //     $res['msg'] = 'A服连接失败';
        //     return $res;
        // }
        // if (!@ftp_login($conn, $cus['ftp_user'], $cus['ftp_pwd'])) {
        //     $res['err'] = 1006;
        //     $res['msg'] = 'A服登录失败';
        //     return $res;
        // }
        // @ftp_pasv($conn, 1);
        // $this->delStatic($conn, $ftp_dir, $type);
        // @ftp_close($conn);

        //B服连接
        if($cus['ftp_address_b']) {
            $conn_b = @ftp_connect($cus['ftp_address_b'], $cus['ftp_port']);
            if (!$conn_b) {
                $res['err'] = 1005;
                $res['msg'] = 'B服连接失败';
                return $res;
            }
            if (!@ftp_login($conn_b, $cus['ftp_user_b'], $cus['ftp_pwd_b'])) {
                $res['err'] = 1006;
                $res['msg'] = 'B服登录失败';
                return $res;
            }
            @ftp_pasv($conn_b, 1);
            $this->delStatic($conn_b, $ftp_dir, $type);
            @ftp_close($conn_b);
        }

        // return $result;
    }

    //删除静态服务器上对应站除图片外的文件删除
    public function delStatic($conn, $dir, $type) {
        switch ($type) {
            case 1:
                //删除PC站时，PC站图片目录和手机目录保留
                if(!stripos($dir, '/images') && !stripos($dir, '/mobile')) {
                    $exc = true;
                } else {
                    $exc = false;
                }
                break;
            case 2:
                //删除手机站时，手机站图片目录和手机站之外的目录保留
                if(!stripos($dir, 'mobile/images') && stripos($dir, '/mobile')) {
                    $exc = true;
                } else {
                    $exc = false;
                }
                break;
            case 0:
                //删除双站时，保留双站图片目录
                if(!stripos($dir, '/images')) {
                    $exc = true;
                } else {
                    $exc = false;
                }
                break;
            default:
                $exc = false;
                break;
        }

        if ($exc) {//是否继续执行
            // $filelist = ftp_nlist($conn, $dir);//PHP5.3不支持？
            $filelist = ftp_rawlist($conn, $dir);
            foreach ($filelist as $file) {
                // $file_arr = explode('/', $file);
                // $filename = end($file_arr);//nlist处理方式
                $filename = preg_replace("/.+[:]*\\d+\\s/", "", $file);
                if ($filename !== '.' && $filename !== '..') {
                    if (stripos($filename, '.')) {
                        // ftp_delete($conn, $dir . '/' . $filename);
                        file_put_contents('filename7.txt', $dir . '/' . $filename.PHP_EOL,FILE_APPEND);
                    } else {
                        file_put_contents('filename8.txt', $dir . '/' . $filename.PHP_EOL,FILE_APPEND);
                        $this->delStatic($conn, $dir . '/' . $filename, $type);
                        // @ftp_rmdir($conn, $dir . '/' . $filename);                        
                    }
                }
            }
        } else {
            return true;
        }
    }

}
