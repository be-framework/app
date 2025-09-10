<?php

declare(strict_types=1);

namespace Be\App\Being;

use Be\Framework\Attribute\Be;
use Ray\InputQuery\Attribute\Input;

#[Be([ValidatedUser::class])]
final readonly class UnvalidatedUser
{
    public function __construct(
        #[Input] public string $email,
        #[Input] public string $password,
        #[Input] public string $firstName,
        #[Input] public string $lastName,
        #[Input] public string $role
    ) {}
}