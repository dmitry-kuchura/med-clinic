<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MessagesTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data() as $value) {
            DB::table('messages_templates')->insert($value);
        }
    }

    private function data(): array
    {
        return [
            [
                'language' => 'ua',
                'name' => 'Запис на прийом',
                'alias' => 'patient-appointment-ua',
                'content' => 'Ви записані на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'language' => 'ru',
                'name' => 'Запис на прийом (русский)',
                'alias' => 'patient-appointment-ru',
                'content' => 'Вы записаны на приём в Детский Медицинский Центр "Your Baby" {date} в {time}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'language' => 'ua',
                'name' => 'Нагадування про запис на прийом',
                'alias' => 'patient-appointment-reminder-ua',
                'content' => 'Нагадуємо про запис на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'language' => 'ru',
                'name' => 'Нагадування про запис на прийом (русский)',
                'alias' => 'patient-appointment-reminder-ru',
                'content' => 'Напоминаем про запись на приём в Детский Медицинский Центр "Your Baby" {date} в {time}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];
    }
}
