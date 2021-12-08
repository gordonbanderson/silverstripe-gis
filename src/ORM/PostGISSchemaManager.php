<?php

declare(strict_types = 1);

namespace Smindel\GIS\ORM;

use SilverStripe\Control\Director;
use SilverStripe\ORM\DB;
use SilverStripe\PostgreSQL\PostgreSQLSchemaManager;

/*
http://postgis.net/docs/PostGIS_Special_Functions_Index.html#PostGIS_3D_Functions
*/

if (!\class_exists(PostgreSQLSchemaManager::class)) {
    return;
}

class PostGISSchemaManager extends PostgreSQLSchemaManager
{

    use GISSchemaManager;

    public function schemaUpdate($callback): void
    {
        // @todo: terrible hack to make the postgis extension manually installed in the "public" schema
        // abailable in the unit test db
        if (Director::is_cli() && !Director::isLive()) {
            DB::get_conn()->setSchemaSearchPath(DB::get_conn()->currentSchema(), 'public');
        }

        parent::schemaUpdate($callback);
    }
}
