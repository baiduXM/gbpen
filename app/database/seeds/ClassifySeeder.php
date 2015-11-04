<?php

class ClassifySeeder extends Seeder {

    public function run() {
        DB::table('classify')->truncate();
        
        $cus_id = DB::table('customer')
                        ->select('id')
                        ->where('name', 'test')
                        ->first()->id;
        $time = date('Y-m-d H:i:s');
        DB::table('classify')->insert([
            [
                'name' => '社会万象',
                'img' => 'classify1.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => 0,
			    'pc_show' => 1,
                'p_id' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '情感八卦',
                'img' => 'classify2.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '1',
				'pc_show' => 1,
                'p_id' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '拍案说法',
                'img' => 'classify3.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '2',
				'pc_show' => 1,
                'p_id' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '五花八门',
                'img' => 'classify4.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '3',
				'pc_show' => 1,
                'p_id' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '奇闻轶事',
                'img' => 'classify5.jpg',
                'type' => 3,
                'cus_id' => $cus_id,
                'sort' => '4',
				'pc_show' => 1,
                'p_id' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '民生百态',
                'img' => 'classify6.jpg',
                'type' => 2,
                'cus_id' => $cus_id,
                'sort' => '5',
				'pc_show' => 1,
                'p_id' => 0,
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
        $p_id1 = DB::table('classify')
                        ->select('id')
                        ->where('name', '社会万象')
                        ->first()
                        ->id;
        $p_id2 = DB::table('classify')
                        ->select('id')
                        ->where('name', '五花八门')
                        ->first()
                        ->id;
        $p_id3 = DB::table('classify')
                        ->select('id')
                        ->where('name', '奇闻轶事')
                        ->first()
                        ->id;
        DB::table('classify')->insert([
            [
                'name' => '风气',
                'img' => 'classify7.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '0',
				'pc_show' => 1,
                'p_id' => $p_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '道德',
                'img' => 'classify8.jpg',
                'type' => 2,
                'cus_id' => $cus_id,
                'sort' => '0',
				'pc_show' => 1,
                'p_id' => $p_id3,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '环境',
                'img' => 'classify9.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '1',
				'pc_show' => 1,
                'p_id' => $p_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '搞笑',
                'img' => 'classify10.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '0',
				'pc_show' => 1,
                'p_id' => $p_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '幽默',
                'img' => 'classify11.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '1',
				'pc_show' => 1,
                'p_id' => $p_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => '夸张',
                'img' => 'classify12.jpg',
                'type' => 1,
                'cus_id' => $cus_id,
                'sort' => '2',
				'pc_show' => 1,
                'p_id' => $p_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
    }

}
