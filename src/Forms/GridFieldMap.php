<?php

declare(strict_types = 1);

namespace Smindel\GIS\Forms;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_DataManipulator;
use SilverStripe\Forms\GridField\GridField_HTMLProvider;
use SilverStripe\ORM\SS_List;
use SilverStripe\View\Requirements;
use Smindel\GIS\GIS;

/**
 * GridFieldPaginator paginates the {@link GridField} list and adds controls
 * to the bottom of the {@link GridField}.
 */
class GridFieldMap implements GridField_HTMLProvider, GridField_DataManipulator
{

    use Injectable;
    use Configurable;

    protected $attribute;

    public function __construct($attribute = null)
    {
        $this->attribute = $attribute;
    }


    /** @return array */
    public function getHTMLFragments(GridField $gridField): array
    {
        $srid = GIS::config()->default_srid;
        $proj = GIS::config()->projections[$srid];

        Requirements::javascript('smindel/silverstripe-gis: client/dist/js/leaflet.js');
        Requirements::javascript('smindel/silverstripe-gis: client/dist/js/leaflet.markercluster.js');
        Requirements::javascript('smindel/silverstripe-gis: client/dist/js/leaflet-search.js');
        Requirements::javascript('smindel/silverstripe-gis: client/dist/js/proj4.js');
        Requirements::customScript(\sprintf('proj4.defs("EPSG:%s", "%s");', $srid, $proj), 'EPSG:' . $srid);
        Requirements::javascript('smindel/silverstripe-gis: client/dist/js/GridFieldMap.js');
        Requirements::css('smindel/silverstripe-gis: client/dist/css/leaflet.css');
        Requirements::css('smindel/silverstripe-gis: client/dist/css/MarkerCluster.css');
        Requirements::css('smindel/silverstripe-gis: client/dist/css/MarkerCluster.Default.css');
        Requirements::css('smindel/silverstripe-gis: client/dist/css/leaflet-search.css');

        $defaultLocation = Config::inst()->get(MapField::class, 'default_location');

        return array(
            'before' => \sprintf(
                '<div class="grid-field-map" data-map-center="%s" data-list="%s" style="z-index:0;"></div>',
                GIS::create([$defaultLocation['lon'], $defaultLocation['lat']]),
                \htmlentities(
                    self::get_geojson_from_list(
                        $gridField->getList(),
                        $this->attribute ?: GIS::of($gridField->getList()->dataClass())
                    ),
                    \ENT_QUOTES,
                    'UTF-8'
                )
            ),
        );
    }


    /**
     * Manipulate the {@link DataList} as needed by this grid modifier.
     */
    public function getManipulatedData(GridField $gridField, SS_List $dataList): \SilverStripe\ORM\DataList
    {
        return $dataList;
    }


    public static function get_geojson_from_list($list, $geometryField = null)
    {
        $modelClass = $list->dataClass();

        $geometryField = $geometryField
            ? $geometryField
            : GIS::of($modelClass);

        $collection = [];

        foreach ($list as $item) {
            if (!$item->canView()) {
                continue;
            }

            $geo = GIS::create($item->$geometryField)->reproject(4326);

            $collection[$item->ID] = [
                $item->Title,
                $geo->type,
                $geo->coordinates,
            ];
        }

        return \json_encode($collection);
    }
}
