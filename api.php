<?php
session_start();

require_once './vendor/autoload.php';
include './config.php';
include './includes/DbConnector.php';
include './includes/utils.php';
include './static/data/pokedex.php';

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
if (strpos($rawData, "&columns%") !== false) {
    parse_str($rawData, $data);
} else {
    $data = json_decode($rawData, true);
}
if ($data === 0) {
    die();
}

$token = filter_var($data["token"], FILTER_SANITIZE_STRING);
if (!(isset($token) && !empty($token))) {
    die();
}
if ($_SESSION['token'] !== $token) {
    die();
}
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
        $data = $result->fetchAll();
        ini_set('memory_limit', '-1');
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
                        "tth_percentage" => $spawnpointStats === 0 ? 0 : $spawnpointStats["percentage"],
                    ];
                    echo json_encode($obj);
                    break;
                case "top":
                    $top10Pokemon = get_top_pokemon(10);
                    $obj = [
                        "top10_pokemon" => $top10Pokemon
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
        case "gyms":
        include_once './includes/GeofenceService.php';
        $geofenceSrvc = new GeofenceService();

// DB table to use
$table = 'gym';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = [
    [
        'db'        => 'id',
        'dt'        => 'DT_RowId',
        'formatter' => function($d, $row) {
            // Technically a DOM id cannot start with an integer, so we prefix
            // a string. This can also be useful if you have multiple tables
            // to ensure that the id is unique with a different prefix
            return 'row_' . $d;
        }
    ],
    [
        'db'        => 'name', 
        'dt'        => 'name'
    ],
    [
        'db'        => 'team_id',
        'dt'        => 'team',
        'formatter' => function($d, $row) {
            $team = get_team($d);
            return '<img src="./static/images/teams/' . strtolower($team) . '.png" height=32 width=32 />&nbsp;' . $team;
        }
    ],
    [
        'db'        => 'availble_slots',
        'dt'        => 'slots',
        'formatter' => function($d, $row) {
            return $d == 0 ? "Full" : $d;
        }
    ],
    [
        'db'        => 'guarding_pokemon_id',
        'dt'        => 'guard',
        'formatter' => function($d, $row) {
            global $config;
            global $pokedex;
            if ($d > 0) {
                return '<img src="' . sprintf($config['urls']['images']['pokemon'], $d) . '" height=32 width=32 />&nbsp;' . $pokedex[$d];
            }
            return "None";
        }
    ],
    [
        'db'        => 'in_battle', 
        'dt'        => 'battle',
        'formatter' => function($d, $row) {
            return $d ? "Under Attack!" : "Safe";
        }
    ],
    [
        'db'        => 'lat',
        'dt'        => 'lat'
    ],
    [
        'db'        => 'lon',
        'dt'        => 'city',
        'formatter' => function($d, $row) {
            global $config;
            global $geofenceSrvc;
            $geofence = $geofenceSrvc->get_geofence($row['lat'], $row['lon']);
            return $geofence->name ?? $config['ui']['unknownValue'];
        }
    ],
    [
        'db'        => 'updated',
        'dt'        => 'updated',
        'formatter' => function($d, $row) {
            global $config;
            return date($config['core']['dateTimeFormat'], $d);
        }
    ]
];
 
// SQL server connection information
$sql_details = [
    'user' => $config['db']['user'],
    'pass' => $config['db']['pass'],
    'db'   => $config['db']['dbname'],
    'host' => $config['db']['host'],
    'port' => $config['db']['port']
];

require('./includes/ssp.class.php');

/*
$city = "Whittier";//$data["columns"][6]["search"]["value"];
file_put_contents("filter.log", print_r($data, true), FILE_APPEND);
$data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);
$data["data"] = array_values(array_filter($data["data"], function($v, $k) {
    return strcasecmp(trim($v["city"]), $city) == 0;
}, ARRAY_FILTER_USE_BOTH));
*/
echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
);
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
        default:
            die();
    }
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
  AND first_seen_timestamp <= ?
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
    global $config;
    $sql = "
SELECT
  id
FROM
  spawnpoint
WHERE
  ST_CONTAINS(ST_GEOMFROMTEXT('POLYGON(($coords))'), point(spawnpoint.lat, spawnpoint.lon))
";
    return execute($sql);
}

function getPokestopNestData($coords) {
    global $config;
    $sql = "
SELECT
  id
FROM
  pokestop
WHERE
  ST_CONTAINS(ST_GEOMFROMTEXT('POLYGON(($coords))'), point(pokestop.lat, pokestop.lon))
";
    return execute($sql);
}

function execute($sql, $mode = PDO::FETCH_COLUMN) {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $result = $pdo->query($sql);
    $data;
    if ($result->rowCount() > 0) {
        $data = $result->fetchAll($mode);
    }
    unset($pdo);
    unset($db);

    return $data;
}
?>