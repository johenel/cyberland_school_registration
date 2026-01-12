<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Guardian;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;

class ApplicationService
{
    private string $generatedGuardianPassword;
    private Application|null $application;

    public function use(Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function new(
        string $guardianFirstName,
        string $guardianLastName,
        string $guardianEmail,
        string $guardianContactNumber,
        string $studentFirstName,
        string $studentLastName,
        string $studentBirthDate,
        string $studentMiddleName = null,
        string $guardianRelationship = null
    ): Application
    {
        // Create guardian record
        $guardian = $this->createGuardian($guardianEmail, $guardianFirstName, $guardianLastName, $guardianContactNumber, $guardianRelationship);
        // Create student record and associate guardian
        $student = $this->createStudent($studentFirstName, $studentLastName, $studentBirthDate, $studentMiddleName);
        $student->guardian()->associate($guardian);
        // Create application and associate guardian and student
        $application = new Application;
        $application->guardian()->associate($guardian);
        $application->student()->associate($student);
        // Application now is created with PENDING status as default status
        $application->save();

        return $application;
    }

    public function getGuardianPassword(): string
    {
        return $this->generatedGuardianPassword;
    }

    public function process(): void
    {
        $this->updateStatus(Application::STATUS_PROCCESSING);
    }

    public function hold(string $remarks): void
    {
        $this->updateStatus(Application::STATUS_ON_HOLD, $remarks);
    }

    public function reject(string $remarks): void
    {
        $this->updateStatus(Application::STATUS_REJECTED, $remarks);
    }

    public function accept(): void
    {
        $this->updateStatus(Application::STATUS_ACCEPTED);
    }

    private function updateStatus(int $status, string $remarks = null): void
    {
        $this->application->status = $status;
        $this->application->remarks = $remarks;
        $this->application->save();
    }

    private function createGuardian(
        string $email,
        string $firstName,
        string $lastName,
        string $contactNumber,
        string $relationship = null
    ): Guardian
    {
        $guardian = new Guardian;
        $guardian->first_name = $firstName;
        $guardian->last_name = $lastName;
        $guardian->contact_number = $contactNumber;
        $guardian->relationship = $relationship;
        $guardian->save();
        // Create user for guardian and associate
        $this->generatedGuardianPassword = $this->generateRandomPassword(6);
        User::create([
            'name' => strtolower("{$firstName} {$lastName}"),
            'email' => $email,
            'password' => \Hash::make($this->generatedGuardianPassword)
        ]);
        $user = User::query()->where('email', $email)->first();
        // Assign role
        $guardianRole = Role::query()->where('name','guardian')->first();
        $user->role()->associate($guardianRole);
        $user->save();
        // associate guardian to user
        $guardian->user()->associate($user);
        $guardian->save();

        return $guardian;
    }

    private function createStudent(
        string $firstName,
        string $lastName,
        string $birthDate,
        string $middleName = null
    ): Student
    {
        $student = new Student;
        $student->first_name = $firstName;
        $student->last_name = $lastName;
        $student->middle_name = $middleName;
        $student->birth_date = $birthDate;
        $student->save();

        return $student;
    }

    private function generateRandomPassword(int $length): string
    {
        // uniqid('', true) adds more entropy
        $uniqueId = uniqid(mt_rand(), true);
        // md5 adds more randomness and ensures alphanumeric output
        $hashed = md5($uniqueId);

        return substr($hashed, 0, $length);
    }


}
