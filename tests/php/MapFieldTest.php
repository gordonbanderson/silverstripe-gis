<?php

namespace Smindel\GIS\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Config\Config;
use Smindel\GIS\GIS;
use Smindel\GIS\Forms\MapField;

class MapFieldTest extends SapphireTest
{
    public function setUp()
    {
        // reset GIS environment
        Config::modify()->set(GIS::class, 'default_srid', 3857);
        Config::modify()->set(MapField::class, 'default_location', ['lon' => 174, 'lat' => -41]);
        parent::setUp();
    }

    public function testMapField()
    {
        $field = MapField::create('Location', null, GIS::create(['srid' => '2193', 'type' => 'Point', 'coordinates' => [5436343, 1760120]]))
            ->setControl('polyline', false)
            ->setControl('polygon', false)
            ->enableMulti();

        $html = (string)$field->Field();

        error_log('RESULT T6: ' . $html);
        $this->assertRegExp('/\Wclass="map-field-widget"\W/', $html);
        $this->assertRegExp('/\Wdata-field="Location"\W/', $html);
        $this->assertRegExp('/\Wdata-default-srid="3857"\W/', $html);
        $this->assertRegExp('/\Wdata-multi-enabled="1"\W/', $html);
        $this->assertRegExp('/\Wvalue="SRID=2193;POINT\(5436343 1760120\)"\W/', $html);

        $this->assertRegExp('/\Wdata-controls="{&quot;polyline&quot;:false,&quot;polygon&quot;:false,&quot;marker&quot;:true,&quot;circle&quot;:false,&quot;rectangle&quot;:false,&quot;circlemarker&quot;:false}"\W/', $html);
        $this->assertRegExp('/\Wdata-default-location="\{&quot;lon&quot;:174,&quot;lat&quot;:-41\}"\W/', $html);
    }
}
