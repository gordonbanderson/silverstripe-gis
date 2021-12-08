<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class TestLocation extends DataObject implements TestOnly
{
    private static $table_name = 'TestLocation';

    private static $db = [
        'Name' => 'Varchar',
        'GeoLocation' => 'Geometry',
    ];

    private static $default_sort = 'Name';

    private static $geojsonservice = true;

    private static $webmaptileservice = true;

    public function canView($member = null)
    {
        return true;
    }
}
