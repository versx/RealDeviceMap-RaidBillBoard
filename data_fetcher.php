<?php
require_once './vendor/autoload.php';
require_once './config.php';
require_once './pokedex.php';
require_once './movesets.php';
require_once './geofence_service.php';

$geofence_srvc = new GeofenceService();

// Establish connection to database
try {
  $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;port=$dbPort", $dbuser, $dbpass);
  // Set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("ERROR: Could not connect. " . $e->getMessage());
}
// Query Database and Build Raid Billboard
try {
  $sql = "
SELECT 
    time_format(from_unixtime(raid_battle_timestamp), '%h:%i:%s %p')
        AS starts, 
    time_format(from_unixtime(raid_end_timestamp),'%h:%i:%s %p')
        AS ends, 
    lat, 
    lon,
    raid_level,
    raid_pokemon_id, 
    raid_pokemon_move_1,
    raid_pokemon_move_2,
    name,
    team_id,
    ex_raid_eligible
FROM 
    gym
WHERE
    raid_pokemon_id IS NOT NULL && 
    name IS NOT NULL &&
    raid_end_timestamp >= UNIX_TIMESTAMP()
ORDER BY 
    raid_end_timestamp;
";

  $result = $pdo->query($sql);
  if ($result->rowCount() > 0) {
    echo $filters;
    echo "<table class='table table-".$table_style." ".($table_striped ? 'table-striped' : null)."' border='1' id='gym-table';>";
    echo "<thead class='thead-".$table_header_style."'>";
    echo "<tr>";
        echo "<th>Raid Starts</th>";
        echo "<th>Raid Ends</th>";
        echo "<th>Raid Level</th>";
        echo "<th>Raid Boss</th>";
        echo "<th>Moveset</th>";
        echo "<th>City</th>";
        echo "<th>Team</th>";
        echo "<th>Ex-Eligible</th>";
        echo "<th>Gym</th>";
    echo "</tr>";
    echo "</thead>";
    while ($row = $result->fetch()) {
      $geofence = $geofence_srvc->get_geofence($row['lat'], $row['lon']);
      $city = ($geofence == null ? $unknown_value : $geofence->name);
      $map_link = sprintf($googleMapsLink, $row["lat"], $row["lon"]);
      $pokemon = $pokedex[$row['raid_pokemon_id']];
      $fast_move = $quick_moves[$row['raid_pokemon_move_1']];
      $charge_move = $charge_moves[$row['raid_pokemon_move_2']];
      $moveset = (($fast_move == $unknown_value && $charge_move == $unknown_value) ? $unknown_value : $fast_move . "/" . $charge_move);
      echo "<tr>";
        echo "<td scope='row'><a title='Remove' data-toggle='tooltip' class='delete'>X&nbsp;</a>" . $row['starts'] . "</td>";
        echo "<td>" . $row['ends'] . "</td>";
        echo "<td>" . $row['raid_level'] . "</td>";
        echo "<td>" . $pokemon . "</td>";
        echo "<td>" . $moveset . "</td>";
        echo "<td>" . $city . "</td>";
        echo "<td>" . get_team($row['team_id']) . "</td>";
        echo "<td>" . ($row['ex_raid_eligible'] ? "Yes" : "No") . "</td>";
        echo "<td><a href='" . $map_link . "' target='_blank'>" . $row['name'] . "</a></td>";
      echo "</tr>";
    }
    echo "</table>";

  // Free result set
  unset($result);
  } else{
    echo "<p>No raids available, come back tomorrow after 5am.</p>";
  }
} catch(PDOException $e){
  die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
// Close connection
unset($pdo);

function get_team($team_id) {
  switch ($team_id) {
    case "1": return "Mystic";
    case "2": return "Valor";
    case "3": return "Instinct";
    default:  return "Neutral";
  }
}
?>
