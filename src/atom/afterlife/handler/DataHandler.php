<?php

/**
 *  ____            _               _   _                       _   _               
 * |  _ \    __ _  | |_    __ _    | | | |   __ _   _ __     __| | | |   ___   _ __ 
 * | | | |  / _` | | __|  / _` |   | |_| |  / _` | | '_ \   / _` | | |  / _ \ | '__|
 * | |_| | | (_| | | |_  | (_| |   |  _  | | (_| | | | | | | (_| | | | |  __/ | |   
 * |____/   \__,_|  \__|  \__,_|   |_| |_|  \__,_| |_| |_|  \__,_| |_|  \___| |_|                                                                                  
 *                                                                                    
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife\handler;

use atom\afterlife\Main;

# libasynql
use poggit\libasynql\libasynql;
use poggit\libasynql\DataConnector;


class DataHandler {

    public static $mysql;
    public static $database;

    public static function create () {
        $main = Main::getInstance();
        self::$database = libasynql::create($main, $main->getConfig()->get("database"), [
            "mysql" => "mysql.sql"
        ]);

        self::$database->executeGeneric("afterlife.init.main");
        self::$database->waitAll();
    }

    public static function disConnect () {
        if(isset(self::$database)) self::getDatabase()->close();
    }

    public static function getDatabase() : DataConnector {
        return self::$database;
    }
}
