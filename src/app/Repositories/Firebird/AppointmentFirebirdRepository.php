<?php

namespace App\Repositories\Firebird;

use App\Models\Firebird\Appointment;
use Illuminate\Support\Collection;

class AppointmentFirebirdRepository
{
    public function lastAppointment(string $timestamp, ?int $external): ?Collection
    {
        return Appointment::select(
            'APPOINTMENT_LOG.NR',
            'APPOINTMENT_LOG.TIMESTART',
            'APPOINTMENT_LOG.PAT_NR',
            'APPOINTMENT_LOG.STAFF_NR',
            'APPOINTMENT_LOG.STAFFFIO',
            'APPOINTMENT_LOG.OPTYPE_NR',
            'APPOINTMENT_LOG.COMMENT'
        )
            ->with('patient', 'doctor')
            ->where(function ($query) use ($external, $timestamp) {
                if (!$external) {
                    $query->where('APPOINTMENT_LOG.TIMESTART', '>', $timestamp);
                }
            })
            ->where(function ($query) use ($external) {
                if ($external) {
                    $query->where('APPOINTMENT_LOG.NR', '>', $external);
                }
            })
            ->where('APPOINTMENT_LOG.PAT_NR', '>', 0)
            ->limit(50)
            ->orderBy('APPOINTMENT_LOG.NR', 'ASC')
            ->get();
    }
}
