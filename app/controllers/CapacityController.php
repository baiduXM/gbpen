<?php

/**
 * ===容量控制器===
 * @author xieqixiang
 * @date 2016.07.18
 * 
 * 1、初始化当前已使用空间容量
 *      统计物理存在的图片大小，去除cache_images、images/s、mobile文件下的图片
 *      （cache_images：图片缓存，网站推送后删除该文件下的图片）
 *      （images/s、mobile：每上传一张图片，都将保存为4张图片images/l、images/s、mobile/images/l、mobile/images/s，所以只统计images/l原图大小）
 *      TODO:可写个函数统一对图片删除时物理删除4个文件夹下的图片
 * 2、对用户操作进行容量增减
 *      普通直接上传图片、ueditor上传文件（图片、附件）
 *      只对用户空间容量数值上的增减
 * 
 *  ·直接上传图片
 *      删除图片释放空间结算时间：点击“保存”按钮后才结束，在此操作前添加图片/更换图片可能造成容量不足
 *      ？添加图片：添加新图片即扣除容量。可能造成容量不足。
 *      更换图片：更换图片原理删除原图，添加新图，结算空间时间同“添加图片”操作。
 *  ·ueditor上传图片
 *      文章ueditor：对比当前内容和数据库存储内容，进行使用释放空间
 * 
 * 数据库：
 *      表up_customer_info、添加init_capacity字段：是否初始化:0-未初始化，1-已初始化
 * 
 * 注意：
 *      此方法原理统计出的空间容量并非真实用户占用空间，真实占用空间容量>>统计占用空间容量
 *      所以在非必要时候，请勿手动！直接！删除文件夹中文件
 * 
 */
class CapacityController extends BaseController {

    private $size; //容量大小

    /**
     * 初始化、统计物理容量大小
     */

    function init() {
        $customer = Auth::user()->name;
        $path = public_path('customers/' . $customer);
        $this->tree($path);
//        echo $this->format_bytes($this->size);
    }

    /**
     * 遍历目录下的文件
     * @param type $directory 要遍历的根目录
     *      排除cache_images、images/s、mobile
     * @return null 计算文件大小，对$this->size进行赋值
     */
    private function tree($directory) {
        $mydir = dir($directory);
        while ($file = $mydir->read()) {
            if ((is_dir("$directory/$file")) && ( $file != ".") && ( $file != "..") && ( $file != "cache_images") && ( $file != "s") && ( $file != "mobile") && ( $file != "detail") && ( $file != "category")) {
                $this->tree("$directory/$file");
            } else if (( $file != ".") && ( $file != "..") && ( $file != "cache_images") && ( $file != "s") && ( $file != "mobile") && ( !strpos( $file , ".zip" ))) {
                $_size = filesize($directory . '/' . $file);
                $this->size += $_size;
            }
        }
        $mydir->close();
    }

    /**
     * 获取容量数据
     * @return type
     */
    public function getInfo() {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $capacity = $customer_info->capacity;
        $capacity_use = $customer_info->capacity_use;
        if ($customer_info->init_capacity == 0) {//===如果容量使用为0，则初始化===
            $this->init();
            $capacity_use = $this->size;
            $this->setCapacity($capacity_use, $capacity);
        }
        if ($capacity_use > $capacity) {
            $err = 1;
            $msg = '空间容量不足';
        } else {
            $err = 0;
            $msg = '容量数据';
        }
        $data['capacity'] = $this->format_bytes($capacity);
        $data['capacity_use'] = $this->format_bytes($capacity_use);
        $result = ['err' => $err, 'msg' => $msg, 'data' => $data];
        return Response::json($result);
    }

    /**
     * 设置容量
     * @param type $capacity_use        已使用空间
     * @param type $capacity            总空间
     */
    private function setCapacity($capacity_use = 0, $capacity = 0) {
        $cus_id = Auth::id();
        $data = array();
        if (!empty($capacity_use)) {
            $data['capacity_use'] = $capacity_use;
        }
        if (!empty($capacity)) {
            $data['capacity'] = $capacity;
        }
        $data['init_capacity'] = 1;
        CustomerInfo::where('cus_id', $cus_id)->update($data);
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
        } else if ($way == 'free') {
            if (($capacity_use - $size) < 0) {
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
     *      先获取文件然后
     * @param string $content       html内容
     * @return string $file_str     文件名字符串
     */
    public function reg_ueditor_content($content) {
        if (empty($content)) {
            return false;
        }
        $customer = Auth::user()->name;
        $file_str = '';
        preg_match_all('/customers\/' . $customer . '\/images\/ueditor\/[\w\/]*\.\w+/', $content, $matches);
        if (!empty($matches)) {
            foreach ($matches[0] as $value) {
                $temp_array = explode('/', $value);
                $file_array[] = end($temp_array);
            }
        }
        if (!empty($file_array)) {
            $file_str = implode(',', $file_array);
        }
        return $file_str;
    }

    /**
     * 比较文件名差异，删除不存在文件
     * @param type $new_content
     *      string
     * @param type $old_content
     */
    public function compare_filename($new_content = '', $old_content = '') {
        $new_array = explode(',', $this->reg_ueditor_content($new_content));
        $old_array = explode(',', $old_content);
        if (!is_array($new_array)) {
            $new_array = array();
        }
        if (!is_array($old_array)) {
            $old_array = array();
        }
        $customer = Auth::user()->name;
        $free_array = array_diff($old_array, $new_array); //===删除的文件名===
        if (!empty($free_array)) {
            $free_size = 0;
            foreach ($free_array as $value) {
                $filepath = public_path('customers/' . $customer . '/images/ueditor/' . $value);
                if (is_file($filepath)) {
                    $free_size += filesize($filepath);
                }
            }
            $this->change_capa($free_size, 'free');
        }
        $use_array = array_diff($new_array, $old_array); //===新增的文件名===
        if (!empty($use_array)) {
            $use_size = 0;
            foreach ($use_array as $value) {
                $filepath = public_path('customers/' . $customer . '/images/ueditor/' . $value);
                if (is_file($filepath)) {
                    $use_size += filesize($filepath);
                }
            }
            $this->change_capa($use_size, 'use');
        }
    }

}

?>
