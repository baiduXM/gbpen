<?php

class CustomerInfoSeeder extends Seeder {

    public function run() {
        DB::table('customer_info')->truncate();
        $cus_id = DB::table('customer')
                        ->select('id')
                        ->where('name', 'test')
                        ->first()->id;
        $time = date('Y-m-d H:i:s');
        DB::table('customer_info')->insert([
            [
                'cus_id' => $cus_id,
                'pc_domain' => 'http://www.baidu.com/',
                'mobile_domain' => 'http://wap.baidu.com/',
                'favicon' => 'favicon.ico',
                'logo' => 'logo.jpg',
                'logo_small' => 'logo_small.jpg',
                'pc_header_script' => '',
                'mobile_header_script' => '',
                'title' => '漳州市计有贸易有限公司',
                'keywords' => '漳州计有,漳州市计计有贸易,漳州市计有贸易有限公司',
                'description' => '漳州市计有贸易有限公司',
                'footer' => '体验中心 ：漳浦县绥安镇麦市街富丽山庄24幢D21号（金仕顿会所对面） 体验中心电话：0596-3165138<br>
							 龙成店：漳浦县绥安镇麦市街龙成尊庭格力专卖店（星湖酒店对面） 龙成店电话：0596-3115138<br>
							 Copyright © 漳州市计有贸易有限公司 版权所有<br>',
                'pc_footer_script' => '',
                'mobile_footer_script' => '',
                'telephone' => '0596-3165138',
                'fax' => '0596-3165138',
                'mobile' => '0151600000000000',
                'qq' => 'c@nerso.cn',
                'email' => 'c@nerso.cn',
                'address' => '漳浦县绥安镇麦市街',
                'contact_name' => '何经理',
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
    }

}
