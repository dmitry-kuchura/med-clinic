<?php

namespace App\Repositories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class DoctorsRepository implements Repository
{
    public function search(string $query): ?Collection
    {
        return Doctor::with('user')
            ->where('first_name', 'like', '%' . $query . '%')
            ->orWhere('last_name', 'like', '%' . $query . '%')
            ->orWhere('middle_name', 'like', '%' . $query . '%')
            ->limit(7)
            ->get();
    }

    public function findByExternalId(int $externalId): ?Doctor
    {
        return Doctor::where('external_id', $externalId)->first();
    }

    public function findByEmail(string $email): ?Doctor
    {
        return Doctor::whereHas('user', function ($query) use ($email) {
            return $query->where('email', $email);
        })->first();
    }

    public function paginate(int $offset)
    {
        return Doctor::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id): ?Doctor
    {
        return Doctor::with('user')->find($id);
    }

    public function all()
    {
        return Doctor::all();
    }

    public function store(array $data): Doctor
    {
        return Doctor::create($data);
    }

    public function update(array $data, int $id)
    {
        return Doctor::where('id', $id)->update($data);
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
