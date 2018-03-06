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

use Youshido\GraphQL\Field\FieldInterface;

/**
 * Class AutoNameTrait.
 */
trait AutoNameTrait
{
    public function getName()
    {
        if (!empty($this->config)) {
            return $this->config->getName();
        }

        $className = \get_called_class();

        if ($prevPos = \strrpos($className, '\\')) {
            $className = \substr($className, $prevPos + 1);
        }

        if (\substr($className, -5) == 'Field') {
            return \lcfirst(\substr($className, 0, -5));
        }

        if (\substr($className, -4) == 'Type') {
            return \substr($className, 0, -4);
        }

        if ($this instanceof FieldInterface) {
            return \lcfirst($className);
        }

        return $className;
    }
}
