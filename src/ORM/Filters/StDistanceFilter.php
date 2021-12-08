<?php

declare(strict_types = 1);

namespace Smindel\GIS\ORM\Filters;

use InvalidArgumentException;
use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Filters\SearchFilter;

class StDistanceFilter extends SearchFilter
{
    /**
     * Applies an exact match (equals) on a field value.
     */
    protected function applyOne(DataQuery $query): DataQuery
    {
        throw new InvalidArgumentException(static::class . " is used by supplying an array containing a geometry and a distance.");
    }


    protected function applyMany(DataQuery $query)
    {
        return $this->oneFilter($query, true);
    }


    /**
     * Excludes an exact match (equals) on a field value.
     */
    protected function excludeOne(DataQuery $query): DataQuery
    {
        throw new InvalidArgumentException(static::class . " is used by supplying an array containing a geometry and a distance.");
    }


    protected function excludeMany(DataQuery $query)
    {
        return $this->oneFilter($query, false);
    }


    /**
     * Applies a single match, either as inclusive or exclusive
     *
     * @param bool $inclusive True if this is inclusive, or false if exclusive
     */
    protected function oneFilter(DataQuery $query, bool $inclusive): DataQuery
    {
        $this->model = $query->applyRelation($this->relation);
        $field = $this->getDbName();
        $value = $this->getValue();

        // Value comparison check
        $where = DB::get_schema()->translateStDistanceFilter($field, $value, $inclusive);

        return $this->aggregate ?
            $this->applyAggregate($query, $where) : $query->where($where);
    }
}
