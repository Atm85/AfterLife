<?php

/**
 *   ____          _     ____            _           
 *  / ___|   ___  | |_  |  _ \    __ _  | |_    __ _ 
 * | |  _   / _ \ | __| | | | |  / _` | | __|  / _` |
 * | |_| | |  __/ | |_  | |_| | | (_| | | |_  | (_| |
 *  \____|  \___|  \__| |____/   \__,_|  \__|  \__,_|
 *              
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                      
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use pocketmine\utils\TextFormat as color;
use atom\afterlife\handler\DataHandler as mySQL;


class GetData {

    private $plugin;
    private $stats = [];
    private $string = "";

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function getData(string $type) {
        $files = scandir($this->plugin->getDataFolder() . "players/");
        switch($type) {
            case "levels":
                $this->string = "level";
                break;
            case "kills":
                $this->string = "kills";
                break;
            case "kdr":
                $this->string = "ratio";
                break;
            case "streaks":
                $this->string = "streak";
        }
        if ($this->plugin->config->get('storage-method') !== "online") {
            foreach($files as $file) {
                if(pathinfo($file, PATHINFO_EXTENSION) == "yml") {
                    $yaml = file_get_contents($this->plugin->getDataFolder() . "players/" . $file);
                    $rawData = yaml_parse($yaml);
                    if(isset($rawData[$this->string])) {
                        $this->stats[$rawData["name"]] = $rawData[$this->string];
                    }
                }
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.all", [],
                function (array $rows){
                    foreach ($rows as $row){
                        if(isset($row[$this->string])){
                            $this->stats[$row['name']] = $row[$this->string];
                        }
                    }
                });

            DataHandler::getDatabase()->waitAll();
        }
        arsort($this->stats, SORT_NUMERIC);
        $finalRankings = "";
        $integer = 1;
        foreach($this->stats as $name => $number) {
            $finalRankings .= color::YELLOW . $integer . ".) " . $name . ": " . $number . "\n";
            if($integer > $this->plugin->config->get("texts-top")) {
                return $finalRankings;
            }
            if(count($this->stats) <= $integer) {
                return $finalRankings;
            }
            $integer++;
        }
        return "";
    }
}
 
