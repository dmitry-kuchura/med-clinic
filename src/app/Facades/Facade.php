<?php

namespace App\Facades;

interface Facade
{
    const RECORDS_AT_PAGE = 30;

    public function find(int $id);

    public function create(array $data);

    public function update(array $data);

    public function delete(int $id);
}
