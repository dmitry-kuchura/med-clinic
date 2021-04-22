<?php

namespace App\Console\Commands;

use App\Exceptions\UpdateMessageErrorException;
use App\Services\MessagesService;
use Illuminate\Console\Command;
use Throwable;

class UpdateMessageStatusCommand extends Command
{
    /** @var string */
    protected $signature = 'update:messages-status';

    /** @var string */
    protected $description = 'Update message status.';

    /** @var MessagesService */
    private MessagesService $messagesService;

    public function __construct(
        MessagesService $messagesService
    )
    {
        parent::__construct();
        $this->messagesService = $messagesService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $messagesForUpdate = $this->messagesService->getMessagesForUpdateStatus();

        try {
            $this->messagesService->getMessagesStatus($messagesForUpdate);
        } catch (Throwable $throwable) {
            throw new UpdateMessageErrorException($throwable->getMessage());
        }

        return true;
    }
}
