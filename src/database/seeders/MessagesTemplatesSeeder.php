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
                'alias' => 'patient-appointment',
                'content' => 'Ви записані на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}. Якщо Ви бажаєте відмінити візит, будь ласка, повідомте нам за телефоном (або Viber) 0996606624',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'language' => 'ua',
                'name' => 'Запис на прийом LAB',
                'alias' => 'patient-appointment-lab',
                'content' => 'Ви записані на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}. Чекаємо за адресою м. Харків, вуд. Пушкінська 14. Про будь які зміни повідомте нам за телефоном (або Viber) 0996606624',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'language' => 'ua',
                'name' => 'Нагадування про запис на прийом',
                'alias' => 'patient-appointment-reminder',
                'content' => 'Нагадуємо про запис на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}. Якщо Ви бажаєте відмінити візит, будь ласка, повідомте нам за телефоном (або Viber) 0996606624',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'language' => 'ua',
                'name' => 'Нагадування про запис на прийом LAB',
                'alias' => 'patient-appointment-reminder-lab',
                'content' => 'Нагадуємо про запис на прийом в Дитячий Медичний Центр "Your Baby" {date} на {time}. Чекаємо за адресою м. Харків, вуд. Пушкінська 14. Про будь які зміни повідомте нам за телефоном (або Viber) 0996606624',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
