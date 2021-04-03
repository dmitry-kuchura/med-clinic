<?php

namespace App\Repositories;

namespace App\Repositories;

use App\Models\Test;

class TestsRepository implements Repository
{
    public function paginate(int $offset)
    {
        return Test::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id)
    {
        return Test::with('user')->find($id);
    }

    public function all()
    {
        return Test::all();
    }

    public function store(array $data): Test
    {
        return Test::create($data);
    }

    public function update(array $data, int $id)
    {
        return Test::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
