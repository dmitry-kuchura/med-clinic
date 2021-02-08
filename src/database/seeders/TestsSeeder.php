<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 4; $i++) {

            DB::table('tests')->insert([
                'name' => $this->getRandName(),
                'cost' => rand(150.77, 17777.95),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }

    private function getRandName(): string
    {
        $names = [
            'АНАЛИЗ КРОВИ РАЗВЕРНУТЫЙ',
            'АНАЛИЗ МОЧИ ОБЩИЙ',
            'СОСКОБ НА ЭНТЕРОБИОЗ',
            'ГРУППА КРОВИ И РЕЗУС ФАКТОР'
        ];

        return $names[rand(0, 3)];
    }
}
