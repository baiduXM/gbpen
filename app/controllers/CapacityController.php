<?php

/**
 * 容量条统计
 * @author xieqixiang 
 * 用户添加文件、删除文件时对用户空间容量进行修改
 */
class CapacityController extends BaseController {

    /**
     * 改变空间容量
     * @param type $capacity_free
     * @param type $size
     * @param type $way free/deduct
     * @return boolean
     */
    public function change_capa($capacity_free = 0, $size = 0, $way = 'free') {
        $cus_id = Auth::id();
        if ($way == 'free') {
            $data = array(
                "capacity_free" => $capacity_free + $size,
            );
        } elseif ($way == 'deduct') {
            $data = array(
                "capacity_free" => $capacity_free - $size,
            );
        }
        $flag = CustomerInfo::where('cus_id', $cus_id)->update($data);
        if ($flag) {
            return true; //扣除成功
        }
    }

    /**
     * 格式转换
     * b=>kb=>mb=>gb
     * 1 2 3 4
     */
    public function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];
    }

}

?>
