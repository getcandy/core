<?php

namespace GetCandy\Tests\Unit\FieldTypes;

use GetCandy\Exceptions\FieldTypeException;
use GetCandy\FieldTypes\Number;
use GetCandy\Tests\TestCase;

class NumberTest extends TestCase
{
    /** @test */
    public function can_set_value()
    {
        $field = new Number();
        $field->setValue(12345);

        $this->assertEquals(12345, $field->getValue());
    }

    /** @test */
    public function can_set_value_in_constructor()
    {
        $field = new Number(12345);

        $this->assertEquals(12345, $field->getValue());
    }

    /** @test */
    public function check_does_not_allow_non_numerics()
    {
        $this->expectException(FieldTypeException::class);

        $field = new Number('bad string value');
    }
}
