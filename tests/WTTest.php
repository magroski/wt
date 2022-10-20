<?php

declare(strict_types=1);

namespace WT\Tests;

use PHPUnit\Framework\TestCase;
use WT\WT;

class WTTest extends TestCase
{
    public function testEncodeDecodeWithDefaultKey() : void
    {
        $wt = new WT('AES-256-CBC', 'abcdefgh', 'potato');

        $object       = new \stdClass();
        $object->name = 'french fries';

        $token = $wt->encode($object);

        $this->assertIsString($token);

        $decoded = $wt->decode($token);

        $this->assertIsObject($decoded);
        $this->assertObjectHasAttribute('name', $decoded);
        $this->assertEquals('french fries', $decoded->name);
    }

    public function testEncodeDecodeWithoutDefaultKey() : void
    {
        $wt = new WT('AES-256-CBC', 'xkfo39sp');

        $object       = new \stdClass();
        $object->name = 'french fries';

        $token = $wt->encode($object, 'potato');

        $this->assertIsString($token);

        $decoded = $wt->decode($token, 'potato');

        $this->assertIsObject($decoded);
        $this->assertObjectHasAttribute('name', $decoded);
        $this->assertEquals('french fries', $decoded->name);
    }

    public function testShouldNotDecodeWithDifferentKey() : void
    {
        $wt = new WT('AES-256-CBC', '7dh38sik');

        $object       = new \stdClass();
        $object->name = 'french fries';

        $token = $wt->encode($object, 'potato');

        $this->assertIsString($token);

        $content = $wt->decode($token, 'batata');
        $this->assertNull($content);
    }

    public function testShouldNotEncodeWithMissingKey() : void
    {
        $wt = new WT('AES-256-CBC', 'vbghyejd');

        $object       = new \stdClass();
        $object->name = 'french fries';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Encryption key must be initialized or passed as argument');
        $token = $wt->encode($object);
    }

    public function testShouldNotDecodeWithMissingKey() : void
    {
        $wt = new WT('AES-256-CBC', 'lokijuhy');

        $object       = new \stdClass();
        $object->name = 'french fries';

        $token = $wt->encode($object, 'potato');

        $this->assertIsString($token);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Encryption key must be initialized or passed as argument');
        $wt->decode($token);
    }

    public function testShouldNotEncodeResource() : void
    {
        $wt = new WT('AES-256-CBC', 'hfr2c98s', 'pizza');

        $resource = opendir(__DIR__);

        $this->expectException(\RuntimeException::class);
        $wt->encode($resource);
    }
}
