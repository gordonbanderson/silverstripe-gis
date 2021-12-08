<?php

declare(strict_types = 1);

namespace Smindel\GIS\ORM\Filters;

use ReflectionClass;
use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Filters\SearchFilter;

class StContainsFilter extends SearchFilter
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
        $shortName = (new ReflectionClass($this))->getShortName();
        $translationMethod = 'translate' . $shortName;
        $stMethodHint = \preg_match('/^St([a-zA-Z]+)Filter$/', $shortName, $matches)
            ? $matches[1]
            : false;
        $where = DB::get_schema()->$translationMethod($field, $value, $inclusive, $stMethodHint);

        return $this->aggregate ?
            $this->applyAggregate($query, $where) : $query->where($where);
    }
}
