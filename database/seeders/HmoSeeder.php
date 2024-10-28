<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HmoSeeder extends Seeder
{
    private $hmos = [
        ['name'=>'HMO A', 'code'=> 'HMO-A', 'is_batched_by_encounter' => false],
        ['name'=>'HMO B', 'code'=> 'HMO-B', 'is_batched_by_encounter' => true],
        ['name'=>'HMO C', 'code'=> 'HMO-C', 'is_batched_by_encounter' => true],
        ['name'=>'HMO D', 'code'=> 'HMO-D', 'is_batched_by_encounter' => true],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hmos')->insert($this->hmos);
    }
}
