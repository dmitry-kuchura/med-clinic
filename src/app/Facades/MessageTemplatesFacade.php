<?php

namespace App\Facades;

use App\Repositories\MessagesTemplatesRepository;

class MessageTemplatesFacade implements Facade
{
    /** @var MessagesTemplatesRepository */
    private MessagesTemplatesRepository $messageTemplatesRepository;

    public function __construct(
        MessagesTemplatesRepository $messageTemplatesRepository
    )
    {
        $this->messageTemplatesRepository = $messageTemplatesRepository;
    }

    public function paginate()
    {
        return $this->messageTemplatesRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function find(int $id)
    {
        return $this->messageTemplatesRepository->get($id);
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }
}
