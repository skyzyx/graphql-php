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

namespace Youshido\GraphQL\Introspection\Field;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Introspection\QueryType;
use Youshido\GraphQL\Introspection\Traits\TypeCollectorTrait;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TypeDefinitionField extends AbstractField
{
    use TypeCollectorTrait;

    public function resolve($value, array $args, ResolveInfo $info)
    {
        $schema = $info->getExecutionContext()->getSchema();
        $this->collectTypes($schema->getQueryType());
        $this->collectTypes($schema->getMutationType());

        foreach ($schema->getTypesList()->getTypes() as $type) {
            $this->collectTypes($type);
        }

        foreach ($this->types as $name => $info) {
            if ($name === $args['name']) {
                return $info;
            }
        }
    }

    public function build(FieldConfig $config): void
    {
        $config->addArgument(new InputField([
            'name' => 'name',
            'type' => new NonNullType(new StringType()),
        ]));
    }

    /**
     * @return string type name
     */
    public function getName()
    {
        return '__type';
    }

    /**
     * @return AbstractObjectType
     */
    public function getType()
    {
        return new QueryType();
    }
}
