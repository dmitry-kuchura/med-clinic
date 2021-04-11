<?php

namespace App\Services;

use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogService
{
    const RECORDS_AT_PAGE = 30;

    /** @var LogsRepository */
    private LogsRepository $logsRepository;

    /** @var Request */
    protected Request $request;

    /** @var array */
    private array $data;

    public function __construct(
        LogsRepository $logsRepository,
        Request $request
    )
    {
        $this->logsRepository = $logsRepository;
        $this->request = $request;
        $this->data = [];

        $this->prepareData();
    }

    public function list()
    {
        return $this->logsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function info(string $message, array $context = [])
    {
        $this->data['level'] = 'info';
        $this->data['message'] = $message;

        $this->save($this->data);
    }

    public function debug()
    {
        $this->data['level'] = 'debug';

        $this->save($this->data);
    }

    public function alert()
    {
        $this->data['level'] = 'alert';

        $this->save($this->data);
    }

    public function warning()
    {
        $this->data['level'] = 'warning';

        $this->save($this->data);
    }

    public function error()
    {
        $this->data['level'] = 'error';

        $this->save($this->data);
    }

    public function exception(string $message)
    {
        $this->data['level'] = 'exception';
        $this->data['message'] = $message;

        $this->save($this->data);
    }

    private function prepareData(): void
    {
        $this->data['remote_addr'] = $this->request->ip();
        $this->data['user_agent'] = $this->request->userAgent();
        $this->data['request'] = json_encode($this->request->all());
    }

    private function save(array $data): void
    {
        $this->logsRepository->store($data);
    }
}
