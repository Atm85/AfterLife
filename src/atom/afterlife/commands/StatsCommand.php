<?php

/**
 *  ____    _             _            ____                                                       _ 
 * / ___|  | |_    __ _  | |_   ___   / ___|   ___    _ __ ___    _ __ ___     __ _   _ __     __| |
 * \___ \  | __|  / _` | | __| / __| | |      / _ \  | '_ ` _ \  | '_ ` _ \   / _` | | '_ \   / _` |
 *  ___) | | |_  | (_| | | |_  \__ \ | |___  | (_) | | | | | | | | | | | | | | (_| | | | | | | (_| |
 * |____/   \__|  \__,_|  \__| |___/  \____|  \___/  |_| |_| |_| |_| |_| |_|  \__,_| |_| |_|  \__,_|
 *
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                                                 
 */

namespace atom\afterlife\commands;

# player instance
use pocketmine\Player;

# commands
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;

# main
use atom\afterlife\Main;
use pocketmine\utils\TextFormat;

class StatsCommand extends PluginCommand {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("stats", $plugin);
        $this->setDescription("shows your or another players stats");
        $this->setAliases(["st"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $cmd, array $args) {
        if ($sender instanceof Player) {
            if (!isset($args[0])) {
                $this->plugin->getAPI()->getStats($sender);
            } else {
                $target = $this->plugin->getServer()->getPlayerExact($args[0]);
                if ($target !== null){
                    $this->plugin->getAPI()->getStats($target);
                } else {
                    $sender->sendMessage(TextFormat::RED . "Player is not online!");
                }
            }
        } else {
            $sender->sendMessage("Run commands in-game");
        }
    }
}
