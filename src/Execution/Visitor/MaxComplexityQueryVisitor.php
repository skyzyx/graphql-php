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

namespace Youshido\GraphQL\Execution\Visitor;

use Youshido\GraphQL\Config\Field\FieldConfig;

/**
 * Concrete implementation of query visitor.
 *
 * Enforces maximum complexity on a query, computed from "cost" functions on
 * the fields touched by that query.
 */
class MaxComplexityQueryVisitor extends AbstractQueryVisitor
{
    /**
     * @var int max score allowed before throwing an exception (causing processing to stop)
     */
    public $maxScore;

    /**
     * @var int default score for nodes without explicit cost functions
     */
    protected $defaultScore = 1;

    /**
     * MaxComplexityQueryVisitor constructor.
     *
     * @param int $max max allowed complexity score
     */
    public function __construct($max)
    {
        parent::__construct();

        $this->maxScore = $max;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(array $args, FieldConfig $fieldConfig, $childScore = 0)
    {
        $cost = $fieldConfig->get('cost', null);

        if (\is_callable($cost)) {
            $cost = $cost($args, $fieldConfig, $childScore);
        }

        $cost = null === $cost ? $this->defaultScore : $cost;
        $this->memo += $cost;

        if ($this->memo > $this->maxScore) {
            throw new \Exception('query exceeded max allowed complexity of ' . $this->maxScore);
        }

        return $cost;
    }
}
