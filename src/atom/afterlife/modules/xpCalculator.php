<?php

/**
 *                  ____           _                  _           _                  
 * __  __  _ __    / ___|   __ _  | |   ___   _   _  | |   __ _  | |_    ___    _ __ 
 * \ \/ / | '_ \  | |      / _` | | |  / __| | | | | | |  / _` | | __|  / _ \  | '__|
 *  >  <  | |_) | | |___  | (_| | | | | (__  | |_| | | | | (_| | | |_  | (_) | | |   
 * /_/\_\ | .__/   \____|  \__,_| |_|  \___|  \__,_| |_|  \__,_|  \__|  \___/  |_|   
 *        |_| 
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                       
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;

class xpCalculator {

    /** @var Main */
    private $plugin;

    /** @var string */
    private $playername;

    /** @var string */
    private $uuid;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function add (Player $player, int $amount) :void {
        $this->query($player, function ($data) use ($player, $amount) {
            $data['totalXp'] += $amount;
            $data['rawXp'] += $amount;
            $this->save($player, $data);
            if ($data['rawXp'] >= $this->plugin->getConfig()->get("xp-levelup-amount")) {
                $this->plugin->getAPI()->addLevel($player, 1);
                $data['rawXp'] = 0;
                $this->save($player, $data);
            }
        });
    }

    public function remove (Player $player, int $amount) :void {
        $this->query($player, function ($data) use ($player, $amount) {
            if ($data['rawXp'] > 0) {
                if ($amount > $data['rawXp']) {
                    $dif = abs($amount - $data['rawXp']);
                    $data['rawXp'] -= $dif;
                    $data['totalXp'] -= $dif;
                } else {
                    $data['rawXp'] -= $amount;
                    $data['totalXp'] -= $amount;
                }
                $this->save($player, $data);
            }
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
                'neededXp' => $data['rawXp'],
                'kills' => $data['kills'],
                'deaths' => $data['deaths'],
                'streak' => $data['streak']
            ]);
        } else {
            DataHandler::getDatabase()->executeChange("afterlife.update.xp",[
                'totalXp'=>$data['totalXp'],
                'xpTo'=>$data['rawXp'],
                'uuid'=>$player->getUniqueId()->toString()
            ]);
        }
    }
}
