<?php
use Illuminate\Database\Seeder;
use \Colors\RandomColor;

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
            'position' => '0',
            'color' => RandomColor::one()
        ));
        
        DB::table('answers')->insertGetId(array(
            'questions_id' => '1',
            'answer' => 'Peut être',
            'position' => '1',
            'color' => RandomColor::one()
        ));
        
        DB::table('answers')->insertGetId(array(
            'questions_id' => '1',
            'answer' => 'Non',
            'position' => '2',
            'color' => RandomColor::one()
        ));
    }
}
