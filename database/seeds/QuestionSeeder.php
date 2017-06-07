<?php
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
	public function run() 
	{
		DB::table('questions')->insertgetId(array(
			'polls_id' => '1',
			'question' => 'Est-ce que ça fonctionne ?',
			'multiple_answers' => 'false',
			'position' => '0'
		));
	}
}
            