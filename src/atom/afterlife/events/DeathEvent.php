<?php

/**
 *  ____                   _     _       _____                          _   
 * |  _ \    ___    __ _  | |_  | |__   | ____| __   __   ___   _ __   | |_ 
 * | | | |  / _ \  / _` | | __| | '_ \  |  _|   \ \ / /  / _ \ | '_ \  | __|
 * | |_| | |  __/ | (_| | | |_  | | | | | |___   \ V /  |  __/ | | | | | |_ 
 * |____/   \___|  \__,_|  \__| |_| |_| |_____|   \_/    \___| |_| |_|  \__|
 *                
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                         
 */

namespace atom\afterlife\events;

# player instance
use pocketmine\Player;

#events
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class DeathEvent implements Listener {

    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function onDeath(PlayerDeathEvent $event) {
        if ($this->plugin->config->get("death-method") == "default") {

            $victim = $event->getPlayer();
            if ($victim->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
                $killer = $victim->getLastDamageCause()->getDamager();
                if ($killer instanceof Player && $victim instanceof Player) {
                    $this->plugin->getAPI()->addKill($killer->getName());
                    $this->plugin->getAPI()->addDeath($victim->getName());
                }
            } else {
                /**
                * @author TheWalker0
                * Fixing a bug that i couldnt reproduce
                */
                if ($victim instanceof Player) {
                    $this->plugin->getAPI()->addDeath($victim->getName());
                    if ($this->plugin->config->get("use-levels") == true) {
                        $this->plugin->getAPI()->removeXp($victim->getName(), $this->plugin->config->get("loose-level-xp-amount"));
                    }
                }
            }
        }
    }
}
