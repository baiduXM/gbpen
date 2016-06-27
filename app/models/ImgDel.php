<?php

class ImgDel extends Eloquent {

    protected $table = 'img_del';
    public $timestamps = false;

    public function mysave($img, $target = "articles") {
        if ($img != '') {
            $size = 0;
            $cus = new CustomerController;
            $cus->change_capa($size, 'free');
            return DB::table($this->table)->insert(array('img' => $img, 'cus_id' => Auth::id(), 'target' => $target));
        }
    }

}
