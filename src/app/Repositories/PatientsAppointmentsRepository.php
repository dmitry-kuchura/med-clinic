<?php

namespace App\Repositories;

use App\Models\PatientAppointment;

class PatientsAppointmentsRepository implements Repository
{
    public function search(string $timestamp, int $patient_id): ?PatientAppointment
    {
        return PatientAppointment::where('appointment_at', $timestamp)
            ->where('patient_id', $patient_id)
            ->first();
    }

    public function getLastPatient(): ?PatientAppointment
    {
        return PatientAppointment::orderBy('id', 'desc')->first();
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
        $model = new PatientAppointment();

        $model->appointment_at = $data['appointment_at'];
        $model->comment = $data['comment'];
        $model->doctor_name = $data['doctor_name'];
        $model->patient_id = $data['patient_id'];
        $model->external_id = $data['external_id'];

        $model->save();
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
