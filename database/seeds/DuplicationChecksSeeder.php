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
        DB::table('duplication_checks')->insertGetId(array('name' => 'notcontrolled', 'label' => 'Autoriser plusieurs votes par utilisateur'));
        DB::table('duplication_checks')->insertGetId(array('name' => 'ip', 'label' => "Restreindre sur la base de l'adresse IP"));
        DB::table('duplication_checks')->insertGetId(array('name' => 'cookie', 'label' => "Restreindre sur la base d'un cookie"));
    }
}
