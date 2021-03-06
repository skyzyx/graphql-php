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

class DateTimeTzType extends AbstractScalarType
{
    private $format = 'D, d M Y H:i:s O';

    public function getName()
    {
        return 'DateTimeTz';
    }

    public function isValidValue($value)
    {
        if ((\is_object($value) && $value instanceof \DateTimeInterface) || null === $value) {
            return true;
        }

        if (\is_string($value)) {
            $date = $this->createFromFormat($value);
        } else {
            $date = null;
        }

        return $date ? true : false;
    }

    public function serialize($value)
    {
        $date = null;

        if (\is_string($value)) {
            $date = $this->createFromFormat($value);
        } elseif ($value instanceof \DateTimeInterface) {
            $date = $value;
        }

        return $date ? $date->format($this->format) : null;
    }

    public function parseValue($value)
    {
        if (\is_string($value)) {
            $date = $this->createFromFormat($value);
        } elseif ($value instanceof \DateTimeInterface) {
            $date = $value;
        } else {
            $date = false;
        }

        return $date ?: null;
    }

    public function getDescription()
    {
        return 'Representation of date and time in "r" format';
    }

    private function createFromFormat($value)
    {
        return \DateTime::createFromFormat($this->format, $value);
    }
}
