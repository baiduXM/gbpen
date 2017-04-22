<?php

class ColorSeeder extends Seeder {

    public function run() {
        DB::table('color')->truncate();

        $time = date('Y-m-d H:i:s');
        DB::table('color')->insert([
            // [
            //     'color' => '灰色',
            //     'color_en' => 'grey'
            // ],
            [
                'color' => '黑色',
                'color_en' => 'black'
            ],
            [
                'color' => '蓝色',
                'color_en' => 'blue'
            ],
            [
                'color' => '绿色',
                'color_en' => 'green'
            ],
            [
                'color' => '黄色',
                'color_en' => 'yellow'
            ],
            [
                'color' => '橘色',
                'color_en' => 'orange'
            ],
            // [
            //     'color' => '粉色',
            //     'color_en' => 'pink'
            // ],
            [
                'color' => '红色',
                'color_en' => 'red'
            ],
            [
                'color' => '紫色',
                'color_en' => 'purple'
            ],
            // [
            //     'color' => '咖啡',
            //     'color_en' => 'coffee'
            // ],
			[
                'color' => '多色',
                'color_en' => 'colorful'
            ],
        ]);
    }

}
