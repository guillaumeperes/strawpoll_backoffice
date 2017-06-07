<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PollsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('polls')->insertGetId(array(
			'duplication_checks_id' => '1',
			'has_captcha' => 'true',
			'is_draft' => 'false',
			"created" => Carbon::now(),
			"updated" => Carbon::now(),
			"published" => Carbon::now()
		));
    }
}