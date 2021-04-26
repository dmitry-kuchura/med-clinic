<?php

namespace App\Services;

use App\Exceptions\UpdatePatientException;
use App\Helpers\GenerateTempEmail;
use App\Helpers\PhoneNumber;
use App\Models\Enum\UserRole;
use App\Models\Patient;
use App\Models\Firebird\Patient as FirebirdPatient;
use App\Repositories\Firebird\PatientFirebirdRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Throwable;

class PatientsService
{
    const RECORDS_AT_PAGE = 30;

    /** @var PatientFirebirdRepository */
    private PatientFirebirdRepository $patientFirebirdRepository;

    /** @var PatientsRepository */
    private PatientsRepository $patientsRepository;

    /** @var UsersRepository */
    private UsersRepository $usersRepository;

    /** @var GenerateTempEmail */
    private GenerateTempEmail $helper;

    public function __construct(
        PatientFirebirdRepository $patientFirebirdRepository,
        PatientsRepository $patientsRepository,
        UsersRepository $usersRepository
    )
    {
        $this->helper = new GenerateTempEmail($patientsRepository);
        $this->patientFirebirdRepository = $patientFirebirdRepository;
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

    public function update(array $data): void
    {
        $patientData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'gender' => $data['gender'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        $patient = $this->findPatient($data['id']);

        try {
            $this->usersRepository->update(['email' => $data['email']], $patient->user_id);
            $this->patientsRepository->update($patientData, $data['id']);
        } catch (Throwable $throwable) {
            throw new UpdatePatientException();
        }
    }

    public function updatePatientByExternal(array $data): void
    {
        $patientData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'phone' => $data['phone'] ?? null,
        ];

        $patient = $this->findPatientByExternalId($data['external_id']);

        try {
            $this->patientsRepository->update($patientData, $patient->id);
        } catch (Throwable $throwable) {
            throw new UpdatePatientException();
        }
    }

    public function create(array $data): ?Patient
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
                'email' => isset($data['email']) ? $data['email'] : $this->helper->generateTempEmail(),
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'password' => bcrypt(Str::random(9)),
                'role' => UserRole::PATIENT,
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

    public function getPatientsWithoutPhone(): array
    {
        $ids = [];

        $patients = $this->patientsRepository->getPatientsWithoutPhone();

        /** @var Patient $patient */
        foreach ($patients as $patient) {
            $ids[] = $patient->external_id;
        }

        return $ids;
    }

    public function getRemotePatients(array $ids): array
    {
        $data = [];

        $results = $this->patientFirebirdRepository->getPatients($ids);

        /** @var FirebirdPatient $result */
        foreach ($results as $result) {
            $data[] = [
                'external_id' => $result->NR,
                'first_name' => $result->human->FIRSTNAME,
                'last_name' => $result->human->SURNAME,
                'middle_name' => $result->human->SECNAME,
                'gender' => $result->human->SEX === 1 ? 'male' : 'female',
                'birthday' => $result->human->DOB ?? null,
                'phone' => $this->getPatientPhone($result),
            ];
        }

        return $data;
    }

    public function getPatientPhone(FirebirdPatient $patient): ?string
    {
        if ($patient->human->PHONE) {
            return trim($patient->human->PHONE);
        }

        if ($patient->human->MOBPHONE) {
            return trim($patient->human->MOBPHONE);
        }

        if ($patient->human->OTHERPHONES) {
            return trim($patient->human->OTHERPHONES);
        }

        return null;
    }

    public function search(string $query): ?Collection
    {
        return $this->patientsRepository->search($query);
    }

    public function syncPatient(array $data): ?Patient
    {
        $data['phone'] = PhoneNumber::prepare($data['phone']);

        return $this->create($data);
    }

    public function syncPatientPhoneNumber(array $data)
    {
        $data['phone'] = PhoneNumber::prepare($data['phone']);

        $this->updatePatientByExternal($data);
    }

    public function findExistPatient(int $externalId): ?Patient
    {
        return $this->findPatientByExternalId($externalId);
    }

    public function findPatient(int $id): ?Patient
    {
        return $this->patientsRepository->get($id);
    }

    public function findPatientByEmail(string $email): ?Patient
    {
        return $this->patientsRepository->findByEmail($email);
    }

    public function findPatientByExternalId(string $externalId): ?Patient
    {
        return $this->patientsRepository->findByExternalId($externalId);
    }
}
