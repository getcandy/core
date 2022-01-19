<?php

namespace GetCandy\Tests\Unit\Base;

use GetCandy\Base\AttributeManifest;
use GetCandy\Base\AttributeManifestInterface;
use GetCandy\Models\Channel;
use GetCandy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

/**
 * @group core.attribute-manifest
 */
class AttributeManifestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_instantiate_class()
    {
        $manifest = app(AttributeManifestInterface::class);

        $this->assertInstanceOf(AttributeManifest::class, $manifest);
    }

    /** @test */
    public function can_return_types()
    {
        $manifest = app(AttributeManifestInterface::class);

        $this->assertInstanceOf(Collection::class, $manifest->getTypes());
    }

    /** @test */
    public function has_base_types_set()
    {
        $manifest = app(AttributeManifestInterface::class);

        $this->assertInstanceOf(Collection::class, $manifest->getTypes());

        $this->assertNotEmpty($manifest->getTypes());
    }

    /** @test */
    public function can_add_type()
    {
        $manifest = app(AttributeManifestInterface::class);

        $manifest->addType(Channel::class);

        $this->assertNotNull($manifest->getType('channel'));
    }
}
