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

namespace Youshido\GraphQL\Type\InputObject;

use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;

final class InputObjectType extends AbstractInputObjectType
{
    public function __construct($config)
    {
        $this->config = new InputObjectTypeConfig($config, $this, true);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function build($config): void
    {
    }
}
