<?php

class ImgDel extends Eloquent {

    protected $table = 'img_del';
    public $timestamps = false;

    public function mysave($img, $target = "articles") {
        if ($img != '') {
            $size = 0;
            $customer = Auth::user()->name;
            $size += filesize(public_path('customers/' . $customer . '/images/l/' . $target . '/' . $img)); //===累加文件大小
            $size += filesize(public_path('customers/' . $customer . '/images/s/' . $target . '/' . $img)); //===累加文件大小
            $cus = new CustomerController;
            $cus->change_capa($size, 'free');
            return DB::table($this->table)->insert(array('img' => $img, 'cus_id' => Auth::id(), 'target' => $target));
        }
    }

}
