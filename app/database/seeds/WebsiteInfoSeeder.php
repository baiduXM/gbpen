<?php

class WebsiteInfoSeeder extends Seeder {

    public function run() {
        $pc_tpl_id = DB::table('template')->where('name', 'GP006')->where('type','1')->pluck('id');
        $mobile_tpl_id = DB::table('template')->where('name', 'GM001')->where('type','2')->pluck('id');
        $cus_id = DB::table('customer')->where('name', 'test')->pluck('id');
        DB::table('website_info')->truncate();
        DB::table('website_info')->insert([
            [
                'pc_tpl_id' => $pc_tpl_id,
                'mobile_tpl_id' => $mobile_tpl_id,
                'pc_color_id' => 2,
                'mobile_color_id' => 7,
                'cus_id' => $cus_id
            ]
        ]);
    }

}
