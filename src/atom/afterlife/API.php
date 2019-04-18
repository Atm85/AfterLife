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
use atom\afterlife\modules\GetStats;
use pocketmine\Player;

# utils
use pocketmine\utils\TextFormat as color;

# plugin instance - Main::getInstance()
//use atom\afterlife\Main;
use atom\afterlife\handler\FormHandler as Form;

# api
use atom\afterlife\modules\GetData;
use atom\afterlife\modules\KillCounter;
use atom\afterlife\modules\xpCalculator;
use atom\afterlife\modules\LevelCounter;
use atom\afterlife\modules\DeathCounter;



class API {

    /** @var API */
	public static $instance;

//	/** @var GetData */
//	private $data;

	/** @var GetStats */
	private $stats;

	/** @var array */
	private $data = [
	    /*0*/'kills',
        /*1*/'deaths',
        /*2*/'xp',
        /*3*/'level'
    ];

	public static function getInstance(): API{
		return self::$instance;
	}

	public function __construct(Main $plugin) {
		self::$instance = $this;
//		$this->data = new GetData($plugin);
		$this->stats = new GetStats($plugin);
		$this->data[0] = new KillCounter($plugin);
		$this->data[1] = new DeathCounter($plugin);
	}

    public function sendStats (Player $player):void {
		switch (Main::getInstance()->getConfig()->get("profile-method")) {
			case "form":
				Form::statsUi($player);
				break;

			case "standard":
			    $this->getStats($player, function ($data) use ($player){
                    $player->sendMessage(color::LIGHT_PURPLE."--------------------");
                    $player->sendMessage($player->getName()." stats\n\n");
                    $player->sendMessage(color::YELLOW."Current Win Streak ".color::GREEN.$data['streak']."\n\n");
                    $player->sendMessage(color::RED."Kills: ".color::GREEN.$data['kills']);
                    $player->sendMessage(color::RED."Deaths: ".color::GREEN.$data['deaths']);
                    $player->sendMessage(color::RED."K/D Ratio: ".color::GREEN.$data['kdr']);
                    $player->sendMessage(color::RED."Level: ".color::GREEN.$data['level']);
                    $player->sendMessage(color::RED."Xp to level: ".color::GREEN.$data['xpTo']);
                    $player->sendMessage(color::RED."Total XP: ".color::GREEN.$data['totalXp']);
                    $player->sendMessage(color::LIGHT_PURPLE."--------------------");
                });
				break;
		}
	}


    /**
     * Gets player stats
     * @api
     * @param Player $player
     * @param callable $callback
     */
	public function getStats(Player $player, callable $callback) : void {
	    $this->stats->getData($player, $callback);
    }

    /**
	 * Returns Player Data for leaderboards
     * @api
	 * @param $type
	 * @return GetData
	 */
//	public function getData ($type):string {
//		return $this->data->getData($type);
//    }

    /**
     * Adds 1 to the number of kills
     * @api
     * @param $player
     */
	public function addKill(Player $player):void {
		$this->data[0]->add($player);
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
	public function addDeath (Player $player):void {
        $this->data[1]->add($player);
	}
}
