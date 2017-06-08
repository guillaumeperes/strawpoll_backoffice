<?php
use Illuminate\Database\Seeder;

class AnswersSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
	public function run()
	{
		DB::table('answers')->insertGetId(array(
			'questions_id' => '1',
			'answer' => 'Oui',
			'position' => '0'
		));
		
		DB::table('answers')->insertGetId(array(
			'questions_id' => '1',
			'answer' => 'Peut être',
			'position' => '1'
		));
		
		DB::table('answers')->insertGetId(array(
			'questions_id' => '1',
			'answer' => 'Non',
			'position' => '2'
		));
	}
}	