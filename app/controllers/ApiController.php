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
    
    public function deletemytest($name=''){
        $name = "test";
        $Customer = Customer::where('name', $name)->get();  
        $cus_id = $Customer[0]['id'];
        $WebsiteInfo = WebsiteInfo::where('cus_id', $cus_id)->get();
        $CustomerInfo = CustomerInfo::where('cus_id', $cus_id)->get();
        $Customer = $Customer[0]; 
        $db = new PDO('sqlite:sqlite_1.db');
        if ($db) {
            $sql = "INSERT INTO customer (id,name,email,password,password_temp,remember_token,weburl,serv_id,ftp,ftp_address,ftp_port,ftp_user,ftp_pwd,ftp_dir,pc_tpl_id,mobile_tpl_id,pc_domain,mobile_domain,ended_at,status,created_at,updated_at,pc_end_time,mobile_end_time,color_id,switch_cus_id,customization) "
                    . "values('".$Customer['id']."','".$Customer['name']."','".$Customer['email']."','".$Customer['password']."','".$Customer['password_temp']."','".$Customer['remember_token']."','"
                    .$Customer['weburl']."','".$Customer['serv_id']."','".$Customer['ftp']."','".$Customer['ftp_address']."','".$Customer['ftp_port']."','".$Customer['ftp_user']."','".$Customer['ftp_pwd']."','".$Customer['ftp_dir']."','"
                    .$Customer['pc_tpl_id']."','".$Customer['mobile_tpl_id']."','".$Customer['pc_domain']."','".$Customer['mobile_domain']."','".$Customer['ended_at']."','".$Customer['status']."','".$Customer['created_at']."','".$Customer['updated_at']."','".$Customer['pc_end_time']."','".$Customer['mobile_end_time']."','".$Customer['color_id']."','".$Customer['switch_cus_id']."','".$Customer['customization']."')";
            $ret = $db->exec($sql);
            if ($WebsiteInfo->count()) {
                $WebsiteInfo = $WebsiteInfo[0];
                $sql = "INSERT INTO website_info (id,cus_id,pc_tpl_id,mobile_tpl_id,pc_color_id,mobile_color_id,pc_htpl_id,mobile_htpl_id,pc_hcolor_id,mobile_hcolor_id,pushed) values('"
                        .$WebsiteInfo['id']."','".$WebsiteInfo['cus_id']."','".$WebsiteInfo['pc_tpl_id']."','".$WebsiteInfo['mobile_tpl_id']."','".$WebsiteInfo['pc_color_id']."','".$WebsiteInfo['mobile_color_id']
                        ."','".$WebsiteInfo['pc_htpl_id']."','".$WebsiteInfo['mobile_htpl_id']."','".$WebsiteInfo['pc_hcolor_id']."','".$WebsiteInfo['mobile_hcolor_id']."','".$WebsiteInfo['pushed']."')";
                $ret1 = $db->exec($sql);
                
            }
            if ($CustomerInfo->count()) {
                $CustomerInfo = $CustomerInfo[0];
                $sql = "INSERT INTO customer_info (id,cus_id,company,pc_domain,mobile_domain,favicon,logo,logo_small,title,keywords,description,pc_header_script,mobile_header_script,footer,mobile_footer,pc_footer_script,mobile_footer_script,pc_page_count,pc_page_links,mobile_page_count,mobile_page_links,telephone,mobile,address,fax,"
                        . "email,qq,contact_name,pushed_at,created_at,updated_at,pc_page_imgtxt_count,pc_page_img_count,pc_page_txt_count,pc_page_count_switch,enlarge,floatadv,pushed,lang,copyright,capacity_use,capacity,lastpushtime,init_capacity,is_openmember,background_music,talent_support) values('"
                        .$CustomerInfo['id']."','".$CustomerInfo['cus_id']."','".$CustomerInfo['company']."','".$CustomerInfo['pc_domain']."','".$CustomerInfo['mobile_domain']."','".$CustomerInfo['favicon']
                        ."','".$CustomerInfo['logo']."','".$CustomerInfo['logo_small']."','".$CustomerInfo['title']."','".$CustomerInfo['keywords']."','".$CustomerInfo['description']
                        ."','".$CustomerInfo['pc_header_script']."','".$CustomerInfo['mobile_header_script']."','".$CustomerInfo['footer']."','".$CustomerInfo['mobile_footer']."','".$CustomerInfo['pc_footer_script']."','".$CustomerInfo['mobile_footer_script']."','".$CustomerInfo['pc_page_count']
                        ."','".$CustomerInfo['pc_page_links']."','".$CustomerInfo['mobile_page_count']."','".$CustomerInfo['mobile_page_links']."','".$CustomerInfo['telephone']."','".$CustomerInfo['mobile']."','".$CustomerInfo['address']."','".$CustomerInfo['fax']
                        ."','".$CustomerInfo['email']."','".$CustomerInfo['qq']."','".$CustomerInfo['contact_name']."','".$CustomerInfo['pushed_at']."','".$CustomerInfo['created_at']."','".$CustomerInfo['updated_at']."','".$CustomerInfo['pc_page_imgtxt_count']
                        ."','".$CustomerInfo['pc_page_img_count']."','".$CustomerInfo['pc_page_txt_count']."','".$CustomerInfo['pc_page_count_switch']."','".$CustomerInfo['enlarge']."','".$CustomerInfo['floatadv']."','".$CustomerInfo['pushed']."','".$CustomerInfo['lang']
                        ."','".$CustomerInfo['copyright']."','".$CustomerInfo['capacity_use']."','".$CustomerInfo['capacity']."','".$CustomerInfo['lastpushtime']."','".$CustomerInfo['init_capacity']."','".$CustomerInfo['is_openmember']."','".$CustomerInfo['background_music']."','".$CustomerInfo['talent_support']."')";
                
                $ret2 = $db->exec($sql);
            }
            if ($ret && $ret1 && $ret2) {
                echo "数据库备份成功！<br/>";
                echo "开始备份文件<br/>";
                $zip=new ZipArchive();
                if($zip->open("customers_backups/".$name.'-x.zip', ZipArchive::OVERWRITE)=== TRUE){
                    $this->addFileToZip("customers/".$name."/images", $zip);
                    $zip->addEmptyDir("mobile");
                    $this->addFileToZip("customers/".$name."/mobile/images", $zip,"mobile"); 
                    $zip->close();
                    echo "完成";
                }else{
                    echo "错误";
                }
            }
        } else {
            $result = ['err' => 1003, 'msg' => '数据库备份失败'];
        }
    }
    
    function addFileToZip($path, $zip, $array = '') {
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
    }

}
