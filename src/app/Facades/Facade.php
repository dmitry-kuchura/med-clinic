<?php

namespace App\Facades;

interface Facade
{
    public function find(int $id);

    public function create(array $data);

    public function update(array $data);

    public function delete(int $id);
}
