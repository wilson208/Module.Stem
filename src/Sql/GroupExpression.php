<?php


namespace Rhubarb\Stem\Sql;


class GroupExpression extends SqlClause
{
    private $expression;

    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    public function getSql()
    {
        return $this->expression;
    }
}