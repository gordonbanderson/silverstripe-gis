<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Core\Config\Config;
use Smindel\GIS\GIS;

class GeometryTest extends GeographyTest
{
    protected static $fixture_file = 'TestGeometry.yml';

    protected static $test_methods = [
        'Contains' => ['Contains', 'Equals'],
        'Crosses' => ['Crosses'],
        'Disjoint' => ['Disjoint', 'Distance', 'GeometryType'],
        'Equals' => ['Equals'],
        'Intersects' => ['Contains', 'Crosses', 'Equals', 'Intersects', 'Overlaps', 'Touches', 'Within'],
        'Overlaps' => ['Intersects', 'Overlaps'],
        'Touches' => ['Touches'],
        'Within' => ['Equals', 'Within'],
    ];

    protected static $test_distances = [
        9 => 2,
        10 => 8,
        11 => 10,
    ];

    public function setUp(): void
    {
        // reset GIS environment
        Config::modify()->set(GIS::class, 'default_srid', 0);
        Config::modify()->set(GIS::class, 'projections', [
            0 => null,
        ]);

        parent::setUp();
    }


    public static function getExtraDataObjects()
    {
        return [TestGeometry::class];
    }
}
