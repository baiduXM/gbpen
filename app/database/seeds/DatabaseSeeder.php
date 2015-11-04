<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
        $this->call('CustomerSeeder');
        $this->call('CustomerInfoSeeder');
        $this->call('ClassifySeeder');
        $this->call('ArticleSeeder');
        $this->call('ColorSeeder');
        $this->call('TemplateSeeder');
        $this->call('TemplateToColorSeeder');
        $this->call('WebsiteConfigSeeder');
		$this->call('WebsiteInfoSeeder');
	}

}
