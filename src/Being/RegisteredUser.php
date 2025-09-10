<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Semantic\Email;
use Be\App\Semantic\Password;
use Ray\InputQuery\Attribute\Input;

final readonly class RegisteredUser
{
    public string $userId;
    public string $passwordHash;
    public string $registeredAt;
    public string $status;

    public function __construct(
        #[Input] public string $email,
        #[Input] public string $password,
        #[Input] public string $firstName,
        #[Input] public string $lastName,
        #[Input] public string $role

    ) {
        $this->userId = uniqid('user_');
        $this->passwordHash = password_hash($this->password, PASSWORD_ARGON2ID);
        $this->registeredAt = date('Y-m-d H:i:s');
        $this->status = 'active';
    }
}
