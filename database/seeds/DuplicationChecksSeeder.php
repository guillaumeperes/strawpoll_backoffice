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
        DB::table('duplication_checks')->insertGetId(array('name' => 'notcontrolled', 'label' => 'Aucun contrôle'));
        DB::table('duplication_checks')->insertGetId(array('name' => 'ip', 'label' => 'Contrôle par adresse IP'));
        DB::table('duplication_checks')->insertGetId(array('name' => 'cookie', 'label' => 'Contrôle par cookie'));
    }
}
