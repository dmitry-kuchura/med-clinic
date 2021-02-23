<?php

namespace App\Repositories;

namespace App\Repositories;

use App\Models\Tests;

class TestsRepository implements Repository
{
    public function paginate(int $offset)
    {
        return Tests::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id)
    {
        return Tests::with('user')->find($id);
    }

    public function all()
    {
        return Tests::all();
    }

    public function store(array $data): Tests
    {
        return Tests::create($data);
    }

    public function update(array $data, int $id)
    {
        return Tests::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
