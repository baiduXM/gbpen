<?php

class TemplateSeeder extends Seeder {

    public function run() {
        DB::table('template')->truncate();

        $cus_id = DB::table('customer')
                        ->select('id')
                        ->where('name', 'test')
                        ->first()->id;

        $time = date('Y-m-d H:i:s');
        DB::table('template')->insert([
            [
                'name' => 'GP001',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP002',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP003',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP004',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP005',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP006',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP007',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP008',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP009',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP0010',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP0011',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP0012',
                'classify' => '基础',
                'type' => '1',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM001',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM002',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM003',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM004',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM005',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM006',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM007',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM008',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM009',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM0010',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM0011',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM0012',
                'classify' => '基础',
                'type' => '2',
                'created_at' => $time,
                'updated_at' => $time
            ],
        ]);
        DB::table('template')->insert([
            [
                'name' => 'GP002_1',
                'classify' => '基础',
                'type' => '1',
                'cus_id' =>$cus_id,
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GP005_1',
                'classify' => '基础',
                'type' => '1',
                'cus_id' =>$cus_id,
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM001_1',
                'classify' => '基础',
                'type' => '2',
                'cus_id' =>$cus_id,
                'created_at' => $time,
                'updated_at' => $time
            ],
            [
                'name' => 'GM002_1',
                'classify' => '基础',
                'type' => '2',
                'cus_id' =>$cus_id,
                'created_at' => $time,
                'updated_at' => $time
            ],
        ]);
    }

}
