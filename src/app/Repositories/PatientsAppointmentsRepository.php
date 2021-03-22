<?php

namespace App\Repositories;

use App\Models\PatientsAppointments;

class PatientsAppointmentsRepository implements Repository
{
    public function getLastPatient(): ?PatientsAppointments
    {
        return PatientsAppointments::orderBy('id', 'desc')->first();
    }

    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data)
    {
        // TODO: Implement store() method.
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
