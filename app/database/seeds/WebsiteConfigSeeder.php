<?php

class WebsiteConfigSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('website_config')->truncate();
    }

}
