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

namespace Youshido\GraphQL\Introspection\Traits;

use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\TypeMap;
use Youshido\GraphQL\Type\Union\AbstractUnionType;

trait TypeCollectorTrait
{
    protected $types = [];

    protected function collectTypes(AbstractType $type): void
    {
        if (\is_object($type) && \array_key_exists($type->getName(), $this->types)) {
            return;
        }

        switch ($type->getKind()) {
            case TypeMap::KIND_INTERFACE:
            case TypeMap::KIND_UNION:
            case TypeMap::KIND_ENUM:
            case TypeMap::KIND_SCALAR:
                $this->insertType($type->getName(), $type);

                if (TypeMap::KIND_UNION === $type->getKind()) {
                    /** @var AbstractUnionType $type */
                    foreach ($type->getTypes() as $subType) {
                        $this->collectTypes($subType);
                    }
                }

                break;

            case TypeMap::KIND_INPUT_OBJECT:
            case TypeMap::KIND_OBJECT:
                /** @var AbstractObjectType $namedType */
                $namedType = $type->getNamedType();
                $this->checkAndInsertInterfaces($namedType);

                if ($this->insertType($namedType->getName(), $namedType)) {
                    $this->collectFieldsArgsTypes($namedType);
                }

                break;

            case TypeMap::KIND_LIST:
                $this->collectTypes($type->getNamedType());

                break;

            case TypeMap::KIND_NON_NULL:
                $this->collectTypes($type->getNamedType());

                break;
        }
    }

    private function checkAndInsertInterfaces($type): void
    {
        foreach ((array) $type->getConfig()->getInterfaces() as $interface) {
            /* @var AbstractInterfaceType $interface */
            $this->insertType($interface->getName(), $interface);
        }
    }

    /**
     * @param $type AbstractObjectType
     */
    private function collectFieldsArgsTypes($type): void
    {
        foreach ($type->getConfig()->getFields() as $field) {
            $arguments = $field->getConfig()->getArguments();

            if (\is_array($arguments)) {
                foreach ($arguments as $argument) {
                    $this->collectTypes($argument->getType());
                }
            }

            $this->collectTypes($field->getType());
        }
    }

    private function insertType($name, $type)
    {
        if (!\array_key_exists($name, $this->types)) {
            $this->types[$name] = $type;

            return true;
        }

        return false;
    }
}
