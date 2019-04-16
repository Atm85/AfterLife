<?php

/**
 *   ____          _     _  __  _   _   _       
 *  / ___|   ___  | |_  | |/ / (_) | | | |  ___ 
 * | |  _   / _ \ | __| | ' /  | | | | | | / __|
 * | |_| | |  __/ | |_  | . \  | | | | | | \__ \
 *  \____|  \___|  \__| |_|\_\ |_| |_| |_| |___/
 *                
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                              
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class GetKills{

    private $plugin;
    private $kills;
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
            } else {
                return;
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.kills", [
                'uuid' => $this->uuid
            ],
                function (array $rows) {
                    foreach ($rows as $row){
                        $this->kills = $row["kills"];
                    }
                });

            DataHandler::getDatabase()->waitAll();
        }
    }

    public function getKills() {
        return $this->kills;
    }

    public function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

}
