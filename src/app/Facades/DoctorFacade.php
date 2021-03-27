<?php

namespace App\Facades;

use App\Exceptions\UpdatePatientException;
use App\Models\Doctor;
use App\Repositories\DoctorsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\Str;

class DoctorFacade implements Facade
{
    /** @var DoctorsRepository */
    private DoctorsRepository $doctorsRepository;

    /** @var UsersRepository */
    private UsersRepository $usersRepository;

    public function __construct(
        DoctorsRepository $doctorsRepository,
        UsersRepository $usersRepository
    )
    {
        $this->doctorsRepository = $doctorsRepository;
        $this->usersRepository = $usersRepository;
    }

    public function list()
    {
        return $this->doctorsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function find(int $id): ?Doctor
    {
        return $this->findDoctor($id);
    }

    public function create(array $data): Doctor
    {
        $existDoctor = $this->findExistDoctor($data['external_id']);

        if ($existDoctor) {
            return $existDoctor;
        }

        $existUser = null;

        if (isset($data['email']) && $data['email']) {
            $existUser = $this->findDoctorByEmail($data['email']);
        }

        if ($existUser) {
            $user = $existUser;
        } else {
            $user = $this->usersRepository->store([
                'email' => isset($data['email']) ? $data['email'] : $this->generateTempEmail(),
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'password' => bcrypt(Str::random(9)),
            ]);
        }

        $doctorData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'birthday' => $data['birthday'] ?? null,
            'gender' => $data['gender'],
            'phone' => $data['phone'] ? trim(str_replace(' ', '', $data['phone'])) : null,
            'external_id' => $data['external_id'] ?? null,
            'user_id' => $user->id
        ];

        return $this->doctorsRepository->store($doctorData);
    }

    public function update(array $data): void
    {
        $patientData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        $patient = $this->findDoctor($data['id']);

        try {
            $this->usersRepository->update(['email' => $data['email']], $patient->user_id);
            $this->doctorsRepository->update($patientData, $data['id']);
        } catch (\Throwable $throwable) {
            throw new UpdatePatientException();
        }
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    private function findExistDoctor(int $externalId): ?Doctor
    {
        return $this->findDoctorByExternalId($externalId);
    }

    private function findDoctor(int $id): ?Doctor
    {
        return $this->doctorsRepository->get($id);
    }

    private function findDoctorByEmail(string $email): ?Doctor
    {
        return $this->doctorsRepository->findByEmail($email);
    }

    private function findDoctorByExternalId(int $externalId): ?Doctor
    {
        return $this->doctorsRepository->findByExternalId($externalId);
    }

    private function generateTempEmail(): string
    {
        $temporaryEmail = 'temp_' . Str::random(10) . '@temporary.email';

        $doctor = $this->findDoctorByEmail($temporaryEmail);

        if ($doctor) {
            return $this->generateTempEmail();
        }

        return $temporaryEmail;
    }
}
