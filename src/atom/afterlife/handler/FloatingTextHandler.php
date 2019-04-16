<?php

/**
 *  _____   _                   _     _                   _____                 _     _   _                       _   _               
 * |  ___| | |   ___     __ _  | |_  (_)  _ __     __ _  |_   _|   ___  __  __ | |_  | | | |   __ _   _ __     __| | | |   ___   _ __ 
 * | |_    | |  / _ \   / _` | | __| | | | '_ \   / _` |   | |    / _ \ \ \/ / | __| | |_| |  / _` | | '_ \   / _` | | |  / _ \ | '__|
 * |  _|   | | | (_) | | (_| | | |_  | | | | | | | (_| |   | |   |  __/  >  <  | |_  |  _  | | (_| | | | | | | (_| | | | |  __/ | |   
 * |_|     |_|  \___/   \__,_|  \__| |_| |_| |_|  \__, |   |_|    \___| /_/\_\  \__| |_| |_|  \__,_| |_| |_|  \__,_| |_|  \___| |_|   
 *                                                |___/                                                                               
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife\handler;

# main files
//use pocketmine\Player;
use pocketmine\Server;

# utils
use pocketmine\math\Vector3;
//use pocketmine\utils\TextFormat as color;

# floatingtext
use pocketmine\level\particle\FloatingTextParticle;

# plugin instance - Main::getInstance()
use atom\afterlife\Main;

class FloatingTextHandler {

    /**
     * Initializes Floating Texts.
     * @param Vector3 $location
     * @param $level
     * @param string $type
     * @param array $player
     *
     * @todo add support for forks
     */
    public static function addText(Vector3 $location, $level, string $type = "title", $player) {
		switch (Server::getInstance()->getName()) {
			case 'PocketMine-MP':
				$title = Main::getInstance()->getConfig()->get("texts-title")[$type];
				$particle = new FloatingTextParticle($location, Main::getInstance()->colorize($title) . "\n" . Main::getInstance()->getAPI()->getData($type));
				$player->getLevel()->addParticle($particle, [$player]);
				Main::getInstance()->ftps[$type][$level] = $particle;
				break;

			// case 'Altay':
			// 	$typetitle = $this->config->get("texts-title")[$type];
			// 	$id = implode("_", [$location->getX(), $location->getY(), $location->getZ()]);
			// 	$particle = new FloatingTextParticle(color::GOLD . "<<<<<>>>>>", $this->colorize($typetitle) . "\n" . $this->getData($type), $location);
			// 	Server::getInstance()->getLevelByName($this->config->get("texts-world"))->addParticle($location, $particle);
			// 	$this->particles[$id] = $particle;
			// 	break;
		}
    }
}
