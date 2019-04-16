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
            ratio FLOAT UNSIGNED NOT NULL,
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
            name, uuid ,kills, deaths, ratio, totalXP, neededXp, level, streak
          )
          values (
            :name, :uuid, 0, 0, 0, 0, 0, 0, 0
          );
-- #    }
-- #  }
-- #  { select
-- #    { player
-- #      :name string
          SELECT COUNT(*) FROM afterlife WHERE name = :name;
-- #    }
-- #    { all
          SELECT * FROM afterlife;
-- #    }
-- #    { deaths
-- #      :uuid string
          SELECT deaths FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #    { kills
-- #      :uuid string
          SELECT kills FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #    { level
-- #      :uuid string
          SELECT level FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #    { ratio
-- #      :uuid string
          SELECT ratio FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #    { streak
-- #      :uuid string
          SELECT streak FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #    { neededXp
-- #      :uuid string
          SELECT neededXp FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #    { totalXp
-- #      :uuid string
          SELECT totalXp FROM afterlife WHERE uuid = :uuid;
-- #    }
-- #  }
-- #}