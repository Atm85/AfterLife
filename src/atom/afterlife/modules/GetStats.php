<?php

/**
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler;
use atom\afterlife\Main;
use pocketmine\Player;


class GetStats{

    /** @var Main  */
    private $plugin;

    /** @var Player */
    private $player;

    /** @var string*/
    private $uuid;

    private $data = null;
    private $kills;
    private $deaths;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function getData(Player $player, callable $callback){
        $this->query($player, $callback);
    }

    private function query(Player $player, callable $callback){
        $this->player = $player->getName();
        $this->uuid = $player->getUniqueId()->toString();
        $path = $this->getPath();
        if ($this->plugin->getConfig()->get('storage-method') !== 'online') {
            if(is_file($path)) {
                $data = yaml_parse_file($path);
                $this->data = $data;
                $this->kills = $data['kills'];
                $this->deaths = $data['deaths'];
            } else {
                return;
            }
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.all", [
                'uuid' => (string)$this->uuid
            ],
                function (array $rows) use ($callback) {
                    $data = [];
                    foreach ($rows as $row) {
                        $data['kills'] = $row['kills'];
                        $data['deaths'] = $row['deaths'];
                        $data['kdr'] = $row['ratio'];
                        $data['totalXp'] = $row['totalXp'];
                        $data['xpTo'] = $row['neededXp'];
                        $data['level'] = $row['level'];
                        $data['streak'] = $row['streak'];
                    }
                    $callback($data);
                });
        }
    }

    private function getPath(){
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }
}