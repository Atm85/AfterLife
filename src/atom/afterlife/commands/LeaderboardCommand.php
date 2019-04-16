<?php

/**
 *  _                          _                 _                                  _    ____                                                       _ 
 * | |       ___    __ _    __| |   ___   _ __  | |__     ___     __ _   _ __    __| |  / ___|   ___    _ __ ___    _ __ ___     __ _   _ __     __| |
 * | |      / _ \  / _` |  / _` |  / _ \ | '__| | '_ \   / _ \   / _` | | '__|  / _` | | |      / _ \  | '_ ` _ \  | '_ ` _ \   / _` | | '_ \   / _` |
 * | |___  |  __/ | (_| | | (_| | |  __/ | |    | |_) | | (_) | | (_| | | |    | (_| | | |___  | (_) | | | | | | | | | | | | | | (_| | | | | | | (_| |
 * |_____|  \___|  \__,_|  \__,_|  \___| |_|    |_.__/   \___/   \__,_| |_|     \__,_|  \____|  \___/  |_| |_| |_| |_| |_| |_|  \__,_| |_| |_|  \__,_|                                                                                                                                                   
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife\commands;

# player instance
use pocketmine\Player;

# commands
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

# utils
use pocketmine\level\Position;
use pocketmine\utils\TextFormat as color;

# main
use atom\afterlife\Main;

# plugin files
use atom\afterlife\handler\FloatingTextHandler as Leaderboard;

class LeaderboardCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct('setleaderboard', 'create floating text leaderboards', '/setleaderboard <type>', ['sl']);
        $this->plugin = $plugin;
		$this->setPermission('afterlife.admin');
    }

    public function execute(CommandSender $player, string $cmd, array $args) {
        if ($player instanceof Player) {
			if ($this->plugin->config->get("texts-enabled") == true) {
				if ($player->hasPermission('afterlife.admin')) {
					if ($cmd == "setleaderboard") {
						if (isset($args[0])) {
							if (in_array($args[0], ["levels", "kills", "kdr", "streaks"])) {
								if (!isset($this->ftps[$args[0]][$player->getLevel()->getName()])) {
									$level = $player->getLevel()->getName();
									$x = round($player->getX(), 1);
									$y = round($player->getY(), 1) + 1.7;
									$z = round($player->getZ(), 1);
									yaml_emit_file($this->plugin->getDataFolder() . "leaderboards/" . $args[0] . "_" . $level . ".yml", ['level'=>$level, 'type'=>$args[0], 'xx'=>$x, 'yy'=>$y, 'zz'=>$z]);
									$possition = new Position($player->getX(), $player->getY() + 1.7, $player->getZ(), $player->getLevel());
									Leaderboard::addText($possition, $player->getLevel()->getName(), $args[0], $player);
									$player->sendMessage(color::RED.$args[0].color::YELLOW." leaderboard created!");
								} else {
									$player->sendMessage('Error:'.' '.$args[0].' '.'Floating text already exists in'.' '.$player->getLevel()->getName());
								}
							} elseif ((in_array($args[0], ["del", "remove", "delete"]))) {
                                // coming soon
							} elseif (in_array($args[0], ["debug"])) {
								switch($args[1]) {
									case 'addKill':
										$this->plugin->getAPI()->addKill($player->getName());
										break;

									case 'addDeath':
										$this->plugin->getAPI()->addDeath($player->getName());
										break;
								}
							}
						} else {
							$player->sendMessage(color::RED . "Please choose \n ---kills, \n ---levels, \n ---kdr, \n ---streaks");
						}
					}
				} else {
					$player->sendMessage(color::RED."You donot have permission to run this command!");
				}
			} else {
				$player->sendMessage(color::RED."leaderboards are not enabled... edit config");
			}
		} else {
			$player->sendMessage("Run commands in-game");
		}
    }
       
}
