<?php

namespace App\Actions;

use App\Exceptions\UpdatePatientException;
use App\Models\Patients;
use App\Models\User;
use App\Repositories\PatientsRepository;
use App\Repositories\PatientsTestsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\Str;

class PatientAction
{
    const RECORDS_AT_PAGE = 10;

    private UsersRepository $usersRepository;

    private PatientsRepository $patientsRepository;

    private PatientsTestsRepository $patientsTestsRepository;

    public function __construct(
        UsersRepository $usersRepository,
        PatientsRepository $patientsRepository,
        PatientsTestsRepository $patientsTestsRepository
    )
    {
        $this->patientsRepository = $patientsRepository;
        $this->usersRepository = $usersRepository;
        $this->patientsTestsRepository = $patientsTestsRepository;
    }

    public function list()
    {
        return $this->patientsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function info(int $id)
    {
        return $this->patientsRepository->get($id);
    }

    public function update(array $data)
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

    public function create(array $data): ?Patients
    {
        if (isset($data['phone']) && $data['phone']) {
            $existPatient = $this->findPatientByPhone($data['phone']);

            if ($existPatient) {
                return $existPatient;
            }

        }

        $existUser = null;

        if (isset($data['email']) && $data['email']) {
            $existUser = $this->findUserByEmail($data['email']);
        }

        if ($existUser) {
            $user = $existUser;
        } else {
            $user = $this->usersRepository->store([
                'email' => isset($data['email']) ? $data['email'] : $this->generateTempEmail(),
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'password' => bcrypt(Str::random(6)),
            ]);
        }

        $patientData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'user_id' => $user->id
        ];

        return $this->patientsRepository->store($patientData);
    }

    private function findPatient(int $id): ?Patients
    {
        return $this->patientsRepository->find($id);
    }

    private function findPatientByPhone(string $phone): ?Patients
    {
        return $this->patientsRepository->findByPhone($phone);
    }

    private function findUserByEmail(string $email): ?User
    {
        return $this->usersRepository->findByEmail($email);
    }

    private function generateTempEmail(): string
    {
        $temporaryEmail = 'temp_' . Str::random(10) . '@temporary.email';

        $user = $this->findUserByEmail($temporaryEmail);

        if ($user) {
            return $this->generateTempEmail();
        }

        return $temporaryEmail;
    }
}
