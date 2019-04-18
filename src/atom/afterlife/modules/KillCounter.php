<?php

/**
 *  _  __  _   _   _    ____                           _                 
 * | |/ / (_) | | | |  / ___|   ___    _   _   _ __   | |_    ___   _ __ 
 * | ' /  | | | | | | | |      / _ \  | | | | | '_ \  | __|  / _ \ | '__|
 * | . \  | | | | | | | |___  | (_) | | |_| | | | | | | |_  |  __/ | |   
 * |_|\_\ |_| |_| |_|  \____|  \___/   \__,_| |_| |_|  \__|  \___| |_|   
 *   
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                   
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class KillCounter{

    /** @var Main */
    private $plugin;

    /** @var string */
    private $playername;

    /** @var string */
    private $uuid;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function add (Player $player) :void {
        $this->query($player, function ($data) use ($player) {
            $data['kills'] += 1;
            $data['streak'] += 1;
            if ($this->plugin->getConfig()->get("use-levels") === true) {
                $amount = $this->plugin->getConfig()->get("add-level-xp-amount");
                $this->plugin->getAPI()->addXp($player, $amount);
                $player->sendPopup("§l§2+".$amount." xp");
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

    private function getPath() :string {
        return $this->plugin->getDataFolder() . "players/" . $this->playername . ".yml";
    }

    private function save (Player $player, array $data) :void {
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
            DataHandler::getDatabase()->executeChange("afterlife.update.kills",[
                'kills'=>$data['kills'],
                'streak'=>$data['streak'],
                'uuid'=>$player->getUniqueId()->toString()
            ]);
        }
    }
}
