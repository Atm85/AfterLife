<?php

/**
 *   ____                 _                         _____                          _   
 *  / ___|  _   _   ___  | |_    ___    _ __ ___   | ____| __   __   ___   _ __   | |_ 
 * | |     | | | | / __| | __|  / _ \  | '_ ` _ \  |  _|   \ \ / /  / _ \ | '_ \  | __|
 * | |___  | |_| | \__ \ | |_  | (_) | | | | | | | | |___   \ V /  |  __/ | | | | | |_ 
 *  \____|  \__,_| |___/  \__|  \___/  |_| |_| |_| |_____|   \_/    \___| |_| |_|  \__|            
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                        
 */

namespace atom\afterlife\events;

# player instance
use pocketmine\Player;

# utils
use pocketmine\utils\TextFormat as color;

# events
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class CustomDeathEvent implements Listener {

    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function onDamage(EntityDamageEvent $event) {

        if ($this->plugin->config->get("death-method") == "custom") {

            $victim = $event->getEntity();
            if ($event->getFinalDamage() >= $victim->getHealth()) {
                $event->setCancelled();
                if ($victim instanceof Player) {
                    $victim->setHealth($victim->getMaxHealth());
                    $victim->setFood(20);
                    $victim->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(), 0, 0);
                    $victim->getInventory()->setHeldItemIndex(1);
                }

                if ($event instanceof EntityDamageByEntityEvent) {
                    $killer = $event->getDamager();
                    if ($killer instanceof Player && $victim instanceof Player) {
                        $this->plugin->getAPI()->addKill($killer->getName());
                        $this->plugin->getAPI()->addDeath($victim->getName());
                        $this->plugin->getServer()->broadcastMessage(color::GRAY.$victim->getName().color::WHITE." Was Killed by ".color::GRAY.$killer->getName());
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
}
