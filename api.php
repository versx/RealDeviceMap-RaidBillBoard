<?php
session_start();

include './config.php';
include './includes/DbConnector.php';
include './includes/utils.php';

define("DEFAULT_LIMIT", 999999);

$pos = !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST'));
if ($pos === false) {
    http_response_code(401);
    die();
}

if (!(isset($_SESSION['token']) && !empty($_SESSION['token']))) {
    die();
}
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
if ($data === 0) {
    die();
}

$token = filter_var($data["token"], FILTER_SANITIZE_STRING);
if (!(isset($token) && !empty($token))) {
    die();
}
//TODO: Fix
//if ($_SESSION['token'] !== $token) {
//    die();
//}
if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest")) {
    die();
}

$allowedTables = [
    "pokemon",
    "gym",
    "pokestop",
    "spawnpoint",
    "pokemon_stats",
    "raid_stats",
    "quest_stats"
];

if (!(isset($data['type']) && !empty($data['type']))) {
    if (!(isset($data['table']) && !empty($data['table']) && in_array($data['table'], $allowedTables))) {
        die();
    }

    $table = filter_var($data["table"], FILTER_SANITIZE_STRING);
    $limit = filter_var(isset($data["limit"]) ? $data["limit"] : DEFAULT_LIMIT, FILTER_SANITIZE_STRING);
    $db = new DbConnector($config["db"]);
    $pdo = $db->getConnection();
    $sql = "SELECT * FROM $table LIMIT $limit";
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        ini_set('memory_limit', '-1');
        $data = $result->fetchAll();
        echo json_encode($data);
    } else {
        if ($config["core"]["showDebug"]) {
            echo json_encode(["error" => 1, "message" => "Query returned zero results."]);
        }
    }
    unset($pdo);
    unset($db);
} else {
    $type = filter_var($data["type"], FILTER_SANITIZE_STRING);
    switch ($type) {
        case "dashboard":
            $stat = filter_var($data["stat"], FILTER_SANITIZE_STRING);
            switch ($stat) {
                case "pokemon":
                    $pokemonStats = get_pokemon_stats();
                    $obj = [
                        "pokemon" => $pokemonStats["total"],
                        "active_pokemon" => $pokemonStats["active"],
                        "iv_total" => $pokemonStats["iv_total"],
                        "iv_active" => $pokemonStats["iv_active"],
                    ];
                    echo json_encode($obj);
                    break;
                case "gyms":
                    $gymStats = get_gym_stats();
                    $gymCount = get_table_count("gym");
                    $raidCount = get_raid_stats();
                    $obj = [
                        "gyms" => $gymCount,
                        "raids" => $raidCount,
                        "neutral" => $gymStats === 0 ? 0 : count($gymStats) < 4 ? 0 : $gymStats[0],
                        "mystic" => $gymStats === 0 ? 0 : $gymStats[1],
                        "valor" => $gymStats === 0 ? 0 : $gymStats[2],
                        "instinct" => $gymStats === 0 ? 0 : $gymStats[3],
                    ];
                    echo json_encode($obj);
                    break;
                case "pokestops":
                    $stopStats = get_pokestop_stats();
                    $obj = [
                        "pokestops" => $stopStats === 0 ? 0 : $stopStats["total"],
                        "lured" => $stopStats === 0 ? 0 : $stopStats["lured"],
                        "quests" => $stopStats === 0 ? 0 : $stopStats["quests"],
                    ];
                    echo json_encode($obj);
                    break;
                case "tth":
                    $spawnpointStats = get_spawnpoint_stats();
                    $obj = [
                        "tth_total" => $spawnpointStats === 0 ? 0 : $spawnpointStats["total"],
                        "tth_found" => $spawnpointStats === 0 ? 0 : $spawnpointStats["found"],
                        "tth_missing" => $spawnpointStats === 0 ? 0 : $spawnpointStats["missing"],
                        "tth_30min" => $spawnpointStats === 0 ? 0 : $spawnpointStats["min30"],
                        "tth_60min" => $spawnpointStats === 0 ? 0 : $spawnpointStats["min60"]
                        //"tth_percentage" => $spawnpointStats === 0 ? 0 : $spawnpointStats["percentage"],
                    ];
                    echo json_encode($obj);
                    break;
                case "top":
                    $top10Pokemon = get_top_pokemon(false, false, 10);
                    $top10PokemonToday = get_top_pokemon(true, false, 10);
                    $top10PokemonIV = get_top_pokemon(true, true, 10);
                    $obj = [
                        "top10_pokemon" => $top10Pokemon,
                        "top10_pokemon_today" => $top10PokemonToday,
                        "top10_pokemon_iv" => $top10PokemonIV
                    ];
                    echo json_encode($obj);
                    break;
            }
            /*
            $gymStats = get_gym_stats();
            $stopStats = get_pokestop_stats();
            $pokemonStats = get_pokemon_stats();
            $gymCount = get_table_count("gym");
            $raidCount = get_raid_stats();
            $spawnpointStats = get_spawnpoint_stats();
            $top10Pokemon = get_top_pokemon(10);
            $obj = [
                "pokemon" => $pokemonStats["total"],
                "active_pokemon" => $pokemonStats["active"],
                "iv_total" => $pokemonStats["iv_total"],
                "iv_active" => $pokemonStats["iv_active"],
                "gyms" => $gymCount,
                "raids" => $raidCount,
                "neutral" => $gymStats === 0 ? 0 : count($gymStats) < 4 ? 0 : $gymStats[0],
                "mystic" => $gymStats === 0 ? 0 : $gymStats[1],
                "valor" => $gymStats === 0 ? 0 : $gymStats[2],
                "instinct" => $gymStats === 0 ? 0 : $gymStats[3],
                "pokestops" => $stopStats === 0 ? 0 : $stopStats["total"],
                "lured" => $stopStats === 0 ? 0 : $stopStats["lured"],
                "quests" => $stopStats === 0 ? 0 : $stopStats["quests"],
                "tth_total" => $spawnpointStats === 0 ? 0 : $spawnpointStats["total"],
                "tth_found" => $spawnpointStats === 0 ? 0 : $spawnpointStats["found"],
                "tth_missing" => $spawnpointStats === 0 ? 0 : $spawnpointStats["missing"],
                "tth_percentage" => $spawnpointStats === 0 ? 0 : $spawnpointStats["percentage"],
                "top10_pokemon" => $top10Pokemon
            ];
            echo json_encode($obj);
            */
            break;
        case "nests":
            $coords = $data["data"]["coordinates"];
            $spawnpoints = getSpawnpointNestData($coords);
            $pokestops = getPokestopNestData($coords);
            $args = [
                "spawn_ids" => $spawnpoints, 
                "pokestop_ids" => $pokestops, 
                "nest_migration_timestamp" => $data["data"]["nest_migration_timestamp"], 
                "spawn_report_limit" => $data["data"]["spawn_report_limit"]
            ];
            try {
                getSpawnData($args);
            } catch (Exception $e) {
                echo json_encode(["error" => true, "message" => $e]);
            }
            break;
        case "stats":
            $stats = getSpawnDataReport($data["start"], $data["end"], $data["pokemon_id"]);
            echo json_encode($stats);
            break;
        default:
            die();
    }
}

function getSpawnDataReport($start, $end, $pokemon_id) {
    $sql = "
SELECT
  COUNT(id) AS total,
  SUM(iv = 100) AS iv100,
  SUM(iv = 0) AS iv0,
  SUM(iv > 0) AS with_iv,
  SUM(iv IS NULL) AS without_iv,
  SUM(iv > 90 AND iv < 100) AS iv90,
  SUM(iv >= 1 AND iv < 50) AS iv_1_49,
  SUM(iv >= 50 AND iv < 80) AS iv_50_79,
  SUM(iv >= 80 AND iv < 90) AS iv_80_89,
  SUM(iv >= 90 AND iv < 100) AS iv_90_99,
  SUM(gender = 1) AS male,
  SUM(gender = 2) AS female,
  SUM(gender = 3) AS genderless,
  SUM(level >= 1 AND level <= 9) AS level_1_9,
  SUM(level >= 10 AND level <= 19) AS level_10_19,
  SUM(level >= 20 AND level <= 29) AS level_20_29,
  SUM(level >= 30 AND level <= 35) AS level_30_35
FROM
  pokemon
WHERE
  pokemon_id = $pokemon_id
  AND first_seen_timestamp >= $start
  AND first_seen_timestamp <= $end
";
    return execute($sql, PDO::FETCH_OBJ);
}

function getSpawnData($args) {
    global $config;
    $binds = array();

    if (isset($args["spawn_ids"]) || isset($args["pokestop_ids"])) {
        if (isset($args["spawn_ids"]) && count($args["spawn_ids"]) > 0) {
            $spawns_in  = str_repeat('?,', count($args["spawn_ids"]) - 1) . '?';
            $binds = array_merge($binds, $args["spawn_ids"]);
        }
        if (isset($args["pokestop_ids"]) && count($args["pokestop_ids"]) > 0) {
            $stops_in  = str_repeat('?,', count($args["pokestop_ids"]) - 1) . '?';
            $binds = array_merge($binds, $args["pokestop_ids"]);
        }
      
        if ($stops_in && $spawns_in) {
            $points_string = "(pokestop_id IN (" . $stops_in . ") OR spawn_id IN (" . $spawns_in . "))";
        } else if ($stops_in) {
            $points_string = "pokestop_id IN (" . $stops_in . ")";
        } else if ($spawns_in) {
            $points_string = "spawn_id IN (" . $spawns_in . ")";
        } else {
            echo json_encode(array('spawns' => null, 'status'=>'Error: no points!'));
            return;
        }
        if (is_numeric($args["nest_migration_timestamp"]) && (int)$args["nest_migration_timestamp"] == $args["nest_migration_timestamp"]) {
            $ts = $args["nest_migration_timestamp"];
        } else {
            $ts = 0;
        }
        $binds[] = $ts;

        if (is_numeric($args["spawn_report_limit"]) && (int)$args["spawn_report_limit"] == $args["spawn_report_limit"] && (int)$args["spawn_report_limit"] != 0) {
            $limit = " LIMIT " . $args["spawn_report_limit"];
        } else {
            $limit = '';
        }    

        $sql_spawn = "
SELECT
  pokemon_id,
  COUNT(pokemon_id) AS count
FROM
  pokemon
WHERE " . $points_string . "
  AND first_seen_timestamp >= ?
GROUP BY
  pokemon_id
ORDER BY
  count DESC" . $limit;
        $db = new DbConnector($config['db']);
        $pdo = $db->getConnection();
        $stmt = $pdo->prepare($sql_spawn);
        try {
            $stmt->execute($binds);
        } catch (PDOException $e) {
            echo json_encode(["error" => true, "message" => $e]);
        } 

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($pdo);
        unset($db);
        echo json_encode(array('spawns' => $result, 'sql' => $sql_spawn));
    } else {
        echo json_encode(["error" => true, "message" => "No data provided."]);
    }
}

function getSpawnpointNestData($coords) {
    $sql = "
SELECT
  id
FROM
  spawnpoint
WHERE
  ST_CONTAINS(ST_GEOMFROMTEXT('POLYGON(($coords))'), point(spawnpoint.lat, spawnpoint.lon))
";
    return execute($sql, PDO::FETCH_COLUMN);
}

function getPokestopNestData($coords) {
    $sql = "
SELECT
  id
FROM
  pokestop
WHERE
  ST_CONTAINS(ST_GEOMFROMTEXT('POLYGON(($coords))'), point(pokestop.lat, pokestop.lon))
";
    return execute($sql, PDO::FETCH_COLUMN);
}
?>