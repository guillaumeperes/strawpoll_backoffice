<?php

use Illuminate\Database\Seeder;

class DuplicationChecksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('duplication_checks')->insertGetId(array('label' => 'Aucun contrôle'));
        DB::table('duplication_checks')->insertGetId(array('label' => 'Contrôle par adresse IP'));
        DB::table('duplication_checks')->insertGetId(array('label' => 'Contrôle par cookie'));
    }
}
