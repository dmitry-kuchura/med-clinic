<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data() as $value) {
            DB::table('settings')->insert($value);
        }
    }

    private function data(): array
    {
        return [
            [
                'name' => 'Унікальний ключ',
                'type' => 'input',
                'value' => 'ae9753fa09ba530a25cb29e68b9f59ab39997986',
                'key' => 'turbo-sms-key',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'Ім\'я відправника',
                'type' => 'input',
                'value' => 'Your baby',
                'key' => 'turbo-sms-sender',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'Час нагадування за добу',
                'type' => 'input',
                'value' => '14:00',
                'key' => 'reminder-time-per-day',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'Нагадування день у день (з)',
                'type' => 'input',
                'value' => '07:00',
                'key' => 'reminder-day-on-day-from',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'Нагадування день у день (до)',
                'type' => 'input',
                'value' => '18:00',
                'key' => 'reminder-day-on-day-to',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];
    }
}
