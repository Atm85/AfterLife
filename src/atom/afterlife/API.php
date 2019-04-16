<?php

/**
 *     _       __   _                   _   _    __               _      ____    ___ 
 *    / \     / _| | |_    ___   _ __  | | (_)  / _|   ___       / \    |  _ \  |_ _|
 *   / _ \   | |_  | __|  / _ \ | '__| | | | | | |_   / _ \     / _ \   | |_) |  | | 
 *  / ___ \  |  _| | |_  |  __/ | |    | | | | |  _| |  __/    / ___ \  |  __/   | | 
 * /_/   \_\ |_|    \__|  \___| |_|    |_| |_| |_|    \___|   /_/   \_\ |_|     |___|                                                                                                                                                            
 *                                                                                    
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 */

namespace atom\afterlife;

# player instance
use pocketmine\Player;

# utils
use pocketmine\utils\TextFormat as color;

# plugin instance - Main::getInstance()
//use atom\afterlife\Main;
use atom\afterlife\handler\FormHandler as Form;

# api
use atom\afterlife\modules\GetData;
use atom\afterlife\modules\GetKills;
use atom\afterlife\modules\GetStreak;
use atom\afterlife\modules\GetXp;
use atom\afterlife\modules\GetLevel;
use atom\afterlife\modules\GetDeaths;
use atom\afterlife\modules\GetRatio;
use atom\afterlife\modules\KillCounter;
use atom\afterlife\modules\xpCalculator;
use atom\afterlife\modules\LevelCounter;
use atom\afterlife\modules\DeathCounter;



class API {

	public static $instance;

	public static function getInstance(): API{
		return self::$instance;
	}

	public function __construct() {
		self::$instance = $this;
	}

    public function getStats (Player $player):void {
		switch (Main::getInstance()->getConfig()->get("profile-method")) {
			case "form":
				Form::statsUi($player);
				break;

			case "standard":
				$player->sendMessage(color::LIGHT_PURPLE."--------------------");
				$player->sendMessage($player->getName()." stats\n\n");
				$player->sendMessage(color::YELLOW."Current Win Streak ".color::GREEN.$this->getStreak($player)."\n\n");
				$player->sendMessage(color::RED."Kills: ".color::GREEN.$this->getKills($player));
				$player->sendMessage(color::RED."Deaths: ".color::GREEN.$this->getDeaths($player));
				$player->sendMessage(color::RED."K/D Ratio: ".color::GREEN.$this->getKdr($player));
				$player->sendMessage(color::RED."Level: ".color::GREEN.$this->getLevel($player));
				$player->sendMessage(color::RED."Total XP: ".color::GREEN.$this->getTotalXp($player));
				$player->sendMessage(color::RED."Xp needed to level up: ".color::GREEN.$this->getNeededXp($player));
				$player->sendMessage(color::LIGHT_PURPLE."--------------------");
				break;
		}
	}

    /**
	 * Returns Player Data for leaderboards
     * @api
	 * @param $type
	 * @return GetData
	 */
	public function getData ($type):string {
		$data = new GetData(Main::getInstance());
		return $data->getData($type);
    }
    
    /**
     * Returns Players kills
     * @api
     * @param $player
     * @return int
     */
	public function getKills (Player $player): ?int {
		$data = new GetKills(Main::getInstance(), $player);
		return $data->getKills();
    }
    
    /**
	 * Returns Players Win Streak
     * @api
	 * @param $player
	 * @return int
	 */
	public function getStreak(Player $player): ?int {
		$data = new GetStreak(Main::getInstance(), $player);
		return $data->getStreak();
    }
    
    /**
	 * Returns Player Xp till level up
     * @api
	 * @param $player
	 * @return int
	 */
	public function getNeededXp(Player $player): ?int {
		$data = new GetXp(Main::getInstance(), $player);
		return $data->getXp();
	}

	/**
	 * Returns Player total xp
     * @api
	 * @param $player
	 * @return int
	 */
	public function getTotalXp(Player $player): ?int {
		$data = new GetXp(Main::getInstance(), $player);
		return $data->getTotalXp();
    }
    
    /**
     * Returns player level
     * @api
     * @param $player
     * @return int
     */
    public function getLevel(Player $player): ?int {
		$data = new GetLevel(Main::getInstance(), $player);
		return $data->getLevel();
    }

    /**
     * Returns Player death count
     * @api
     * @param Player $player
     * @return int
     */
	public function getDeaths(Player $player): ?int {
		$data = new GetDeaths(Main::getInstance(), $player);
		return $data->getDeaths();
    }
    
    /**
     * Returns kills/death ratio
     * @api
     * @param $player
     * @return int
     */
    public function getKdr(Player $player): ?int {
		$data = new GetRatio(Main::getInstance(), $player);
		return $data->getRatio();
    }
    
    /**
     * Adds 1 to the number of kills
     * @api
     * @param $player
     */
	public function addKill(string $player):void {
		$data = new KillCounter(Main::getInstance(), $player);
		$data->addKill();
    }
    
    /**
	 * Adds xp to player
     * @api
	 * @param $amount
	 * @param $player
	 */
	public function addXp (string $player, ?int $amount):void {
		$data = new xpCalculator(Main::getInstance(), $player);
		$data->addXp($amount);
	}

	/**
	 * removes xp to player
     * @api
	 * @param $player
	 * @param $amount
	 */
	public function removeXp (string $player, ?int $amount):void {
		$data = new xpCalculator(Main::getInstance(), $player);
		$data->removeXp($amount);
    }
    
    /**
     * adds amount of levels to player
     * @api
     * @param $player
     * @param $amount
     */
    public function addLevel (string $player, ?int $amount):void {
		$data = new LevelCounter(Main::getInstance(), $player);
		$data->addLevel($amount);
	}

    /**
     * removes amount of levels to player
     * @api
     * @param $player
     * @param $amount
     */
	public function removeLevel (string $player, ?int $amount):void {
		$data = new LevelCounter(Main::getInstance(), $player);
		$data->removeLevel($amount);
    }
    
    /**
     * Adds to the number of deaths
     * @param $player
     * @return void
     */
	public function addDeath (string $player):void {
        $data = new DeathCounter(Main::getInstance(), $player);
        $data->addDeath();
	}
}
