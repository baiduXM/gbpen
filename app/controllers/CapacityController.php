<?php

/**
 * 容量条统计
 * @author xieqixiang 
 * 用户添加文件、删除文件时对用户空间容量进行修改
 */
class CapacityController extends BaseController {

    /**
     * 获取信息
     */
    public function getinfo() {
        
    }

    /**
     * 释放空间
     * @param type $param
     */
    public function free($param = null) {
        $cus_id = Auth::id();
        $customer = Auth::user()->name;
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        var_dump($param);
        echo '<br>---$param---<br>';
        $data = array(
            "capacity_free" => $customer_info->capacity_free - $param
        );
        $flag = CustomerInfo::where('cus_id', $cus_id)->update($data);
        var_dump($flag);
        echo '<br>---$flag---<br>';
        var_dump($customer_info);
        echo '<br>---customer_info---<br>';
        var_dump($customer);
        echo '<br>---$customer---<br>';
        
    }

    /**
     * 扣除空间
     */
    public function deduct() {
        $cus_id = Auth::id();
        $customer = Auth::user()->name;
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
    }

}

?>
