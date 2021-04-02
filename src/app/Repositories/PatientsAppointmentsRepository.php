<?php

namespace App\Repositories;

use App\Models\PatientAppointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class PatientsAppointmentsRepository implements Repository
{
    public function getLastPatient(): ?PatientAppointment
    {
        return PatientAppointment::orderBy('id', 'desc')->first();
    }

    public function getFirstPatient(): ?PatientAppointment
    {
        return PatientAppointment::orderBy('id', 'asc')->first();
    }

    public function findHistory(int $patient, int $doctor, string $date_from, string $date_to): ?Collection
    {
        return PatientAppointment::where('patient_id', $patient)
            ->where('doctor_id', $doctor)
            ->where('is_mark', false)
            ->whereBetween('appointment_at', [$date_from, $date_to])
            ->orderBy('external_id', 'desc')
            ->get();
    }

    public function markedWithHistory(array $ids): void
    {
        PatientAppointment::whereIn('id', $ids)
            ->update(['is_mark' => true]);
    }

    public function paginate(int $id, int $offset)
    {
        return PatientAppointment::where('patient_id', $id)->with(['doctor', 'patient'])->orderBy('id', 'desc')->paginate($offset);
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
        $model->type = $data['type'];
        $model->doctor_name = $data['doctor_name'];
        $model->patient_id = $data['patient_id'];
        $model->doctor_id = $data['doctor_id'];
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
