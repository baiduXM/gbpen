<?php

/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";
include __DIR__ . '/../../../../vendor/autoload.php';
$laravel_app = include __DIR__ . '/../../../../bootstrap/start.php';
$laravel_app->boot();
include __DIR__ . '/laravelManager.php';
$manager = new laravelSessionManager($laravel_app);
$manager->startSession();
$cus_id = Auth::id();
$cus_name = Auth::user()->name;
/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => "/customers/$cus_name/images/ueditor/{time}{rand:6}",
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => "/customers/$cus_name/images/ueditor/{time}{rand:6}",
            "maxSize" => $CONFIG['scrawlMaxSize'],
            "allowFiles" => $CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => "/customers/$cus_name/images/ueditor/{time}{rand:6}",
            "maxSize" => $CONFIG['videoMaxSize'],
            "allowFiles" => $CONFIG['videoAllowFiles']
        );
        $fieldName = $CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => "/customers/$cus_name/images/ueditor/{time}{rand:6}",
            "maxSize" => $CONFIG['fileMaxSize'],
            "allowFiles" => $CONFIG['fileAllowFiles']
        );
        $fieldName = $CONFIG['fileFieldName'];
        break;
}
//$test=array('sta',1);
//return json_encode($test);

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64);
$up_result = $up->getFileInfo();
/* 同步到客户服务器 */
if ($up_result['state'] == 'SUCCESS') {
    //同步到客户服务器
    $customerinfo = Customer::find($cus_id);
    $ftp_array = explode(':', $customerinfo->ftp_address);
    $ftp_array[1] = isset($ftp_array[1]) ? $ftp_array[1] : '21';
    $conn = ftp_connect($ftp_array[0], $ftp_array[1]);
    if ($conn) {
        ftp_login($conn, $customerinfo->ftp_user, $customerinfo->ftp_pwd);
        /* 编辑器上传图片问题 */
        ftp_pasv($conn, 1);
        //===扣除空间===
//                customers/$cus_name/images/ueditor/
//        $size = filesize(public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']));
//        $reg=ueditor_regular(1024);
//        $cus = new CapacityController;
//        if (!$cus->change_capa($reg, 'use')) {
//            return Response::json(['err' => 1001, 'msg' => '容量不足', 'data' => []]);
//        }
//        var_dump($reg);
//        exit;
        //===扣除空间end===
        if($customerinfo->ftp != 2){
            @ftp_put($conn, $cus_name . '/' . 'images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
            @ftp_put($conn, $cus_name . '/' . 'mobile/images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
        }else{
            @ftp_put($conn, $customerinfo->ftp_dir . '/' . 'images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
            @ftp_put($conn, $customerinfo->ftp_dir . '/' . 'mobile/images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
        }
        
        ftp_close($conn);
    }
    //如果有ftp_b，则再上传一份
    if($customerinfo->ftp_address_b){
        $ftp_array_b = explode(':', $customerinfo->ftp_address_b);
        $ftp_array_b[1] = isset($ftp_array_b[1]) ? $ftp_array_b[1] : '21';
        $conn_b = ftp_connect($ftp_array_b[0], $ftp_array_b[1]);
        if ($conn_b) {
            ftp_login($conn_b, $customerinfo->ftp_user_b, $customerinfo->ftp_pwd_b);
            ftp_pasv($conn_b, 1);
            @ftp_put($conn_b, $cus_name . '/' . 'images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
            @ftp_put($conn_b, $cus_name . '/' . 'mobile/images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
            ftp_close($conn_b);
        }
    }
    //小程序上传图片
    if($customerinfo->is_applets) {
        //A服
        if($customerinfo->xcx_a) {
            $xcx_array_a = explode(':', $customerinfo->xcx_a);
            $xcx_array_a[1] = isset($xcx_array_a[1]) ? $xcx_array_a[1] : 21;
            $xconn_a = ftp_connect($xcx_array_a[0], $xcx_array_a[1]);
            if($xconn_a) {
                $xcx_pcdomain_a = "http://" . $xcx_array_a[0] . '/' . $cus_name;
                ftp_login($xconn_a, $customerinfo->xusr_a, $customerinfo->xpwd_a);
                ftp_pasv($xconn_a, 1);
                @ftp_put($xconn_a, $cus_name . '/' . 'images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
            }
        }
        //B服
        if($customerinfo->xcx_b) {
            $xcx_array_b = explode(':', $customerinfo->xcx_b);
            $xcx_array_b[1] = isset($xcx_array_b[1]) ? $xcx_array_b[1] : 21;
            $xconn_b = ftp_connect($xcx_array_b[0], $xcx_array_b[1]);
            if($xconn_b) {
                $xcx_pcdomain_b = "http://" . $xcx_array_b[0] . '/' . $cus_name;
                ftp_login($xconn_b, $customerinfo->xusr_b, $customerinfo->xpwd_b);
                ftp_pasv($xconn_b, 1);
                @ftp_put($xconn_b, $cus_name . '/' . 'images/ueditor/' . $up_result['title'], public_path('customers/' . $cus_name . '/images/ueditor/' . $up_result['title']), FTP_BINARY);
            }
        }                  
    }
}
/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */
/* 返回数据 */
return json_encode($up_result);
