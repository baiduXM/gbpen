<?php

/**
  |--------------------------------------------------------------------------
  | 日志管理控制器
  |--------------------------------------------------------------------------
  |方法：
  |
  |logsList    日志列表
 */

class LogsController extends BaseController {
     /**
     * 日志列表
     * @return json
     */
    public function logsList() {
        $type = Input::has('type') ? Input::get('type') : '';
        $time1 = Input::has('time1') ? strtotime(Input::get('time1')) : '';
        $time2 = Input::has('time2') ? strtotime(Input::get('time2')) : '';
        $username = Input::has('username') ? Input::get('username') : '';
        $per_page = Input::has('per_page') ? Input::get('per_page') : 15;
        $data = $this->logsListData($per_page,$type,$time1,$time2,$username);
        if ($data != NULL) {
            foreach($data['data'] as &$value){
                $value['operation_time'] = date("Y-m-d H:i:s",$value['operation_time']);
                $value['operation_type'] = $this->getType($value['operation_type']);
            }
            return Response::json(['err' => 0, 'msg' => '', 'data' => $data]);
        } else {
            return Response::json(['err' => 3001, 'msg' => '不存在文章', 'data' => '']);
        }
    }
    
//    public function logsAdda() {
////        $time_min = Logs::min('operation_time');
////        $time_minday = strtotime(date("Y-m-d",  $time_min));
////        $time_tomorrow = $time_minday + 86400;
////        $logs_data = Logs::where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->get()->toArray();
////        if($logs_data){
////            foreach($logs_data as &$value){
////                $value['operation_time'] = date("Y-m-d H:i:s",$value['operation_time']);
////            }
////            $logs_data = str_replace("),","),\r\n\r\n",var_export($logs_data,TRUE));
////            $logs_data = str_replace("operation_time","操作时间",$logs_data);
////            $logs_data = str_replace("operation_table","操作表",$logs_data);
////            $logs_data = str_replace("operation_class","操作类",$logs_data);
////            $logs_data = str_replace("operation_function","操作方法",$logs_data);
////            $logs_data = str_replace("operation_type","操作类型",$logs_data);
////            $logs_data = str_replace("operation_describe","操作描述",$logs_data);
////            $logs_data = str_replace("username","操作用户",$logs_data);
////            $logs_data = str_replace("cus_id","操作用户id",$logs_data);
////            $logs_data = str_replace("fk_id","操作表id",$logs_data);
////            file_put_contents(public_path('logs/'.date("Y-m-d",  $time_min).'.txt'), $logs_data, FILE_APPEND); 
////        }
//        $logs = new Logs();
//        $time_min = $logs->min('operation_time');
//        if(date("Y-m-d",  $time_min) !== date("Y-m-d",  time())){
//            $time_minday = strtotime(date("Y-m-d",  $time_min));
//            $time_tomorrow = $time_minday + 86400;
//            $logs_data = $logs->where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->get()->toArray();
//            if($logs_data){
//                foreach($logs_data as &$value){
//                    $value['operation_time'] = date("Y-m-d H:i:s",$value['operation_time']);
//                }
//                $logs_data = str_replace("),","),\r\n\r\n",var_export($logs_data,TRUE));
//                $logs_data = str_replace("operation_time","操作时间",$logs_data);
//                $logs_data = str_replace("operation_table","操作表",$logs_data);
//                $logs_data = str_replace("operation_class","操作类",$logs_data);
//                $logs_data = str_replace("operation_function","操作方法",$logs_data);
//                $logs_data = str_replace("operation_type","操作类型",$logs_data);
//                $logs_data = str_replace("operation_describe","操作描述",$logs_data);
//                $logs_data = str_replace("username","操作用户",$logs_data);
//                $logs_data = str_replace("cus_id","操作用户id",$logs_data);
//                $logs_data = str_replace("fk_id","操作表id",$logs_data);
//                
//                file_put_contents(public_path('logs/'.date("Y-m-d",  $time_min).'.txt'), $logs_data, FILE_APPEND); 
//                
//                $logs->where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->delete();
//            }
////            $this->WriteLogs($logs);
//        }else{
//            echo "no";   
//        }
//    }
    /**
     * 日志读取
     * @param type $time1
     * @param type $type
     * @param type $time1
     * @param type $time2 
     * @return data
     */
    protected function logsListData($per_page = 15,$type = '',$time1 = '',$time2 = '',$username = '') {
        if(!empty($type) && !empty($time1) && !empty($time2) && !empty($username)){
            //全有
            $logs_list = Logs::where("operation_time",'>=',$time1)->where("operation_time",'<=',$time2)->where("operation_type",'=',$type)->where("username",'=',$username)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($type) && !empty($time1) && !empty($time2)){
            //只有类型和时间
            $logs_list = Logs::where("operation_time",'>=',$time1)->where("operation_time",'<=',$time2)->where("operation_type",'=',$type)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($type) && !empty ($username)){
            //只有类型和用户
            $logs_list = Logs::where("operation_type",'=',$type)->where("username",'=',$username)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($time1) && !empty($time2) && !empty ($username)){
            //只有时间和用户
            $logs_list = Logs::where("operation_time",'>=',$time1)->where("operation_time",'<=',$time2)->where("username",'=',$username)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($type)){
            //只有类型
            $logs_list = Logs::where("operation_type",'=',$type)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($time1) && !empty($time2)){
            //只有时间
            $logs_list = Logs::where("operation_time",'>=',$time1)->where("operation_time",'<=',$time2)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($username)){
            //只有用户
            $logs_list = Logs::where("username",'=',$username)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }else{
            //全没有
            $logs_list = Logs::orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }
        return $logs_list;
    }
    
    protected function getType($type){
        switch ($type){
            case 1:{
                return "增加";
                break;
            }
            case 2:{
                return "删除";
                break;
            }
            case 3:{
                return "修改";
                break;
            }
            case 4:{
                return "批量增加";
                break;
            }
            case 5:{
                return "批量删除";
                break;
            }
            case 6:{
                return "批量修改";
                break;
            }
            case 100:{
                return "登录";
                break;
            }
            case 101:{
                return "退出";
                break;
            }
            case 999:{
                return "其他";
                break;
            }
            default :{
                return "未定义";
                break;
            }
        }
    }

    //当前用户的日志
    public function usrList() {
        $type = Input::has('type') ? Input::get('type') : '';
        $date = Input::has('date') ? Input::get('date') : date('Y-m-d',time());
        $id = Auth::id();
        $now = date('Y-m-d',time());

        if($date == $now) {
            //如果是当天前时间，从数据库读取
            if($type) {
                $data = Logs::where('cus_id',$id)->where("operation_type",$type)->orderBy('operation_time', 'DESC')->get()->toArray();
            } else {
                $data = Logs::where('cus_id',$id)->orderBy('operation_time', 'DESC')->get()->toArray();
            }

            if($data) {
                foreach ($data as &$v) {
                    $v['operation_time'] = date("Y-m-d H:i:s",$v['operation_time']);
                    $v['operation_type'] = $this->getType($v['operation_type']);
                }
                $err = 1000;
                $msg = '获取成功';
            } else {
                $err = 1001;
                $msg = '无此操作';
                $data = '';
            }
        } else {
            //如果不是当天，则从日志目录读取文本文件
            $filename = public_path('logs/' . $date . '.txt') ;     //日志文件路径
            $file_bak = 'http://182.61.23.43/logs/' . $date . '.txt';  //日志可能存在推送服务器
            if(!file_exists($filename)) {
                $headers = get_headers('http://182.61.23.43/logs/' . $date . '.txt',true);
                if(strpos($headers['0'],'404')) {                    
                    return Response::json(['err' => 1001, 'msg' => '无日志', 'data' => '']);
                }
                $filename = $file_bak;   //如果主控不存在，而推送服存在                
            }
            $files = file($filename);                               //逐行读出为数组            
            $str = "'操作用户id' => " . $id;                        //查找的字符串
            $i = 0;                                                 //返回的数组的索引
            $replace = array("=>" , "'" , ",");                     //需要替换的字符
            $data = array();
            if($type) {
                $sort = $this->getType($type);                      //操作类型转中文
            }

            foreach ($files as $k => $v) {
                if(strpos($v, $str)) {
                    //操作类型
                    $kind = strstr($files[$k-3], '=>');
                    $kind = str_replace($replace, '', $kind);
                    if($type) {
                        //如果有选择类型，则和当前记录比较，不一样则跳出本次循环
                        if($type != trim($kind)) {
                            continue;
                        }
                        $data[$i]['operation_type'] = $sort;
                    } else {
                        $data[$i]['operation_type'] = $this->getType($kind);
                    }
                    
                    //操作描述
                    $describe = strstr($files[$k-2], '=>');
                    $describe = str_replace($replace, '', $describe);
                    $data[$i]['operation_describe'] = $describe;                    
                    //IP
                    $ip = strstr($files[$k-8], '=>');
                    $ip = str_replace($replace, '', $ip);
                    $data[$i]['ip'] = $ip;
                    //时间
                    $time = strstr($files[$k-7], '=>');
                    $time = str_replace($replace, '', $time);
                    $data[$i]['operation_time'] = $time;

                    $i++;
                }
            }

            if($data) {
                $err = 1000;
                $msg = '获取成功';
            } else {
                $err = 1001;
                $msg = '无操作';
                $data = '';
            }
        }
                
        return Response::json(['err' => $err, 'msg' => $msg, 'data' => $data]);
    }
}