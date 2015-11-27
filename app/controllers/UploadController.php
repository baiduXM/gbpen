<?php

class UploadController extends BaseController{

	public function fileRead(){
        $customer = Auth::user()->name;
        $cus_id = Auth::id();
        $target = Input::get('target');
        $this->check_dir($target,$customer);
        $files=Input::file();
        $destinationPath = public_path('customers/'.$customer.'/images/');
        if($files){
            $data=array();
            $i=0;
            foreach($files as $file){
                if($file -> isValid()){
                    $type = $file->getClientOriginalExtension();
                    $fileName=time().str_random(4).'.'.$type;
                    $up_result=$file->move($destinationPath.'/l/'.$target.'/',$fileName);
                    if($up_result){
                        $s_path = $destinationPath.'/s/'.$target.'/'.$fileName;
                        $img_info=  getimagesize($destinationPath.'/l/'.$target.'/'.$fileName);
                        switch($img_info[2]){
                            case 1:$type='gif';break;
                            case 2:$type='jpg';break;
                            case 3:$type='png';break;
                        }
                        $this->resizeImage($destinationPath.'/l/'.$target.'/'.$fileName,$type,$s_path,400,400);
                        copy($destinationPath.'/s/'.$target.'/'.$fileName, public_path('customers/'.$customer.'/mobile/images/l/'.$target.'/'.$fileName));
                        $mobile_s_path=public_path('customers/'.$customer.'/mobile/images/s/').$target.'/'.$fileName;
                        $this->resizeImage(public_path('customers/'.$customer.'/mobile/images/l/').$target.'/'.$fileName,$type,$mobile_s_path,400,400);
                       //同步到客户服务器
                        $customerinfo = Customer::find($cus_id);
                        $ftp_array = explode(':',$customerinfo->ftp_address);
                        $ftp_array[1] = isset($ftp_array[1])?$ftp_array[1]:'21';
                        $conn = ftp_connect($ftp_array[0],$ftp_array[1]);
                        if($conn){
                            ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                            ftp_pasv($conn, 1);
                            ftp_put($conn,$customer.'images/l/'.$target.'/'.$fileName,$destinationPath.'/l/'.$target.'/'.$fileName,FTP_BINARY);
                            ftp_put($conn,$customer.'images/s/'.$target.'/'.$fileName,$destinationPath.'/s/'.$target.'/'.$fileName,FTP_BINARY);
                            ftp_put($conn,$customer.'mobile/images/l/'.$target.'/'.$fileName,public_path('customers/'.$customer.'/mobile/images/l/').$target.'/'.$fileName,FTP_BINARY);
                            ftp_put($conn,$customer.'mobile/images/s/'.$target.'/'.$fileName,public_path('customers/'.$customer.'/mobile/images/s/').$target.'/'.$fileName,FTP_BINARY);
                            ftp_close($conn);
                        }
                        $data[$i]['name']=$fileName;
                        $data[$i]['url']=asset('customers/'.$customer.'/images/l/'.$target.'/'.$fileName);
                        $i++;
                    }
                }
            }
            return Response::json(['err' => 0, 'msg' => '','data'=>$data]);
        }else{
            $file = Input::get('image');
            if(strpos($file,'jpeg')){
                $type = 'jpg';
            }else{
                $type = 'png'; 
            }
            $fileName = time().str_random(4).'.'.$type;
            if(strpos($file,'jpeg')){
                $upload = file_put_contents($destinationPath.'/l/'.$target.'/'.$fileName,base64_decode(preg_replace('/data\:image\/jpeg\;base64\,/i','',$file)));
            }else{
                $upload = file_put_contents($destinationPath.'/l/'.$target.'/'.$fileName,base64_decode(preg_replace('/data\:image\/png\;base64\,/i','',$file)));               
            }
            if($upload){
                $s_path = $destinationPath.'/s/'.$target.'/'.$fileName;
                $img_info=  getimagesize($destinationPath.'/l/'.$target.'/'.$fileName);
                switch($img_info[2]){
                    case 1:$type='gif';break;
                    case 2:$type='jpg';break;
                    case 3:$type='png';break;
                }
                $this->resizeImage($destinationPath.'/l/'.$target.'/'.$fileName,$type,$s_path,400,400);
                copy($destinationPath.'/s/'.$target.'/'.$fileName, public_path('customers/'.$customer.'/mobile/images/l/'.$target.'/'.$fileName));
                $mobile_s_path=public_path('customers/'.$customer.'/mobile/images/s/').$target.'/'.$fileName;
                $this->resizeImage(public_path('customers/'.$customer.'/mobile/images/l/').$target.'/'.$fileName,$type,$mobile_s_path,400,400);
               //同步到客户服务器
                $customerinfo = Customer::find($cus_id);
                $ftp_array = explode(':',$customerinfo->ftp_address);
                $ftp_array[1] = isset($ftp_array[1])?$ftp_array[1]:'21';
                $conn = ftp_connect($ftp_array[0],$ftp_array[1]);
                 if($conn){
                    ftp_login($conn,$customerinfo->ftp_user,$customerinfo->ftp_pwd);
                    ftp_pasv($conn, 1);
                    ftp_put($conn,$customer.'/'.'images/l/'.$target.'/'.$fileName,$destinationPath.'/l/'.$target.'/'.$fileName,FTP_BINARY);
                    ftp_put($conn,$customer.'/'.'images/s/'.$target.'/'.$fileName,$destinationPath.'/s/'.$target.'/'.$fileName,FTP_BINARY);
                    ftp_put($conn,$customer.'/'.'mobile/images/l/'.$target.'/'.$fileName,public_path('customers/'.$customer.'/mobile/images/l/').$target.'/'.$fileName,FTP_BINARY);
                    ftp_put($conn,$customer.'/'.'mobile/images/s/'.$target.'/'.$fileName,public_path('customers/'.$customer.'/mobile/images/s/').$target.'/'.$fileName,FTP_BINARY);
                    ftp_close($conn);
                 }

                return Response::json(['err' => 0, 'msg' => '','data'=>['name' => $fileName,'url' => asset('customers/'.$customer.'/images/l/'.$target.'/'.$fileName)]]);

            }
            else{
                return Response::json(['err' => 1001, 'msg' => '上传文件失败','data'=>[]]);
            }            
        }
	}
	
    private function check_dir($dirName,$customer){
        $path_arr=array(
            public_path("customers/$customer/images/l/$dirName"),
            public_path("customers/$customer/images/s/$dirName"),
            public_path("customers/$customer/mobile/images/l/$dirName"),
            public_path("customers/$customer/mobile/images/s/$dirName")
        );
        foreach($path_arr as $dirPath){
            if(!file_exists($dirPath)){
                mkdir($dirPath);
            }
        }
    }
    
	private function openImage($fileName,$type) {
        switch($type) {
        case 'jpg':$img = @imagecreatefromjpeg($fileName);break;
        case 'gif':$img = @imagecreatefromgif($fileName);break;
        case 'png':$img = @imagecreatefrompng($fileName);break;
        default:$img = false;
        }
        return $img;
    }
    
    public function resizeImage($src,$type,$path,$newWidth,$newHeight) {
        $image = $this->openImage($src,$type);
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = $height/$width;
        $newHeight = $newWidth*$ratio;
        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        $alpha = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $alpha);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagesavealpha($canvas, true);
        $this->saveImage($type,$canvas,$path);
    }
    
    public function saveImage($type,$canvas,$path) {
        switch($type) {
            case 'jpg':imagejpeg($canvas, $path, 100);break;
            case 'gif':imagegif($canvas, $path);break;
            case 'png':imagepng($canvas, $path, 0);break;
            default:break;
        }
        imagedestroy($canvas);
    }
	
}