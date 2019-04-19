-- #!mysql
-- #{ afterlife
-- #  { init
-- #    { main
          CREATE TABLE IF NOT EXISTS afterlife(
            id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
            name varchar(36) UNIQUE NOT NULL,
            uuid varchar(36) unique NOT NULL,
            kills int(5) UNSIGNED NOT NULL,
            deaths int(5) UNSIGNED NOT NULL,
            totalXp int(5) UNSIGNED NOT NULL,
            neededXp int(5) UNSIGNED NOT NULL,
            level int(5) UNSIGNED NOT NULL,
            streak int(5) UNSIGNED NOT NULL
          );
-- #    }
-- #    { player
-- #      :name string
-- #      :uuid string
          INSERT INTO afterlife(
            name, uuid ,kills, deaths,  totalXP, neededXp, level, streak
          )
          values (
            :name, :uuid, 0, 0, 0, 0, 0, 0
          );
-- #    }
-- #  }
-- #  { select
-- #    { player
-- #      :name string
          SELECT COUNT(*) FROM afterlife WHERE name = :name;
-- #    }
-- #    { all
-- #      :uuid string
          SELECT * FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #  }
-- #  { update
-- #    { deaths
-- #      :uuid string
-- #      :deaths string
          UPDATE afterlife SET deaths=:deaths, streak=0 WHERE uuid=:uuid;
-- #    }
-- #    { kills
-- #      :uuid string
-- #      :kills string
-- #      :streak string
          UPDATE afterlife SET kills=:kills, streak=:streak WHERE uuid=:uuid;
-- #    }
-- #    { xp
-- #      :uuid string
-- #      :xpTo string
-- #      :totalXp string
          UPDATE afterlife SET neededXp=:xpTo, totalXp=:totalXp WHERE uuid=:uuid;
-- #    }
-- #    { level
-- #      :uuid string
-- #      :level string
          UPDATE afterlife SET level=:level WHERE uuid=:uuid;
-- #    }
-- #  }
-- #}