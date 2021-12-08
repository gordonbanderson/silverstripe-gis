<?php

declare(strict_types = 1);

namespace Smindel\GIS\ORM\Filters;

use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Filters\SearchFilter;

class StGeometryTypeFilter extends SearchFilter
{
    /**
     * Applies an exact match (equals) on a field value.
     */
    protected function applyOne(DataQuery $query): DataQuery
    {
        return $this->oneFilter($query, true);
    }


    /**
     * Excludes an exact match (equals) on a field value.
     */
    protected function excludeOne(DataQuery $query): DataQuery
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

        // Null comparison check
        if ($value === null) {
            $where = DB::get_conn()->nullCheckClause($field, $inclusive);

            return $query->where($where);
        }

        // Value comparison check
        $where = DB::get_schema()->translateStGeometryTypeFilter($field, $value, $inclusive);

        return $this->aggregate ?
            $this->applyAggregate($query, $where) : $query->where($where);
    }
}
