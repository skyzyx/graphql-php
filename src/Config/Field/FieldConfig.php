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

namespace Youshido\GraphQL\Config\Field;

use Youshido\GraphQL\Config\AbstractConfig;
use Youshido\GraphQL\Config\Traits\ArgumentsAwareConfigTrait;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\TypeInterface;
use Youshido\GraphQL\Type\TypeService;

/**
 * Class FieldConfig.
 *
 * @method $this setDescription(string $description)
 * @method $this setCost(int $cost)
 */
class FieldConfig extends AbstractConfig
{
    use ArgumentsAwareConfigTrait;

    public function getRules()
    {
        return [
            'name'              => ['type' => TypeService::TYPE_STRING, 'final' => true],
            'type'              => ['type' => TypeService::TYPE_GRAPHQL_TYPE, 'final' => true],
            'args'              => ['type' => TypeService::TYPE_ARRAY],
            'description'       => ['type' => TypeService::TYPE_STRING],
            'resolve'           => ['type' => TypeService::TYPE_CALLABLE],
            'isDeprecated'      => ['type' => TypeService::TYPE_BOOLEAN],
            'deprecationReason' => ['type' => TypeService::TYPE_STRING],
            'cost'              => ['type' => TypeService::TYPE_ANY],
        ];
    }

    /**
     * @return AbstractObjectType|TypeInterface
     */
    public function getType()
    {
        return $this->data['type'];
    }

    protected function build(): void
    {
        $this->buildArguments();
    }
}
