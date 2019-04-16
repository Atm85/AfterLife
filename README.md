# Important!

If running this plugin from source, you will need the <a href="https://poggit.pmmp.io/p/DEvirion">DEvirion</a> plugin and the virion library
<a href="https://poggit.pmmp.io/ci/thebigsmileXD/customui/customui">customUI</a> **ONLY** if you wish to use forms as the method to display stats

# AfterLife Features
Fully featured kill/death scoring plugin with custom death event!

<p align="center">
 <a href="http://hits.dwyl.io/Atomization/Afterlife"><img src="http://hits.dwyl.io/Atomization/Afterlife.svg"></a>
 <a href="https://poggit.pmmp.io/ci/Aurora-MC/AfterLife/AfterLife">
  <img src="https://poggit.pmmp.io/ci.shield/Aurora-MC/Afterlife/Afterlife?style=flat-square">
 </a>
 <a href="https://poggit.pmmp.io/p/Afterlife"><img src="https://poggit.pmmp.io/shield.state/Afterlife"></a>
 <a href="https://poggit.pmmp.io/p/Afterlife"><img src="https://poggit.pmmp.io/shield.dl/Afterlife"></a>
 <a href="https://poggit.pmmp.io/p/Afterlife"><img src="https://poggit.pmmp.io/shield.dl.total/Afterlife"></a>
 <a href="https://poggit.pmmp.io/p/Afterlife"><img src="https://poggit.pmmp.io/shield.api/Afterlife"></a>
</p>

 - [x] The ability to sync data across multiple servers in a network
 - [x] Score points on Kill! `(+ gain xp)`
 - [x] Loose xp on Death!
 - [x] Calculates kill/death ratio 
 - [x] Level up when achieved specified amount of XP `(see config)`
 - [x] Commands to see your or another players' stats `(suports formAPI)`
 - [x] Enable floating texts to see leaderboard of stats `(see commands)`
 - [x] Custom eventing for kills/deaths `(see Custom Event)`
 - [ ] Add commands to easily change settings in config
 - [ ] Add Level up timer `(level up over time, so stay online to level up!)`
 - [ ] Add Top XP Leaderboards
 - [ ] Add Longest Bow Kills & Hits Leaderboards
 - [ ] Add Display Levels beside name in chat and nametag
 
# Custom Event
The custom event is simple, it disables the title screen to prevent accidental quit to menu ;)
```yml
# config.yml
#choose between 'custom' or 'default'
death-method: "custom"
```

# Commands
| Command | Usage | Description |
| ------- | ----- | ----------- |
| `/stats` | `/stats <player>` | Shows yours or another players stats. |
| `/setlearderboard` | `/setleaderboard <type>` | Creates a floating text at players location. |
| ----------------------------- |
| Floating Text Types | 
| `levels` |
| `kills` |
| `kdr` |
| `streaks` |
| `xp` *coming soon* | 

# Full Config
```yml
#enable floating texts.
#true: false:
texts-enabled: true

#how many players to display
texts-top: 5

#setts the title for each leaderboard
texts-title:
  levels: "&b< PvP Levels Leaderboard >"
  kills: "&b< Kills Leaderboard >"
  kdr: "&b< K/D Ratio Leaderboard >"
  streaks: "&b< Top Killstreaks >"

#Disables PvP at spawn... uses server default level, 
#if want to use custom level set this to false and use (no-PvP-in-level)
no-PvP-at-spawn: true

#disables PvP in specified world
#works if no-PvP-at-spawn: is set to false
#may add worlds!
no-PvP-in-level:
  - "world1"
  - "world2"
  - "world3"

#choose to use unique "form" or "standard" message to display stats
#form requires FormApi plugin
#methods => "form" "standard"
profile-method: "standard"

#choose between 'custom' or 'default'
#custom bypasses the death 'main menu' screen and default does not
death-method: "custom"

#use built in level up system that adds levels on kill and removes level on death
#choose 'false' if you already have a level up plugin
#true: false:
use-levels: true

#use level up timer
#adds xp over time
#(example) stay online to gain xp
use-level-up-timer: true

#amount of xp to be given on kill
add-level-xp-amount: 50

#amout of xp to be lost on death
loose-level-xp-amount: 10

#how much xp is required for level up
xp-levelup-amount: 1000

#-------------------------------------------------------------------------------------------------------------------------
# DATA STORING!!!
#
# How you want to store data
#
# - online database (use if you have more than one server and want to sync kill score across all servers)
# - local database (DEFAULT) (use if you only have one single server)
#
# online database is complex to setup, use only if you know what is mysql is and how to operate a online database
# online database tutorial is coming soon to help un-experienced users!
#-------------------------------------------------------------------------------------------------------------------------

# - local - online
storage-method: "online"

database:
  # The maximum number of simultaneous SQL queries
  # Recommended: 2 for MySQL. You may want to further increase this value if your MySQL connection is very slow.
  worker-limit: 2

  # currently only supports mySQL
  type: "mysql"

  #mysql database settings
  mysql:
    host: "127.0.0.1"
    # Avoid using the "root" user for security reasons.
    username: "root"
    password: ""
    schema: ""

```
# 💰 Credits
Icon made by Freepik from www.flaticon.com is licensed by CC 3.0 BY
