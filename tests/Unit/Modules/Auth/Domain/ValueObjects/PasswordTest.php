<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Domain\ValueObjects;

use App\Modules\Auth\Domain\ValueObjects\Password;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PasswordTest extends TestCase
{
    public function test_password_creation_with_valid_password(): void
    {
        $password = new Password('ValidPassword123');
        $this->assertInstanceOf(Password::class, $password);
    }

    public function test_password_value_method_returns_plain_text(): void
    {
        $plainText = 'ValidPassword123';
        $password = new Password($plainText);
        $this->assertEquals($plainText, $password->value());
    }

    public function test_password_to_string(): void
    {
        $plainText = 'ValidPassword123';
        $password = new Password($plainText);
        $this->assertEquals($plainText, (string) $password);
    }

    public function test_password_equals_with_same_value(): void
    {
        $password1 = new Password('ValidPassword123');
        $password2 = new Password('ValidPassword123');
        $this->assertTrue($password1->equals($password2));
    }

    public function test_password_not_equals_with_different_value(): void
    {
        $password1 = new Password('ValidPassword123');
        $password2 = new Password('DifferentPassword456');
        $this->assertFalse($password1->equals($password2));
    }

    public function test_password_throws_exception_for_password_less_than_6_characters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Password('Short');
    }

    public function test_password_throws_exception_for_empty_password(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Password('');
    }

    public function test_password_accepts_6_character_password(): void
    {
        $password = new Password('Passw1');
        $this->assertEquals('Passw1', $password->value());
    }

    public function test_password_is_plain_text_not_hashed(): void
    {
        $plainPassword = 'MySecurePassword';
        $password = new Password($plainPassword);
        // Value Object stores plain text, hashing is Action's responsibility
        $this->assertEquals($plainPassword, $password->value());
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
        $this->assertNotEquals($hashedPassword, $password->value());
    }
}
