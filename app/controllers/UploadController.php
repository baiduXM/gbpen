<?php

class UploadController extends BaseController {

    public function fileRead() {
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
                    } else
                        $return = ['err' => 1001, 'msg' => '图片上传失败', 'data' => ''];
                }
                return Response::json($return);
            }else {
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
                                case 1:$type = 'gif';
                                    break;
                                case 2:$type = 'jpg';
                                    break;
                                case 3:$type = 'png';
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
                    case 1:$type = 'gif';
                        break;
                    case 2:$type = 'jpg';
                        break;
                    case 3:$type = 'png';
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
     * @return type
     */
    public function img_upload() {
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
        if ($files) {
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

            foreach ((array) $files as $fileName) {
                if (file_exists(public_path('customers/' . $customer . '/cache_images/' . $fileName))) {
                    $file = explode('.', $fileName);
                    $type = end($file);
                    $up_result = copy(public_path('customers/' . $customer . '/cache_images/' . $fileName), $destinationPath . '/l/' . $target . '/' . $fileName);
                    if ($up_result) {
                        $s_path = $destinationPath . '/s/' . $target . '/' . $fileName;
                        $img_info = getimagesize($destinationPath . '/l/' . $target . '/' . $fileName);
                        switch ($img_info[2]) {
                            case 1:$type = 'gif';
                                break;
                            case 2:$type = 'jpg';
                                break;
                            case 3:$type = 'png';
                                break;
                        }
                        $this->resizeImage($destinationPath . '/l/' . $target . '/' . $fileName, $type, $s_path, $img_size, $img_size);
                        copy($destinationPath . '/l/' . $target . '/' . $fileName, public_path('customers/' . $customer . '/mobile/images/l/' . $target . '/' . $fileName));
                        $mobile_s_path = public_path('customers/' . $customer . '/mobile/images/s/') . $target . '/' . $fileName;
                        $this->resizeImage(public_path('customers/' . $customer . '/mobile/images/l/') . $target . '/' . $fileName, $type, $mobile_s_path, $img_size, $img_size);

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
                        }
                        $data[$i]['name'] = $fileName;
                        $data[$i]['url'] = asset('customers/' . $customer . '/images/l/' . $target . '/' . $fileName);
                        $data[$i]['s_url'] = '/images/s/' . $target . '/' . $fileName;
                        $i++;
                    }
                }
            }
            @ftp_close($conn);
            return Response::json(['err' => 0, 'msg' => '保存成功', 'data' => $data]);
        } else {
            return Response::json(['err' => 1001, 'msg' => '保存失败']);
        }
    }

    /**
     * 文件上传
     * ===点击选择图片后上传===
     */
    public function fileupload() {
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $target = Input::get('target');
        $files = Input::file();
        $dir = public_path('customers/' . $customer . '/cache_images/');
        $customerinfo = Customer::find($cus_id);
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
                    } else
                        $return = ['err' => 1001, 'msg' => '图片上传失败', 'data' => ''];
                }
                return Response::json($return);
            }else {
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
            //===扣除用户空间容量===
            $size = filesize(public_path('customers/' . $customer . '/cache_images/' . $fileName));
            $cus = new CapacityController;
            if (!$cus->change_capa($size, 'use')) {
                return Response::json(['err' => 1, 'msg' => '容量不足', 'data' => []]);
            }
            //===end===
            if ($upload) {
                return Response::json(['err' => 0, 'msg' => 'empty($file)&&$upload', 'data' => ['name' => $fileName, 'url' => asset('customers/' . $customer . '/cache_images/' . $fileName), 's_url' => '/images/s/' . $target . '/' . $fileName]]);
            } else {
                return Response::json(['err' => 1001, 'msg' => '上传文件失败', 'data' => []]);
            }
        }
    }

    private function CreateAllDir() {
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
    }

    private function check_dir($dirName, $customer) {
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

    private function openImage($fileName, $type) {
        switch ($type) {
            case 'jpg':$img = @imagecreatefromjpeg($fileName);
                break;
            case 'gif':$img = @imagecreatefromgif($fileName);
                break;
            case 'png':$img = @imagecreatefrompng($fileName);
                break;
            default:$img = false;
        }
        return $img;
    }

    public function resizeImage($src, $type, $path, $newWidth, $newHeight) {
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

    public function saveImage($type, $canvas, $path) {
        switch ($type) {
            case 'jpg':imagejpeg($canvas, $path, 100);
                break;
            case 'gif':imagegif($canvas, $path);
                break;
            case 'png':imagepng($canvas, $path, 0);
                break;
            default:break;
        }
        imagedestroy($canvas);
    }

}
