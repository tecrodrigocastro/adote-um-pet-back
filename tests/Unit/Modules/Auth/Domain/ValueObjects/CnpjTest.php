<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Domain\ValueObjects;

use App\Modules\Auth\Domain\ValueObjects\Cnpj;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CnpjTest extends TestCase
{
    public function test_cnpj_creation_with_valid_cnpj_formatted(): void
    {
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertInstanceOf(Cnpj::class, $cnpj);
    }

    public function test_cnpj_creation_with_valid_cnpj_unformatted(): void
    {
        $cnpj = new Cnpj('11222333000181');
        $this->assertInstanceOf(Cnpj::class, $cnpj);
    }

    public function test_cnpj_value_method_returns_normalized_cnpj(): void
    {
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertEquals('11222333000181', $cnpj->value());
    }

    public function test_cnpj_formatted_method_returns_formatted_cnpj(): void
    {
        $cnpj = new Cnpj('11222333000181');
        $this->assertEquals('11.222.333/0001-81', $cnpj->formatted());
    }

    public function test_cnpj_to_string(): void
    {
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertEquals('11222333000181', (string) $cnpj);
    }

    public function test_cnpj_equals_with_same_value(): void
    {
        $cnpj1 = new Cnpj('11.222.333/0001-81');
        $cnpj2 = new Cnpj('11222333000181');
        $this->assertTrue($cnpj1->equals($cnpj2));
    }

    public function test_cnpj_not_equals_with_different_value(): void
    {
        $cnpj1 = new Cnpj('11.222.333/0001-81');
        $cnpj2 = new Cnpj('33.445.566/0001-86');
        $this->assertFalse($cnpj1->equals($cnpj2));
    }

    public function test_cnpj_throws_exception_for_invalid_cnpj(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cnpj('11.222.333/0001-00');
    }

    public function test_cnpj_throws_exception_for_empty_cnpj(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cnpj('');
    }

    public function test_cnpj_throws_exception_for_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cnpj('invalid-cnpj');
    }

    public function test_cnpj_throws_exception_for_all_same_digits(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Cnpj('11111111111111');
    }

    public function test_cnpj_validation_with_real_cnpj(): void
    {
        // CNPJ: 11.222.333/0001-81 is valid
        $cnpj = new Cnpj('11.222.333/0001-81');
        $this->assertEquals('11222333000181', $cnpj->value());
    }
}
