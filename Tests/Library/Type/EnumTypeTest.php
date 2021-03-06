<?php
/**
 * Copyright (c) 2015–2018 Alexandr Viniychuk <http://youshido.com>.
 * Copyright (c) 2015–2018 Portey Vasil <https://github.com/portey>.
 * Copyright (c) 2018 Ryan Parman <https://github.com/skyzyx>.
 * Copyright (c) 2018 Ashley Hutson <https://github.com/asheliahut>.
 * Copyright (c) 2015–2018 Contributors.
 *
 * http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace Youshido\Tests\Library\Type;

use Youshido\GraphQL\Type\Enum\EnumType;
use Youshido\GraphQL\Type\TypeMap;
use Youshido\GraphQL\Validator\ConfigValidator\ConfigValidator;
use Youshido\Tests\DataProvider\TestEnumType;

class EnumTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidInlineCreation(): void
    {
        $this->expectException(\Youshido\GraphQL\Exception\ConfigurationException::class);

        new EnumType([]);
    }

    public function testInvalidEmptyParams(): void
    {
        $this->expectException(\Youshido\GraphQL\Exception\ConfigurationException::class);

        $enumField = new EnumType([
            'values' => [],
        ]);
        ConfigValidator::getInstance()->assertValidConfig($enumField->getConfig());
    }


    public function testInvalidValueParams(): void
    {
        $this->expectException(\Youshido\GraphQL\Exception\ConfigurationException::class);

        $enumField = new EnumType([
            'values' => [
                'test'  => 'asd',
                'value' => 'asdasd',
            ],
        ]);
        ConfigValidator::getInstance()->assertValidConfig($enumField->getConfig());
    }


    public function testExistingNameParams(): void
    {
        $this->expectException(\Youshido\GraphQL\Exception\ConfigurationException::class);

        $enumField = new EnumType([
            'values' => [
                [
                    'test'  => 'asd',
                    'value' => 'asdasd',
                ],
            ],
        ]);
        ConfigValidator::getInstance()->assertValidConfig($enumField->getConfig());
    }


    public function testInvalidNameParams(): void
    {
        $this->expectException(\Youshido\GraphQL\Exception\ConfigurationException::class);

        $enumField = new EnumType([
            'values' => [
                [
                    'name'  => false,
                    'value' => 'asdasd',
                ],
            ],
        ]);
        ConfigValidator::getInstance()->assertValidConfig($enumField->getConfig());
    }


    public function testWithoutValueParams(): void
    {
        $this->expectException(\Youshido\GraphQL\Exception\ConfigurationException::class);

        $enumField = new EnumType([
            'values' => [
                [
                    'name' => 'TEST_ENUM',
                ],
            ],
        ]);
        ConfigValidator::getInstance()->assertValidConfig($enumField->getConfig());
    }

    public function testNormalCreatingParams(): void
    {
        $valuesData = [
            [
                'name'  => 'ENABLE',
                'value' => true,
            ],
            [
                'name'  => 'DISABLE',
                'value' => 'disable',
            ],
        ];
        $enumType = new EnumType([
            'name'   => 'BoolEnum',
            'values' => $valuesData,
        ]);

        $this->assertEquals($enumType->getKind(), TypeMap::KIND_ENUM);
        $this->assertEquals($enumType->getName(), 'BoolEnum');
        $this->assertEquals($enumType->getType(), $enumType);
        $this->assertEquals($enumType->getNamedType(), $enumType);

        $this->assertFalse($enumType->isValidValue($enumType));
        $this->assertTrue($enumType->isValidValue(null));

        $this->assertTrue($enumType->isValidValue(true));
        $this->assertTrue($enumType->isValidValue('disable'));

        $this->assertNull($enumType->serialize('invalid value'));
        $this->assertNull($enumType->parseValue('invalid literal'));
        $this->assertTrue($enumType->parseValue('ENABLE'));

        $this->assertEquals($valuesData, $enumType->getValues());
    }

    public function testExtendedObject(): void
    {
        $testEnumType = new TestEnumType();
        $this->assertEquals('TestEnum', $testEnumType->getName());
    }
}
