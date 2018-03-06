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

namespace Youshido\GraphQL\Type\Scalar;

class FloatType extends AbstractScalarType
{
    public function getName()
    {
        return 'Float';
    }

    public function serialize($value)
    {
        if (null === $value) {
            return;
        }

        return (float) $value;
    }

    public function isValidValue($value)
    {
        return null === $value || \is_float($value) || \is_int($value);
    }

    public function getDescription()
    {
        return 'The `Float` scalar type represents signed double-precision fractional ' .
               'values as specified by ' .
               '[IEEE 754](http://en.wikipedia.org/wiki/IEEE_floating_point).';
    }
}
