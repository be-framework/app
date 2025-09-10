<?php

declare(strict_types=1);

namespace Be\App\Input;

use Be\App\Being\UnvalidatedUser;
use Be\Framework\Attribute\Be;

#[Be([UnvalidatedUser::class])]
final readonly class UserRegistrationInput
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
        public string $role = 'contributor'
    ) {}
}