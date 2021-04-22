<?php

namespace App\Repositories;

use App\Models\PatientAppointment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PatientsAppointmentsRepository implements Repository
{
    public function getLastPatient(): ?PatientAppointment
    {
        return PatientAppointment::orderBy('id', 'desc')->first();
    }

    public function today(string $timestamp): ?Collection
    {
        $distinct = DB::table('patients_appointments')
            ->select('doctor_id', DB::raw('MAX(id) as maxId'))
            ->groupBy('doctor_id', 'patient_id');

        return PatientAppointment::select('*')
            ->joinSub($distinct, 'pa', function ($join) {
                $join->on('patients_appointments.id', '=', DB::raw('pa.maxId'));
            })
            ->where('appointment_at', '>', $timestamp)
            ->with(['doctor', 'patient'])
            ->limit(15)
            ->orderBy('appointment_at', 'asc')
            ->get();
    }

    public function getPatientsForRemind(string $timestamp, string $endDayTimestamp): ?Collection
    {
        return PatientAppointment::where('appointment_at', '>', $timestamp)
            ->where('appointment_at', '<', $timestamp)
            ->where('is_mark', false)
            ->limit(1)
            ->orderBy('appointment_at', 'asc')
            ->groupBy('id', 'patient_id', 'doctor_id', 'appointment_at')
            ->get();
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
        PatientAppointment::whereIn('id', $ids)->update(['is_mark' => true]);
    }

    public function paginate(int $id, int $offset)
    {
        return PatientAppointment::where('patient_id', $id)
            ->with(['doctor', 'patient'])
            ->orderBy('id', 'desc')
            ->paginate($offset);
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
