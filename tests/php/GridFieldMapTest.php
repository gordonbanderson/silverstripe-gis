<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\GridField\GridField;
use Smindel\GIS\Forms\GridFieldMap;
use Smindel\GIS\Forms\MapField;
use Smindel\GIS\GIS;

class GridFieldMapTest extends SapphireTest
{
    protected static $fixture_file = 'TestLocation.yml';

    public function setUp(): void
    {
        // reset GIS environment
        Config::modify()->set(GIS::class, 'default_srid', 4326);
        Config::modify()->set(MapField::class, 'default_location', ['lon' => 174, 'lat' => -41]);

        parent::setUp();
    }


    public function testGridFieldMap(): void
    {
        $gridField = Gridfield::create('Locations', null, TestLocation::get());
        $map = GridFieldMap::create();

        $html = $map->getHTMLFragments($gridField)['before'];

        $this->assertRegExp('/\Wclass="grid-field-map"\W/', $html);
        $this->assertRegExp('/\Wdata-map-center="SRID=4326;POINT\(174 -41\)"\W/', $html);
        $this->assertRegExp('/\Wdata-list="\{&quot;/', $html);
    }


    public static function getExtraDataObjects()
    {
        return [TestLocation::class];
    }
}
