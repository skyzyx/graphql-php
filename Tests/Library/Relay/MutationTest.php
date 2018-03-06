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


namespace Youshido\Tests\Library\Relay;

use Youshido\GraphQL\Relay\RelayMutation;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class MutationTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation(): void
    {
        $mutation = RelayMutation::buildMutation('ship', [
            'name' => new StringType(),
        ], [
            'id'   => new IdType(),
            'name' => new StringType(),
        ], static function ($source, $args, $info): void {
        });
        $this->assertEquals('ship', $mutation->getName());
    }


    public function testInvalidType(): void
    {
        $this->expectException(\Exception::class);

        RelayMutation::buildMutation('ship', [
            'name' => new StringType(),
        ], new IntType(), static function ($source, $args, $info): void {
        });
    }
}
