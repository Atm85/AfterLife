<?php

/**
 *   ____          _    __  __  ____  
 *  / ___|   ___  | |_  \ \/ / |  _ \ 
 * | |  _   / _ \ | __|  \  /  | |_) |
 * | |_| | |  __/ | |_   /  \  |  __/ 
 *  \____|  \___|  \__| /_/\_\ |_|    
 *                                   
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */
namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class GetXp {

    private $plugin;
    private $xp;
    private $totalXP;
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
                $this->xp = $data["xp"];
                $this->totalXP = $data['totalXP'];
            } else {
                return;
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.neededXp", [
                'uuid' => $this->uuid
            ],
                function (array $rows){
                    foreach ($rows as $row){
                        $this->xp = $row['neededXp'];
                    }
                });

            DataHandler::getDatabase()->executeSelect("afterlife.select.totalXp", [
                'uuid' => $this->uuid
            ],
                function (array $rows){
                    foreach ($rows as $row){
                        $this->totalXP = $row['totalXp'];
                    }
                });

            DataHandler::getDatabase()->waitAll();
        }
    }

    public function getXp() {
        return abs($this->plugin->getConfig()->get('xp-levelup-amount') - $this->xp);
    }

    public function getTotalXp() {
        return $this->totalXP;
    }

    public function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

}
