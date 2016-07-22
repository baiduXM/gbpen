<?php

/**
 * ===容量控制器===
 * @author xieqixiang
 * @time 2016.07.18
 */
class CapacityController extends BaseController {
    /*
     * 1、初始化当前已使用空间容量
     * 2、对用户操作进行容量增减
     *      普通上传文件、ueditor上传文件
     * 3、更新容量
     */

    private $size; //容量大小

    /*   (●'◡'●)   */

    /**
     * 初始化
     */
    public function init() {
        $customer = Auth::user()->name;
        $path = public_path('customers/' . $customer);
        $this->tree($path);
//        echo  $this->format_bytes($this->size);
    }

    /**
     * 遍历目录下的文件
     * @param type $directory 要遍历的根目录
     *      排除cache_images、images/s、mobile
     * @return null 计算文件大小，对$this->size进行赋值
     */
    function tree($directory) {
        $mydir = dir($directory);
//        echo "<ul>\n";
        while ($file = $mydir->read()) {
            if ((is_dir("$directory/$file")) && ( $file != ".") && ( $file != "..") && ( $file != "cache_images") && ( $file != "s") && ( $file != "mobile")) {
//                echo "<li><font color=\"#ff00cc\"><b>$file</b></font></li>\n";
                $this->tree("$directory/$file");
            } else if (( $file != ".") && ( $file != "..") && ( $file != "cache_images") && ( $file != "s") && ( $file != "mobile")) {
//                echo $directory . '/' . $file;
                $_size = filesize($directory . '/' . $file);
                $this->size += $_size;
//                echo "<li>---$file---$_size---</li>\n";
            }
        }
//        echo "</ul>\n";
        $mydir->close();
    }

    /**
     * 获取容量数据
     * @param type $is_first        是否第一次，1/0
     * @return type
     */
    public function getCapacity($is_first = 0) {
        $cus_id = Auth::id();
        $customer_info = CustomerInfo::where('cus_id', $cus_id)->first();
        $capacity = $customer_info->capacity;
        $capacity_use = $customer_info->capacity_use;
//        if ($customer_info->capacity_use == 0 || $is_first == 1) {//===如果容量使用为0，则初始化===
        $this->init();
        $capacity_use = $this->size;
        $this->setCapacity($capacity_use, $capacity);
//        } else {
//            
//        }
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
    public function setCapacity($capacity_use = 0, $capacity = 0) {
        $cus_id = Auth::id();
        $data = array();
        if (!empty($capacity_use)) {
            $data['capacity_use'] = $capacity_use;
        }
        if (!empty($capacity)) {
            $data['capacity'] = $capacity;
        }
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

    /**
     * 获取文件大小
     * @param type $filepath        文件路径
     * @param type $filename        文件名
     */
    public function get_filesize($filepath, $filename) {
        
    }

}

?>
