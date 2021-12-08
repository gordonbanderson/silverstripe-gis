<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Dev\FunctionalTest;
use Smindel\GIS\Model\Raster;

class RasterTest extends FunctionalTest
{

    use RenderingAssertion;

    public function testSrid(): void
    {
        $filename = __DIR__ . \DIRECTORY_SEPARATOR . 'RasterTest.tif';
        $srid = Raster::create($filename)->getSrid();
        $this->assertEquals(4326, $srid);
    }


    public function testLocationInfo(): void
    {
        $filename = __DIR__ . \DIRECTORY_SEPARATOR . 'RasterTest.tif';
        $bandValues = Raster::create($filename)->getLocationInfo([174.777, -41.2955]);
        $this->assertEquals([
            1 => '195',
            2 => '195',
            3 => '195',
            4 => '255',
        ], $bandValues);
    }


    public function testRasterTile(): void
    {
        $response = $this->get('webmaptileservice/Smindel-GIS-Tests-TestRaster/18/258340/164145.png');
        $this->assertRenders($response->getBody(), 255, 1, [195, 195, 195]);
    }
}
