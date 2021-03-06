<?php
namespace Salesking\PHPSDK;

/**
 * This file brings in the Salesking helper class
 * @version     2.0.0
 * @package     SalesKing PHP SDK
 * @license     MIT License; see LICENSE
 * @copyright   Copyright (C) 2012 David Jardin
 * @link        http://www.salesking.eu
 */

/**
 * Salesking SDK file for helper stuff
 * @since 2.0.0
 * @package SalesKing PHP SDK
 */
class Helper
{
    /**
     * keeps some special pluralization cases
     * @var array special cases for pluralization
     */
    protected static $plurals = array(
        "address" => "addresses",
        "company" => "companies"
    );

    /**
     * helper method to pluralize object titles
     * @static
     * @param string $obj_type object type
     * @return string
     */
    public static function pluralize($obj_type)
    {
        if (array_key_exists($obj_type, self::$plurals)) {
            return self::$plurals[$obj_type];
        } else {
            return $obj_type . "s";
        }
    }

    /**
     * load object properties from schema file
     * @param string $obj_type object type
     * @return Collection
     * @throws Exception
     * @since 2.0.0
     */
    public static function loadSchema($obj_type)
    {
        // set schema filename
        $file = dirname(__FILE__)."/schemes/".$obj_type.".json";

        // check if the schema file exists
        if (file_exists($file) && is_readable($file)) {
            //load schema, decode it and assign it to schema property
            $schema = json_decode(file_get_contents($file));

            // Verify schema was valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("INVALID_SCHEMA", "Schema is invalid JSON");
            }

            //set link relation as key name to make it easier to call these
            foreach ($schema->links as $key => $link) {
                $schema->links[$link->rel] = $link;
                unset($schema->links[$key]);
            }

            return $schema;
        } else {
            //couldn't find file, seems like our object doesn't exist
            throw new Exception("SCHEMA_NOTFOUND", "Could not find schema file.");
        }
    }
}
