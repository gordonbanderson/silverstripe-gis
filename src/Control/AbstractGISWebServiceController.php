<?php

declare(strict_types = 1);

namespace Smindel\GIS\Control;

use Exception;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Permission;
use Smindel\GIS\GIS;

class AbstractGISWebServiceController extends Controller
{
    public function getModel($request)
    {
        $request = $request
            ? $request
            : $this->getRequest();

        return \str_replace('-', '\\', $request->param('Model'));
    }


    public function getConfig($model)
    {
        $defaults = Config::inst()->get(static::class);
        $modelConfig = Config::inst()->get($model, \strtolower(\array_reverse(\explode('\\', static::class))[0]));
        if (!$modelConfig) {
            return false;
        }
        $defaults['record_provider'] = null;
        $defaults['access_control_allow_origin'] = Director::absoluteURL('/');
        $defaults['geometry_field'] = GIS::of($model);
        $defaults['searchable_fields'] = \singleton($model)->searchableFields();

        return \is_array($modelConfig)
            ? \array_merge($defaults, $modelConfig)
            : $defaults;
    }


    public function getRecords($request)
    {
        $model = $this->getModel($request);
        $config = $this->getConfig($model);

        if (!\is_a($model, DataObject::class, true)) {
            throw new Exception(\sprintf('%s not found', $model), 404);
        }

        if (!$config) {
            throw new Exception(\sprintf('%s not configured for %s', static::class, $model), 404);
        }

        if (isset($config['code']) && !Permission::check($config['code'])) {
            throw new Exception(\sprintf('You are not allowed to access %s through %s', $model, static::class), 403);
        }

        $skip_filter = false;
        $list = \is_callable($config['record_provider'])
            ? \Closure::fromCallable($config['record_provider'])($request, $skip_filter)
            : $model::get();

        if (!$skip_filter) {
            $queryParams = \array_intersect_ukey(
                $request->requestVars(),
                $config['searchable_fields'],
                static function ($a, $b) {
                    $a = \explode(':', $a)[0];
                    $b = \explode(':', $b)[0];

                    return \strcmp($a, $b);
                }
            );

            $list = $list->filter($queryParams);
        }

        return $list;
    }
}
