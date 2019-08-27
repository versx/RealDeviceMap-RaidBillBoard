<?php
$html = "
<div class='container'>
<h2 class='page-header text-center' data-i18n='dashboard_title'>Overview</h2>
<div class='card text-center p-1 m-3'>
  <div class='card-header bg-dark text-light'><b data-id='dashboard_overview_header'>Overview</b></div>
  <div class='card-body'>
    <div class='container'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/quests/1.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading pokemon-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_overview_active_pokemon'>Active Pokemon</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/teams/neutral.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading gym-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_overview_gyms'>Total Gyms</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/quests/1402.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading raids-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_overview_active_raids'>Active Raids</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/pokestop.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading pokestop-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_overview_pokestops'>Pokestops</p>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class='card text-center p-1 m-3'>
  <div class='card-header bg-dark text-light'><b data-i18n='dashboard_pokemon_header'>Pokemon</b></div>
  <div class='card-body'>
    <div class='container'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/quests/1.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading total-pokemon-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_pokemon_total'>Total Pokemon</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/quests/1.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading pokemon-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_pokemon_active'>Active Pokemon</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/100.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading total-iv-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_pokemon_total_iv'>Total IVs</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item'>
              <h3 class='pull-right'><img src='./static/images/100.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading active-iv-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_pokemon_active_iv'>Active with IVs</p>
            </a>
          </div>
        </div>
      </div>

      <div class='card-body text-center p-1 m-3'>
      <div class='card-header bg-dark text-light'><b data-i18n='dashboard_pokemon_top10'>Top 10 Pokemon</b></div>
        <ul class='nav nav-tabs justify-content-center' role='tablist'>
          <li class='nav-item'>
            <a class='nav-link active' id='top10-lifetime-tab' data-toggle='tab' href='#top10-lifetime' role='tab' aria-controls='top10-lifetime' aria-selected='true' data-i18n='dashboard_pokemon_top10_lifetime'>Lifetime</a>
          </li>
          <li class='nav-item'>
            <a class='nav-link' id='top10-today-tab' data-toggle='tab' href='#top10-today' role='tab' aria-controls='top10-today' aria-selected='false' data-i18n='dashboard_pokemon_top10_today'>Today</a>
          </li>
          <li class='nav-item'>
            <a class='nav-link' id='top10-iv-tab' data-toggle='tab' href='#top10-iv' role='tab' aria-controls='top10-iv' aria-selected='false' data-i18n='dashboard_pokemon_top10_iv'>IV (Today)</a>
          </li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane fade show active' id='top10-lifetime' role='tabpanel' aria-labelledby='top10-lifetime-tab'>
            <div class='card-body'>
              <div class='container'>
                <div id='top-10-pokemon-lifetime' class='row justify-content-center'></div>
              </div>
            </div>
          </div>
          <div class='tab-pane fade' id='top10-today' role='tabpanel' aria-labelledby='top10-today-tab'>
            <div class='card-body'>
              <div class='container'>
                <div id='top-10-pokemon-today' class='row justify-content-center'></div>
              </div>
            </div>
          </div>
          <div class='tab-pane fade' id='top10-iv' role='tabpanel' aria-labelledby='top10-iv-tab'>
            <div class='card-body'>
              <div class='container'>
                <div id='top-10-pokemon-iv' class='row justify-content-center'></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='card-body text-center p-1 m-3'>
        <div class='card-header bg-dark text-light'><b>Shiny Rates</b></div>
          <div class='tab-content'>
            <div class='tab-pane fade show active' id='shiny-rates' role='tabpanel' aria-labelledby='shiny-rates'>
              <div class='card-body'>
                <div class='container'>
                  <div id='shiny-rates' class='row justify-content-center'></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class='card text-center p-1 m-3'>
  <div class='card-header bg-dark text-light'><b data-i18n='dashboard_teams_header'>Teams</b></div>
  <div class='card-body'>
    <div class='container'>
      <div class='row'>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item neutral'>
              <h3 class='pull-right'><img src='./static/images/teams/neutral.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading neutral-gyms-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_teams_neutral_gyms'>Neutral Gyms</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item valor'>
              <h3 class='pull-right'><img src='./static/images/teams/valor.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading valor-gyms-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_teams_valor_gyms'>Valor Gyms</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item mystic'>
              <h3 class='pull-right'><img src='./static/images/teams/mystic.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading mystic-gyms-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_teams_mystic_gyms'>Mystic Gyms</p>
            </a>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='list-group'>
            <a class='list-group-item instinct'>
              <h3 class='pull-right'><img src='./static/images/teams/instinct.png' width='64' height='64'/></h3>
              <h4 class='list-group-item-heading instinct-gyms-count'>0</h4>
              <p class='list-group-item-text' data-i18n='dashboard_teams_instinct_gyms'>Instinct Gyms</p>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class='card text-center p-1 m-3'>
  <div class='card-header bg-dark text-light'><b data-i18n='dashboard_pokestops_header'>Pokestops</b></div>
  <div class='card-body'>
    <div class='container'>
      <div class='row'>
        <div class='col-md-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/pokestop.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading pokestop-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_total'>Pokestops</p>
          </a>
        </div>
        <div class='col-md-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/lure-module.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading lured-pokestop-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_normal_lures'>Normal Lures</p>
          </a>
        </div>
        <div class='col-md-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/lure-module-glacial.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading lured-glacial-pokestop-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_glacial_lures'>Glacial Lures</p>
          </a>
        </div>
        <div class='col-md-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/lure-module-mossy.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading lured-mossy-pokestop-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_mossy_lures'>Mossy Lures</p>
          </a>
        </div>
        <div class='col-md-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/lure-module-magnetic.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading lured-magnetic-pokestop-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_magnetic_lures'>Magnetic Lures</p>
          </a>
        </div>
        <div class='col-md-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/quests/0.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading quest-pokestop-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_quests'>Field Research</p>
          </a>
        </div>
        <div class='col-md-3'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/invasion.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading invasion-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_pokestops_invasions'>Invasions</p>
          </a>
        </div>
      <div class='card-body text-center p-1 m-3'>
        <div class='card-header bg-dark text-light'><b>Rare Quests</b></div>
          <div class='tab-content'>
            <div class='tab-pane fade show active' id='rare_quests' role='tabpanel' aria-labelledby='rare_quests'>
              <div class='card-body'>
                <div class='container'>
                  <div id='rare-quests' class='row justify-content-center'></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class='card text-center p-1 m-3'>
  <div class='card-header bg-dark text-light'><b data-i18n='dashboard_tth_header'>Spawnpoint Timers</b></div>
  <div class='card-body'>
    <div class='container'>
      <div class='row'>
        <div class='col-lg-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/spawnpoint.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading spawnpoint-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_tth_total'>Total</p>
          </a>
        </div>
        <div class='col-lg-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/found.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading found-spawnpoint-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_tth_found'>Found</p>
          </a>
        </div>
        <div class='col-lg-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/missing.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading missing-spawnpoint-count'>0</h4>
            <p class='list-group-item-text' data-i18n='dashboard_tth_missing'>Missing</p>
          </a>
        </div>
        <div class='col-lg-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/percentage.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading percentage-spawnpoint-count'>0%</h4>
            <p class='list-group-item-text' data-i18n='dashboard_tth_percentage'>Percentage</p>
          </a>
        </div>
        <div class='col-lg-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/30min.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading timer-30-min-count'>0%</h4>
            <p class='list-group-item-text' data-i18n='dashboard_tth_30min'>30 min timers</p>
          </a>
        </div>
        <div class='col-lg-4 p-1'>
          <a class='list-group-item'>
            <h3 class='pull-right'><img src='./static/images/60min.png' width='64' height='64'/></h3>
            <h4 class='list-group-item-heading timer-60-min-count'>0%</h4>
            <p class='list-group-item-text' data-i18n='dashboard_tth_60min'>60 min timers</p>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
";
echo $html;
?>
<link rel="stylesheet" href="./static/css/dashboard.css"/>
<script type="text/javascript" src="./static/js/pokedex.js"></script>
<script type="text/javascript" src="./static/js/utils.js"></script>
<script type="text/javascript">
var debug = <?=$config['core']['showDebug'] !== false ? '1' : '0'?>;
getStats();

function getStats() {
  var tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "pokemon", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    updateCounter(".pokemon-count", obj.active_pokemon);
    updateCounter(".total-pokemon-count", obj.pokemon);
    updateCounter(".total-iv-count", obj.iv_total);
    updateCounter(".active-iv-count", obj.iv_active);
  });

  tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "gyms", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    updateCounter(".gym-count", obj.gyms);
    updateCounter(".raids-count", obj.raids);
    updateCounter(".neutral-gyms-count", obj.neutral);
    updateCounter(".valor-gyms-count", obj.valor);
    updateCounter(".mystic-gyms-count", obj.mystic);
    updateCounter(".instinct-gyms-count", obj.instinct);
  });

  tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "pokestops", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    updateCounter(".pokestop-count", obj.pokestops);
    updateCounter(".lured-pokestop-count", obj.normal_lures);
    updateCounter(".lured-glacial-pokestop-count", obj.glacial_lures);
    updateCounter(".lured-mossy-pokestop-count", obj.mossy_lures);
    updateCounter(".lured-magnetic-pokestop-count", obj.magnetic_lures);
    updateCounter(".quest-pokestop-count", obj.quests);
    updateCounter(".invasion-count", obj.invasions);
  });

  tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "tth", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    var percentage = Math.round(((obj.tth_found / obj.tth_total) * 100), 2);
    updateCounter(".spawnpoint-count", obj.tth_total);
    updateCounter(".found-spawnpoint-count", obj.tth_found);
    updateCounter(".missing-spawnpoint-count", obj.tth_missing);
    updateCounter(".percentage-spawnpoint-count", percentage);
    updateCounter(".timer-30-min-count", obj.tth_30min);
    updateCounter(".timer-60-min-count", obj.tth_60min);
  });

  tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "top", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    var html = "";
    var count = 0;
    $.each(obj.top10_pokemon, function(key, value) {
      if (count == 0) {
        html += "<div class='row justify-content-center'>";
      }
      var name = pokedex[value.pokemon_id];
      var pkmnImage = sprintf("<?=$config['urls']['images']['pokemon']?>", value.pokemon_id);
      html += "<div class='col-md-2" + (count == 0 ? " col-md-offset-1" : "") + "'>";
      html += "<img src='" + pkmnImage + "' width='64' height='64'><span class='text-nowrap'>" + name + ": " + numberWithCommas(value.count) + "</span></br>";
      html += "</div>";
      if (count == 4) {
        html += "</div>";
        count = 0;
      } else {
        count++;
      }
    });
    $('#top-10-pokemon-lifetime').html(html);

    html = "";
    count = 0;
    $.each(obj.top10_pokemon_today, function(key, value) {
      if (count == 0) {
        html += "<div class='row justify-content-center'>";
      }
      var name = pokedex[value.pokemon_id];
      var pkmnImage = sprintf("<?=$config['urls']['images']['pokemon']?>", value.pokemon_id);
      html += "<div class='col-md-2" + (count == 0 ? " col-md-offset-1" : "") + "'>";
      html += "<img src='" + pkmnImage + "' width='64' height='64'><span class='text-nowrap'>" + name + ": " + numberWithCommas(value.count) + "</span></br>";
      html += "</div>";
      if (count == 4) {
        html += "</div>";
        count = 0;
      } else {
        count++;
      }
    });
    $('#top-10-pokemon-today').html(html);

    html = "";
    count = 0;
    $.each(obj.top10_pokemon_iv, function(key, value) {
      if (count == 0) {
        html += "<div class='row justify-content-center'>";
      }
      var name = pokedex[value.pokemon_id];
      var pkmnImage = sprintf("<?=$config['urls']['images']['pokemon']?>", value.pokemon_id);
      html += "<div class='col-md-2" + (count == 0 ? " col-md-offset-1" : "") + "'>";
      html += "<img src='" + pkmnImage + "' width='64' height='64'><span class='text-nowrap'>" + name + ": " + numberWithCommas(value.count) + "</span></br>";
      html += "</div>";
      if (count == 4) {
        html += "</div>";
        count = 0;
      } else {
        count++;
      }
    });
    $('#top-10-pokemon-iv').html(html);
  });

  tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "rare", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    var html = "";
    var count = 0;
    $.each(obj.top_quests, function(key, value) {
      if (count == 0) {
        html += "<div class='row justify-content-center'>";
      }
      var name = pokedex[value.quest_pokemon_id];
      var pkmnImage = sprintf("<?=$config['urls']['images']['pokemon']?>", value.quest_pokemon_id);
      html += "<div class='col-md-2" + (count == 0 ? " col-md-offset-1" : "") + "'>";
      html += "<img src='" + pkmnImage + "' width='64' height='64'><p><span class='text-nowrap'>" + name + ": " + numberWithCommas(value.count) + "</span></p></br>";
      html += "</div>";
      if (count == 4) {
        html += "</div>";
        count = 0;
      } else {
        count++;
      }
    });
    $('#rare-quests').html(html);
  });

  tmp = createToken();
  sendRequest({ "type": "dashboard", "stat": "shiny", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data === 0) {
        console.log("Failed to get data for dashboard.");
        return;
      } else {
        console.log("Dashboard:", data);
      }
    }
    var obj = JSON.parse(data);
    var html = "";
    var count = 0;
    $.each(obj.shiny_rates, function(key, value) {
      if (count == 0) {
        html += "<div class='row justify-content-center'>";
      }
      var name = pokedex[value.pokeid];
      var pkmnImage = sprintf("<?=$config['urls']['images']['pokemon']?>", value.pokeid);
      html += "<div class='col-md-2" + (count == 0 ? " col-md-offset-1" : "") + "'>";
      html += "<img src='" + pkmnImage + "' width='64' height='64'><p><span class='text-nowrap'>" + name + ": " + numberWithCommas(value.count) + "</span>";
      html += "<span class='text-nowrap'><br>(" + numberWithCommas(((value.count/value.total)*100).toFixed(3)) + "%)</span></p></br>";
      html += "</div>";
      if (count == 4) {
        html += "</div>";
        count = 0;
      } else {
        count++;
      }
    });
    $('#shiny-rates').html(html);
  });
}

/*
var tmp = createToken();
sendRequest({ "type": "dashboard", "token": tmp }, function(data, status) {
  tmp = null;
  if (debug) {
    if (data === 0) {
      console.log("Failed to get data for dashboard.");
      return;
    } else {
      console.log("Dashboard:", data);
    }
  }
  var obj = JSON.parse(data);
  updateCounter(".pokemon-count", obj.active_pokemon);
  updateCounter(".gym-count", obj.gyms);
  updateCounter(".raids-count", obj.raids);
  updateCounter(".total-pokemon-count", obj.pokemon);
  updateCounter(".total-iv-count", obj.iv_total);
  updateCounter(".active-iv-count", obj.iv_active);
  updateCounter(".neutral-gyms-count", obj.neutral);
  updateCounter(".valor-gyms-count", obj.valor);
  updateCounter(".mystic-gyms-count", obj.mystic);
  updateCounter(".instinct-gyms-count", obj.instinct);
  updateCounter(".pokestop-count", obj.pokestops);
  updateCounter(".lured-pokestop-count", obj.lured);
  updateCounter(".quest-pokestop-count", obj.quests);
  updateCounter(".spawnpoint-count", obj.tth_total);
  updateCounter(".found-spawnpoint-count", obj.tth_found);
  updateCounter(".missing-spawnpoint-count", obj.tth_missing);
  updateCounter(".percentage-spawnpoint-count", obj.tth_percentage);

  var html = "";
  var count = 0;
  $.each(obj.top10_pokemon, function(key, value) {
    if (count == 0) {
      html += "<div class='row justify-content-center'>";
    }
    var name = pokedex[value.pokemon_id];
    var pkmnImage = sprintf("<?=$config['urls']['images']['pokemon']?>", value.pokemon_id);
    html += "<div class='col-md-2" + (count == 0 ? " col-md-offset-1" : "") + "'>";
    html += "<img src='" + pkmnImage + "' width='64' height='64'><span class='text-nowrap'>" + name + ": " + numberWithCommas(value.count) + "</span></br>";
    html += "</div>";
    if (count == 4) {
      html += "</div>";
      count = 0;
    } else {
      count++;
    }
  });
  $('#top-10-pokemon').html(html);
});
*/

function createToken() {
  //TODO: Secure
  <?php $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16)); ?>
  return "<?php echo $_SESSION['token']; ?>";
}
</script>
