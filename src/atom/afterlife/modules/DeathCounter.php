<?php

/**
 *  ____                   _     _        ____                           _                 
 * |  _ \    ___    __ _  | |_  | |__    / ___|   ___    _   _   _ __   | |_    ___   _ __ 
 * | | | |  / _ \  / _` | | __| | '_ \  | |      / _ \  | | | | | '_ \  | __|  / _ \ | '__|
 * | |_| | |  __/ | (_| | | |_  | | | | | |___  | (_) | | |_| | | | | | | |_  |  __/ | |   
 * |____/   \___|  \__,_|  \__| |_| |_|  \____|  \___/   \__,_| |_| |_|  \__|  \___| |_|   
 *   
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                           
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class DeathCounter{

    /** @var Main */
    private $plugin;

    /** @var string */
    private $playername;

    /** @var string */
    private $uuid;

    public function __construct (Main $plugin) {
        $this->plugin = $plugin;
    }

    public function add (Player $player) : void {
        $this->query($player, function ($data) use ($player) {
            $data['deaths'] += 1;
            $data['streak'] = 0;
            if ($this->plugin->getConfig()->get("use-levels") === true) {
                $amount = $this->plugin->getConfig()->get("loose-level-xp-amount");
                $this->plugin->getAPI()->removeXp($player, $amount);
                $player->sendPopup("§l§4-".$amount." xp");
            }
            $this->save($player, $data);
        });
    }

    private function query (Player $player, callable $callback) : void {
        $this->playername = $player->getName();
        $this->uuid = $player->getUniqueId()->toString();
        $this->plugin->getAPI()->getStats($player, function ($data) use ($callback) {
            $callback($data);
        });
    }

    private function getPath () : string {
        return $this->plugin->getDataFolder() . "players/" . $this->playername . ".yml";
    }

    private function save (Player $player ,array $data) :void {
        if ($this->plugin->getConfig()->get('storage-method') !== "online") {
            yaml_emit_file($this->getPath(), [
                'name' => $data['name'],
                'level' => $data['level'],
                'totalXp' => $data['totalXp'],
                'neededXp' => $data['xpTo'],
                'kills' => $data['kills'],
                'deaths' => $data['deaths'],
                'streak' => $data['streak']
            ]);
        } else {
            DataHandler::getDatabase()->executeChange("afterlife.update.deaths",[
                'deaths'=>$data['deaths'],
                'uuid'=>$player->getUniqueId()->toString()
            ]);
        }
    }
}
