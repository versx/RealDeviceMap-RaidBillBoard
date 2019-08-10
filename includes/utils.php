<?php
require_once './config.php';
require_once './includes/DbConnector.php';

function get_team($team_id) {
    switch ($team_id) {
        case "1": return "Mystic";
        case "2": return "Valor";
        case "3": return "Instinct";
        default:  return "Neutral";
    }
}
function get_gym_stats() {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $sql = "
SELECT
  COUNT(id) AS gyms,
  SUM(team_id=0) AS neutral,
  SUM(team_id=1) AS mystic,
  SUM(team_id=2) AS valor,
  SUM(team_id=3) AS instinct,
  SUM(raid_pokemon_id IS NOT NULL AND 
      name IS NOT NULL AND 
      raid_end_timestamp >= UNIX_TIMESTAMP()) AS raids
FROM
  gym
";
    $result = $pdo->query($sql);
    if ($result->rowcount() > 0) {
        $data = $result->fetchAll()[0];
    }
    unset($pdo);
    unset($db);
 
    return $data;
}
  
function get_pokestop_stats() {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $sql = "
SELECT
  COUNT(id) AS total,
  SUM(lure_expire_timestamp > UNIX_TIMESTAMP() AND lure_id=501) AS normal_lures,
  SUM(lure_expire_timestamp > UNIX_TIMESTAMP() AND lure_id=502) AS glacial_lures,
  SUM(lure_expire_timestamp > UNIX_TIMESTAMP() AND lure_id=503) AS mossy_lures,
  SUM(lure_expire_timestamp > UNIX_TIMESTAMP() AND lure_id=504) AS magnetic_lures,
  SUM(quest_reward_type IS NOT NULL) quests
FROM pokestop
";
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        $data = $result->fetchAll()[0];
    }
    unset($pdo);
    unset($db);
  
    return $data;
}
  
function get_table_count($table) {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $sql = "SELECT count(id) FROM $table";
    $count = $pdo->query($sql)->fetchColumn();
    unset($pdo);
    unset($db);
    
    return $count;
}

function get_pokestop_objects() {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $sql = "SELECT id,lat,lon,name FROM pokestop";
    $result = $pdo->query($sql)->fetchAll();
    unset($pdo);
    unset($db);
  
    return $result;
}

//TODO: Fix spawnpoint timer stats
function get_spawnpoint_stats() {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $sql = "
SELECT 
  COUNT(id) AS total,
  SUM(despawn_sec IS NOT NULL) AS found,
  SUM(despawn_sec IS NULL) AS missing,
  SUM(despawn_sec <= 1800) AS min30,
  SUM(despawn_sec > 1800) AS min60
FROM
  spawnpoint
";
//ROUND(((SELECT found)/(SELECT total) * 100 ), 2) AS percentage
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        $data = $result->fetchAll()[0];
    }
    unset($pdo);
    unset($db);

    return $data;
}

function get_pokemon_stats() {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $sql = "
SELECT
  COUNT(id) AS total,
  SUM(expire_timestamp >= UNIX_TIMESTAMP()) AS active,
  SUM(iv IS NOT NULL) AS iv_total,
  SUM(iv IS NOT NULL && expire_timestamp >= UNIX_TIMESTAMP()) AS iv_active
FROM
  pokemon
";
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        $data = $result->fetchAll()[0];
    }
    unset($pdo);
    unset($db);

    return $data;
}

function get_top_pokemon($today, $hasIV, $limit = 10) {
    global $config;
    $db = new DbConnector($config['db']);
    $pdo = $db->getConnection();
    $where = $today !== false ? " WHERE expire_timestamp >= UNIX_TIMESTAMP(CURDATE())" : "";
    $where = $hasIV !== false ? $where .= " AND iv IS NOT NULL" : $where;
    $sql = "
SELECT
  pokemon_id,
  COUNT(pokemon_id) AS count
FROM
  pokemon
$where
GROUP BY
  pokemon_id
ORDER BY
  count DESC
LIMIT
  $limit;
";
    $result = $pdo->query($sql);
    $data = null;
    if ($result->rowCount() > 0) {
        $data = $result->fetchAll();
    }
    unset($pdo);
    unset($db);

    return $data;
}

function get_raids() {
    global $config;

    $sql = "
SELECT 
  CONVERT_TZ(FROM_UNIXTIME(raid_battle_timestamp)," . "'" . $config['core']['fromTimeZoneOffset'] . "', '" . $config['core']['timeZone'] . "')
    AS starts, 
  CONVERT_TZ(FROM_UNIXTIME(raid_end_timestamp)," . "'" . $config['core']['fromTimeZoneOffset'] . "', '" . $config['core']['timeZone'] . "')
    AS ends, 
  id,
  raid_battle_timestamp,
  raid_end_timestamp,
  lat, 
  lon,
  raid_level,
  raid_pokemon_id, 
  raid_pokemon_move_1,
  raid_pokemon_move_2,
  name,
  team_id,
  ex_raid_eligible,
  updated
FROM
  gym
WHERE
  raid_pokemon_id IS NOT NULL
  AND name IS NOT NULL 
  AND raid_end_timestamp > UNIX_TIMESTAMP()
ORDER BY
  raid_end_timestamp;
";
    return execute($sql, PDO::FETCH_ASSOC);
}

function execute($sql, $mode = PDO::FETCH_ASSOC) {
      global $config;
      $db = new DbConnector($config['db']);
      $pdo = $db->getConnection();
      $result = $pdo->query($sql);
      $data = null;
      if ($result->rowCount() > 0) {
            $data = $result->fetchAll($mode);
      }
      unset($pdo);
      unset($db);
    
      return $data;
}

function get_raid_image($pokemonId, $raidLevel) {
    global $config;
    if ($pokemonId > 0) {
        return sprintf($config['urls']['images']['pokemon'], $pokemonId);
    }
    return sprintf($config['urls']['images']['egg'], $raidLevel);
}

function is_mobile() {
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));  
}

function getMinutesLeft($endTimestamp) {
    $now = new DateTime("now");
    $ends = new DateTime();
    $ends->setTimestamp($endTimestamp);
    $diff = $now->diff($ends);
    $minutes = $diff->format("%I");
    return $minutes;
}

function hasDiscordRole($userRoles, $requiredRoles) {
    if (count($requiredRoles) == 0) {
        return true;
    }
    foreach ($userRoles as $role) {
        if (in_array($role, $requiredRoles)) {
            return true;
        }
    }
    return false;
}

//TODO: Better impl
function getRedirectPage() {
    global $config;
    if ($config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'dashboard';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        $config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'pokemon';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        $config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'raids';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        $config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'gyms';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        $config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'quests';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        $config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'pokestops';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        $config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'nests';
    } else if (!$config['ui']['pages']['dashboard']['enabled'] &&
        !$config['ui']['pages']['pokemon']['enabled'] &&
        !$config['ui']['pages']['raids']['enabled'] &&
        !$config['ui']['pages']['gyms']['enabled'] &&
        !$config['ui']['pages']['quests']['enabled'] &&
        !$config['ui']['pages']['pokestops']['enabled'] &&
        !$config['ui']['pages']['nests']['enabled'] &&
        !$config['ui']['pages']['stats']['enabled']) {
        return 'stats';
    } else {
        return '404';
    }
}

?>