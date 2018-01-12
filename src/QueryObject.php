<?php

namespace Kfirba;

class QueryObject
{
    /**
     * The generated query.
     *
     * @var string
     */
    protected $query;

    /**
     * The query's bindings.
     *
     * @var array
     */
    protected $bindings;

    public function __construct($query, $bindings)
    {
        $this->query = $query;
        $this->bindings = $bindings;
    }

    /**
     * Get the generated query.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the query's bindings.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->bindings;
    }
}