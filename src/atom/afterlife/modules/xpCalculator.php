<?php

/**
 *                  ____           _                  _           _                  
 * __  __  _ __    / ___|   __ _  | |   ___   _   _  | |   __ _  | |_    ___    _ __ 
 * \ \/ / | '_ \  | |      / _` | | |  / __| | | | | | |  / _` | | __|  / _ \  | '__|
 *  >  <  | |_) | | |___  | (_| | | | | (__  | |_| | | | | (_| | | |_  | (_) | | |   
 * /_/\_\ | .__/   \____|  \__,_| |_|  \___|  \__,_| |_|  \__,_|  \__|  \___/  |_|   
 *        |_| 
 * 
 * @author iAtomPlaza
 * @link https://twitter.com/iAtomPlaza                                                                       
 */

namespace atom\afterlife\modules;

use atom\afterlife\handler\DataHandler as mySQL;

class xpCalculator {

    private $plugin;
    private $level;
    private $xp;
    private $totalXp;
    private $kills;
    private $deaths;
    private $killStreak;
    private $ratio;
    private $data = null;
    private $player = null;

    public function __construct($plugin, $player) {
        $this->plugin = $plugin;
        $this->player = $player;
        $path = $this->getPath();
        if ($this->plugin->config->get('type') !== "online") {
            if(is_file($path)) {
                $data = yaml_parse_file($path);
                $this->data = $data;
                $this->level = $data["level"];
                $this->xp = $data["xp"];
                $this->totalXp = $data["totalXP"];
                $this->kills = $data["kills"];
                $this->deaths = $data["deaths"];
                $this->killStreak = $data["streak"];
                $this->ratio = $data["ratio"];
            } else {
                return;
            }
        } else {
            $sql = "SELECT * FROM afterlife;";
            $result = mysqli_query(mySQL::$database, $sql);
            $check = mysqli_num_rows($result);
            $db = array();
            $names = array();
            if ($check > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $db[] = $row;
                }
                foreach ($db as $kay => $value) {
                    array_push($names, $value['name']);
                }
                if (in_array($this->player, $names)) {
                    $x = array_search($this->player, $names);
                    $this->kills = $db[$x]['kills'];
                    $this->deaths = $db[$x]['deaths'];
                    $this->ratio = $db[$x]['ratio'];
                    $this->xp = $db[$x]['xp'];
                    $this->totalXp = $db[$x]['totalXP'];
                    $this->level = $db[$x]['level'];
                    $this->killStreak = $db[$x]['streak'];
                }
            }
        }
    }

    public function addXp($amount) {
        $this->xp += $amount;
        $this->totalXp += $amount;
        $this->save();
        if ($this->xp >= $this->plugin->config->get("xp-levelup-ammount")) {
            $this->plugin->getAPI()->addLevel($this->player, 1);
        }
    }

    public function removeXp ($amount) {
        if ($this->xp > 0) {
            if ($amount > $this->xp) {
                $dif = abs($amount - $this->xp);
                $this->xp -= $dif;
                $this->totalXp -= $dif; 
            } else {
                $this->xp -= $amount;
                $this->totalXp -= $amount;
            }
            $this->save();
        }
    }

    public function getPath() {
        return $this->plugin->getDataFolder() . "players/" . $this->player . ".yml";
    }

    public function save() {
        if ($this->plugin->config->get('type') !== "online") {
            yaml_emit_file($this->getPath(), ["name" => $this->player, "level" => $this->level, "totalXP" => $this->totalXp, "xp" => $this->xp, "kills" => $this->kills, "deaths" => $this->deaths, "streak" => $this->killStreak, "ratio" => $this->ratio]);
        } else {
            $sql = "UPDATE afterlife SET totalXP='$this->totalXp', xp='$this->xp' WHERE name='$this->player'";
            mysqli_query(mySQL::$database, $sql);
        }
    }
}
