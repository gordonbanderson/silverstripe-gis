<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class TestGeometry extends DataObject implements TestOnly
{
    private static $table_name = 'TestGeometry';

    private static $db = [
        'Name' => 'Varchar',
        'GeoLocation' => 'Geometry',
    ];
}
