<?php

/**
 *  _                             _    ____   _                                      _____                          _   
 * | |       ___  __   __   ___  | |  / ___| | |__     __ _   _ __     __ _    ___  | ____| __   __   ___   _ __   | |_ 
 * | |      / _ \ \ \ / /  / _ \ | | | |     | '_ \   / _` | | '_ \   / _` |  / _ \ |  _|   \ \ / /  / _ \ | '_ \  | __|
 * | |___  |  __/  \ V /  |  __/ | | | |___  | | | | | (_| | | | | | | (_| | |  __/ | |___   \ V /  |  __/ | | | | | |_ 
 * |_____|  \___|   \_/    \___| |_|  \____| |_| |_|  \__,_| |_| |_|  \__, |  \___| |_____|   \_/    \___| |_| |_|  \__|
 *                                                                    |___/                                                  
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife\events;

# events
use atom\afterlife\Main;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;

class LevelChangeEvent implements Listener {

    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function levelChangeEvent(EntityLevelChangeEvent $action):void {
        switch ($this->plugin->getServer()->getName()) {
            case 'PocketMine-MP':
                $player = $action->getEntity();
                $target = $action->getTarget();
                $files = scandir($this->plugin->getDataFolder() . 'leaderboards/');
                foreach ($files as $file) {
                    $path = $this->plugin->getDataFolder(). 'leaderboards/' . $file;
                    if (is_file($path)) {
                        $data = yaml_parse_file($path);
                        $level = $data['level'];
                        $type = $data['type'];
                        if (!isset($this->plugin->ftps[$type][$target->getName()])) {
                            $ftp = $this->plugin->ftps[$type][$level];
                            $ftp->setInvisible();
                            $player->getLevel()->addParticle($ftp, [$player]);
                        } else {
                            $ftp = $this->plugin->ftps[$type][$level];
                            $ftp->setInvisible(false);
                            $player->getLevel()->addParticle($ftp, [$player]);
                        }
                    }
                }
                break;
        }
	}

}
