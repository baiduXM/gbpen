<?php

class BaseController extends Controller {

    /**
     * ===判断服务器是否正常===
     * @return type
     */
    public function __construct() {
//        $cus_id = Auth::id();
//        $weburl = Customer::where('id', $cus_id)->pluck('weburl');
//        if ($weburl) {
//            if ($this->MonitorCheck($weburl) == false) {
//                return Response::json(['err' => 1001, 'msg' => '请检测服务器是否正常', 'data' => '']);
//            }
//        }
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    /**
     * 日志记录
     * @param String $table 操作表
     * @param String $function 操作方法
     * @param String $class 操作类
     * @param String $type 类型 1：增加，2：删除，3：修改，4：批量增加，5：批量删除，6：批量修改，100：登录，101：退出，999：其他
     * @param String $describe 操作描述
     * @param int $pingtai 操作平台
     * @param String $fk_id 操作表id，仅仅增删改需要
     */
    public function logsAdd($table, $function, $class, $type, $describe, $pingtai, $fk_id = '') {

        $logs = new Logs();
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        $logs->ip = $realip;
        $logs->operation_time = time();
        $logs->operation_table = $table;
        $logs->operation_class = $class;
        $logs->operation_function = $function;
        $logs->operation_type = $type;
        if ($pingtai == 0) {
            $describe = "统一平台：" . $describe;
        } else {
            $describe = "代理平台：" . $describe;
        }
        $logs->operation_describe = $describe;
        if (is_array($fk_id)) {
            foreach ($fk_id as $value) {
                $logs->fk_id .= $value . ",";
            }
            $logs->fk_id = rtrim($logs->fk_id, ",");
        } else {
            $logs->fk_id = $fk_id;
        }
        if (Auth::check()) {
            $logs->username = Auth::user()->name;
            $logs->cus_id = Auth::user()->id;
        } else {
            $logs->username = "未登录";
            $logs->cus_id = 0;
        }
        $logs->save();
        
       
        $this->WriteLogs($logs);
        
    }
    
    /**
     * 日志整理写入，并删除数据库中
     */
    protected function WriteLogs($logs){
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
                
                echo file_put_contents(public_path('logs/'.date("Y-m-d",  $time_min).'.txt'), $logs_data, FILE_APPEND); 
                echo "</br>";
                echo public_path('logs/'.date("Y-m-d",  $time_min).'.txt');
                echo "</br>";
                $logs->where("operation_time",">=",$time_minday)->where("operation_time","<",$time_tomorrow)->delete();
            }
            $this->WriteLogs($logs);
        }
    }

    /**
     * 检测服务器是否正常
     * @param type $host    要监控的网站
     *      c.n01.5067.org  (182.61.7.87);
     *      c.n02.5067.org  (182.61.29.25);
     *      c.hk01.5067.org (182.61.100.142);
     * @param type $find    找你的网站首页源代码中的一段字符串
     * @return boolen       true-连接成功，false-服务器连接失败
     */
    public function MonitorCheck($host, $find = '域名未绑定') {
        $hostarr = explode('.', $host);
        switch ($hostarr[1]) {
            case "n01":
                $host = "182.61.7.87";
                $find = $hostarr[1];
                break;
            case "n02":
                $host = "182.61.29.25";
                $find = $hostarr[1];
                break;
            case "hk01":
                $host = "182.61.100.142";
                $find = $hostarr[1];
                break;
            default:
                break;
        }
        $fp = @fsockopen($host, 80, $errno, $errstr, 15);
        if (!$fp) {//===连接不成功===
//            echo "$errstr ($errno)<br />n";
            return false;
        } else {
            $header = "GET / HTTP/1.1\r\n";
            $header .= "Host: $host\r\n";
            $header .= "Connection: close\r\n\r\n";
            fputs($fp, $header);
            $str = '';
            while (!feof($fp)) {
                $str .= fgets($fp);
            }
            fclose($fp);
            return (strpos($str, $find) !== false);
//            return (strpos($str, "域名未绑定") !== false);
        }
    }

}
