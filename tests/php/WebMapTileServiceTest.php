<?php

declare(strict_types = 1);

namespace Smindel\GIS\Tests;

use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\FunctionalTest;
use Smindel\GIS\GIS;

class WebMapTileServiceTest extends FunctionalTest
{

    use RenderingAssertion;

    protected static $fixture_file = 'TestLocation.yml';

    public function setUp(): void
    {
        // reset GIS environment
        Config::modify()->set(GIS::class, 'default_srid', 4326);

        parent::setUp();
    }


    public function testService(): void
    {
        $response = $this->get('webmaptileservice/Smindel-GIS-Tests-TestLocation/6/63/40.png');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/png', $response->getHeaders()['content-type']);

        $this->assertRenders($response->getBody(), 18, 18);
    }


    public function testCachedService(): void
    {
        $test_cache_path = 'test-tile-cache-' . \uniqid();
        $full_path = \TEMP_PATH . \DIRECTORY_SEPARATOR . $test_cache_path;
        $full_name = $full_path . \DIRECTORY_SEPARATOR . \sha1(\json_encode([]));
        Config::modify()->set(TestLocation::class, 'webmaptileservice', ['cache_ttl' => 100, 'cache_path' => $test_cache_path]);
        $response = $this->get('webmaptileservice/Smindel-GIS-Tests-TestLocation/6/63/40.png');
        Config::modify()->set(TestLocation::class, 'webmaptileservice', true);

        $this->assertTrue(\is_dir($full_path), 'Cache path created');
        $this->assertTrue(\is_readable($full_name), 'Cache file created');

        $this->assertRenders(\file_get_contents($full_name), 18, 18);

        \unlink($full_name);
        \rmdir($full_path);
    }


    public static function getExtraDataObjects()
    {
        return [TestLocation::class];
    }
}
