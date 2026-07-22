<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Infrastructure\Casts;

use App\Modules\Auth\Domain\ValueObjects\Email;
use App\Modules\Auth\Infrastructure\Casts\EmailCast;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class EmailCastTest extends TestCase
{
    private EmailCast $cast;
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cast = new EmailCast();
        $this->model = new class extends Model {};
    }

    public function test_get_returns_email_instance_from_string(): void
    {
        $email = $this->cast->get($this->model, 'email', 'USER@EXAMPLE.COM', []);

        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('user@example.com', $email->value());
    }

    public function test_get_returns_null_when_value_is_null_or_empty(): void
    {
        $this->assertNull($this->cast->get($this->model, 'email', null, []));
        $this->assertNull($this->cast->get($this->model, 'email', '', []));
    }

    public function test_set_prepares_string_or_email_for_storage(): void
    {
        $emailObj = new Email('test@example.com');
        $storedValueFromObj = $this->cast->set($this->model, 'email', $emailObj, []);
        $this->assertEquals('test@example.com', $storedValueFromObj);

        $storedValueFromString = $this->cast->set($this->model, 'email', '  TEST@EXAMPLE.COM  ', []);
        $this->assertEquals('test@example.com', $storedValueFromString);
    }

    public function test_set_returns_null_when_value_is_null_or_empty(): void
    {
        $this->assertNull($this->cast->set($this->model, 'email', null, []));
        $this->assertNull($this->cast->set($this->model, 'email', '', []));
    }
}
