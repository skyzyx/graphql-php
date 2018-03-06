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

namespace Youshido\Tests\Schema;

use Youshido\GraphQL\Execution\Processor;
use Youshido\GraphQL\Schema\Schema;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\Tests\DataProvider\TestEmptySchema;
use Youshido\Tests\DataProvider\TestObjectType;
use Youshido\Tests\DataProvider\TestSchema;

class SchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testStandaloneEmptySchema(): void
    {
        $schema = new TestEmptySchema();
        $this->assertFalse($schema->getQueryType()->hasFields());
    }

    public function testStandaloneSchema(): void
    {
        $schema = new TestSchema();
        $this->assertTrue($schema->getQueryType()->hasFields());
        $this->assertTrue($schema->getMutationType()->hasFields());

        $this->assertEquals(1, \count($schema->getMutationType()->getFields()));

        $schema->addMutationField('changeUser', ['type' => new TestObjectType(), 'resolve' => static function (): void {
        }]);
        $this->assertEquals(2, \count($schema->getMutationType()->getFields()));
    }

    public function testSchemaWithoutClosuresSerializable(): void
    {
        $schema = new TestEmptySchema();
        $schema->getQueryType()->addField('randomInt', [
            'type'    => new NonNullType(new IntType()),
            'resolve' => 'rand',
        ]);

        $serialized = \serialize($schema);
        /** @var Schema $unserialized */
        $unserialized = \unserialize($serialized);

        $this->assertTrue($unserialized->getQueryType()->hasFields());
        $this->assertFalse($unserialized->getMutationType()->hasFields());
        $this->assertEquals(1, \count($unserialized->getQueryType()->getFields()));
    }

    public function testCustomTypes(): void
    {
        $authorType = null;

        $userInterface = new ObjectType([
            'name'   => 'UserInterface',
            'fields' => [
                'name' => new StringType(),
            ],
            'resolveType' => static function () use ($authorType) {
                return $authorType;
            },
        ]);

        $authorType = new ObjectType([
            'name'   => 'Author',
            'fields' => [
                'name' => new StringType(),
            ],
            'interfaces' => [$userInterface],
        ]);

        $schema = new Schema([
            'query' => new ObjectType([
                'name'   => 'QueryType',
                'fields' => [
                    'user' => [
                        'type'    => $userInterface,
                        'resolve' => static function () {
                            return [
                                'name' => 'Alex',
                            ];
                        },
                    ],
                ],
            ]),
        ]);
        $schema->getTypesList()->addType($authorType);
        $processor = new Processor($schema);
        $processor->processPayload('{ user { name } }');
        $this->assertEquals(['data' => ['user' => ['name' => 'Alex']]], $processor->getResponseData());

        $processor->processPayload('{
                    __schema {
                        types {
                            name
                        }
                    }
                }');
        $data = $processor->getResponseData();
        $this->assertArraySubset([11 => ['name' => 'Author']], $data['data']['__schema']['types']);

        $processor->processPayload('{ user { name { } } }');
        $result = $processor->getResponseData();

        $this->assertEquals(['errors' => [[
            'message'   => 'Unexpected token "RBRACE"',
            'locations' => [
                [
                    'line'   => 1,
                    'column' => 19,
                ],
            ],
        ]]], $result);
        $processor->getExecutionContext()->clearErrors();

        $processor->processPayload('{ user { name { invalidSelection } } }');
        $result = $processor->getResponseData();

        $this->assertEquals(['data' => ['user' => null], 'errors' => [[
            'message'   => 'You can\'t specify fields for scalar type "String"',
            'locations' => [
                [
                    'line'   => 1,
                    'column' => 10,
                ],
            ],
        ]]], $result);
    }
}
