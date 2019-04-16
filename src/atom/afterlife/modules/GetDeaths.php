<?php

/**
 *   ____          _     ____                   _     _           
 *  / ___|   ___  | |_  |  _ \    ___    __ _  | |_  | |__    ___ 
 * | |  _   / _ \ | __| | | | |  / _ \  / _` | | __| | '_ \  / __|
 * | |_| | |  __/ | |_  | |_| | |  __/ | (_| | | |_  | | | | \__ \
 *  \____|  \___|  \__| |____/   \___|  \__,_|  \__| |_| |_| |___/
 *     
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                           
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class GetDeaths {

    private $plugin;
    private $deaths;
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
                $this->deaths = $data["deaths"];
            } else {
                return;
            }
        } else {
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

    public function getDeaths() {
        return $this->deaths;
    }

    public function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

}
