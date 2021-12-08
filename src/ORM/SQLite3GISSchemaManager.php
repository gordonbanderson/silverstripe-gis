<?php

declare(strict_types = 1);

namespace Smindel\GIS\ORM;

use SilverStripe\ORM\DB;
use SilverStripe\SQLite\SQLite3SchemaManager;

if (!\class_exists(SQLite3SchemaManager::class)) {
    return;
}

class SQLite3GISSchemaManager extends SQLite3SchemaManager
{

    use GISSchemaManager;

    protected static $is_initialised = false;

    public function initialise(): void
    {
        if (self::$is_initialised) {
            return;
        }

        $connector = DB::get_connector()->getRawConnector();
        $connector->loadExtension('mod_spatialite.so');
        $connector->exec("SELECT InitSpatialMetadata()");
        self::$is_initialised = true;
    }


    public function geography($values)
    {
        // ATTENTION: GEOGRAPHY IS NOT SUPPORTED BY MYSQL. THIS IS STRICTLY FOR COMPATIBILITY
        return 'geometry';
    }


    public function translateStGeometryTypeFilter($field, $value, $inclusive)
    {
        $null = $inclusive
            ? ''
            : ' OR ' . DB::get_conn()->nullCheckClause($field, true);
        $fragment = \sprintf(
            '%sLOWER(ST_GeometryType(%s)) = ?%s',
            $inclusive ? '' : 'NOT ',
            $field,
            $null
        );

        return [$fragment => \strtolower($value)];
    }


    public function translateBasicSelectGeo()
    {
        DB::get_schema()->initialise();

        return 'CASE WHEN %s IS NULL THEN NULL ELSE \'SRID=\' || ST_SRID(%s) || \';\' || ST_AsText(%s) END AS "%s"';
    }
}
