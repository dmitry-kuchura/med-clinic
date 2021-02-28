<?php

namespace App\Actions;

use App\Exceptions\NotAddPatientTestException;
use App\Models\PatientsTests;
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
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        if ($data['email']) {
            $existUser = $this->findUserByID($data['id']);
            if ($existUser->email !== $data['email']) {
                if (!$existUser = $this->findUserByEmail($data['email'])) {
                    $this->usersRepository->update(['email' => $data['email']], $data['id']);
                }
            }
        }

        $this->patientsRepository->update($patientData, $data['id']);
    }

    public function create(array $data): bool
    {
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

        $this->patientsRepository->store($patientData);

        return true;
    }

    public function addPatientTest(array $data): PatientsTests
    {
        $testData = [
            'test_id' => $data['test_id'],
            'patient_id' => $data['patient_id'],
            'file' => 'asfgsdfgsdfgKJHksfkBKbKLSADfbvklB123kjBklbaklblkhbdvbvksdbvksdbvksdbvksdbjv',
            'result' => $data['result'] ?? null,
            'reference_values' => $data['reference_values'] ?? null,
        ];

        try {
            $patientTest = $this->patientsTestsRepository->store($testData);
        } catch (\Throwable $e) {
            throw new NotAddPatientTestException();
        }

        return $this->patientsTestsRepository->get($patientTest->id);
    }

    public function listPatientTests(int $id)
    {
        return $this->patientsTestsRepository->paginate($id, self::RECORDS_AT_PAGE);
    }

    private function findUserByEmail(string $email): ?User
    {
        return $this->usersRepository->findByEmail($email);
    }

    private function findUserByID(int $id): ?User
    {
        return $this->usersRepository->findByID($id);
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
