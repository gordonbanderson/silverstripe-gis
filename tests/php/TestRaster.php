<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Dev\TestOnly;
use Smindel\GIS\Model\Raster;

class TestRaster extends Raster implements TestOnly
{
    private static $full_path = __DIR__ . \DIRECTORY_SEPARATOR . 'RasterTest.tif';
    private static $webmaptileservice = true;
}
