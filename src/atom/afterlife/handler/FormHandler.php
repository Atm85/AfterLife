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
use pocketmine\Player;
use pocketmine\Server;

# utils
use pocketmine\utils\TextFormat as color;

# customui
use xenialdan\customui\API as Form;
use xenialdan\customui\elements\Button;
use xenialdan\customui\windows\SimpleForm;

# plugin instance - Main::getInstance()
use atom\afterlife\Main;
use atom\afterlife\API;

class FormHandler {

    /** @var int[] **/
    public static $uis = [];
    
    public static function statsUi(Player $player){
		switch (Server::getInstance()->getName()) {
			case 'PocketMine-MP':
				$ui = new SimpleForm($player->getName().' Stats',
				color::YELLOW."\nCurrent Win Streak ".color::BLUE.API::getInstance()->getStreak($player)."\n\n".
				color::RED."\nKills: ".color::GREEN.API::getInstance()->getKills($player).
				color::RED."\nDeaths: ".color::GREEN.API::getInstance()->getDeaths($player).
				color::RED."\nK/D Ratio: ".color::GREEN.API::getInstance()->getKdr($player).
				color::RED."\n\n\nLevel: ".color::GREEN.API::getInstance()->getLevel($player).
				color::RED."\nTotal XP: ".color::GREEN.API::getInstance()->getTotalXp($player).
				color::RED."\nXp needed to level up: ".color::GREEN.API::getInstance()->getNeededXp($player)."\n\n"
				);
				$button = new Button(color::RED.'Close'); 
//				$button->addImage(Button::IMAGE_TYPE_PATH, "textures/items/stick");
				$ui->addButton($button);
                self::$uis['statsui'] = Form::addUI(Main::getInstance(), $ui);
                Form::showUIbyID(Main::getInstance(), self::$uis['statsui'], $player);
				break;
				
			default;
				$player->sendMessage("Forms are not *YET* supported on this server... please choose 'standard in config'");
				break;
		}
    }

}
