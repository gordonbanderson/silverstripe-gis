<?php

declare(strict_types = 1);

namespace Smindel\GIS\ORM\FieldType;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBComposite;
use SilverStripe\ORM\FieldType\DBField;
use Smindel\GIS\Forms\MapField;
use Smindel\GIS\GIS;

class DBGeography extends DBComposite
{
    /**
     * Add the field to the underlying database.
     */
    public function requireField(): void
    {
        DB::require_field(
            $this->tableName,
            $this->name,
            [
                'type'=>'geography',
            ]
        );
    }


    public function addToQuery(&$query): void
    {
        $table = $this->getTable();
        $column = $this->getName();
        $identifier = $table
            ? \sprintf('"%s"."%s"', $table, $column)
            : \sprintf('"%s"', $column);
        $sqlFragment = DB::get_schema()->translateBasicSelectGeo();
        $select = \sprintf(
            $sqlFragment,
            $identifier,
            $identifier,
            $identifier,
            $column
        );
        $query->selectField($select);
    }


    public function compositeDatabaseFields()
    {
        return ['' => 'Geography'];
    }


    public function prepValueForDB($value)
    {
        $value = GIS::create($value);

        if ($value->isNull()) {
            return null;
        }

        return ['ST_GeogFromText(?)' => [$value->reproject(4326)->wkt]];
    }


    public function exists()
    {
        // reinstates parent::parent::exists()
        return DBField::exists();
    }


    public function writeToManipulation(&$manipulation): void
    {
        // reinstates parent::parent::writeToManipulation()
        DBField::writeToManipulation($manipulation);
    }


    public function saveInto($dataObject): void
    {
        // reinstates parent::parent::saveInto()
        DBField::saveInto($dataObject);
    }


    public function setValue($value, $record = null, $markChanged = true)
    {
        // reinstates parent::parent::setValue()
        return DBField::setValue($value, $record, $markChanged);
    }


    public function scaffoldFormField($title = null, $params = null)
    {
        return MapField::create($this->name, $title);
    }


    public function getRAW()
    {
        return (string)$this;
    }
}
