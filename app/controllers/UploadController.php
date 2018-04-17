<?php

class UploadController extends BaseController
{

    public function fileRead()
    {
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $target = Input::get('target');
        $this->check_dir($target, $customer);
        $files = Input::file();
        $img_size = Input::get('imgsize') ? Input::get('imgsize') : 800;
        $destinationPath = public_path('customers/' . $customer . '/images/');
        if ($files) {
            if ($target == 'imgcache') {
                $id = $cus_id;
                $filename = Input::get('filenamecho');
                $typeModel = Input::get('type');
                $filename = explode('.', $filename);
                $filetype = end($filename);
                if ($typeModel == 1) {
                    $name = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->pluck('name');
                } else {
                    $name = WebsiteInfo::leftJoin('template', 'mobile_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->pluck('name');
                }
                if ($files['upload_file0']->isValid()) {
                    $type = $files['upload_file0']->getClientOriginalExtension();
                    $truth_name = time() . mt_rand(100, 999) . '.' . $type;
                    $up_result = $files['upload_file0']->move(public_path('templates/' . $name . '/img_cache/'), $truth_name);
                    if ($up_result) {
                        $load['name'] = $truth_name;
                        if ($filetype == 'html')
                            $load['url'] = '{$site_url}images/' . $truth_name;
                        elseif ($filetype == 'json')
                            $load['url'] = 'images/' . $truth_name;
                        else
                            $load['url'] = '../images/' . $truth_name;
                        $return = ['err' => 0, 'msg' => '图片上传成功', 'data' => $load];
                    } else
                        $return = ['err' => 1001, 'msg' => '图片上传失败', 'data' => ''];
                }
                return Response::json($return);
            } else {
                $data = array();
                $i = 0;
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $type = $file->getClientOriginalExtension();
                        $fileName = time() . str_random(4) . '.' . $type;
                        $up_result = $file->move($destinationPath . '/l/' . $target . '/', $fileName);
                        if ($up_result) {
                            $s_path = $destinationPath . '/s/' . $target . '/' . $fileName;
                            $img_info = getimagesize($destinationPath . '/l/' . $target . '/' . $fileName);
                            switch ($img_info[2]) {
                                case 1:
                                    $type = 'gif';
                                    break;
                                case 2:
                                    $type = 'jpg';
                                    break;
                                case 3:
                                    $type = 'png';
                                    break;
                            }
                            $this->resizeImage($destinationPath . '/l/' . $target . '/' . $fileName, $type, $s_path, $img_size, $img_size);
                            copy($destinationPath . '/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/' . $target . '/' . $fileName));
                            if (Input::get('imgsize')) {
                                copy($destinationPath . '/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/images/l/' . $target . '/' . $fileName));
                            }
                            $mobile_s_path = public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName;
                            $this->resizeImage(public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, $type, $mobile_s_path, $img_size, $img_size);
                            //同步到客户服务器
                            $customerinfo = Customer::find($cus_id);
                            $ftp_array = explode(':', $customerinfo->ftp_address);
                            $port = $customerinfo->ftp_port;
                            $ftpdir = $customerinfo->ftp_dir;
                            $ftp = $customerinfo->ftp;
                            $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
                            $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
                            if ($conn) {
                                ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                                ftp_pasv($conn, 1);
                                if (trim($ftp) == '1') {
                                    ftp_put($conn, $customer . '/images/l/' . $target . '/' . $fileName, $destinationPath . '/l/' . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $customer . '/images/s/' . $target . '/' . $fileName, $destinationPath . '/s/' . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $customer . '/mobile/images/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $customer . '/mobile/images/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName, FTP_BINARY);
                                } else {
                                    ftp_put($conn, $ftpdir . '/' . 'images/l/' . $target . '/' . $fileName, $destinationPath . '/l/' . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $ftpdir . '/' . 'images/s/' . $target . '/' . $fileName, $destinationPath . '/s/' . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $ftpdir . '/' . 'mobile/images/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $ftpdir . '/' . 'mobile/images/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName, FTP_BINARY);
                                }
                                ftp_close($conn);
                            }
                            $data[$i]['name'] = $fileName;
                            $data[$i]['url'] = asset('customers/' . $customer . '/images/l/' . $target . '/' . $fileName);
                            $i++;
                        }
                    }
                }
                return Response::json(['err' => 0, 'msg' => '', 'data' => $data]);
            }
        } else {
            $file = Input::get('image');
            if (strpos($file, 'jpeg')) {
                $type = 'jpg';
            } else {
                $type = 'png';
            }
            $fileName = time() . str_random(4) . '.' . $type;
            if (strpos($file, 'jpeg')) {
                $upload = file_put_contents($destinationPath . '/l/' . $target . '/' . $fileName, base64_decode(preg_replace('/data\:image\/jpeg\;base64\,/i', '', $file)));
            } else {
                $upload = file_put_contents($destinationPath . '/l/' . $target . '/' . $fileName, base64_decode(preg_replace('/data\:image\/png\;base64\,/i', '', $file)));
            }
            if ($upload) {
                $s_path = $destinationPath . '/s/' . $target . '/' . $fileName;
                $img_info = getimagesize($destinationPath . '/l/' . $target . '/' . $fileName);
                switch ($img_info[2]) {
                    case 1:
                        $type = 'gif';
                        break;
                    case 2:
                        $type = 'jpg';
                        break;
                    case 3:
                        $type = 'png';
                        break;
                }
                $this->resizeImage($destinationPath . '/l/' . $target . '/' . $fileName, $type, $s_path, $img_size, $img_size);
                copy($destinationPath . '/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/' . $target . '/' . $fileName));
                if (Input::get('imgsize')) {
                    copy($destinationPath . '/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/images/l/' . $target . '/' . $fileName));
                }
                $mobile_s_path = public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName;
                $this->resizeImage(public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, $type, $mobile_s_path, $img_size, $img_size);
                //同步到客户服务器
                $customerinfo = Customer::find($cus_id);
                $ftp_array = explode(':', $customerinfo->ftp_address);
                $port = $customerinfo->ftp_port;
                $ftpdir = $customerinfo->ftp_dir;
                $ftp = $customerinfo->ftp;
                $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
                $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
                if ($conn) {
                    ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    if (trim($ftp) == '1') {
                        ftp_put($conn, $customer . '/images/l/' . $target . '/' . $fileName, $destinationPath . '/l/' . $target . '/' . $fileName, FTP_BINARY);
                        ftp_put($conn, $customer . '/images/s/' . $target . '/' . $fileName, $destinationPath . '/s/' . $target . '/' . $fileName, FTP_BINARY);
                        ftp_put($conn, $customer . '/mobile/images/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, FTP_BINARY);
                        ftp_put($conn, $customer . '/mobile/images/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName, FTP_BINARY);
                    } else {
                        ftp_put($conn, $ftpdir . '/' . 'images/l/' . $target . '/' . $fileName, $destinationPath . '/l/' . $target . '/' . $fileName, FTP_BINARY);
                        ftp_put($conn, $ftpdir . '/' . 'images/s/' . $target . '/' . $fileName, $destinationPath . '/s/' . $target . '/' . $fileName, FTP_BINARY);
                        ftp_put($conn, $ftpdir . '/' . 'mobile/images/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, FTP_BINARY);
                        ftp_put($conn, $ftpdir . '/' . 'mobile/images/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName, FTP_BINARY);
                    }
                    ftp_close($conn);
                }

                return Response::json(['err' => 0, 'msg' => '', 'data' => ['name' => $fileName, 'url' => asset('customers/' . $customer . '/images/l/' . $target . '/' . $fileName)]]);
            } else {
                return Response::json(['err' => 1001, 'msg' => '上传文件失败', 'data' => []]);
            }
        }
    }

    /**
     * ===点击保存按钮后动作===
     * 容量不足：退出循环，将已有压缩包上传，返回容量不足提示
     * @return type
     */
    public function img_upload()
    {
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $target = Input::get('target');
        $this->check_dir($target, $customer);
        $files = explode(',', ltrim(Input::get('files'), ','));
        $dir = public_path('customers/' . $customer . '/cache_images/');
        $img_size = Input::get('imgsize') ? Input::get('imgsize') : 400;
        $destinationPath = public_path('customers/' . $customer . '/images/');
        $weburl = Customer::where('id', $cus_id)->pluck('weburl');
        $suf_url = str_replace('http://c', '', $weburl);
        $imgzip = 'img'.str_random(4).'.zip';
        $enough = 1;//容量是否足够 1-是；0-否
        file_put_contents(public_path('logs/zip_log/'.date('Y-m-d',time()).'.txt'), $imgzip.':'.Input::get('files').PHP_EOL,FILE_APPEND);
        if ($files) {
            //===判断服务器是否正常===
            // if ($weburl) {
            //     if ($this->MonitorCheck($weburl) == false) {
            //         @$time = date('Y-m-d H:i:s',time());
            //         @file_put_contents('moni.txt',$customer.'-'.$time.'-'.json_encode($files).'-'.$target.PHP_EOL,FILE_APPEND);
            //         return Response::json(['err' => 1001, 'msg' => '请检测服务器是否正常', 'data' => '']);
            //     }
            // }
            $data = array();
            $i = 0;
            //同步到客户服务器
            $customerinfo = Customer::find($cus_id);
            $ftp_array = explode(':', $customerinfo->ftp_address);
            $port = $customerinfo->ftp_port;
            $ftpdir = $customerinfo->ftp_dir;
            $ftp = $customerinfo->ftp;
            $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
            $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
            $filesize = 0; //===文件大小===
            if (file_exists(public_path('customers/' . $customer . '/img.zip'))) {
                @unlink(public_path('customers/' . $customer . '/img.zip'));
            }
            if ($conn) {
                ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
                ftp_pasv($conn, 1);

                $zip = new ZipArchive;
                if ($zip->open(public_path('customers/' . $customer . '/' . $imgzip), ZipArchive::CREATE) === TRUE) {
                    $Capacity = new CapacityController;//容量
                    foreach ((array)$files as $fileName) {
                        $filepath = public_path('customers/' . $customer . '/cache_images/' . $fileName);
                        if (file_exists($filepath)) {
                            // $filesize += filesize($filepath); //===累加大小
                            $filesize = filesize($filepath);
                            if (!$Capacity->change_capa($filesize, 'use')) {
                                return Response::json(['err' => 0, 'msg' => '空间容量不足', 'data' => 1005]);
                                //$enough = 0;//容量不足的标记
                                //break;//容量不足，退出循环，执行上传
                            }
                            $file = explode('.', $fileName);
                            $type = end($file);
                            //将图片由缓存目录移动到站点图片目录
                            $up_result = copy(public_path('customers/' . $customer . '/cache_images/' . $fileName), $destinationPath . '/l/' . $target . '/' . $fileName);
                            if ($up_result) {
                                //PC站图片缩略图目录
                                $s_path = $destinationPath . '/s/' . $target . '/' . $fileName;
                                $img_info = getimagesize($destinationPath . '/l/' . $target . '/' . $fileName);
                                switch ($img_info[2]) {
                                    case 1:
                                        $type = 'gif';
                                        break;
                                    case 2:
                                        $type = 'jpg';
                                        break;
                                    case 3:
                                        $type = 'png';
                                        break;
                                }
                                //生成PC站图片缩略图
                                $this->resizeImage($destinationPath . '/l/' . $target . '/' . $fileName, $type, $s_path, $img_size, $img_size);
                                //复制图片到手机站目录
                                copy($destinationPath . '/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/' . $target . '/' . $fileName));
                                //手机站图片缩略图目录
                                $mobile_s_path = public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName;
                                //生成手机站图片缩略图
                                $this->resizeImage(public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, $type, $mobile_s_path, $img_size, $img_size);
                                if (trim($ftp) == '1') {//使用公司空间：压缩后上传
                                    $zip->addFile($destinationPath . '/l/' . $target . '/' . $fileName, 'images/l/' . $target . '/' . $fileName);
                                    $zip->addFile($destinationPath . '/s/' . $target . '/' . $fileName, 'images/s/' . $target . '/' . $fileName);
//                                    $zip->addFile(public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName,'mobile/images/l/' . $target . '/' . $fileName);
//                                    $zip->addFile(public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName,'mobile/images/s/' . $target . '/' . $fileName);
//                                    ftp_put($conn, $customer . '/images/l/' . $target . '/' . $fileName, $destinationPath . '/l/' . $target . '/' . $fileName, FTP_BINARY);
//                                    ftp_put($conn, $customer . '/images/s/' . $target . '/' . $fileName, $destinationPath . '/s/' . $target . '/' . $fileName, FTP_BINARY);
//                                    ftp_put($conn, $customer . '/mobile/images/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, FTP_BINARY);
//                                    ftp_put($conn, $customer . '/mobile/images/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName, FTP_BINARY);
                                } else {//使用客户自己的空间：不压缩，一张张上传
                                    ftp_put($conn, $ftpdir . '/' . 'images/l/' . $target . '/' . $fileName, $destinationPath . '/l/' . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $ftpdir . '/' . 'images/s/' . $target . '/' . $fileName, $destinationPath . '/s/' . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $ftpdir . '/' . 'mobile/images/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, FTP_BINARY);
                                    ftp_put($conn, $ftpdir . '/' . 'mobile/images/s/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName, FTP_BINARY);
                                }
                                $data[$i]['name'] = $fileName;
                                $data[$i]['url'] = asset('customers/' . $customer . '/images/l/' . $target . '/' . $fileName);
                                $data[$i]['s_url'] = '/images/s/' . $target . '/' . $fileName;
                                $i++;
                            }
                        }
                    }
                    $zip->close();
                    if ($conn) {
                        if (trim($ftp) == '1') {
                            $weburl = Customer::where('id', $cus_id)->pluck('weburl');
                            $suf_url = str_replace('http://c', '', $weburl);
                            // $cus_name = strtolower(Customer::where('id', $cus_id)->pluck('name'));
                            $ftp_pcdomain = "http://" . $ftp_array[0] . '/' . $customer;
                            ftp_put($conn, $customer . '/' . $imgzip, public_path('customers/' . $customer . '/' . $imgzip), FTP_BINARY);
                            ftp_put($conn, $customer . '/img_unzip.php', public_path('packages/img_unzip.php'), FTP_ASCII);
                            @$res1 = file_get_contents("$ftp_pcdomain/img_unzip.php?img=" . $imgzip);
                            $i = 1;
                            while($i<=3 && $res1!=1000){
                                $j = 1;
                                while($j<=3 && $res1!=1000){
                                   $ftp_pcdomain = "http://" . $ftp_array[0] . ':808' . $j . '/' . $customer;
                                   @$res1 = file_get_contents("$ftp_pcdomain/img_unzip.php?img=" . $imgzip);
                                   $j++;
                                }                                                                
                                $i++;
                            }
                            // @unlink(public_path('customers/' . $customer . '/img.zip'));
                        }
                    }
					if($customerinfo->ftp_address_b){						
						$ftp_array_b = explode(':', $customerinfo->ftp_address_b);
						$ftp_array_b[1] = isset($ftp_array_b[1]) ? $ftp_array_b[1] : $port;
						$conn_b = ftp_connect($ftp_array_b[0], $ftp_array_b[1]);
						if($conn_b){
							ftp_login($conn_b, $customerinfo->ftp_user_b, $customerinfo->ftp_pwd_b);
							ftp_pasv($conn_b, 1);
							if (trim($ftp) == '1') {							
								$ftp_pcdomain_b = "http://" . $ftp_array_b[0] . '/' . $customer;
								ftp_put($conn_b, $customer . '/' . $imgzip, public_path('customers/' . $customer . '/' . $imgzip), FTP_BINARY);
								ftp_put($conn_b, $customer . '/img_unzip.php', public_path('packages/img_unzip.php'), FTP_ASCII);
								@$res2 = file_get_contents("$ftp_pcdomain_b/img_unzip.php?img=" . $imgzip);
                                $i = 1;
                                while($i<=3 && $res2!=1000){
                                    $j = 1;
                                    while($j<=3 && $res2!=1000){
                                       $ftp_pcdomain_b = "http://" . $ftp_array_b[0] . ':808' . $j . '/' . $customer;
                                       @$res2 = file_get_contents("$ftp_pcdomain_b/img_unzip.php?img=" . $imgzip);
                                       $j++;
                                    }                                                                
                                    $i++;
                                }
								//@unlink(public_path('customers/' . $customer . '/img.zip'));
							}
						}
					}

                    //删除压缩包的原位置
                    
                }
                //===扣除空间容量===
                // $Capacity = new CapacityController;
                // if (!$Capacity->change_capa($filesize, 'use')) {
                //     return Response::json(['err' => 0, 'msg' => '空间容量不足', 'data' => 1005]);
                // }
                //===end===
                @ftp_close($conn);
                if(isset($conn_b)){
                    if($conn_b){
                       @ftp_close($conn_b); 
                   }                    
                }

                //小程序上传图片
                if($customerinfo->is_applets) {
                    //图片压缩包是否存在
                    if(file_exists(public_path('customers/' . $customer . '/' . $imgzip))) {
                        //A服
                        if($customerinfo->xcx_a) {
                            $xcx_array_a = explode(':', $customerinfo->xcx_a);
                            $xcx_array_a[1] = isset($xcx_array_a[1]) ? $xcx_array_a[1] : 21;
                            $xconn_a = ftp_connect($xcx_array_a[0], $xcx_array_a[1]);
                            if($xconn_a) {
                                $xcx_pcdomain_a = "http://" . $xcx_array_a[0] . '/' . $customer;
                                ftp_login($xconn_a, $customerinfo->xusr_a, $customerinfo->xpwd_a);
                                ftp_pasv($xconn_a, 1);
                                ftp_put($xconn_a, $customer . '/' . $imgzip, public_path('customers/' . $customer . '/' . $imgzip), FTP_BINARY);
                                ftp_put($xconn_a, $customer . '/img_unzip.php', public_path('packages/iwx_unzip.php'), FTP_ASCII);
                                ftp_close($xconn_a);
                                //解压
                                $res3 = file_get_contents("$xcx_pcdomain_a/img_unzip.php?img=" . $imgzip);
                            }
                        }
                        //B服
                        if($customerinfo->xcx_b) {
                            $xcx_array_b = explode(':', $customerinfo->xcx_b);
                            $xcx_array_b[1] = isset($xcx_array_b[1]) ? $xcx_array_b[1] : 21;
                            $xconn_b = ftp_connect($xcx_array_b[0], $xcx_array_b[1]);
                            if($xconn_b) {
                                $xcx_pcdomain_b = "http://" . $xcx_array_b[0] . '/' . $customer;
                                ftp_login($xconn_b, $customerinfo->xusr_b, $customerinfo->xpwd_b);
                                ftp_pasv($xconn_b, 1);
                                ftp_put($xconn_b, $customer . '/' . $imgzip, public_path('customers/' . $customer . '/' . $imgzip), FTP_BINARY);
                                ftp_put($xconn_b, $customer . '/img_unzip.php', public_path('packages/iwx_unzip.php'), FTP_ASCII);
                                ftp_close($xconn_b);
                                $res4 = file_get_contents("$xcx_pcdomain_b/img_unzip.php?img=" . $imgzip);
                            }
                        }
                    }                    
                }

                //如果容量不足，返回错误码以跳转页面
                // if(!$enough) {
                //     return Response::json(['err' => 0, 'msg' => '空间容量不足', 'data' => 1005]);
                // }

                if(isset($res1)&&isset($res2)){
                    if($res1 != 1000 && $res2 != 1000){
                        @file_put_contents(public_path('customers/' . $customer . '/zk_img.txt'), '1003:'.$imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
                        return Response::json(['err' => 0, 'msg' => '图片推送失败', 'data' => 1003, 'img' => $imgzip]);
                    }elseif($res1 != 1000 && $res2 == 1000){
                        @file_put_contents(public_path('customers/' . $customer . '/zk_img.txt'), '1001:'.$imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
                        return Response::json(['err' => 0, 'msg' => 'A服图片推送失败', 'data' => 1001, 'img' => $imgzip]);
                    }elseif($res1 == 1000 && $res2 != 1000){
                        @file_put_contents(public_path('customers/' . $customer . '/zk_img.txt'), '1002:'.$imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
                        return Response::json(['err' => 0, 'msg' => 'B服图片推送失败', 'data' => 1002, 'img' => $imgzip]);
                    }
                    file_put_contents(public_path('logs/img_log/'.date('Y-m-d',time()).'.txt'), $customer.','.$res1.','.$res2.','.$imgzip.','.date('Y-m-d H:i:s',time()).';'.PHP_EOL,FILE_APPEND);
                }elseif(isset($res1)&&!isset($res2)){
                    if($res1 != 1000){                            
                        @file_put_contents(public_path('customers/' . $customer . '/zk_img.txt'), '1001:'.$imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
                        return Response::json(['err' => 0, 'msg' => 'A服推送失败', 'data' => 1001, 'img' => $imgzip]);
                    }
                }elseif(isset($res1)&&!isset($res2)){
                    if($res2 != 1000){
                        @file_put_contents(public_path('customers/' . $customer . '/zk_img.txt'), '1002:'.$imgzip.','.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
                        return Response::json(['err' => 0, 'msg' => 'B服推送失败', 'data' => 1002, 'img' => $imgzip]);
                    }
                }
                @unlink(public_path('customers/' . $customer . '/' . $imgzip));

                return Response::json(['err' => 0, 'msg' => '保存成功', 'data' => $data]);
            }
        } else {
            //===判断服务器是否正常===
            if ($weburl) {
                if ($this->MonitorCheck($weburl)) {
                    $return = ['err' => 1001, 'msg' => '保存失败', 'data' => ''];
                } else {
                    return Response::json(['err' => 1001, 'msg' => '请检测服务器是否正常', 'data' => '']);
                }
            } else {
                $return = ['err' => 1001, 'msg' => '保存失败', 'data' => ''];
            }
            return Response::json($return);
        }
    }

    /**
     * 文件上传
     * ===点击选择图片后上传===
     */
    public function fileupload()
    {
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $target = Input::get('target');
        $files = Input::file();
        $dir = public_path('customers/' . $customer . '/cache_images/');
        $customerinfo = Customer::find($cus_id);
        $weburl = Customer::where('id', $cus_id)->pluck('weburl');
        if (!is_dir($dir)) {
            $this->CreateAllDir();
            mkdir($dir, 0777, true);
        }
//        $size = 0; //===文件大小
        if ($files) {
            if ($target == 'imgcache') {
                $id = $cus_id;
                $filename = Input::get('filename');
                $filename = explode('.', $filename);
                $filetype = end($filename);
                $name = WebsiteInfo::leftJoin('template', 'pc_tpl_id', '=', 'template.id')->where('website_info.cus_id', $id)->pluck('name');
                if ($files['upload_file0']->isValid()) {
                    $type = $files['upload_file0']->getClientOriginalExtension();
                    $truth_name = time() . mt_rand(100, 999) . '.' . $type;
                    $up_result = $files['upload_file0']->move(public_path('templates/' . $name . '/img_cache/'), $truth_name);
                    if ($up_result) {
                        $load['name'] = $truth_name;
                        if ($filetype == 'html')
                            $load['url'] = '{$site_url}images/' . $truth_name;
                        elseif ($filetype == 'json')
                            $load['url'] = 'images/' . $truth_name;
                        else
                            $load['url'] = '../images/' . $truth_name;
                        $return = ['err' => 0, 'msg' => '图片上传成功', 'data' => $load];
                    } else {
                        //===判断服务器是否正常===
                        if ($weburl) {
                            if ($this->MonitorCheck($weburl)) {
                                $return = ['err' => 1001, 'msg' => '图片上传失败', 'data' => ''];
                            } else {
                                return Response::json(['err' => 1001, 'msg' => '服务器链接失败', 'data' => '']);
                            }
                        } else {
                            $return = ['err' => 1001, 'msg' => '图片上传失败', 'data' => ''];
                        }
                    }
                }
                return Response::json($return);
            } else {
                $data = array();
                $i = 0;
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $type = $file->getClientOriginalExtension();
                        $fileName = time() . str_random(4) . '.' . $type;
                        $up_result = $file->move($dir . '/', $fileName);
                        if ($up_result) {
                            $data[$i]['name'] = $fileName;
                            $data[$i]['url'] = asset('customers/' . $customer . '/cache_images/' . $fileName);
                            $i++;
                        }
                    }
                }
                return Response::json(['err' => 0, 'msg' => '$target!=imgcache', 'data' => $data]);
            }
        } else {
            //===判断服务器是否正常===
            // if ($weburl) {
            //     if ($this->MonitorCheck($weburl) == false) {
            //         return Response::json(['err' => 1001, 'msg' => '服务器链接失败', 'data' => []]);
            //     }
            // }
            $file = Input::get('image');
            if (strpos($file, 'jpeg')) {
                $type = 'jpg';
            } else {
                $type = 'png';
            }
            $fileName = time() . str_random(4) . '.' . $type;
            if (strpos($file, 'jpeg')) {
                $upload = file_put_contents($dir . '/' . $fileName, base64_decode(preg_replace('/data\:image\/jpeg\;base64\,/i', '', $file)));
            } else {
                $upload = file_put_contents($dir . '/' . $fileName, base64_decode(preg_replace('/data\:image\/png\;base64\,/i', '', $file)));
            }
            if ($upload) {
                return Response::json(['err' => 0, 'msg' => 'empty($file)&&$upload', 'data' => ['name' => $fileName, 'url' => asset('customers/' . $customer . '/cache_images/' . $fileName), 's_url' => '/images/s/' . $target . '/' . $fileName]]);
            } else {
                return Response::json(['err' => 1001, 'msg' => '上传文件失败', 'data' => []]);
            }
        }
    }

    private function CreateAllDir()
    {
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $weburl = Customer::where('id', $cus_id)->pluck('weburl');
        $suf_url = str_replace('http://c', '', $weburl);
        if (!file_exists(public_path('customers/' . $customer)))
            mkdir(public_path('customers/' . $customer));
        $zip = new ZipArchive; //新建一个ZipArchive的对象
        if ($zip->open(public_path('packages/customernull.zip')) === TRUE) {
            $zip->extractTo(public_path('customers/' . $customer));
        }
        $zip->close();
        $customerinfo = Customer::find($cus_id);
        $ftp_array = explode(':', $customerinfo->ftp_address);
        $port = $customerinfo->ftp_port;
        $ftpdir = $customerinfo->ftp_dir;
        $ftp = $customerinfo->ftp;
        $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
        $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
        if ($conn) {
            ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
            ftp_pasv($conn, 1);
            if (trim($ftp) == '1') {
                if (ftp_nlist($conn, $customer) === FALSE) {
                    ftp_mkdir($conn, $customer);
                }
                ftp_put($conn, $customer . "/unzip.php", public_path("packages/unzip.php"), FTP_ASCII);
                ftp_put($conn, $customer . "/site.zip", public_path('packages/customernull.zip'), FTP_BINARY);
                @file_get_contents('http://' . $customer . $suf_url . "/unzip.php");
            } else {
                if (ftp_nlist($conn, $ftpdir) === FALSE) {
                    ftp_mkdir($conn, $ftpdir);
                }
                if (ftp_nlist($conn, $ftpdir . '/mobile') === FALSE) {
                    ftp_mkdir($conn, $ftpdir . '/mobile');
                }
                $domain = strlen(str_replace('http://', '', $customerinfo->pc_domain)) > 0 ? $customerinfo->pc_domain . '/mobile' : $customerinfo->mobile_domain;
                ftp_put($conn, $ftpdir . "/mobile/m_unzip.php", public_path("packages/m_unzip.php"), FTP_ASCII);
                ftp_put($conn, $ftpdir . "/mobile/site.zip", public_path('packages/customernull.zip'), FTP_BINARY);
                @file_get_contents("$domain/m_unzip.php");
            }
            ftp_close($conn);
        }
		if($customerinfo->ftp_address_b){
			$ftp_array_b = explode(':', $customerinfo->ftp_address_b);
			$ftp_array_b[1] = isset($ftp_array_b[1]) ? $ftp_array_b[1] : $port;
			$conn_b = ftp_connect($ftp_array_b[0], $ftp_array_b[1]);
			if($conn_b){
				ftp_login($conn_b, $customerinfo->ftp_user_b, $customerinfo->ftp_pwd_b);
				ftp_pasv($conn_b, 1);
				if(trim($ftp) == '1'){
					if (ftp_nlist($conn_b, $customer) === FALSE) {
						ftp_mkdir($conn_b, $customer);
					}
					ftp_put($conn_b, $customer . "/unzip.php", public_path("packages/unzip.php"), FTP_ASCII);
					ftp_put($conn_b, $customer . "/site.zip", public_path('packages/customernull.zip'), FTP_BINARY);
					@file_get_contents('http://' . $ftp_array_b[0] . '/' . $customer . "/unzip.php");
				}
			}
			ftp_close($conn_b);
		}
    }

    private function check_dir($dirName, $customer)
    {
        $path_arr = array(
            public_path("customers/$customer/images/l/$dirName"),
            public_path("customers/$customer/images/s/$dirName"),
            public_path("customers/$customer/mobile/images/l/$dirName"),
            public_path("customers/$customer/mobile/images/s/$dirName")
        );
        foreach ($path_arr as $dirPath) {
            if (!file_exists($dirPath)) {
                $this->CreateAllDir();
            }
        }
    }

    private function openImage($fileName, $type)
    {
        switch ($type) {
            case 'jpg':
                $img = @imagecreatefromjpeg($fileName);
                break;
            case 'gif':
                $img = @imagecreatefromgif($fileName);
                break;
            case 'png':
                $img = @imagecreatefrompng($fileName);
                break;
            default:
                $img = false;
        }
        return $img;
    }

    public function resizeImage($src, $type, $path, $newWidth, $newHeight)
    {
        $image = $this->openImage($src, $type);
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = $height / $width;
        $newHeight = $newWidth * $ratio;
        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        $alpha = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $alpha);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagesavealpha($canvas, true);
        $this->saveImage($type, $canvas, $path);
    }

    public function saveImage($type, $canvas, $path)
    {
        switch ($type) {
            case 'jpg':
                imagejpeg($canvas, $path, 100);
                break;
            case 'gif':
                imagegif($canvas, $path);
                break;
            case 'png':
                imagepng($canvas, $path, 0);
                break;
            default:
                break;
        }
        imagedestroy($canvas);
    }

    //图片失败重推
    public function pushagain(){
        $push = Input::get('msg');
        $imgzip = Input::get('img');
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $customerinfo = Customer::find($cus_id);
        $path = public_path('customers/' . $customer . '/' . $imgzip);
        if(file_exists($path)){             
            if($push == 1001){
                $push = $this->pushimga($customer,$customerinfo,$imgzip);
                if($push == 1000){
                    @unlink(public_path('customers/' . $customer . '/' . $imgzip));
                    return Response::json(['err' => 0, 'msg' => 'A服务器重传成功', 'data' => '']);                
                }else{
                    return Response::json(['err' => 1001, 'msg' => 'A服务器重传失败，请联系技术人员', 'data' => '']);
                } 
            }elseif($push == 1002){
                $push = $this->pushimgb($customer,$customerinfo,$imgzip);
                if($push == 1000){
                    @unlink(public_path('customers/' . $customer . '/' . $imgzip));
                    return Response::json(['err' => 0, 'msg' => 'B服务器重传成功', 'data' => '']);
                }else{
                    return Response::json(['err' => 1002, 'msg' => 'B服务器重传失败，请联系技术人员', 'data' => '']);
                } 
            }elseif($push == 1003){
                $pusha = $this->pushimga($customer,$customerinfo,$imgzip);
                $pushb = $this->pushimgb($customer,$customerinfo,$imgzip);
                if($pusha == 1000 && $pushb == 1000){
                    @unlink(public_path('customers/' . $customer . '/' . $imgzip));
                    return Response::json(['err' => 0, 'msg' => '服务器重传成功', 'data' => '']);
                }elseif($pusha == 1000 && $pushb!= 1000){
                    return Response::json(['err' => 1001, 'msg' => 'A服务器重传失败，请联系技术人员', 'data' => '']);
                }elseif($pushb == 1000 && $pusha != 1000){
                    return Response::json(['err' => 1002, 'msg' => 'B服务器重传失败，请联系技术人员', 'data' => '']);
                }else{
                    return Response::json(['err' => 1003, 'msg' => '服务器重传失败，请联系技术人员', 'data' => '']);
                } 
            }                    
        }else{
            return Response::json(['err' => 1004, 'msg' => '压缩包图片已删除，请重新上传', 'data' => '']);
        }
    }
    //A服推送图片
    public function pushimga($customer,$customerinfo,$imgzip){
        $ftp = $customerinfo->ftp;
        $ftpdir = $customerinfo->ftp_dir;
        $port = $customerinfo->ftp_port;
        //A服
        $ftp_array = explode(':', $customerinfo->ftp_address);
        $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : $port;
        $ftp_pcdomain = "http://" . $ftp_array[0] . '/' . $customer;

        $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
        if($conn){
            ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
            ftp_pasv($conn, 1);               
            ftp_put($conn, $customer . '/' . $imgzip, public_path('customers/' . $customer . '/' . $imgzip), FTP_BINARY);
            ftp_put($conn, $customer . '/img_unzip.php', public_path('packages/img_unzip.php'), FTP_ASCII);
            $ftp_pcdomain = "http://" . $ftp_array[0] . '/' . $customer;
            @$res = file_get_contents("$ftp_pcdomain/img_unzip.php?img=" . $imgzip);
            @ftp_close($conn);

            return $res;    
        } 
    }
    //B服推送图片
    public function pushimgb($customer,$customerinfo,$imgzip){
        $ftp = $customerinfo->ftp;
        $ftpdir = $customerinfo->ftp_dir;
        $port = $customerinfo->ftp_port;
        
        //B服
        $ftp_array_b = explode(':', $customerinfo->ftp_address_b);
        $ftp_array_b[1] = isset($ftp_array_b[1]) ? $ftp_array_b[1] : $port;
        $ftp_pcdomain_b = "http://" . $ftp_array_b[0] . '/' . $customer; 

        $conn_b = ftp_connect($ftp_array_b[0], $ftp_array_b[1]);
        if($conn_b){
            ftp_login($conn_b, $customerinfo->ftp_user_b, $customerinfo->ftp_pwd_b);
            ftp_pasv($conn_b, 1);            
            ftp_put($conn_b, $customer . '/' . $imgzip, public_path('customers/' . $customer . '/' . $imgzip), FTP_BINARY);
            ftp_put($conn_b, $customer . '/img_unzip.php', public_path('packages/img_unzip.php'), FTP_ASCII);
            $ftp_pcdomain = "http://" . $ftp_array_b[0] . '/' . $customer;
            @$res = file_get_contents("$ftp_pcdomain/img_unzip.php?img=" . $imgzip);
            @ftp_close($conn_b);
            
            return $res;    
        } 
    }

    //整站推送图片
    public function pushimg(){
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $customerinfo = Customer::find($cus_id);
        $imgzip = 'img.zip';

        $path = public_path('customers/' . $customer . '/img.zip');
        $images = public_path('customers/' . $customer . '/images');
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE) === TRUE) {
            $this->addImagesToZip($images,$zip);
            $zip->close();

            //小程序整站重推
            if($customerinfo->is_applets) {
                //A服
                if($customerinfo->xcx_a) {
                    $this->wxImages($customerinfo->xcx_a, $customerinfo->xusr_a, $customerinfo->xpwd_a, $customer, $path);
                }
                //B服
                if($customerinfo->xcx_b) {
                    $this->wxImages($customerinfo->xcx_b, $customerinfo->xusr_b, $customerinfo->xpwd_b, $customer, $path);
                }
            }
        }

        //A服
        $res1 = $this->pushimga($customer,$customerinfo,$imgzip);

        //B服
        if($customerinfo->ftp_address_b){
            $res2 = $this->pushimgb($customer,$customerinfo,$imgzip);
        }else{
            $res2 = 1000;
        }

        if($res1==1000&&$res2==1000){
            @unlink(public_path('customers/' . $customer . '/img.zip'));
            return Response::json(['err' => 0, 'msg' => '整站图片推送成功', 'data' => '']);            
        }elseif($res1!=1000&&$res2==1000){
            return Response::json(['err' => 1001, 'msg' => 'A服务器推送失败', 'data' => '']);
        }elseif($res1==1000&&$res2!=1000){
            return Response::json(['err' => 1002, 'msg' => 'B服务器推送失败', 'data' => '']);
        }else{
            return Response::json(['err' => 1003, 'msg' => '整站图片推送失败', 'data' => '']);
        }

    }

    //压缩用户图片目录
    public function addImagesToZip($images, $zip){
        $lastDir = explode('images/', $images);
        if(count($lastDir)<=1){
            $lastDir = 'images';
        }else{
            $lastDir = 'images/' . end($lastDir);
        }
        $array = $lastDir;
        $handler = opendir($images);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                if (is_dir($images . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    if (count(@scandir($images . "/" . $filename)) == 2) {
                        $zip->addEmptyDir($array . '/' . $filename);
                    } else {
                        $this->addImagesToZip($images . "/" . $filename, $zip);
                    }
                } else { //将文件加入zip对象
                    $zip->addFile($images . "/" . $filename, $array . '/' . $filename);
                }
            }
        }
        @closedir($images); 
    }

    //推送小程序图片压缩包
    public function wxImages($ftp, $usr, $pwd, $customer, $path) {
        $xftp_array = explode(':', $ftp);
        $xftp_array[1] = isset($xftp_array[1]) ? $xftp_array[1] : 21;
        $xconn = ftp_connect($xftp_array[0], $xftp_array[1]);
        ftp_login($xconn, $usr, $pwd);
        ftp_pasv($xconn, 1);
        ftp_put($xconn, $customer . '/img.zip', $path, FTP_BINARY);
        ftp_put($xconn, $customer . '/img_unzip.php', public_path('packages/iwx_unzip.php'), FTP_ASCII);
        $ftp_pcdomain = "http://" . $xftp_array[0] . '/' . $customer;
        file_get_contents("$ftp_pcdomain/img_unzip.php");
        ftp_close($xconn);
    }

}
