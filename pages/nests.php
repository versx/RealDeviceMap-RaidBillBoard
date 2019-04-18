<?php
require_once './config.php';
require_once './includes/GeofenceService.php';
require_once './static/data/pokedex.php';
$osm = $config['ui']['pages']['nests']['type'] === 'osm';
?>

<h2 class='page-header text-center' data-i18n='nests_title'>Neighborhood nests</h2>
<p id="migration" class='text-center'></p>
<ul class='nav nav-pills mb-3 justify-content-center' role='tablist'>
<?php if ($osm) { ?>
  <li class='nav-item'><a class='nav-link <?=!$osm ? '' : 'active'?>' role='tab' aria-controls='visual' aria-selected='true' data-toggle='pill' href='#visual' data-i18n='nests_tab_map'>Map</a></li>
  <li class='nav-item'><a class='nav-link' role='tab' aria-controls='table' aria-selected='false' data-toggle='pill' href='#table' data-i18n='nests_tab_table'>Table</a></li>
<?php } else { ?>
  <li class='nav-item'><a class='nav-link <?=$osm ? '' : 'active'?>' role='tab' aria-controls='pmsf' aria-selected='false' data-toggle='pill' href='#pmsf' data-i18n='nests_tab_pmsf' hidden>PMSF</a></li>
<?php } ?>
</ul>

<center>
<div class='container m-2'>
  <div class='tab-content'>
    <div id='visual' class='tab-pane fade <?=!$osm ? '' : 'show active'?>' role='tabpanel'>
      <button id="nest-refresh" class="btn btn-secondary m-2" data-i18n='nests_button_refresh'>Refresh Nests</button>
      <button id="getAllNests" class="btn btn-secondary m-2" data-i18n='nests_button_report'>Nests Report</button>
      <div id='mapid' style='width: 100%; height: 600px;'></div>
      <div class="modal" id="modalSpawnReport" tabindex="-1" role="dialog" aria-labelledby='nestModalLabel' aria-hidden='true'>
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="nestModalLabel"></h5>
              <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>  
            <div class="modal-body">
              <div style="height: 450px !important; overflow-y: scroll;">
              <table class='table table-sm table-<?=$config['ui']['table']['style']?> <?=($config['ui']['table']['striped'] ? 'table-striped' : null)?>' id="spawnReportTable">
                <thead class='thead-<?=$config['ui']['table']['headerStyle']?>'>
                  <tr>
                    <th scope="col" data-i18n='nests_column_pokemon'>Pokemon</th>
                    <th scope="col" data-i18n='nests_column_count'>Count</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <table class="table table-sm" id="spawnReportTableMissed">
                <tbody>
                </tbody>
              </table>
              </div>
            </div>
            <div class="modal-footer">
              <span id='nestsComplete' class='text-left'></span>
              <button id="exportNests" class="btn btn-primary m-2" data-i18n='nests_button_export'>Export Nests</button>
              <button type="button" class="btn btn-secondary closeModal" data-dismiss="modal" data-i18n='nests_modal_close'>Close</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal modal-loader" id="modalLoading" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-sm">
          <div class="modal-content" style="width: 48px">
            <span class="fa fa-spinner fa-spin fa-3x"></span>
          </div>
        </div>
      </div>
    </div>

    <div id='table' class='tab-pane fade' role='tabpanel'>
      <table class='table table-sm table-<?=$config['ui']['table']['style']?> <?=($config['ui']['table']['striped'] ? 'table-striped' : null)?>' id="nestReportTable">
        <thead class='thead-<?=$config['ui']['table']['headerStyle']?>'>
          <tr>
            <th scope="col" data-i18n='nests_column_park'>Park</th>
            <th scope="col" data-i18n='nests_column_pokemon'>Pokemon</th>
            <th scope="col" data-i18n='nests_column_count'>Count</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div id='pmsf' class='tab-pane fade <?=$osm ? '' : 'show active'?>' role='tabpanel'>
<?php

if ($osm === false) {

  $geofenceSrvc = new GeofenceService();
  $filters = "
  <div class='container'>
    <div class='row'>
      <div class='input-group mb-3'>
        <div class='input-group-prepend'>
          <label class='input-group-text' for='filter-nest' data-i18n='nests_filter_nest'>Park</label>
        </div>
        <input type='text' id='filter-nest' class='form-control input-lg' onkeyup='filter_nests()' placeholder='Search by park name...' title='Type in a park name'></input>
      </div>
      <div class='input-group mb-3'>
        <div class='input-group-prepend'>
          <label class='input-group-text' for='filter-pokemon' data-i18n='nests_filter_pokemon'>Pokemon</label>
        </div>
        <input type='text' id='filter-pokemon' class='form-control input-lg' onkeyup='filter_nests()' placeholder='Search by Pokemon name...' title='Type in a Pokemon name'></input>
      </div>
      <div class='input-group mb-3'>
        <div class='input-group-prepend'>
          <label class='input-group-text' for='filter-city' data-i18n='nests_filter_city'>City</label>
        </div>
        <select multiple id='filter-city' class='custom-select' onchange='filter_nests()'>
          <option value='' selected>All</option>";
          $count = count($geofenceSrvc->geofences);
          for ($i = 0; $i < $count; $i++) {
              $geofence = $geofenceSrvc->geofences[$i];
              $filters .= "<option value='".$geofence->name."'>".$geofence->name."</option>";
          }
          $filters .= "
          <option value='" . $config['ui']['unknownValue'] . "'>" . $config['ui']['unknownValue'] . "</option>
        </select>
      </div>
    </div>
  </div>
";
  
  $modal = "
  <div class='btn-group btn-group-sm float-right'>
    <button type='button' class='btn btn-dark' data-toggle='modal' data-target='#filtersModal'>
      <i class='fa fa-fw fa-filter' aria-hidden='true'></i>
    </button>
  </div>
  <p>&nbsp;</p>
  <div class='modal fade' id='filtersModal' tabindex='-1' role='dialog' aria-labelledby='filtersModalLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h5 class='modal-title' id='filtersModalLabel' data-i18n='nests_filters_title'>Nest Filters</h5>
          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>
        </div>
        <div class='modal-body'>" . $filters . "</div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-danger' id='reset-filters' data-i18n='nests_modal_reset_filters'>Reset Filters</button>
          <button type='button' class='btn btn-primary' data-dismiss='modal' data-i18n='nests_modal_close'>Close</button>
        </div>
      </div>
    </div>
  </div>
";

  $geofenceSrvc = new GeofenceService();

  // Establish connection to database
  $db = new DbConnector($config['ui']['pages']['nests']['db']);
  $pdo = $db->getConnection();

// Query Database and Build Raid Billboard
try {
    $sql = "
SELECT 
  lat,
  lon,
  name,
  pokemon_id,
  pokemon_count,
  ROUND(pokemon_avg) AS avg,
  updated
FROM
  nests
WHERE
  name IS NOT NULL
  AND pokemon_count > 1
ORDER BY
  pokemon_count DESC;
";

    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        echo $modal;
        echo "<div id='no-more-tables'>";
        echo "<table id='nest-table' class='table table-".$config['ui']['table']['style']." ".($config['ui']['table']['striped'] ? 'table-striped' : null)."' border='1'>";
        echo "<thead class='thead-".$config['ui']['table']['headerStyle']."'>";
        echo "<tr class='text-nowrap'>";
            echo "<th class='park' data-i18n='nests_column_park'>Park</th>";
            echo "<th class='pokemon' data-i18n='nests_column_pokemon'>Pokemon</th>";
            echo "<th class='count' data-i18n='nests_column_count'>Count</th>";
            echo "<th class='average' data-i18n='nests_column_average'>Average</th>";
            echo "<th class='city' data-i18n='nests_column_city'>City</th>";
        echo "</tr>";
        echo "</thead>";
        while ($row = $result->fetch()) {	
            $geofence = $geofenceSrvc->get_geofence($row['lat'], $row['lon']);
            if ($geofence == null && $config['ui']['pages']['nests']['ignoreUnknown'] !== false) {
                continue;
            }
            $city = ($geofence == null ? $config['ui']['unknownValue'] : $geofence->name);
            $map_link = sprintf($config['google']['maps'], $row["lat"], $row["lon"]);
            $pokemon_id = $row['pokemon_id'];
            $pokemon = $pokedex[$pokemon_id];

            echo "<tr class='text-nowrap'>";
                echo "<td data-title='Park'><a href='" . $map_link . "' target='_blank'>" . $row['name'] . "</a></td>";
                echo "<td data-title='Pokemon'><img src='" . sprintf($config['urls']['images']['pokemon'], $pokemon_id) . "' height=32 width=32 />&nbsp;" . $pokemon . "</td>";
                echo "<td data-title='Count'>" . $row['pokemon_count'] . "</td>";
                echo "<td data-title='Average'>" . $row['avg'] . "</td>";
                echo "<td data-title='City'>" . $city . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
	  	
        // Free result set
        unset($result);
    } else {
        echo "<p>No nests found.</p>";
    }
} catch (PDOException $e) {
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
// Close connection
unset($pdo);
unset($db);
}
?>
    </div>
  </div>
</div>
</center>

<script type='text/javascript' src='./static/js/jquery.countdown.min.js'></script>
<script type='text/javascript' src='./static/js/pokedex.js'></script>
<script type='text/javascript' src="https://cdn.jsdelivr.net/npm/@turf/turf@5/turf.min.js"></script>
<script type='text/javascript' src="https://cdn.jsdelivr.net/npm/osmtogeojson@3.0.0-beta.3/osmtogeojson.js"></script>

<script type='text/javascript'>
if (get("nests-filter-nest") !== false) {
  $('#filter-nest').val(get("nests-filter-nest"));
}
if (get("nest-filter-pokemon") !== false) {
  $('#filter-pokemon').val(get("nests-filter-pokemon"));
}
if (get("nests-filter-city") !== false) {
  $('#filter-city').val(JSON.parse(get("nests-filter-city")));
}

filter_nests();

$('#reset-filters').on('click', function() {
  if (confirm($.i18n('nests_filters_reset_confirm'))) {
    $('#filter-nest').val('');
    $('#filter-pokemon').val('');
    $('#filter-city').val('');
    filter_nests();
  }
});


/*
$('#modalSpawnReport').on('hidden.bs.modal', function(event) {
  $('#spawnReportTable > tbody').empty();
  $('#spawnReportTableMissed > tbody').empty();
  $('#modalSpawnReport .modal-title').text();
});
*/
$(document).on('click', '.closeModal', function() { //TODO: Temp workaround, fix modal not closing.
  $('#modalSpawnReport').hide();
  $('#spawnReportTable > tbody').empty();
  $('#spawnReportTableMissed > tbody').empty();
  $('#modalSpawnReport .modal-title').text();
});

$(document).on("click", ".deleteLayer", function() {
  var id = $(this).attr('data-layer-id');
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'nestLayer':
      nestLayer.removeLayer(parseInt(id));
      break;
  }
});

var debug = <?=$config['core']['showDebug'] !== false ? '1' : '0'?>;
var lastMigrationDate = new Date("<?=$config['core']['lastNestMigration']?>");
var migrationDate = new Date("<?=$config['core']['lastNestMigration']?>");
while (migrationDate < new Date()) {
  migrationDate = addDays(migrationDate, 14);
}

$("#migration").countdown(migrationDate, function(event) {
  var msg = "The next <b>nest migration</b> occurs in<br/> ";
  var time = event.strftime("%w %!w:<span>week</span>,<span>weeks</span>;, %d %!d:<span>day</span>,<span>days</span>;, %H %!H:<span>hour</span>,<span>hours</span>;, %M %!M:<span>minute</span>,<span>minutes</span>;, and %S %!S:<span>second</span>,<span>seconds</span>;");
  $(this).html(msg + time + "<br/>on<br/><span>" + moment(migrationDate).format("dddd MMMM Do YYYY, h:mm:ss A") + "</span>");
});

var mymap = L.map('mapid').setView(<?=json_encode($config['core']['startupLocation'])?>, <?=$config['core']['startupZoom']?>);
var tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
  maxZoom: 18,
  attribution: '',
  id: 'mapbox.streets'
}).addTo(mymap);

var nestLayer = new L.LayerGroup();
nestLayer.addTo(mymap);

if ('<?=$osm?>') {
  getNests();
}

$("#nest-refresh").on("click", getNests);

$(document).on("click", ".getSpawnReport", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }

  getSpawnReport(layer);
});

$('#getAllNests').on('click', getAllNestsReport);

function getNests() {//p1_lat, p1_lon, p2_lat, p2_lon) {
  nestLayer.clearLayers();
  const bounds = mymap.getBounds();
  const overpassApiEndpoint = 'https://overpass-api.de/api/interpreter';
  var queryBbox = [ // s, e, n, w
    bounds.getSouthWest().lat,
    bounds.getSouthWest().lng,
    bounds.getNorthEast().lat,
    bounds.getNorthEast().lng
  ].join(',');
  var queryDate = "2018-04-09T01:32:00Z";
  var queryOptions = [
    '[out:json]',
    '[bbox:' + queryBbox + ']',
    '[date:"' + queryDate + '"]'
  ].join('');
  var queryNestWays = [
/*
    'way["leisure"="park"];',
    'way["leisure"="recreation_ground"];',
    'way["landuse"="recreation_ground"];'
*/
    'way["landuse"="farmland"];',
    'way["landuse"="farmyard"];',
    'way["landuse"="grass"];',
    'way["landuse"="greenfield"];',
    'way["landuse"="meadow"];',
    'way["landuse"="orchard"];',
    'way["landuse"="recreation_ground"];',
    'way["landuse"="vineyard"];',
    'way["leisure"="garden"];',
    'way["leisure"="golf_course"];',
    'way["leisure"="park"];',
    'way["leisure"="pitch"];',
    'way["leisure"="playground"];',
    'way["leisure"="recreation_ground"];',
    'way["natural"="grassland"];',
    'way["natural"="heath"];',
    'way["natural"="scrub"];'
  ].join('');
  var overPassQuery = queryOptions + ';(' + queryNestWays + ')' + ';out;>;out skel qt;';
  var debug = true;
  if (debug !== false) {
    console.log(overPassQuery);
  }
  
  $.ajax({
    beforeSend: function() {
      $("#modalLoading").show();
    },
    url: overpassApiEndpoint,
    type: 'GET',
    dataType: 'json',
    data: {'data': overPassQuery},
    success: function (result) {
      if (debug) {
        if (result === 0) {
          console.log("Failed to get osm nest data.");
          return;
        } else {
          console.log("Nests:", result);
        }
      }
      var geoJsonFeatures = osmtogeojson(result);
      geoJsonFeatures.features.forEach(function(feature) {
        feature = turf.flip(feature);
        var polygon = L.polygon(feature.geometry.coordinates, {
          clickable: false,
          color: "#ff8833",
          fill: true,
          fillColor: null,
          fillOpacity: 0.2,
          opacity: 0.5,
          stroke: true,
          weight: 4
        });
        polygon.tags = {};
        polygon.tags.name = feature.properties.tags.name;
        polygon.addTo(nestLayer);
        polygon.bindPopup(function (layer) {
          if (typeof layer.tags.name !== 'undefined') {
            var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Nest: ' + layer.tags.name + '</span></div>';
          }
          var output = name +
                  '<div class="input-group mb-3 justify-content-center"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Get Spawn Report</button><div class="input-group-append"></div></div>';/* +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
                  '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>';*/
          return output;
        });
      });
    },
    complete: function() {
      $("#modalLoading").hide();
    }
  });
}

function getSpawnReport(layer) {
  var poly = layer.toGeoJSON();
  var coords = poly.geometry.coordinates;
  var flatCoords = flattenCoordinates(coords);
  const data = {
    'coordinates': flatCoords,
    'nest_migration_timestamp': lastMigrationDate.getTime() / 1000,
    'spawn_report_limit': 5,
  };

  var tmp = createToken();
  $.ajax({
    beforeSend: function() {
      $("#modalLoading").show();
    },
    url: "api.php",
    type: 'POST',
    dataType: 'json',
    data: JSON.stringify({'data': data, 'type': 'nests', 'token': tmp}),
    success: function (result, success) {
      tmp = null;
      if (debug) {
        if (result === 0) {
          console.log("Failed to get nest spawn report data.");
          return;
        } else {
          console.log("Spawn report:", result);
        }
      }
      if (result.spawns !== null && typeof result.spawns !== 'undefined') {
        result.spawns.forEach(function(item) {
          if (typeof layer.tags !== 'undefined') {
            $('#modalSpawnReport  .modal-title').text($.i18n('nests_modal_spawn_report') + ' - ' + layer.tags.name);
          }
          $('#spawnReportTable > tbody:last-child').append('<tr><td><img src="' + sprintf("<?=$config['urls']['images']['pokemon']?>", item.pokemon_id) + '" width=32 height=32 />&nbsp;' + pokedex[item.pokemon_id] + '</td><td>' + item.count + '</td></tr>');
        });
      } else {
        if (typeof layer.tags !== 'undefined') {
          $('#modalSpawnReport  .modal-title').text($.i18n('nests_modal_spawn_report') + ' - ' + layer.tags.name);
        }
        $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2" data-i18n="nests_no_data_available"></td></tr>');
      }
    },
    complete: function() {
      $("#modalLoading").hide();
      $('#modalSpawnReport').show();
    },
    error: function(data) {
      console.log("ERROR:", data);
    }
  });
}

$('#exportNests').on('click', function() {
  nests.sort(function(a, b) {
    var nameA = a.park.toLowerCase();
    var nameB = b.park.toLowerCase();
    if (nameA < nameB) {
      return -1;
    }
    if (nameA > nameB) {
      return 1;
    }
    return 0;
  });
  var text = "data:text/csv;charset=utf-8,";
  nests.forEach(function(nest) {
    var gmapsUrl = "\"https://maps.google.com/maps?q=" + nest.lat + "," + nest.lon + "\"";
    text += nest.nest + "," + nest.pokemon + "," + nest.count + "," + gmapsUrl + "\r\n";
  });
  var encodedUri = encodeURI(text);
  window.open(encodedUri);
});

var nests = [];
function getAllNestsReport() {
  var nestMigrationDate = lastMigrationDate.getTime() / 1000;  
  var missedCount = 0;
  nests = [];
  nestLayer.eachLayer(function(layer) {
    var center = layer.getBounds().getCenter()
    var poly = layer.toGeoJSON();
    var coords = poly.geometry.coordinates;
    var flatCoords = flattenCoordinates(coords);
    const data = {
      'coordinates': flatCoords,
      'nest_migration_timestamp': lastMigrationDate.getTime() / 1000,
      'spawn_report_limit': 1,
    };
    
    var tmp = createToken();
    $.ajax({
      beforeSend: function() {
        $("#modalLoading").modal('show');
      },
      url: 'api.php',
      type: 'POST',
      dataType: 'json',
      data: JSON.stringify({'data': data, 'type': 'nests', 'token': tmp}),
      success: function (result) {
        tmp = null;
        if (debug) {
          if (result === 0) {
            console.log("Failed to get nest spawn report data.");
            return;
          } else {
            if (result.error) {
              console.log("ERROR:", result.message);
              return;
            } else {
              console.log("Spawn report:", result);
            }
          }
        }
        if (result.spawns !== null) {
          /*
          if (typeof layer.tags.name !== 'undefined' && typeof result.spawns !== 'undefined') {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>' + layer.tags.name + '</strong> <em style="font-size:xx-small">at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          } else {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>Unnamed</strong> at <em style="font-size:xx-small">' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          }
          */
          if (typeof result.spawns !== 'undefined') {
            result.spawns.forEach(function(item) {
              //$('#spawnReportTable > tbody:last-child').append('<tr><td>' + pokedex[item.pokemon_id] + '</td><td>' + item.count + '</td></tr>');
              $('#spawnReportTable > tbody:last-child').append('<tr><td><strong>' + layer.tags.name + '</strong> <em style="font-size:xx-small">at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td><td>' + pokedex[item.pokemon_id] + '</td><td>' + item.count + '</td></tr>');
              $('#nestReportTable > tbody:last-child').append('<tr><td><strong>' + layer.tags.name + '</strong> <em style="font-size:xx-small">at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td><td>' + pokedex[item.pokemon_id] + '</td><td>' + item.count + '</td></tr>');
              nests.push({nest: layer.tags.name, pokemon: pokedex[item.pokemon_id], count: item.count, lat: center.lat.toFixed(4), lon: center.lng.toFixed(4)});
            });
          }
        } /*else {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>' + layer.tags.name + '</strong>  at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + ' skipped, no data</em></td></tr>');
          } else {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>Unnamed</strong> at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + ' skipped, no data</em></td></tr>');
          }
        }*/
      },
      complete: function() {
        $("#modalLoading").modal('hide');
        $('#modalSpawnReport  .modal-title').text('Nest Report - All Nests in View');
        $('#modalSpawnReport').modal('show');
      },
      error: function(data) {
        console.log("ERROR:", data);
      }
    });
  });
  //$('#nestsComplete').show();
  $('#nestsComplete').text('Complete!');
}

function flattenCoordinates(coords) {
  var flatCoords = "";
  for (var i = 0; i < coords[0].length; i++) {
    var coord = coords[0][i];
    if (typeof coord[0] !== 'undefined' && typeof coord[1] !== 'undefined') {
      flatCoords += coord[1] + " " + coord[0];
      if (i == coords[0].length - 1) {
        break;
      }
      flatCoords += ",";
    }
  }
  return flatCoords;
}

function createToken() {
  //TODO: Secure
  <?php $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16)); ?>
  return "<?=$_SESSION['token']?>";
}
</script>
<style>
.modal-loader .modal-dialog{
  display: table;
  position: relative;
  margin: 0 auto;
  top: calc(50% - 24px);
}
.modal-loader .modal-dialog .modal-content{
  background-color: transparent;
  border: none;
}
</style>