<?php

/**
 * ===容量控制器===
 * @author xieqixiang
 * @time 2016.07.18
 */
class CapacityController extends BaseController {
    /*
     * getCapacity              获取当前容量
     * change_capa              改变空间容量（添加/删除）
     * 
     */

    /**
     * 获取容量数据
     * @return type
     */
    public function getCapacity() {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $data['capacity'] = $this->format_bytes($customer_info->capacity);
        $data['capacity_free'] = $this->format_bytes($customer_info->capacity - $customer_info->capacity_use);
        $result = ['err' => 0, 'msg' => '容量数据', 'data' => $data];
        return Response::json($result);
    }

    /**
     * 改变空间容量（添加/删除）
     * @param type $size    图片大小
     * @param type $way     释放free/占用use
     * @return boolean
     */
    public function change_capa($size = 0, $way = 'use') {
        $cus_id = Auth::id();
        $cusinfo = CustomerInfo::where('cus_id', $cus_id)->first();
        $capacity_use = $cusinfo->capacity_use;
        $capacity = $cusinfo->capacity;
        if ($way == 'use') {
            if ($capacity >= ($capacity_use + $size)) {
                $data = array(
                    "capacity_use" => $capacity_use + $size,
                );
            } else {
                return false; //容量不足
            }
        }
        if ($way == 'free') {
            if (0 < ($capacity_use - $size)) {
                $data = array(
                    "capacity_use" => 0,
                );
            } else {
                $data = array(
                    "capacity_use" => $capacity_use - $size,
                );
            }
        }
        $flag = CustomerInfo::where('cus_id', $cus_id)->update($data);
        if ($flag) {//是否更新成功
            return true; //修改成功
        } else {
            return false; //修改失败
        }
    }

    /**
     * 格式转换
     * b=>kb=>mb=>gb=>tb
     * 0 1 2 3 4
     */
    public function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];
    }

    /**
     * ===对ueditor进行内容匹配===
     * @param type $content
     * @param type $fileName
     * @return type
     */
    public static function reg_ueditor_content($content = null) {
        if (empty($content)) {
            return Auth::id();
        }
        $html = strip_tags($content);
//        preg_match_all($pattern, $subject,)
        $customer = Auth::user()->name;
        $reg = 'customers/' . $customer . '/images/ueditor/(\w|/)*\.(\w)+/';
        //版主
        preg_match_all($reg, $content, $matches);
//        $size = filesize(public_path('customers/' . $customer . '/images/ueditor/' . $fileName));
        return $matches;
        //todo
    }

    /**
     * 保存文件名，用作后期比较
     */
    public function save_filename() {
        
    }

    public function test($param) {
        DB::table('test')->insert(
                array('content' => $param)
        );
    }

}

?>
