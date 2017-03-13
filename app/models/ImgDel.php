<?php

class ImgDel extends Eloquent {

    protected $table = 'img_del';
    public $timestamps = false;

    public function mysave($img, $target = "articles") {
        if ($img != '') {
            DB::table($this->table)->insert(array('img' => $img, 'cus_id' => Auth::id(), 'target' => $target));
            //===释放用户空间容量===
            $customer = Auth::user()->name;
            $filepath = public_path('customers/' . $customer . '/images/l/' . $target . '/' . $img);
            file_put_contents("test.txt",$filepath);
            if (is_file($filepath)) {
                $size = filesize($filepath); //===images/l不一定有，要推送后才有图片
            } else {
                $size = 0;
            }
            $Capacity = new CapacityController;
            $Capacity->change_capa($size, 'free');
            //===end===
            return $Capacity->format_bytes($size);
//            return DB::table($this->table)->insert(array('img' => $img, 'cus_id' => Auth::id(), 'target' => $target));
        }
    }

}
