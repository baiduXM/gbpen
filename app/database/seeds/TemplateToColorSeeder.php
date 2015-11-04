<?php

class TemplateToColorSeeder extends Seeder {

    public function run() {
        DB::table('template_to_color')->truncate();
        DB::table('template_to_color')->insert([
            [
                'template_id' => '1',
                'color_id' => '1',
                'color_code' => '#CCCCCC'
            ],
            [
                'template_id' => '1',
                'color_id' => '2',
                'color_code' => '#000000'
            ],
            [
                'template_id' => '1',
                'color_id' => '3',
                'color_code' => '#3399FF'
            ],
            [
                'template_id' => '2',
                'color_id' => '1',
                'color_code' => '#666666'
            ],
            [
                'template_id' => '2',
                'color_id' => '2',
                'color_code' => '#222222'
            ],
            [
                'template_id' => '2',
                'color_id' => '3',
                'color_code' => '#006699'
            ],
            [
                'template_id' => '3',
                'color_id' => '1',
                'color_code' => '#666666'
            ],
            [
                'template_id' => '3',
                'color_id' => '3',
                'color_code' => '#0099CC'
            ],
            [
                'template_id' => '3',
                'color_id' => '4',
                'color_code' => '#009933'
            ],
            [
                'template_id' => '4',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '4',
                'color_id' => '5',
                'color_code' => '#FFFF33'
            ],
            [
                'template_id' => '4',
                'color_id' => '6',
                'color_code' => '#FF6600'
            ],
            [
                'template_id' => '4',
                'color_id' => '7',
                'color_code' => '#FF6666'
            ],
            [
                'template_id' => '5',
                'color_id' => '1',
                'color_code' => '#999999'
            ],
            [
                'template_id' => '5',
                'color_id' => '2',
                'color_code' => '#333333'
            ],
            [
                'template_id' => '5',
                'color_id' => '6',
                'color_code' => '#FF6600'
            ],
            [
                'template_id' => '5',
                'color_id' => '7',
                'color_code' => '#FF6666'
            ],
            [
                'template_id' => '6',
                'color_id' => '2',
                'color_code' => '#000000'
            ],
            [
                'template_id' => '6',
                'color_id' => '3',
                'color_code' => '#00CCFF'
            ],
            [
                'template_id' => '6',
                'color_id' => '6',
                'color_code' => '#FF9900'
            ],
            [
                'template_id' => '6',
                'color_id' => '7',
                'color_code' => '#FF3333'
            ],
            [
                'template_id' => '7',
                'color_id' => '2',
                'color_code' => '#000000'
            ],
            [
                'template_id' => '7',
                'color_id' => '4',
                'color_code' => '#33CC99'
            ],
            [
                'template_id' => '7',
                'color_id' => '6',
                'color_code' => '#FF6600'
            ],
            [
                'template_id' => '7',
                'color_id' => '7',
                'color_code' => '#FF6666'
            ],
            [
                'template_id' => '8',
                'color_id' => '7',
                'color_code' => '#FF6666'
            ],
            [
                'template_id' => '8',
                'color_id' => '8',
                'color_code' => '#CC0000'
            ],
            [
                'template_id' => '8',
                'color_id' => '9',
                'color_code' => '#9900FF'
            ],
            [
                'template_id' => '8',
                'color_id' => '10',
                'color_code' => '#663333'
            ],
            [
                'template_id' => '9',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '9',
                'color_id' => '8',
                'color_code' => '#FF0000'
            ],
            [
                'template_id' => '9',
                'color_id' => '9',
                'color_code' => '#9966CC'
            ],
            [
                'template_id' => '9',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '10',
                'color_id' => '1',
                'color_code' => '#CCCCCC'
            ],
            [
                'template_id' => '10',
                'color_id' => '8',
                'color_code' => '#FF0000'
            ],
            [
                'template_id' => '10',
                'color_id' => '9',
                'color_code' => '#9966CC'
            ],
            [
                'template_id' => '10',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '11',
                'color_id' => '1',
                'color_code' => '#CCCCCC'
            ],
            [
                'template_id' => '11',
                'color_id' => '2',
                'color_code' => '#222222'
            ],
            [
                'template_id' => '11',
                'color_id' => '9',
                'color_code' => '#660099'
            ],
            [
                'template_id' => '11',
                'color_id' => '10',
                'color_code' => '#330000'
            ],
            [
                'template_id' => '12',
                'color_id' => '3',
                'color_code' => '#3366FF'
            ],
            [
                'template_id' => '12',
                'color_id' => '4',
                'color_code' => '#009966'
            ],
            [
                'template_id' => '12',
                'color_id' => '9',
                'color_code' => '#6633FF'
            ],
            [
                'template_id' => '12',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '13',
                'color_id' => '3',
                'color_code' => '#3366FF'
            ],
            [
                'template_id' => '13',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '13',
                'color_id' => '9',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '13',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '14',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '14',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '14',
                'color_id' => '9',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '14',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '15',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '15',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '15',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '15',
                'color_id' => '8',
                'color_code' => '#FF0000'
            ],
            [
                'template_id' => '16',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '16',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '16',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '16',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '17',
                'color_id' => '1',
                'color_code' => '#CCCCCC'
            ],
            [
                'template_id' => '17',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '17',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '17',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '18',
                'color_id' => '2',
                'color_code' => '#000000'
            ],
            [
                'template_id' => '18',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '18',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '18',
                'color_id' => '10',
                'color_code' => '#663300'
            ],
            [
                'template_id' => '19',
                'color_id' => '2',
                'color_code' => '#000000'
            ],
            [
                'template_id' => '19',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '19',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '19',
                'color_id' => '8',
                'color_code' => '#FF0000'
            ],
            [
                'template_id' => '20',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
            [
                'template_id' => '20',
                'color_id' => '9',
                'color_code' => '#9900FF'
            ],
            [
                'template_id' => '21',
                'color_id' => '3',
                'color_code' => '#0066CC'
            ],
            [
                'template_id' => '21',
                'color_id' => '8',
                'color_code' => '#FF0000'
            ],
            [
                'template_id' => '22',
                'color_id' => '3',
                'color_code' => '#0066CC'
            ],
            [
                'template_id' => '22',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '23',
                'color_id' => '1',
                'color_code' => '#CCCCCC'
            ],
            [
                'template_id' => '23',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '24',
                'color_id' => '1',
                'color_code' => '#999999'
            ],
            [
                'template_id' => '24',
                'color_id' => '4',
                'color_code' => '#00CC99'
            ],
            [
                'template_id' => '25',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '26',
                'color_id' => '5',
                'color_code' => '#FFCC00'
            ],
            [
                'template_id' => '27',
                'color_id' => '6',
                'color_code' => '#FF9900'
            ],
            [
                'template_id' => '28',
                'color_id' => '7',
                'color_code' => '#CC6666'
            ],
        ]);
    }

}
