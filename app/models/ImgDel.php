<?php

class ImgDel extends Eloquent {

    protected $table = 'img_del';
    public $timestamps = false;

    public function mysave($img, $target = "articles") {
        if ($img != '') {
            //===释放用户空间容量===
//            $customer = Auth::user()->name;
//            $size = filesize(public_path('customers/' . $customer . '/cache_images/' . $img));
//            $cus = new CustomerController;
//            $cus->change_capa($size, 'free');
            return DB::table($this->table)->insert(array('img' => $img, 'cus_id' => Auth::id(), 'target' => $target));
        }
    }

}
