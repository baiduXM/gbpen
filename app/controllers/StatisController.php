<?php

/**
 * 数据统计
 * @author xieqixiang 
 * 在交互服务器上
 */
class StatisController extends BaseController {

    // public function getCount() {
    //     $cus_id = Auth::id();
    //     $param['cus_id'] = $cus_id;
    //     $postFun = new CommonController;
    //     $res2 = $postFun->postsend("http://swap.5067.org/admin/statis_admin.php", $param);
    //     $data = json_decode($res2);
    //     if ($data != NULL) {
    //         $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $data]);
    //     } else {
    //         $res = Response::json(['err' => 1, 'msg' => '获取统计数据失败', 'data' => null]);
    //     }
    //     return $res;
    // }

    public function getCount() {
        //获取用户信息
        $cus_id = Auth::id();
        $customer = Customer::where('id',$cus_id)->get()->toArray();

        $address = $customer[0]['ftp_address'];
        $name = $customer[0]['name'];
        @$res = file_get_contents('http://'.$address.'/'.$name.'/read.php');
        $data = json_decode($res,true);

        //如果有B服务器
        if($customer[0]['ftp_address_b']){
            $address_b = $customer[0]['ftp_address_b'];
            @$res_b = file_get_contents('http://'.$address_b.'/'.$name.'/read.php');
            $data_b = json_decode($res_b,true);
        }else{
            $data_b =array();
        }
        
        if($data != NULL && $data_b != NULL){
            $end['count_all'] = $data[0]['count_all'] + $data_b[0]['count_all'];
            $end['count_mobile'] = $data[0]['count_mobile'] + $data_b[0]['count_mobile'];
            $end['count_today'] = $data[0]['count_today'] + $data_b[0]['count_today'];
            $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $end]);
        }elseif ($data != NULL) {
            $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $data[0]]);
        } else {
            $res = Response::json(['err' => 1, 'msg' => '获取统计数据失败', 'data' => null]);
        }
        return $res;
    }

}

?>
