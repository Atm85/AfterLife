<?php

/**
 *   ____          _     _                             _ 
 *  / ___|   ___  | |_  | |       ___  __   __   ___  | |
 * | |  _   / _ \ | __| | |      / _ \ \ \ / /  / _ \ | |
 * | |_| | |  __/ | |_  | |___  |  __/  \ V /  |  __/ | |
 *  \____|  \___|  \__| |_____|  \___|   \_/    \___| |_|
 *                          
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                             
 */

namespace atom\afterlife\modules;


use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class GetLevel {

    private $plugin;
    private $level;
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
                $this->level = $data["level"];
            } else {
                return;
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.level", [
                'uuid' => $this->uuid
            ],
                function (array $rows){
                    foreach ($rows as $row){
                        $this->level = $row["level"];
                    }
                });

            DataHandler::getDatabase()->waitAll();
        }
    }

    public function getlevel() {
        return $this->level;
    }

    public function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

}
