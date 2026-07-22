<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Auth\Infrastructure\Casts;

use App\Modules\Auth\Domain\ValueObjects\Cnpj;
use App\Modules\Auth\Infrastructure\Casts\CnpjCast;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class CnpjCastTest extends TestCase
{
    private CnpjCast $cast;
    private Model $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cast = new CnpjCast();
        $this->model = new class extends Model {};
    }

    public function test_get_returns_cnpj_instance_from_string(): void
    {
        $cnpj = $this->cast->get($this->model, 'cnpj', '00000000000191', []);

        $this->assertInstanceOf(Cnpj::class, $cnpj);
        $this->assertEquals('00000000000191', $cnpj->value());
        $this->assertEquals('00.000.000/0001-91', $cnpj->formatted());
    }

    public function test_get_returns_null_when_value_is_null_or_empty(): void
    {
        $this->assertNull($this->cast->get($this->model, 'cnpj', null, []));
        $this->assertNull($this->cast->get($this->model, 'cnpj', '', []));
    }

    public function test_set_prepares_string_or_cnpj_for_storage(): void
    {
        $cnpjObj = new Cnpj('00000000000191');
        $storedValueFromObj = $this->cast->set($this->model, 'cnpj', $cnpjObj, []);
        $this->assertEquals('00000000000191', $storedValueFromObj);

        $storedValueFromString = $this->cast->set($this->model, 'cnpj', '00.000.000/0001-91', []);
        $this->assertEquals('00000000000191', $storedValueFromString);
    }

    public function test_set_returns_null_when_value_is_null_or_empty(): void
    {
        $this->assertNull($this->cast->set($this->model, 'cnpj', null, []));
        $this->assertNull($this->cast->set($this->model, 'cnpj', '', []));
    }
}
