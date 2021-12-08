<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class TestGeography extends DataObject implements TestOnly
{
    private static $table_name = 'TestGeography';

    private static $db = [
        'Name' => 'Varchar',
        'GeoLocation' => 'Geography',
    ];
}
