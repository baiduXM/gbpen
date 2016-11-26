<?php

class ApiController extends BaseController {

    /**
     * 接口验证    authData
     * @param type timemap      操作时间戳
     * @param type taget        加密结果
     * @return boolean
     */
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

    /**
     * 用户登录    login
     * @param type id       用户id
     * @return type null    跳转至管理页(标识为通过代理平台登录)
     */
    public function login() {

        if ($this->authData()) {

            $name = Input::get('name');
            $id_del = Customer::where('name', $name)->where('is_del','0')->pluck('id');
            if(!$id_del){
                $id = Customer::where('name', $name)->where('is_del', '1')->pluck('id');
                $user = Customer::find($id);
                Auth::login($user);
                if (Auth::check()) {
                    Session::put('isAdmin', TRUE);
                    return Redirect::to('admin/index.html');
                } else {
                    $result = ['err' => 1001, 'msg' => '登录失败'];
                }
            }else{
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
                        $common = new CommonController();
                        @$common->postsend(trim($update['weburl'], '/') . "/urlbind.php", array('cus_name' => $update['name'], 'stage' => $update['stage'], 'pc_domain' => $update['pc_domain'], 'mobile_domain' => $update['mobile_domain']));
                        $result = ['err' => 1000, 'msg' => '创建用户成功'];
                    } else {
                        $result = ['err' => 1001, 'msg' => '创建用户失败,创建文件失败'];
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
    public function deleteCustomer() {
//        if ($this->authData()) {

            $name = Input::get('name');
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
        return Response::json($result);
    }
    
     /**
     * 删除文件的备份和数据库保存
     * @param type name 用户名
     * @return boole TURE/FALSE
     */
    public function deletemytest(){
        $result = '';
        $name = Input::get('username');
//        if ($this->authData()) {
            header("Content-type: text/html; charset=utf-8"); 
//            $name = $_GET['username'];
            $Customer = Customer::where('name', $name)->get();  
            var_dump($Customer);exit();
            $cus_id = $Customer[0]['id'];
            $WebsiteInfo = WebsiteInfo::where('cus_id', $cus_id)->get();
            $CustomerInfo = CustomerInfo::where('cus_id', $cus_id)->get();
            $handle_a = opendir(public_path("customers/".$name."/images"));
            $handle_b = opendir(public_path("customers/".$name."/mobile/images"));
            if($handle_a){
                closedir($handle_a);
            }else{
                return Response::json(['err' => 1004, 'msg' => '用户images文件夹不存在']);
                exit();
            }
            if($handle_b){
                closedir($handle_b);
            }else{
                return Response::json(['err' => 1004, 'msg' => '用户mobile/images文件夹不存在']);
                exit();
            }
            $Customer = $Customer[0]; 
            $db = new PDO('sqlite:sqlite_1.db');
            if ($db) {
                $conn = @ftp_connect($Customer['ftp_address'], $Customer['ftp_port']);
                if(!$conn){
                    return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
                    exit();
                }
                if(!@ftp_login($conn, $Customer['ftp_user'], $Customer['ftp_pwd'])){
                    return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
                    exit();
                }
                
                //保存数据库
                $sql = "INSERT INTO customer (id,name,email,password,password_temp,remember_token,weburl,serv_id,ftp,ftp_address,ftp_port,ftp_user,ftp_pwd,ftp_dir,pc_tpl_id,mobile_tpl_id,pc_domain,mobile_domain,ended_at,status,created_at,updated_at,pc_end_time,mobile_end_time,color_id,switch_cus_id,customization,del_time) "
                        . "values('".$Customer['id']."','".$Customer['name']."','".$Customer['email']."','".$Customer['password']."','".$Customer['password_temp']."','".$Customer['remember_token']."','"
                        .$Customer['weburl']."','".$Customer['serv_id']."','".$Customer['ftp']."','".$Customer['ftp_address']."','".$Customer['ftp_port']."','".$Customer['ftp_user']."','".$Customer['ftp_pwd']."','".$Customer['ftp_dir']."','"
                        .$Customer['pc_tpl_id']."','".$Customer['mobile_tpl_id']."','".$Customer['pc_domain']."','".$Customer['mobile_domain']."','".$Customer['ended_at']."','".$Customer['status']."','".$Customer['created_at']."','".$Customer['updated_at']."','".$Customer['pc_end_time']."','".$Customer['mobile_end_time']."','".$Customer['color_id']."','".$Customer['switch_cus_id']."','".$Customer['customization']."','".time()."')";
                $ret = $db->exec($sql);
                if ($WebsiteInfo->count()) {
                    $WebsiteInfo = $WebsiteInfo[0];
                    $sql = "INSERT INTO website_info (id,cus_id,pc_tpl_id,mobile_tpl_id,pc_color_id,mobile_color_id,pc_htpl_id,mobile_htpl_id,pc_hcolor_id,mobile_hcolor_id,pushed,del_time) values('"
                            .$WebsiteInfo['id']."','".$WebsiteInfo['cus_id']."','".$WebsiteInfo['pc_tpl_id']."','".$WebsiteInfo['mobile_tpl_id']."','".$WebsiteInfo['pc_color_id']."','".$WebsiteInfo['mobile_color_id']
                            ."','".$WebsiteInfo['pc_htpl_id']."','".$WebsiteInfo['mobile_htpl_id']."','".$WebsiteInfo['pc_hcolor_id']."','".$WebsiteInfo['mobile_hcolor_id']."','".$WebsiteInfo['pushed']."','".time()."')";
                    $ret1 = $db->exec($sql);

                }
                if ($CustomerInfo->count()) {
                    $CustomerInfo = $CustomerInfo[0];
                    $sql = "INSERT INTO customer_info (id,cus_id,company,pc_domain,mobile_domain,favicon,logo,logo_small,title,keywords,description,pc_header_script,mobile_header_script,footer,mobile_footer,pc_footer_script,mobile_footer_script,pc_page_count,pc_page_links,mobile_page_count,mobile_page_links,telephone,mobile,address,fax,"
                            . "email,qq,contact_name,pushed_at,created_at,updated_at,pc_page_imgtxt_count,pc_page_img_count,pc_page_txt_count,pc_page_count_switch,enlarge,floatadv,pushed,lang,copyright,capacity_use,capacity,lastpushtime,init_capacity,is_openmember,background_music,talent_support,del_time) values('"
                            .$CustomerInfo['id']."','".$CustomerInfo['cus_id']."','".$CustomerInfo['company']."','".$CustomerInfo['pc_domain']."','".$CustomerInfo['mobile_domain']."','".$CustomerInfo['favicon']
                            ."','".$CustomerInfo['logo']."','".$CustomerInfo['logo_small']."','".$CustomerInfo['title']."','".$CustomerInfo['keywords']."','".$CustomerInfo['description']
                            ."','".$CustomerInfo['pc_header_script']."','".$CustomerInfo['mobile_header_script']."','".$CustomerInfo['footer']."','".$CustomerInfo['mobile_footer']."','".$CustomerInfo['pc_footer_script']."','".$CustomerInfo['mobile_footer_script']."','".$CustomerInfo['pc_page_count']
                            ."','".$CustomerInfo['pc_page_links']."','".$CustomerInfo['mobile_page_count']."','".$CustomerInfo['mobile_page_links']."','".$CustomerInfo['telephone']."','".$CustomerInfo['mobile']."','".$CustomerInfo['address']."','".$CustomerInfo['fax']
                            ."','".$CustomerInfo['email']."','".$CustomerInfo['qq']."','".$CustomerInfo['contact_name']."','".$CustomerInfo['pushed_at']."','".$CustomerInfo['created_at']."','".$CustomerInfo['updated_at']."','".$CustomerInfo['pc_page_imgtxt_count']
                            ."','".$CustomerInfo['pc_page_img_count']."','".$CustomerInfo['pc_page_txt_count']."','".$CustomerInfo['pc_page_count_switch']."','".$CustomerInfo['enlarge']."','".$CustomerInfo['floatadv']."','".$CustomerInfo['pushed']."','".$CustomerInfo['lang']
                            ."','".$CustomerInfo['copyright']."','".$CustomerInfo['capacity_use']."','".$CustomerInfo['capacity']."','".$CustomerInfo['lastpushtime']."','".$CustomerInfo['init_capacity']."','".$CustomerInfo['is_openmember']."','".$CustomerInfo['background_music']."','".$CustomerInfo['talent_support']."','".time()."')";

                    $ret2 = $db->exec($sql);
                }
                if ($ret && $ret1 && $ret2) {
//                    echo "数据库备份成功！<br/>";
                    $update['is_del'] = 0;
                    Customer::where('id', $cus_id)->update($update);
                    WebsiteInfo::where('cus_id', $cus_id)->update($update);
                    CustomerInfo::where('cus_id', $cus_id)->update($update);
//                    echo "开始备份文件<br/>";
                    $zip=new ZipArchive();
                    if($zip->open(public_path("customers_backups/".$name.'.zip'), ZipArchive::OVERWRITE)=== TRUE){
                        //备份文件
                        $this->addFileToZip(public_path("customers/".$name."/images"), $zip);
                        $zip->addEmptyDir("mobile");
                        $this->addFileToZip(public_path("customers/".$name."/mobile/images"), $zip,"mobile"); 
                        $zip->close();
                        $dir = "./customers/".$name; // 文件夹的名称
                        if($this->delUserFile($dir)){
//                            echo "文件备份完成！<br/>";
//                            echo "开始删除文件<br/>";
                            
                            @ftp_pasv($conn, 1); // 打开被动模拟
                            $this->userFtpDel($conn, './'.$name, $name);
                            @ftp_rename($conn,$name,$name."_beifen");
                            @ftp_close($conn);
//                            echo "删除完成！";
                            $result = ['err' => 1000, 'msg' => '删除用户成功'];
                        }else{
                            return Response::json(['err' => 1002, 'msg' => '文件备份失败']);
                        }
                    }else{
//                        echo "数据库备份失败";
                        return Response::json(['err' => 1004, 'msg' => '数据库备份失败']);
                    }
                }
            } else {
                return Response::json(['err' => 1004, 'msg' => '数据库备份失败']);
            }
//        } else {
//            $result = ['err' => 1002, 'msg' => '验证不通过'];
//        }
        return Response::json($result);
    }
    
    /**
     * 压缩保存用户文件
     * @param string $path 文件路径
     * @param ZipArchive $zip zip对象
     * @param string $array 要压缩在压缩包中的位置
     * @return null
     */
    function addFileToZip($path, $zip, $array = '') {
//        if ($this->authData()) {
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
            }
            @closedir($path);
//        }
    }
    /**
     * 删除用户文件
     * @param string $path 文件路径
     * @return null
     */
    function delUserFile($dir){
//        if ($this->authData()) {
            if ($handle = opendir($dir)) {
                while (false !== ( $item = readdir($handle) )) {
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
//        }else {
//            return false;
//        }
    }
    /**
     * 删除用户FTP文件和目录，保留images和mobile/images
     * @param ftp_connect $conn ftp对象
     * @param string $dir 路径
     * @param string $username 用户名
     * @return null
     */
    function userFtpDel($conn,$dir,$username){
//        if ($this->authData()) {
            if(!stripos($dir,$username.'/images') && !stripos($dir,'mobile/images')){
                $filelist = ftp_rawlist($conn,$dir);
                foreach ($filelist as $file) {
                    $filename = preg_replace("/.+[:]*\\d+\\s/", "", $file);
                    if($filename !== '.' && $filename !== '..' ){
                        if(stripos($filename, '.')){
                           @ftp_delete($conn,$dir.'/'.$filename);
                        }else{
                            if(@ftp_rmdir($conn,$dir.'/'.$filename)){
                                continue;
                            }else{
                                $this->userFtpDel($conn,$dir.'/'.$filename,$username);
                                @ftp_rmdir($conn,$dir.'/'.$filename);
                            }
                            
                        }
                    }
                }
            }else{
                return true;
            }
//        }
    }
     /**
     * 还原用户
     * @param ftp_connect $conn ftp对象
     * @param string $dir 路径
     * @param string $username 用户名
     * @return null
     */
    function reductionCustomer(){
//        if ($this->authData()) {
            $result = '';
            $name = Input::get('username');
            
//            $name = Input::get('name');
            $Customer = Customer::where('name', $name)->where('is_del',0)->get(); 
            
            if(!isset($Customer[0])){
                return Response::json(['err' => 1004, 'msg' => '用户不存在或未删除']);
                exit();
            }
            
            //修改静态服务器文件名
            $conn = @ftp_connect($Customer[0]['ftp_address'], $Customer[0]['ftp_port']);
            if(!$conn){
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器连接失败']);
                exit();
            }
            if(!@ftp_login($conn, $Customer[0]['ftp_user'], $Customer[0]['ftp_pwd'])){
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器登陆失败']);
                exit();
            }
            @ftp_pasv($conn, 1); // 打开被动模拟
            if(!@ftp_rename($conn,$name."_beifen",$name)){
                return Response::json(['err' => 1004, 'msg' => 'FTP服务器静态没有备份']);
                exit();
            }
            @ftp_close($conn);
            
            //解压备份的图片文件
            $zip=new ZipArchive();
            if(!is_dir((public_path("customers/".$name)))){
                mkdir("customers/".$name);
            }
            if($zip->open(public_path("customers_backups/".$name.'.zip'))=== TRUE){
                $zip->extractTo(public_path("customers/".$name));
                $zip->close();
            }else{
                return Response::json(['err' => 1004, 'msg' => '解压失败']);
                exit();
            }
            if($zip->open(public_path("packages/customernull.zip"))=== TRUE){
                $zip->extractTo(public_path("customers/".$name));
            }else{
                return Response::json(['err' => 1004, 'msg' => '解压失败2']);
                exit();
            }
            $zip->close();
            
            //更新数据库
            $update['is_del'] = 1;
            $cus_id = $Customer[0]['id'];
            $WebsiteInfo = WebsiteInfo::where('cus_id', $cus_id)->where('is_del',0)->get();
            if($WebsiteInfo){
                WebsiteInfo::where('cus_id', $cus_id)->update($update);
            }
            $CustomerInfo = CustomerInfo::where('cus_id', $cus_id)->where('is_del',0)->get();
            if($CustomerInfo){
                CustomerInfo::where('cus_id', $cus_id)->update($update);
            }
            Customer::where('id', $cus_id)->update($update);
            
            unlink(public_path("customers_backups/".$name.".zip"));
            
            $result = ['err' => 1000, 'msg' => '还原用户成功'];
            
//        } else {
//            $result = ['err' => 1002, 'msg' => '验证不通过'];
//        }
        return Response::json($result);
    }

}
