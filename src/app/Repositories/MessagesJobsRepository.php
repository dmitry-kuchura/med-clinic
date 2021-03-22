<?php

namespace App\Repositories;

use App\Models\MessagesJobs;
use Illuminate\Support\Carbon;

class MessagesJobsRepository implements Repository
{
    public function getLast(): ?MessagesJobs
    {
        return MessagesJobs::orderBy('id', 'desc')->first();
    }

    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data): MessagesJobs
    {
        $model = new MessagesJobs();

        $model->last_appointment_at = Carbon::now()->format('Y-m-d H:i:s');
        $model->count = $data['count'];

        $model->save();

        return $model;
    }

    public function update(array $data, int $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
