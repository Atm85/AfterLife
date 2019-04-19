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
            DataHandler::getFileData()->executeSelect($path,
                function (array $row) use ($callback) {
                    $data = [];
                    if ($row['deaths'] > 0){
                        $ratio = round(($row['kills']/$row['deaths']), 1);
                    } else {
                        $ratio = 1;
                    }
                    $xpTo = abs($this->plugin->getConfig()->get("xp-levelup-amount") - $data['neededXp']);
                    $data['kills'] = $row['kills'];
                    $data['deaths'] = $row['deaths'];
                    $data['kdr'] = $ratio;
                    $data['totalXp'] = $row['totalXp'];
                    $data['xpTo'] = $xpTo;
                    $data['rawXp'] = $row['neededXp'];
                    $data['level'] = $row['level'];
                    $data['streak'] = $row['streak'];
                    $callback($data);
                });
        } else {
            DataHandler::getDatabase()->executeSelect("afterlife.select.all", [
                'uuid' => (string)$this->uuid
            ],
                function (array $rows) use ($callback) {
                    $data = [];
                    foreach ($rows as $row) {
                        if ($row['deaths'] > 0){
                            $ratio = round(($row['kills']/$row['deaths']), 1);
                        } else {
                            $ratio = 1;
                        }
                        $xpTo = abs($this->plugin->getConfig()->get("xp-levelup-amount") - $row['neededXp']);
                        $data['kills'] = $row['kills'];
                        $data['deaths'] = $row['deaths'];
                        $data['kdr'] = $ratio;
                        $data['totalXp'] = $row['totalXp'];
                        $data['xpTo'] = $xpTo;
                        $data['rawXp'] = $row['neededXp'];
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