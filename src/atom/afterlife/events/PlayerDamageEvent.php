<?php

/**
 *  ____    _                                 ____                                               _____                          _   
 * |  _ \  | |   __ _   _   _    ___   _ __  |  _ \    __ _   _ __ ___     __ _    __ _    ___  | ____| __   __   ___   _ __   | |_ 
 * | |_) | | |  / _` | | | | |  / _ \ | '__| | | | |  / _` | | '_ ` _ \   / _` |  / _` |  / _ \ |  _|   \ \ / /  / _ \ | '_ \  | __|
 * |  __/  | | | (_| | | |_| | |  __/ | |    | |_| | | (_| | | | | | | | | (_| | | (_| | |  __/ | |___   \ V /  |  __/ | | | | | |_ 
 * |_|     |_|  \__,_|  \__, |  \___| |_|    |____/   \__,_| |_| |_| |_|  \__,_|  \__, |  \___| |_____|   \_/    \___| |_| |_|  \__|
 *                      |___/                                                     |___/                                    
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza         
 */

namespace atom\afterlife\events;

# events
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;

class PlayerDamageEvent implements Listener {

    private $plugin;


    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function onDamage(EntityDamageEvent $event) {
        $nopvpAtSpawn = $this->plugin->config->get("no-PvP-at-spawn");
        $nopvpInLevel = $this->plugin->config->get("no-PvP-in-level");

        if ($nopvpAtSpawn == true) {
            if ($event->getEntity()->getLevel() == $this->plugin->getServer()->getDefaultLevel()) {
                $event->setCancelled();
            } 
        }
        if ($nopvpAtSpawn == false) {
            foreach ($nopvpInLevel as $levels) {
                if ($event->getEntity()->getLevel() == $this->plugin->getServer()->getLevelByName($levels)) {
                    $event->setCancelled();
                }
            }
        }
    }

}
