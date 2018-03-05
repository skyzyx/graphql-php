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

namespace Youshido\Tests\DataProvider;

use Youshido\GraphQL\Config\Schema\SchemaConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\IntType;

class TestSchema extends AbstractSchema
{
    private $testStatusValue = 0;

    public function build(SchemaConfig $config): void
    {
        $config->getQuery()->addFields([
            'me' => [
                'type'    => new TestObjectType(),
                'resolve' => static function ($value, $args, ResolveInfo $info) {
                    return $info->getReturnType()->getData();
                },
            ],
            'status' => [
                'type'    => new TestEnumType(),
                'resolve' => function () {
                    return $this->testStatusValue;
                },
            ],
        ]);
        $config->getMutation()->addFields([
            'updateStatus' => [
                'type'    => new TestEnumType(),
                'resolve' => function () {
                    return $this->testStatusValue;
                },
                'args' => [
                    'newStatus' => new TestEnumType(),
                    'list'      => new ListType(new IntType()),
                ],
            ],
        ]);
    }
}
