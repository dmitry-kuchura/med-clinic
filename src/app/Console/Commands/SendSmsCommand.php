<?php

namespace App\Console\Commands;

use App\Helpers\TurboSMS;
use Illuminate\Console\Command;

class SendSmsCommand extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'sms:send {user}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Send drip e-mails to a user';

    /**
     * Служба "TurboSMS"
     *
     * @var TurboSMS
     */
    protected $service;

    /**
     * Создание нового экземпляра команды.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new TurboSMS();
    }

    public function handle()
    {
        $this->service->send(['+380931106215'], 'Send SMS every 4 minutes');
    }
}
