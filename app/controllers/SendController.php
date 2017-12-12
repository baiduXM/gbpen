<?php

class SendController extends BaseController
{
    //图片数组
    protected $imageArr = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'];
    //用户ID
    protected $cus_id;
    //用户名
    protected $customer;    

    //G名片申请
    public function weicardApply(){

        //提交的数据
        $data['company'] = Input::get('txtCompanyName');
        $data['name'] = Input::get('txtName');
        $data['phone'] = Input::get('txtContact');
        $data['city'] = Input::get('txtCity');
        $data['email'] = Input::get('txtContent');
        $data['num'] = Input::get('select3');

        //数据验证
        if(!$data['company']){
            $result = ['err' => 1008, 'msg' => '公司名不能为空'];
            return Response::json($result);
        }
        if(!$data['name']){
            $result = ['err' => 1008, 'msg' => '姓名不能为空'];
            return Response::json($result);
        }
        if(!is_numeric($data['phone'])){
            $result = ['err' => 1008, 'msg' => '电话格式错误'];
            return Response::json($result);
        }
        if(!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$data['email'])){
            $result = ['err' => 1008, 'msg' => '邮箱格式错误'];
            return Response::json($result);
        }

        //其他数据
        $this->cus_id = Auth::id();
        $this->customer = Auth::user()->name;
        $data['cus_id'] = $this->cus_id;
        $data['cus_name'] = $this->customer;
        $data['date'] = date('Y-m-d H:i:s',time());
        
        //上传文件处理
        if(Input::hasFile('image')){
            if (Input::file('image')->isValid())
            {
                $image = Input::file('image');
                $fileRes = $this -> saveImg($image);
                if($fileRes['err'] == 1000){
                    $data['image'] = $fileRes['msg'];
                } else {
                    $result = ['err' => $fileRes['err'], 'msg' => $fileRes['msg']];
                    return Response::json($result);
                }
            } else {
                $result = ['err' => 1003, 'msg' => '无效文件'];
                return Response::json($result);
            }           
        } else {
            $result = ['err' => 1002, 'msg' => '文件上传失败'];
            return Response::json($result);
        }

        //插入数据库
        $id = GApply::insertGetId($data);
        if($id){
            //提交数据到G宝盆官网
            $send = $this -> sendtoGbpen($data);
            if($send['err'] == 1000){
                $result = ['err' => 1000, 'msg' => '提交成功'];
            }else{
                $res = GApply::where('id' , $id)->update(['status' => 0]);
                $result = ['err' => $send['err'], 'msg' => $send['msg']];
            }
        }else{
            $result = ['err' => 1001, 'msg' => '写入失败'];
        }        

        return Response::json($result);
        
    }

    //保存图片
    public function saveImg($image){
        //目标路径
        $path = public_path('customers/'. $this->customer. '/images/other');
        if(!is_dir($path)){
            @mkdir($path);
        }        
        $extension = strtolower($image->getClientOriginalExtension());//拓展名
        if(in_array($extension, $this->imageArr)) {
            $fileName = time(). str_random(4). '.'. $extension;//文件名      
            if($image->move($path, $fileName)){
                $result = ['err' => 1000, 'msg' => $fileName];
            } else {
                $result = ['err' => 1004, 'msg' => '文件移动失败'];
            }
        } else {
            $result = ['err' => 1005, 'msg' => '格式不正确'];
        }        
        
        return $result;
    }

    //提交数据到G宝盆官网
    protected function sendtoGbpen($data){
        //目标路径
        $destinationPath = './uploads/G_leaveword/'. $data['image'];
        //判断文件是否存在
        $localFile = public_path('customers/'. $this->customer. '/images/other/'. $data['image']);
        if(file_exists($localFile)){
            //上传图片到G宝盆官网
            //A服
            $conn = ftp_connect(GBPEN_FTP , GBPEN_FTP_PORT);
            if($conn){
                ftp_login($conn, GBPEN_FTP_USER, GBPEN_FTP_PASSWORD);
                ftp_pasv($conn, 1);
                ftp_put($conn, $destinationPath, $localFile, FTP_BINARY);
                ftp_close($conn);
            }
            //B服
            // $conn_b = ftp_connect(GBPEN_FTP_B , GBPEN_FTP_PORT);
            // if($conn_b){
            //     ftp_login($conn_b, GBPEN_FTP_USER, GBPEN_FTP_PASSWORD);
            //     ftp_pasv($conn_b, 1);
            //     ftp_put($conn_b, $destinationPath, $localFile, FTP_BINARY);
            //     ftp_close($conn_b);
            // }
        } else {
            $result = ['err' => 1006, 'msg' => '文件不存在'];
            return $result;
        }

        //发送数据
        $common = new CommonController();
        $res = $common->postsend(GBPEN_DOMAIN . "/leaveword4.php", $data);
        $res = json_decode($res,true);
        if($res['err'] == 1000){
            $result = ['err' => 1000, 'msg' => 'G宝盆官网同步成功'];
        } else {
            $result = ['err' => $res['err'], 'msg' => $res['msg']];
        }

        return $result;

    }

}
