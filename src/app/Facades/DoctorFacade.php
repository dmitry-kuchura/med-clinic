<?php

namespace App\Facades;

use App\Exceptions\UpdatePatientException;
use App\Models\Doctor;
use App\Repositories\DoctorsRepository;
use App\Repositories\PatientsRepository;
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
        $existDoctor = $this->findExistDoctor($data);

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
            'external_id' => $data['doctor_external_id'] ?? null,
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

    private function findExistDoctor(array $data): ?Doctor
    {
        if (isset($data['phone']) && $data['phone']) {
            $existByPhone = $this->findDoctorByPhone($data['phone']);

            if ($existByPhone) {
                return $existByPhone;
            }
        }

        if (isset($data['email']) && $data['email']) {
            $existByEmail = $this->findDoctorByEmail($data['email']);

            if ($existByEmail) {
                return $existByEmail;
            }
        }

        if (isset($data['birthday']) && $data['birthday']) {
            $existByBirthday = $this->findDoctorByBirthday($data['birthday']);

            if ($existByBirthday) {
                return $existByBirthday;
            }
        }

        if (isset($data['first_name']) || isset($data['last_name']) || isset($data['middle_name'])) {
            $existPatient = $this->findDoctorByName($data);

            if ($existPatient) {
                return $existPatient;
            }
        }

        return null;
    }

    private function findDoctor(int $id): ?Doctor
    {
        return $this->doctorsRepository->get($id);
    }

    private function findDoctorByPhone(string $phone): ?Doctor
    {
        return $this->doctorsRepository->findByPhone($phone);
    }

    private function findDoctorByEmail(string $email): ?Doctor
    {
        return $this->doctorsRepository->findByEmail($email);
    }

    private function findDoctorByBirthday(string $birthday): ?Doctor
    {
        return $this->doctorsRepository->findByBirthday($birthday);
    }

    private function findDoctorByName(array $data): ?Doctor
    {
        return $this->doctorsRepository->findByName($data);
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
