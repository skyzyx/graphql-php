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

namespace Youshido\GraphQL\Field;

use Youshido\GraphQL\Type\Object\AbstractObjectType;

final class InputField extends AbstractInputField
{
    protected $isFinal = false;

    /**
     * @return AbstractObjectType
     */
    public function getType()
    {
        return $this->getConfigValue('type');
    }
}
