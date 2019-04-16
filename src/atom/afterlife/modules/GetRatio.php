<?php

/**
 *   ____          _       _  __     __  ___       ____            _     _         
 *  / ___|   ___  | |_    | |/ /    / / |  _ \    |  _ \    __ _  | |_  (_)   ___  
 * | |  _   / _ \ | __|   | ' /    / /  | | | |   | |_) |  / _` | | __| | |  / _ \ 
 * | |_| | |  __/ | |_    | . \   / /   | |_| |   |  _ <  | (_| | | |_  | | | (_) |
 *  \____|  \___|  \__|   |_|\_\ /_/    |____     |_| \_\  \__,_|  \__| |_|  \___/ 
 *                        
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                              
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class GetRatio {

    private $plugin;
    private $kills;
    private $deaths;
    private $ratio;
    private $data = null;
    private $player = null;
    private $uuid = null;

    public function __construct(Main $plugin, Player $player) {
        $this->plugin = $plugin;
        $this->player = $player->getName();
        $this->uuid = $player->getUniqueId()->toString();
        $path = $this->getPath();
        if ($this->plugin->getConfig()->get('storage-method') !== "online") {
            if(is_file($path)) {
                $data = yaml_parse_file($path);
                $this->data = $data;
                $this->kills = $data["kills"];
                $this->deaths = $data["deaths"];
//                $this->ratio = $data["ratio"];
            } else {
                return;
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.kills", [
                'uuid' => $this->uuid
            ],
                function (array $rows){
                    foreach ($rows as $row){
                        $this->kills = $row["kills"];
                    }
                });

            DataHandler::getDatabase()->executeSelect("afterlife.select.deaths", [
                'uuid' => $this->uuid
            ],
                function (array $rows){
                    foreach ($rows as $row){
                        $this->deaths = $row["deaths"];
                    }
                });

            DataHandler::getDatabase()->waitAll();
        }
    }

    public function getRatio() {
        if ($this->deaths > 0){
            $this->ratio = round(($this->kills / $this->deaths), 1);
            return $this->ratio;
        } else {
            $this->ratio = 1;
            return 1;
        }
    }

    public function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

}
