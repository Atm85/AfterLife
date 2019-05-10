<?php

/**
 *   _____                              _   _                       _   _
 * |  ___|   ___    _ __   _ __ ___   | | | |   __ _   _ __     __| | | |   ___   _ __
 * | |_     / _ \  | '__| | '_ ` _ \  | |_| |  / _` | | '_ \   / _` | | |  / _ \ | '__|
 * |  _|   | (_) | | |    | | | | | | |  _  | | (_| | | | | | | (_| | | | |  __/ | |
 * |_|      \___/  |_|    |_| |_| |_| |_| |_|  \__,_| |_| |_|  \__,_| |_|  \___| |_|
 *
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 * @version 3.2.10
 * @copyright GNU (general public license)
 */

namespace atom\afterlife\handler;

# main files
use atom\gui\GUI;
use atom\gui\type\ModalGui;
use atom\gui\type\SimpleGui;
use pocketmine\Player;
use pocketmine\Server;

# utils
use pocketmine\utils\TextFormat as color;

use atom\afterlife\Main;
use atom\afterlife\API;

class FormHandler {

    /** @var int[] **/
    public static $uis = [];

    public static function statsUi(Player $player){
        switch (Server::getInstance()->getName()) {
            case 'PocketMine-MP':
                $local = [];
                API::getInstance()->getStats($player, function ($data) use ($player, $local){
                    $gui = new SimpleGui();
                    $gui->setTitle($player->getName()." Stats!");
                    $gui->setContent(
                        color::YELLOW."\nCurrent Win Streak ".color::BLUE.$data['streak']."\n\n".
                        color::RED."\nKills: ".color::GREEN.$data['kills'].
                        color::RED."\nDeaths: ".color::GREEN.$data['deaths'].
                        color::RED."\nK/D Ratio: ".color::GREEN.$data['kdr'].
                        color::RED."\n\n\nLevel: ".color::GREEN.$data['level'].
                        color::RED."\nTotal XP: ".color::GREEN.$data['totalXp'].
                        color::RED."\nXp needed to level up: ".color::GREEN.$data['xpTo'].""
                    );
                    $gui->addButton("close");
                    GUI::register("stats", $gui);
                    GUI::send($player, "stats");
                });
                break;

            default;
                $player->sendMessage("Forms are not *YET* supported on this server... please choose 'standard in config'");
                break;
        }
    }

}
