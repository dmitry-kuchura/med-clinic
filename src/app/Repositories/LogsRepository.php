<?php

namespace App\Repositories;

namespace App\Repositories;

use App\Models\Log;

class LogsRepository implements Repository
{
    public function paginate(int $offset)
    {
        return Log::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id)
    {
        return Log::with('user')->find($id);
    }

    public function all()
    {
        return Log::all();
    }

    public function store(array $data): Log
    {
        return Log::create($data);
    }

    public function update(array $data, int $id)
    {
        return Log::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
