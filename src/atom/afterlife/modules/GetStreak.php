<?php

/**
 *   ____          _       _  __  _   _   _     ____    _                           _    
 *  / ___|   ___  | |_    | |/ / (_) | | | |   / ___|  | |_   _ __    ___    __ _  | | __
 * | |  _   / _ \ | __|   | ' /  | | | | | |   \___ \  | __| | '__|  / _ \  / _` | | |/ /
 * | |_| | |  __/ | |_    | . \  | | | | | |    ___) | | |_  | |    |  __/ | (_| | |   < 
 *  \____|  \___|  \__|   |_|\_\ |_| |_| |_|   |____/   \__| |_|     \___|  \__,_| |_|\_\
 *
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                                      
 */

namespace atom\afterlife\modules;


use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class GetStreak {

    private $plugin;
    private $streak;
    private $data = null;
    private $player = null;
    private $uuid = null;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function getStreak(Player $player) {
        $this->query($player);
        return $this->streak;
    }

    private function query(Player $player){
        $this->player = $player->getName();
        $this->uuid = $player->getUniqueId()->toString();
        $path = $this->getPath();
        if ($this->plugin->getConfig()->get('storage-method') !== "online") {
            if(is_file($path)) {
                $data = yaml_parse_file($path);
                $this->data = $data;
                $this->streak = $data["streak"];
            } else {
                return;
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.streak", [
                'uuid' => $this->uuid
            ],
                function (array $rows){
                    foreach ($rows as $row){
                        $this->streak = $row["streak"];
                    }
                });

            DataHandler::getDatabase()->waitAll();
        }
    }

    private function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

}
