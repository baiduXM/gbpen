<?php

class CustomerSeeder extends Seeder {

    public function run() {
        $time = date('Y-m-d H:i:s');
        $endtime = date('Y-m-d H:i:s',strtotime("now + 1 year"));
        DB::table('customer')->truncate();
        DB::table('customer')->insert([
                       [
                'name' => 'test',
                'email' => '530176577@qq.com',
                'password' => Hash::make('test'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GT001',
                'email' => 'GT001@qq.com',
                'password' => Hash::make('GT001'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP001',
                'email' => 'GP001@qq.com',
                'password' => Hash::make('GP001'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ]
			,
            [
                'name' => 'GP002',
                'email' => 'GP002@qq.com',
                'password' => Hash::make('GP002'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP003',
                'email' => 'GP003@qq.com',
                'password' => Hash::make('GP003'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP005',
                'email' => 'GP005@qq.com',
                'password' => Hash::make('GP005'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP006',
                'email' => 'GP006@qq.com',
                'password' => Hash::make('GP006'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP007',
                'email' => 'GP007@qq.com',
                'password' => Hash::make('GP007'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP008',
                'email' => 'GP008@qq.com',
                'password' => Hash::make('GP008'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP009',
                'email' => 'GP009@qq.com',
                'password' => Hash::make('GP009'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GP0010',
                'email' => 'GP0010@qq.com',
                'password' => Hash::make('GP0010'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM002',
                'email' => 'GM002@qq.com',
                'password' => Hash::make('GM002'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM003',
                'email' => 'GM003@qq.com',
                'password' => Hash::make('GM003'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM004',
                'email' => 'GM004@qq.com',
                'password' => Hash::make('GM004'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM005',
                'email' => 'GM005@qq.com',
                'password' => Hash::make('GM005'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM006',
                'email' => 'GM006@qq.com',
                'password' => Hash::make('GM006'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM007',
                'email' => 'GM007@qq.com',
                'password' => Hash::make('GM007'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM008',
                'email' => 'GM008@qq.com',
                'password' => Hash::make('GM008'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM009',
                'email' => 'GM009@qq.com',
                'password' => Hash::make('GM009'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name' => 'GM0010',
                'email' => 'GM0010@qq.com',
                'password' => Hash::make('GM0010'),
                'ended_at' => $endtime,
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
    }

}
