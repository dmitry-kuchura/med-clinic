<?php

namespace App\Actions;

use App\Models\User;
use App\Repositories\PatientRepository;
use App\Repositories\UsersRepository;
use Illuminate\Support\Str;

class PatientAction
{
    const RECORDS_AT_PAGE = 10;

    private PatientRepository $patientRepository;

    private UsersRepository $usersRepository;

    public function __construct(
        PatientRepository $patientRepository,
        UsersRepository $usersRepository
    )
    {
        $this->patientRepository = $patientRepository;
        $this->usersRepository = $usersRepository;
    }

    public function list()
    {
        return $this->patientRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function info(int $id)
    {
        return $this->patientRepository->get($id);
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

        $this->patientRepository->update($patientData, $data['id']);
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
            'gender' => $data['gender'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'email' => $user->email,
            'user_id' => $user->id
        ];

        $this->patientRepository->store($patientData);

        return true;
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
