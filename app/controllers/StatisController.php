<?php

/**
 * 数据统计
 * @author xieqixiang 
 * 在交互服务器上
 */
class StatisController extends BaseController {

    public function index() {
        
        $_SESSION['cus_id'] = 1;
        var_dump($_COOKIE);
        echo '<br>===$_COOKIE===<br>';
        var_dump($_SESSION);
        echo '<br>===$_SESSION===<br>';
    }

    public function getCount() {
        $cus_id = Auth::id();
        $param['cus_id'] = $cus_id;
        $data = DB::table('stats')->where('cus_id', $cus_id)->first();
//		$postFun = new CommonController;
//		$res2 = $postFun->postsend("http://swap.5067.org/admin/stats.php", $param);
//		$data=  json_decode($res2);
//		var_dump($data);
//		echo '<br>---data---<br>';
        if ($data != NULL) {
            $res = Response::json(['err' => 0, 'msg' => '获取统计数据成功', 'data' => $data]);
        } else {
            $res = Response::json(['err' => 1, 'msg' => '获取统计数据失败', 'data' => null]);
        }
//		var_dump($res);
//		echo '<br>---$res---<br>';
        return $res;
    }

}

?>
