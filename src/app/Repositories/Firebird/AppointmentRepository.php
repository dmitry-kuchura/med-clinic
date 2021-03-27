<?php

namespace App\Repositories\Firebird;

use App\Models\Firebird\Appointment;
use Illuminate\Support\Collection;

class AppointmentRepository
{
    public function lastAppointment(string $timestamp, string $end, ?int $external): ?Collection
    {
        return Appointment::select(
            'APPOINTMENT_LOG.NR',
            'APPOINTMENT_LOG.TIMESTART',
            'APPOINTMENT_LOG.PAT_NR',
            'APPOINTMENT_LOG.STAFFFIO',
            'APPOINTMENT_LOG.COMMENT'
        )
            ->with('patient')
            ->where('APPOINTMENT_LOG.TIMESTART', '>', $timestamp)
            ->where('APPOINTMENT_LOG.TIMESTART', '<', $end)
            ->whereIn('APPOINTMENT_LOG.OPTYPE_NR', [1, 2])
            ->where(function ($query) use ($external) {
                if ($external) {
                    $query->where('APPOINTMENT_LOG.NR', '>', $external);
                }
            })->limit(10)
            ->orderBy('APPOINTMENT_LOG.TIMESTART', 'ASC')
            ->orderBy('APPOINTMENT_LOG.NR', 'ASC')
            ->get();
    }
}
