<?php

namespace App\Repositories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientsRepository implements Repository
{
    public function findByExternalId(int $externalId): ?Patient
    {
        return Patient::where('external_id', $externalId)->first();
    }

    public function findByEmail(string $email): ?Patient
    {
        return Patient::whereHas('user', function ($query) use ($email) {
            return $query->where('email', $email);
        })->first();
    }

    public function search(string $query): ?Collection
    {
        return Patient::with('user')
            ->where('first_name', 'like', '%' . $query . '%')
            ->orWhere('last_name', 'like', '%' . $query . '%')
            ->orWhere('middle_name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->limit(25)
            ->get();
    }

    public function paginate(int $offset)
    {
        return Patient::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id): ?Patient
    {
        return Patient::with('user')->find($id);
    }

    public function all()
    {
        return Patient::all();
    }

    public function store(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(array $data, int $id)
    {
        return Patient::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
