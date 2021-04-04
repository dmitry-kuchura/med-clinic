<?php

namespace App\Repositories;

use App\Models\PatientAppointmentReminder;

class PatientAppointmentReminderRepository implements Repository
{
    public function getForRemind(string $timestamp)
    {
        return PatientAppointmentReminder::where('appointment_at', '>', $timestamp)
            ->where('is_mark', false)
            ->with(['patient'])
            ->limit(25)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function marked(PatientAppointmentReminder $reminder)
    {
        PatientAppointmentReminder::where('id', $reminder->id)->update(['is_mark' => true]);
    }

    public function paginate(int $id, int $offset)
    {
        return PatientAppointmentReminder::where('patient_id', $id)
            ->with(['patient'])
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
        $model = new PatientAppointmentReminder();

        $model->appointment_at = $data['appointment_at'];
        $model->patient_id = $data['patient_id'];

        $model->save();
    }

    public function update(array $data, int $id)
    {
        return PatientAppointmentReminder::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
