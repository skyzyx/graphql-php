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

namespace Youshido\GraphQL\Relay\Connection;

use Youshido\GraphQL\Relay\Type\PageInfoType;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\TypeMap;

class Connection
{
    public static function connectionArgs()
    {
        return \array_merge(self::forwardArgs(), self::backwardArgs());
    }

    public static function forwardArgs()
    {
        return [
            'after' => ['type' => TypeMap::TYPE_STRING],
            'first' => ['type' => TypeMap::TYPE_INT],
        ];
    }

    public static function backwardArgs()
    {
        return [
            'before' => ['type' => TypeMap::TYPE_STRING],
            'last'   => ['type' => TypeMap::TYPE_INT],
        ];
    }

    /**
     * @param AbstractType $type
     * @param string|null  $name
     * @param array        $config
     * @option string  edgeFields
     *
     * @return ObjectType
     */
    public static function edgeDefinition(AbstractType $type, $name = null, $config = [])
    {
        $name       = $name ?: $type->getName();
        $edgeFields = !empty($config['edgeFields']) ? $config['edgeFields'] : [];

        $edgeType = new ObjectType([
            'name'        => $name . 'Edge',
            'description' => 'An edge in a connection.',
            'fields'      => \array_merge([
                'node' => [
                    'type'        => $type,
                    'description' => 'The item at the end of the edge',
                    'resolve'     => [__CLASS__, 'getNode'],
                ],
                'cursor' => [
                    'type'        => TypeMap::TYPE_STRING,
                    'description' => 'A cursor for use in pagination',
                ],
            ], $edgeFields),
        ]);

        return $edgeType;
    }

    /**
     * @param AbstractType $type
     * @param string|null  $name
     * @param array        $config
     * @option string  connectionFields
     *
     * @return ObjectType
     */
    public static function connectionDefinition(AbstractType $type, $name = null, $config = [])
    {
        $name             = $name ?: $type->getName();
        $connectionFields = !empty($config['connectionFields']) ? $config['connectionFields'] : [];

        $connectionType = new ObjectType([
            'name'        => $name . 'Connection',
            'description' => 'A connection to a list of items.',
            'fields'      => \array_merge([
                'pageInfo' => [
                    'type'        => new NonNullType(new PageInfoType()),
                    'description' => 'Information to aid in pagination.',
                    'resolve'     => [__CLASS__, 'getPageInfo'],
                ],
                'edges' => [
                    'type'        => new ListType(self::edgeDefinition($type, $name, $config)),
                    'description' => 'A list of edges.',
                    'resolve'     => [__CLASS__, 'getEdges'],
                ],
            ], $connectionFields),
        ]);

        return $connectionType;
    }

    public static function getEdges($value)
    {
        return $value['edges'] ?? null;
    }

    public static function getPageInfo($value)
    {
        return $value['pageInfo'] ?? null;
    }

    public static function getNode($value)
    {
        return $value['node'] ?? null;
    }
}
