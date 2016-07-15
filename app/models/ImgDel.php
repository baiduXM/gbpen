<?php

class ImgDel extends Eloquent {

    protected $table = 'img_del';
    public $timestamps = false;

    public function mysave($img, $target = "articles") {
        if ($img != '') {
            //===释放用户空间容量===
            $customer = Auth::user()->name;
            $size = filesize(public_path('customers/' . $customer . '/images/l/' . $target . '/' . $img));
            $cus = new CustomerController;
            if (!$cus->change_capa($size, 'free')) {
                true;
            }
            return DB::table($this->table)->insert(array('img' => $img, 'cus_id' => Auth::id(), 'target' => $target));
        }
    }

}
