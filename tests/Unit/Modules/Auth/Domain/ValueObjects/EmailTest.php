<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Domain\ValueObjects;

use App\Modules\Auth\Domain\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_email_creation_with_valid_email(): void
    {
        $email = new Email('test@example.com');
        $this->assertInstanceOf(Email::class, $email);
    }

    public function test_email_value_method_returns_normalized_email(): void
    {
        $email = new Email('Test@Example.COM');
        $this->assertEquals('test@example.com', $email->value());
    }

    public function test_email_value_is_trimmed(): void
    {
        $email = new Email('  test@example.com  ');
        $this->assertEquals('test@example.com', $email->value());
    }

    public function test_email_to_string(): void
    {
        $email = new Email('test@example.com');
        $this->assertEquals('test@example.com', (string) $email);
    }

    public function test_email_equals_with_same_value(): void
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $this->assertTrue($email1->equals($email2));
    }

    public function test_email_equals_with_different_case(): void
    {
        $email1 = new Email('Test@Example.COM');
        $email2 = new Email('test@example.com');
        $this->assertTrue($email1->equals($email2));
    }

    public function test_email_not_equals_with_different_value(): void
    {
        $email1 = new Email('test1@example.com');
        $email2 = new Email('test2@example.com');
        $this->assertFalse($email1->equals($email2));
    }

    public function test_email_throws_exception_for_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('invalid-email');
    }

    public function test_email_throws_exception_for_empty_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('');
    }

    public function test_email_throws_exception_for_email_without_domain(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('test@');
    }

    public function test_email_throws_exception_for_email_without_at_symbol(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Email('testexample.com');
    }
}
