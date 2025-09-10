<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\App\Semantic\Email;
use Be\App\Semantic\Password;
use Be\Framework\Attribute\Be;
use Ray\InputQuery\Attribute\Input;

#[Be([RegisteredUser::class])]
final readonly class ValidatedUser
{
    public function __construct(
        #[Input] public string $email,
        #[Input] public string $password,
        #[Input] public string $firstName,
        #[Input] public string $lastName,
        #[Input] public string $role
    ) {}
}