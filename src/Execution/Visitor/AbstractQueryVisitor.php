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
 * Abstract query visitor.
 *
 * The contract between this and the processor is that it will yield tuples to `visit` in DFS manner.  i.e. a query of:
 *
 * {
 *   A {
 *     B
 *     C {
 *       D
 *     }
 *     E
 *   }
 * }
 *
 * ... will visit nodes in order B, D, C, E, A.
 *
 * Implementations are able to "reduce" the query by mutating $this->memo.  A reasonable thing to do is raise an
 * exception if some limit is reached.  (see MaxComplexityQueryVisitor for example concrete implementation)
 */
abstract class AbstractQueryVisitor
{
    /**
     * @var int initial value of->memo
     */
    protected $initialValue = 0;

    /**
     * @var mixed the accumulator
     */
    protected $memo;

    /**
     * AbstractQueryVisitor constructor.
     */
    public function __construct()
    {
        $this->memo = $this->initialValue;
    }

    /**
     * @return mixed getter for the memo, in case callers want to inspect it after a process run
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Visit a query node.  See class docstring.
     *
     * @param array       $args
     * @param FieldConfig $fieldConfig
     * @param int         $childScore
     *
     * @return int|null
     */
    abstract public function visit(array $args, FieldConfig $fieldConfig, $childScore = 0);
}
