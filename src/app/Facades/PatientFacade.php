<?php

namespace App\Facades;

use App\Exceptions\UpdatePatientException;
use App\Models\Patient;
use App\Repositories\PatientsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PatientFacade implements Facade
{
    /** @var PatientsRepository */
    private PatientsRepository $patientsRepository;

    /** @var UsersRepository */
    private UsersRepository $usersRepository;

    public function __construct(
        PatientsRepository $patientsRepository,
        UsersRepository $usersRepository
    )
    {
        $this->patientsRepository = $patientsRepository;
        $this->usersRepository = $usersRepository;
    }

    public function list()
    {
        return $this->patientsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function find(int $id): ?Patient
    {
        return $this->findPatient($id);
    }

    public function search(string $query): ?Collection
    {
        return $this->patientsRepository->search($query);
    }

    public function create(array $data): Patient
    {
        $existPatient = $this->findExistPatient($data['external_id']);

        if ($existPatient) {
            return $existPatient;
        }

        $existUser = null;

        if (isset($data['email']) && $data['email']) {
            $existUser = $this->findPatientByEmail($data['email']);
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


        $patientData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'birthday' => $data['birthday'] ?? null,
            'gender' => $data['gender'],
            'phone' => $data['phone'] ? trim(str_replace(' ', '', $data['phone'])) : null,
            'address' => $data['address'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'user_id' => $user->id
        ];

        return $this->patientsRepository->store($patientData);
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

        $patient = $this->findPatient($data['id']);

        try {
            $this->usersRepository->update(['email' => $data['email']], $patient->user_id);
            $this->patientsRepository->update($patientData, $data['id']);
        } catch (\Throwable $throwable) {
            throw new UpdatePatientException();
        }
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    private function findExistPatient(int $externalId): ?Patient
    {
        return $this->findPatientByExternalId($externalId);
    }

    private function findPatient(int $id): ?Patient
    {
        return $this->patientsRepository->get($id);
    }

    private function findPatientByEmail(string $email): ?Patient
    {
        return $this->patientsRepository->findByEmail($email);
    }

    private function findPatientByExternalId(string $externalId): ?Patient
    {
        return $this->patientsRepository->findByExternalId($externalId);
    }

    private function generateTempEmail(): string
    {
        $temporaryEmail = 'temp_' . Str::random(10) . '@temporary.email';

        $patient = $this->findPatientByEmail($temporaryEmail);

        if ($patient) {
            return $this->generateTempEmail();
        }

        return $temporaryEmail;
    }
}
