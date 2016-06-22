<?php

/**
 * 容量条统计
 * @author xieqixiang 
 * 用户添加文件、删除文件时对用户空间容量进行修改
 */
class CapacityController extends BaseController {

    /**
     * 初始化空间
     */
    public function init($size = 0) {
        $cus_id = Auth::id();
        $customer = Auth::user()->name;
        $data = array(
            "capacity" => $size,
            "capacity_free" => $size
        );
        $flag = CustomerInfo::where('cus_id', $cus_id)->update($data);
    }

    /**
     * 释放空间(删除图片)
     * @param type $param
     */
    public function free($size = 0) {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $capacity_free = $customer_info->capacity_free;
        $data = array(
            "capacity_free" => $capacity_free + $size,
        );
        $flag = CustomerInfo::where('cus_id', $cus_id)->update($data);
        if ($flag) {
            return true; //释放成功
        }
    }

    /**
     * 扣除空间（上传图片）
     */
    public function deduct($size = 0) {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $capacity_free = $customer_info->capacity_free;
        if ($capacity_free - $size < 0) {
            return false; //图片尺寸过大，无法上传
        }
        $data = array(
            "capacity_free" => $capacity_free - $size,
        );
        $flag = CustomerInfo::where('cus_id', $cus_id)->update($data);
        if ($flag) {
            return true; //扣除成功
        }
    }

}

?>
