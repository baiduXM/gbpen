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
        $per_page = Input::has('per_page') ? Input::get('per_page') : 15;
        $data = $this->logsListData($per_page,$type,$time1,$time2);
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
    
    public function logsAdda() {
//        $time_min = Logs::min('operation_time');
//        $time_minday = strtotime(date("Y-m-d",  $time_min));
//        $time_tomorrow = $time_minday + 86400;
//        $logs_data = Logs::where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->get()->toArray();
//        if($logs_data){
//            foreach($logs_data as &$value){
//                $value['operation_time'] = date("Y-m-d H:i:s",$value['operation_time']);
//            }
//            $logs_data = str_replace("),","),\r\n\r\n",var_export($logs_data,TRUE));
//            $logs_data = str_replace("operation_time","操作时间",$logs_data);
//            $logs_data = str_replace("operation_table","操作表",$logs_data);
//            $logs_data = str_replace("operation_class","操作类",$logs_data);
//            $logs_data = str_replace("operation_function","操作方法",$logs_data);
//            $logs_data = str_replace("operation_type","操作类型",$logs_data);
//            $logs_data = str_replace("operation_describe","操作描述",$logs_data);
//            $logs_data = str_replace("username","操作用户",$logs_data);
//            $logs_data = str_replace("cus_id","操作用户id",$logs_data);
//            $logs_data = str_replace("fk_id","操作表id",$logs_data);
//            file_put_contents(public_path('logs/'.date("Y-m-d",  $time_min).'.txt'), $logs_data, FILE_APPEND); 
//        }
        $logs = new Logs();
        $time_min = $logs->min('operation_time');
        if(date("Y-m-d",  $time_min) !== date("Y-m-d",  time())){
            $time_minday = strtotime(date("Y-m-d",  $time_min));
            $time_tomorrow = $time_minday + 86400;
            $logs_data = $logs->where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->get()->toArray();
            if($logs_data){
                foreach($logs_data as &$value){
                    $value['operation_time'] = date("Y-m-d H:i:s",$value['operation_time']);
                }
                $logs_data = str_replace("),","),\r\n\r\n",var_export($logs_data,TRUE));
                $logs_data = str_replace("operation_time","操作时间",$logs_data);
                $logs_data = str_replace("operation_table","操作表",$logs_data);
                $logs_data = str_replace("operation_class","操作类",$logs_data);
                $logs_data = str_replace("operation_function","操作方法",$logs_data);
                $logs_data = str_replace("operation_type","操作类型",$logs_data);
                $logs_data = str_replace("operation_describe","操作描述",$logs_data);
                $logs_data = str_replace("username","操作用户",$logs_data);
                $logs_data = str_replace("cus_id","操作用户id",$logs_data);
                $logs_data = str_replace("fk_id","操作表id",$logs_data);
                
                file_put_contents(public_path('logs/'.date("Y-m-d",  $time_min).'.txt'), $logs_data, FILE_APPEND); 
                
                $logs->where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->delete();
            }
//            $this->WriteLogs($logs);
        }else{
            echo "no";   
        }
    }
    /**
     * 日志读取
     * @param type $time1
     * @param type $type
     * @param type $time1
     * @param type $time2 
     * @return data
     */
    protected function logsListData($per_page = 15,$type = '',$time1 = '',$time2 = '') {
        if(!empty($type) && !empty($time1) && !empty($time2)){
            $logs_list = Logs::where("operation_time",'>=',$time1)->where("operation_time",'<=',$time2)->where("operation_type",'=',$type)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($type)){
            $logs_list = Logs::where("operation_type",'=',$type)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }elseif(!empty($time1) && !empty($time2)){
            $logs_list = Logs::where("operation_time",'>=',$time1)->where("operation_time",'<=',$time2)->orderBy('operation_time', 'DESC')->paginate($per_page)->toArray();
        }else{
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
}