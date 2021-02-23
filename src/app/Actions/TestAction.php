<?php

namespace App\Actions;

use App\Repositories\TestsRepository;

class TestAction
{
    const RECORDS_AT_PAGE = 10;

    private TestsRepository $testsRepository;

    public function __construct(TestsRepository $testsRepository)
    {
        $this->testsRepository = $testsRepository;
    }

    public function list()
    {
        return $this->testsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function all()
    {
        return $this->testsRepository->all();
    }
}
