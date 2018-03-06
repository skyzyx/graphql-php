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

namespace Youshido\GraphQL\Type\Traits;

use Youshido\GraphQL\Config\Traits\ConfigAwareTrait;

trait FieldsAwareObjectTrait
{
    use ConfigAwareTrait;

    public function addFields($fieldsList)
    {
        $this->getConfig()->addFields($fieldsList);

        return $this;
    }

    public function addField($field, $fieldInfo = null)
    {
        $this->getConfig()->addField($field, $fieldInfo);

        return $this;
    }

    public function getFields()
    {
        return $this->getConfig()->getFields();
    }

    public function getField($fieldName)
    {
        return $this->getConfig()->getField($fieldName);
    }

    public function hasField($fieldName)
    {
        return $this->getConfig()->hasField($fieldName);
    }

    public function hasFields()
    {
        return $this->getConfig()->hasFields();
    }
}
