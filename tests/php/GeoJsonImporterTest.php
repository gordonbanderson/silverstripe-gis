<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use Smindel\GIS\GIS;
use Smindel\GIS\Service\GeoJsonImporter;

class GeoJsonImporterTest extends SapphireTest
{
    public function setUp(): void
    {
        // reset GIS environment
        Config::modify()->set(GIS::class, 'default_srid', 4326);

        parent::setUp();
    }


    public function testImport(): void
    {
        $json = \json_encode([
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [91,54],
                    ],
                    'properties' => [
                        'Name' => 'Abakan',
                    ],
                ],
            ],
        ]);

        GeoJsonImporter::import(TestLocation::class, $json);

        $this->assertEquals(['Abakan' => 'SRID=4326;POINT(91 54)'], TestLocation::get()->map('Name', 'GeoLocation')->toArray());
    }


    public static function getExtraDataObjects()
    {
        return [TestLocation::class];
    }
}
