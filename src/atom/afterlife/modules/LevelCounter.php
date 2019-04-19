<?php

/**
 *  _                             _    ____                           _                 
 * | |       ___  __   __   ___  | |  / ___|   ___    _   _   _ __   | |_    ___   _ __ 
 * | |      / _ \ \ \ / /  / _ \ | | | |      / _ \  | | | | | '_ \  | __|  / _ \ | '__|
 * | |___  |  __/  \ V /  |  __/ | | | |___  | (_) | | |_| | | | | | | |_  |  __/ | |   
 * |_____|  \___|   \_/    \___| |_|  \____|  \___/   \__,_| |_| |_|  \__|  \___| |_|   
 *           
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                           
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class LevelCounter {

    /** @var Main */
    private $plugin;

    /** @var string */
    private $playername;

    /** @var string */
    private $uuid;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function add(Player $player, int $amount) :void {
        $this->query($player, function ($data) use ($player, $amount) {
            $data['level'] += $amount;
            $player->addTitle("§k§eiii§r §bLevelup §k§eiii§r", "you are now level§4 ".$data['level']);
            $this->save($player, $data);
        });
    }

    public function remove(Player $player, int $amount) :void {
        $this->query($player, function ($data) use ($player, $amount) {
           $data['level'] -= $amount;
           $this->save($player, $data);
        });
    }

    private function query (Player $player, callable $callback) :void {
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
            DataHandler::getDatabase()->executeChange("afterlife.update.level",[
                'level'=>$data['level'],
                'uuid'=>$player->getUniqueId()->toString()
            ]);
        }
    }
}
