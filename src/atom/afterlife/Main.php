<?php

/**
 *     _       __   _                   _   _    __        
 *    / \     / _| | |_    ___   _ __  | | (_)  / _|   ___ 
 *   / _ \   | |_  | __|  / _ \ | '__| | | | | | |_   / _ \
 *  / ___ \  |  _| | |_  |  __/ | |    | | | | |  _| |  __/
 * /_/   \_\ |_|    \__|  \___| |_|    |_| |_| |_|    \___|
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza
 * @version 4.0.0
 * @copyright cc_by_nc
 */

namespace atom\afterlife;

# Main Files
use pocketmine\plugin\PluginBase;

#commands
use atom\afterlife\commands\StatsCommand;
use atom\afterlife\commands\LeaderboardCommand;

# events
use atom\afterlife\events\InitEvent;
use atom\afterlife\events\DeathEvent;
use atom\afterlife\events\CustomDeathEvent;
use atom\afterlife\events\LevelChangeEvent;
use atom\afterlife\events\PlayerDamageEvent;

# plugin files
//use atom\afterlife\API;
use atom\afterlife\handler\DataHandler as mySQL;


class Main extends PluginBase {

	/** @var $config */
	public $config;

	/** @var $this */
	public static $instance;

	/** @var array */
	public $ftps = [];

	/** @api */
	private $api;


	/**
	 * Registers command functions
	 * @return void
	 */
	public function onLoad() : void {
		$map = $this->getServer()->getCommandMap();
		$map->register("afterlife", new StatsCommand($this));
		$map->register("afterlife", new LeaderboardCommand($this));
	}


    /**
     * Registers plugin instance and events
     * @return void
     */
	public function onEnable() : void {
		self::$instance = $this;
		$this->api = new API($this);

		$this->getServer()->getPluginManager()->registerEvents(new InitEvent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new DeathEvent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new CustomDeathEvent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new LevelChangeEvent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new PlayerDamageEvent($this), $this);

		$this->saveDefaultConfig();
		$this->reloadConfig();

        @mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . 'players/');
		@mkdir($this->getDataFolder() . 'leaderboards/');
        $this->config = $this->getConfig();
		
		# loads mysqli database
		if ($this->config->get('storage-method') === "online") {
		    mySQL::create();
		}
	}


    /**
     * Closes mysql (isset)
     * @return void
     */
	public function onDisable() : void {
        mySQL::disConnect();
	}

	/**
	 * plugins instance
	 * @return Main
	 */
	public static function getInstance() : Main {
		return self::$instance;
	}


	/**
	 * plugin api
	 * @api
	 * @return API 
	 */
	public function getAPI() : API {
		return $this->api;
	}

	public function colorize(string $string) : string {
        $coloredText = str_replace("&", "§", $string);
        return $coloredText;
    }
}
