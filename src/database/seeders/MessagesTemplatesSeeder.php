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
                'name' => 'Нагадування про запис на прийом LAB',
                'alias' => 'patient-reminder-analyse',
                'content' => 'Повідомляємо, що Ваші лабораторні дослідження готові. Надішліть, будь ласка, Ваше повідомлення - запит за номером (Viber)  0996606624, та вкажіть ПІБ пацієнта. Будьте здорові',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
