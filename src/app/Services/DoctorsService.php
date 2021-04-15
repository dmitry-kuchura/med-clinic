<?php

namespace App\Services;

use App\Exceptions\UpdateDoctorException;
use App\Helpers\GenerateTempEmail;
use App\Helpers\PhoneNumber;
use App\Models\Doctor;
use App\Models\DoctorApproved;
use App\Models\Enum\UserRole;
use App\Repositories\DoctorsApprovedRepository;
use App\Repositories\DoctorsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Throwable;

class DoctorsService
{
    const RECORDS_AT_PAGE = 30;

    /** @var DoctorsRepository */
    private DoctorsRepository $doctorsRepository;

    /** @var DoctorsApprovedRepository */
    private DoctorsApprovedRepository $doctorsApprovedRepository;

    /** @var UsersRepository */
    private UsersRepository $usersRepository;

    /** @var GenerateTempEmail */
    private GenerateTempEmail $helper;

    public function __construct(
        DoctorsRepository $doctorsRepository,
        DoctorsApprovedRepository $doctorsApprovedRepository,
        UsersRepository $usersRepository
    )
    {
        $this->helper = new GenerateTempEmail($doctorsRepository);
        $this->doctorsApprovedRepository = $doctorsApprovedRepository;
        $this->doctorsRepository = $doctorsRepository;
        $this->usersRepository = $usersRepository;
    }

    public function list()
    {
        return $this->doctorsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function approvedList()
    {
        return $this->doctorsApprovedRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function addApprove(int $doctorId)
    {
        $doctorData = [
            'doctor_id' => $doctorId,
        ];

        return $this->doctorsApprovedRepository->store($doctorData);
    }

    public function deleteApprove(int $doctorId)
    {
        return $this->doctorsApprovedRepository->destroy($doctorId);
    }

    public function search(string $query): ?Collection
    {
        return $this->doctorsRepository->search($query);
    }

    public function find(int $id): ?Doctor
    {
        return $this->findDoctor($id);
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
        } catch (Throwable $throwable) {
            throw new UpdateDoctorException();
        }
    }

    public function create(array $data): ?Doctor
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
                'email' => isset($data['email']) ? $data['email'] : $this->helper->generateTempEmail(),
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'password' => bcrypt(Str::random(9)),
                'role' => UserRole::DOCTOR,
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

    public function findExistDoctor(int $externalId): ?Doctor
    {
        return $this->findDoctorByExternalId($externalId);
    }

    public function findDoctor(int $id): ?Doctor
    {
        return $this->doctorsRepository->get($id);
    }

    public function findDoctorByEmail(string $email): ?Doctor
    {
        return $this->doctorsRepository->findByEmail($email);
    }

    public function findDoctorByExternalId(int $externalId): ?Doctor
    {
        return $this->doctorsRepository->findByExternalId($externalId);
    }

    public function syncDoctor(array $data): ?Doctor
    {
        $data['phone'] = PhoneNumber::prepare($data['phone']);

        return $this->create($data);
    }

    public function doctorIsApprove(int $doctorId): ?DoctorApproved
    {
        return $this->doctorsApprovedRepository->find($doctorId);
    }
}
