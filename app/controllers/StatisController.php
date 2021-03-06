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

        //在线人数
        $timeout = 300;//超时时间
        $time = time();
        $is_out = $time - 300;
        $online = DB::table('sessions')->where('last_activity', '>', $is_out)->count();
        
        if($data != NULL && $data_b != NULL){
            $end['count_all'] = $data[0]['count_all'] + $data_b[0]['count_all'];
            $end['count_mobile'] = $data[0]['count_mobile'] + $data_b[0]['count_mobile'];
            $end['count_today'] = $data[0]['count_today'] + $data_b[0]['count_today'];
            $end['online'] = $online;
            $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $end]);
        } elseif ($data != NULL) {
            $data[0]['online'] = $online;
            $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $data[0]]);
        } elseif($data_b != NULL){
            $data_b[0]['online'] = $online;
            $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $data_b[0]]);
        } else {
            $data['online'] = $online;
            $res = Response::json(['err' => 1, 'msg' => '获取统计数据失败', 'data' => $data]);
        }
        return $res;
    }

    public function customersNum(){
        //客户数(未过滤已删除)
        $n01 = Customer::where('ftp_address','172.16.0.4')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->count();
        $n02 = Customer::where('ftp_address','172.16.0.18')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->count();
        $n03 = Customer::where('ftp_address','172.16.0.24')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->count();
        $hk01 = Customer::where('ftp_address','182.61.100.142')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->count();
        //客户数(过滤已删除)
        $n1 = Customer::where('ftp_address','172.16.0.4')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->count();
        $n2 = Customer::where('ftp_address','172.16.0.18')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->count();
        $n3 = Customer::where('ftp_address','172.16.0.24')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->count();
        $hk1 = Customer::where('ftp_address','182.61.100.142')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->count();
        //双站(未过滤已删除)
        $s01 = Customer::where('ftp_address','172.16.0.4')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->whereIn('stage',array(3,4))->count();
        $s02 = Customer::where('ftp_address','172.16.0.18')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->whereIn('stage',array(3,4))->count();
        $s03 = Customer::where('ftp_address','172.16.0.24')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->whereIn('stage',array(3,4))->count();
        $hks1 = Customer::where('ftp_address','182.61.100.142')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->whereIn('stage',array(3,4))->count();
        //双站(过滤已删除)
        $s1 = Customer::where('ftp_address','172.16.0.4')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->whereIn('stage',array(3,4))->count();
        $s2 = Customer::where('ftp_address','172.16.0.18')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->whereIn('stage',array(3,4))->count();
        $s3 = Customer::where('ftp_address','172.16.0.24')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->whereIn('stage',array(3,4))->count();
        $hks = Customer::where('ftp_address','182.61.100.142')->where('name','not like','GP%')->where('name','not like','GM%')->where('name','not like','GT%')->where('name','not like','GG%')->where('name','not like','%PCN%')->where('name','not like','%MCN%')->where('name','not like','%PEN%')->where('name','not like','%MEN%')->where('is_del',1)->whereIn('stage',array(3,4))->count();

        $count = '用户数量(包括已删除)：<br/>n01:'.$n01.'<br/>n02:'.$n02.'<br/>n03:'.$n03.'<br/>hk01:'.$hk01.'<hr/>过滤已删除用户后：<br>n01:'.$n1.'<br/>n02:'.$n2.'<br/>n03:'.$n3.'<br/>hk01:'.$hk1.'<hr/>
            双站的用户(包括已删除)：<br>n01:'.$s01.'<br/>n02:'.$s02.'<br/>n03:'.$s03.'<br/>hk01:'.$hks1.'<hr/>
            双站的用户：<br>n01:'.$s1.'<br/>n02:'.$s2.'<br/>n03:'.$s3.'<br/>hk01:'.$hks.'<hr/>';
        return $count;
    }

    /**
     * 公告获取
     */
    public function getNotice(){
        $gapply = GApply::where('cus_id',Auth::id())->first();
        if($gapply){
            $result = ['err' => 1002, 'msg' => '已申请过'];
            return Response::json($result);
        }

        $notice = Notice::where('type',0)->where('is_on',1)->orderBy('id','desc')->get()->toArray();
        if($notice){
            $result = ['err' => 1000, 'msg' => $notice];
        } else {
            $result = ['err' => 1001, 'msg' => '无公告'];
        }
        return Response::json($result);
    }

    /**
     * 系统日志获取
     */
    public function getSyslogs(){

        $id = Input::get('id');
        if($id) {
            $syslogs = Notice::where('type',1)->where('id',$id)->first();
        } else {
            $syslogs = Notice::where('type',1)->orderBy('id','desc')->get()->toArray();
        }
        
        if($syslogs){
            $result = ['err' => 1000, 'msg' => $syslogs];
        } else {
            $result = ['err' => 1001, 'msg' => '暂无日志'];
        }
        return Response::json($result);
    }

}

?>
