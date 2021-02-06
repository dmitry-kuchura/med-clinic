<?php

namespace App\Repositories;

interface Repository
{
    public function get(int $id);

    public function all();

    public function store(array $data);

    public function update(array $data, int $id);

    public function destroy(int $id);
}
