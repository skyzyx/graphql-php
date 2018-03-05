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

namespace Youshido\Tests\Library\Config;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Type\Scalar\StringType;

class FieldConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidParams(): void
    {
        $fieldConfig = new FieldConfig([
            'name'    => 'FirstName',
            'type'    => new StringType(),
            'resolve' => static function ($value, $args = [], $type = null) {
                return 'John';
            },
        ]);

        $this->assertEquals('FirstName', $fieldConfig->getName());
        $this->assertEquals(new StringType(), $fieldConfig->getType());

        $resolveFunction = $fieldConfig->getResolveFunction();
        $this->assertEquals('John', $resolveFunction([]));
    }
}
