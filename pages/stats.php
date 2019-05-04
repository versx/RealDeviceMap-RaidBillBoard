<?php
require_once './config.php';
include_once './static/data/pokedex.php';

$html = "
<h2 class='page-header text-center' data-i18n='stats_title'>Statistics</h2>
<ul class='nav nav-pills mb-3 justify-content-center' role='tablist'>
  <li class='nav-item'><a class='nav-link active' role='tab' aria-controls='pokemon' aria-selected='true' data-toggle='pill' href='#pokemon' data-i18n='stats_tab_pokemon'>Pokemon</a></li>
  <li class='nav-item'><a class='nav-link' role='tab' aria-controls='raids' aria-selected='false' data-toggle='pill' href='#raids' data-i18n='stats_tab_raids'>Raids</a></li>
  <li class='nav-item'><a class='nav-link' role='tab' aria-controls='quests' aria-selected='false' data-toggle='pill' href='#quests' data-i18n='stats_tab_quests'>Quests</a></li>
  <li class='nav-item'><a class='nav-link' role='tab' aria-controls='comday' aria-selected='false' data-toggle='pill' href='#comday' data-i18n='stats_tab_comday'>Comm. Day</a></li>
</ul>

<div class='container'>
  <div class='tab-content'>
    <div id='pokemon' class='tab-pane fade show active' role='tabpanel'>
      <div class='container'>
        <div class='row'>
          <div class='input-group mb-3'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-date' data-i18n='stats_filter_date'>Date</label>
            </div>
            <input id='filter-date' type='text' class='form-control' data-toggle='datepicker'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-pokemon' data-i18n='stats_filter_pokemon'>Pokemon</label>
            </div>
            <select id='filter-pokemon' class='custom-select'>
              <option disabled selected>Select</option>
              <option value='all'>All</option>";
              foreach ($pokedex as $pokemon_id => $name) {
  	  	        if ($pokemon_id <= 0)
                      continue;
  	  	        $html .= "<option value='$pokemon_id'>$name</option>";
  	  	    }
  	  	    $html .= "
            </select>
          </div>
        </div>
      </div>
      <canvas id='pokemon-stats'></canvas>
      <progress id='pokemon-animation' max='1' value='0' style='width: 100%'></progress>
    </div>
    <div id='raids' class='tab-pane fade' role='tabpanel'>
      <div class='container'>
        <div class='row'>
          <div class='input-group mb-3'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-raid-date' data-i18n='stats_filter_date'>Date</label>
            </div>
            <input id='filter-raid-date' type='text' class='form-control' data-toggle='datepicker'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-raid-type' data-i18n='stats_filter_by'>Filter By</label>
            </div>
            <label class='radio-inline'><input type='radio' class='btn' name='filter-raid-type' value='0' data-i18n='stats_filter_by_pokemon' checked>Pokemon</label>
            <label class='radio-inline'><input type='radio' class='btn' name='filter-raid-type' value='1' data-i18n='stats_filter_by_level'>Level</label>
          </div>
        </div>
      </div>
      <canvas id='raid-stats'></canvas>
      <progress id='raid-animation' max='1' value='0' style='width: 100%'></progress>
    </div>
    <div id='quests' class='tab-pane fade' role='tabpanel'>
      <div class='container'>
        <div class='row'>
          <div class='input-group mb-3'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-quest-date' data-i18n='stats_filter_date'>Date</label>
            </div>
            <input id='filter-quest-date' type='text' class='form-control' data-toggle='datepicker'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-reward' data-i18n='stats_filter_reward'>Reward</label>
            </div>
            <select id='filter-reward' class='custom-select' disabled>
              <option disabled selected>Select</option>
              <option value='all'>All</option>";
              foreach ($pokedex as $pokemon_id => $name) {
                if ($pokemon_id <= 0)
                  continue;
                $html .= "<option value='$pokemon_id'>$name</option>";
              }
              $html .= "
            </select>
          </div>
        </div>
      </div>
      <canvas id='quest-stats'></canvas>
      <progress id='quest-animation' max='1' value='0' style='width: 100%'></progress>
    </div>
    <div id='comday' class='tab-pane fade' role='tabpanel'>
      <div class='container'>
        <div class='row'>
          <div class='input-group mb-3'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-date' data-i18n='stats_filter_date_start'>Start Date</label>
            </div>
            <input id='filter-date-start' class='flatpickr' placeholder='Select Date & Time..' data-toggle='datetimepicker'>

            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-date' data-i18n='stats_filter_date_end'>End Date</label>
            </div>
            <input id='filter-date-end' class='flatpickr' placeholder='Select Date & Time..' data-toggle='datetimepicker'>

            <div class='input-group-prepend'>
              <label class='input-group-text' for='filter-pokemon-comday' data-i18n='stats_filter_pokemon'>Pokemon</label>
            </div>
            <select id='filter-pokemon-comday' class='custom-select'>
              <option disabled selected>Select</option>";
              foreach ($pokedex as $pokemon_id => $name) {
  	  	        if ($pokemon_id <= 0)
                      continue;
  	  	        $html .= "<option value='$pokemon_id'>$name</option>";
  	  	    }
  	  	    $html .= "
            </select>
          </div>
        </div>
      </div>
    <div id='comday-stats' class='container' style='background: white'>
    <div class='row m-2'>
      <div class='col-md-5'>
        <div class='row p-2'>
          <div class='col' style='background: white'>
            <h4 id='pkmn-title'>Scans</h4>
            <div class='row p-2'>
              <div class='col-lg-6'>
                <div class='wrapper'><canvas id='scanChart'></canvas></div>
              </div>
              <div class='col-lg-6 justify-content-right'>
                <h5 id='total-seen'>0 seen</h5>
                <h6 id='total-scanned'>0 scanned</h6>
              </div>
            </div>
            <div class='row p-2'>
              <div class='col' style='background: white'>
                <span><b>100% IV:</b><span id='iv100'>0</span></span>
                <span><b>90% IV:</b><span id='iv90'>0</span></span>
                <span><b>0% IV:</b><span id='iv0'>0</span></span>
              </div>
            </div>
          </div>
        </div>
        <div class='row p-2'>
          <div class='col' style='background: white'>
            <div class='wrapper'><canvas id='levelChart'></canvas></div>
          </div>
        </div>
      </div>
      <div class='col-md-7 p-2' style='background: white'>
        <div class='wrapper'><canvas id='ivChart'></canvas></div>
      </div>
    </div>
    <div class='row p-2 m-2'>
      <div class='col-md-4' style='background: white'>
        <h3>Sex</h3>
        <span><span id='total-male'>0</span> male spawns</span><br>
        <span><span id='total-female'>0</span> female spawns</span>
      </div>
      <div class='col-md-8' style='background: white'>
        <div class='row text-center'>
          <div class='col-4'>
            <img id='evo1' src='#' height=72 width=72/><br>
            <b id='evo1-text'></b>
          </div>
          <div class='col-4'>
            <img id='evo2' src='#' height=72 width=72/><br>
            <b id='evo2-text'></b>
          </div>
          <div class='col-4 justify-content-end'>
            <img id='evo3' src='#' height=72 width=72/><br>
            <b id='evo3-text'></b>
          </div>
        </div>
      </div>
    </div>
    </div>
    </div>
  </div>
</div>
";
echo $html;
?>

<script type='text/javascript' src='https://www.chartjs.org/dist/latest/Chart.bundle.js'></script>
<script type='text/javascript' src='./static/js/datepicker.js'></script>
<script type='text/javascript' src='./static/js/pokedex.js'></script>
<script type='text/javascript' src='./static/js/utils.js'></script>
<script type='text/javascript'>
var debug = <?=$config['core']['showDebug'] !== false ? '1' : '0'?>;
var pkmnProgress = document.getElementById("pokemon-animation");
var pkmnCtx = $("#pokemon-stats");
pkmnChart = new Chart(pkmnCtx, {
  type: 'bar',
  //data: createChartData("Seen", pokemon, amounts),
  options: createChartOptions("Pokemon Spawn Statistics", "Pokemon", "Amount Seen", pkmnProgress, "pokemon-stats")
});
$("#pokemon-stats").hide();

var raidProgress = document.getElementById("raid-animation");
var raidCtx = $("#raid-stats");
raidChart = new Chart(raidCtx, {
  type: 'bar',
  //data: createChartData("Seen", pokemon, amounts),
  options: createChartOptions("Raid Boss Statistics", "Pokemon", "Amount Seen", raidProgress, "raid-stats")
});
$("#raid-stats").hide();

var questProgress = document.getElementById("quest-animation");
var questCtx = $("#quest-stats");
questChart = new Chart(questCtx, {
  type: 'bar',
  options: createChartOptions("Field Research Statistics", "Reward", "Scanned", questProgress, "quest-stats")
});
$("#quest-stats").hide();

$("#comday-stats").hide();

$("[data-toggle='datetimepicker']").flatpickr({
  altInput: true,
  altFormat: "F j, Y h\:00 K",
  dateFormat: "U",//"Y-m-d h K",
  enableTime: true
});

$("#filter-pokemon-comday").on('change', function() {
  var start = $("#filter-date-start").val();
  var end = $("#filter-date-end").val();
  var pokemonId = this.value;
  console.log("Start:", start, "End:", end, "Pokemon:", pokemonId);

  var tmp = createToken();
  sendRequest({ "type": "stats", "start": start, "end": end, "pokemon_id": pokemonId, "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data.length > 0) {
        console.log("Stats:", data);
      } else {
        console.log("Failed to get pokemon stats data.");
      }
    }

    var obj = JSON.parse(data)[0];
    $("#comday-stats").show();

    removeDataset(scanChart);
    var scanData = {
      data: [obj.total || 0, obj.with_iv || 0],
      backgroundColor: [
        'green',
        'blue'
      ],
      label: 'Seen'
    };
    addDataset(scanChart, scanData);

    removeDataset(levelChart);
    var levelData = {
      data: [
        obj.level_1_9 || 0,
        obj.level_10_19 || 0,
        obj.level_20_29 || 0,
        obj.level_30_35 || 0
      ],
      backgroundColor: [
		  	'red',
		  	'yellow',
		  	'green',
		  	'blue'
		  ],
		  label: 'Levels'
    };
    addDataset(levelChart, levelData);

    removeDataset(ivChart);
    addDataset(ivChart, { data: [obj.iv0], backgroundColor: 'black', label: '0% IV' });
    addDataset(ivChart, { data: [obj.iv_1_49], backgroundColor: 'red', label: '1-49% IV' });
    addDataset(ivChart, { data: [obj.iv_50_79], backgroundColor: 'blue', label: '50-79% IV' });
    addDataset(ivChart, { data: [obj.iv_80_89], backgroundColor: 'yellow', label: '80-89% IV' });
    addDataset(ivChart, { data: [obj.iv_90_99], backgroundColor: 'orange', label: '90-99% IV' });
    addDataset(ivChart, { data: [obj.iv100], backgroundColor: 'green', label: '100% IV' });

    $("#pkmn-title").text(pokedex[pokemonId] + " Scans");
    $("#total-seen").text(numberWithCommas(obj.total || 0) + " seen");
    $("#total-scanned").text(numberWithCommas(obj.with_iv || 0) + " scanned");
    $("#iv100").text(obj.iv100 || 0);
    $("#iv90").text(obj.iv90 || 0);
    $("#iv0").text(obj.iv0 || 0);
    $("#total-male").text(numberWithCommas(obj.male || 0) + " (" + Math.round((obj.male / obj.total) * 100, 2) + "%)");
    $("#total-female").text(numberWithCommas(obj.female || 0) + " (" + Math.round((obj.female / obj.total) * 100, 2) + "%)");

    //TODO: Check if pokemon is a 3 stage evo
    $("#evo1").attr("src", sprintf("<?=$config['urls']['images']['pokemon']?>", pokemonId));
    $("#evo2").attr("src", sprintf("<?=$config['urls']['images']['pokemon']?>", parseInt(pokemonId) + 1));
    $("#evo3").attr("src", sprintf("<?=$config['urls']['images']['pokemon']?>", parseInt(pokemonId) + 2));

    $("#evo1-text").text(pokedex[pokemonId]);
    $("#evo2-text").text(pokedex[parseInt(pokemonId) + 1]);
    $("#evo3-text").text(pokedex[parseInt(pokemonId) + 2]);
  });
});


$("#filter-raid-level").prop("disabled", true);
$("[data-toggle='datepicker']").datepicker({
  autoHide: true,
  yearFirst: true,
  format: "yyyy-mm-dd",
  zIndex: 2048,
});
$("#filter-date").datepicker("setDate", new Date());
$("#filter-raid-date").datepicker("setDate", new Date());
$("#filter-quest-date").datepicker("setDate", new Date());

$("#filter-date").change(filterPokemonChart);
$("#filter-pokemon").change(filterPokemonChart);
filterPokemonChart();

//$("[name='filter-raid-type']").change(filterRaidChart);
$("#filter-raid-date").change(filterRaidChart);
filterRaidChart();

$("#filter-quest-date").change(filterQuestChart);
$("#filter-reward").change(filterQuestChart);
filterQuestChart();

var pieOptions = {
    responsive: true,
    legend: false,
    tooltips: true,
    elements: {
        arc: {
            backgroundColor: colorize.bind(null, false, false),
            hoverBackgroundColor: hoverColorize
        }
    },
    animation: {
		animateScale: true,
		animateRotate: true
	}
};

var barOptions = {
	legend: {
		position: 'bottom',
	},
    tooltips: true,
	title: {
		display: true,
		text: 'IV Spread'
	},
  tooltips: { mode: "nearest", intersect: false, },
  hover: { mode: "nearest", intersect: true },
	scales: {
		yAxes: [{
      ticks: {
        beginAtZero: true,
        stepSize: 25
      }
		}]
	},
  elements: {
    rectangle: {
      backgroundColor: colorize.bind(null, false, false),
      borderColor: colorize.bind(null, true, false),
      borderWidth: 2
    }
  }
};

var donutOptions = {
  responsive: true,
	legend: {
		position: 'top',
	},
	title: {
		display: true,
		text: 'Level Spread'
	},
	animation: {
		animateScale: true,
		animateRotate: true
	}
};

var scanChart = new Chart('scanChart', {
  type: 'pie',
  data: {
    datasets: [],
    labels: [
      'Seen',
      'Scanned'
    ]
  },
   options: pieOptions
});

var levelChart = new Chart('levelChart', {
  type: 'doughnut',
  data: {
    datasets: [],
    labels: [
      'Level 01-09',
      'Level 10-19',
      'Level 20-29',
      'Level 30-35',
    ]
  },
  options: donutOptions
});

var ivChart = new Chart('ivChart', {
    type: 'bar',
    data: {
      datasets: []
    },
    options: barOptions
});

function addDataset(chart, data) {

  chart.data.datasets.push(data);
  chart.update();
}

function removeDataset(chart) {
  //chart.data.datasets.shift();
  chart.data.datasets = [];
  //chart.data.labels.pop();
  //chart.data.datasets.forEach((dataset) => {
  //  dataset.data.pop();
  //});
  chart.update();
}

function colorize(opaque, hover, ctx) {
    var v = ctx.dataset.data[ctx.dataIndex];
    var c = v < -50 ? '#D60000'
        : v < 0 ? '#F46300'
        : v < 50 ? '#0358B6'
        : '#44DE28';

    var opacity = hover ? 1 - Math.abs(v / 150) - 0.2 : 1 - Math.abs(v / 150);

    return opaque ? c : transparentize(c, opacity);
}

function hoverColorize(ctx) {
    return colorize(false, true, ctx);
}

function transparentize(color, opacity) {
	var alpha = opacity === undefined ? 0.5 : 1 - opacity;
	return Color(color).alpha(alpha).rgbString();
}

function filterPokemonChart() {
  var date_filter = document.getElementById("filter-date").value;
  var pokemon_filter = document.getElementById("filter-pokemon").value;
  if (pokemon_filter.toLowerCase().indexOf("select") === 0) {
    pokemon_filter = "all";
  }
  if (date_filter != null) {
    console.log("Updating pokemon chart...");
    updatePokemonChart(pkmnChart, date_filter, pokemon_filter);
  }
}

function filterRaidChart() {
  var date_filter = document.getElementById("filter-raid-date").value;
  var type_filter = $("[name='filter-raid-type']:checked").val();
  if (date_filter != null) {
    console.log("Updating raid chart...");
    updateRaidChart(raidChart, date_filter, type_filter);
  }
}

function filterQuestChart() {
  var date_filter = document.getElementById("filter-quest-date").value;
  var reward_filter = document.getElementById("filter-reward").value;
  if (reward_filter.toLowerCase().indexOf("select") === 0) {
    reward_filter = "all";
  }
  if (date_filter != null) {
    console.log("Updating quest chart...");
    updateQuestChart(questChart, date_filter, reward_filter);
  }
}

function updatePokemonChart(chart, dateFilter, pokeFilter) {
  console.log("Date:", dateFilter, "Pokemon:", pokeFilter);
  var tmp = createToken();
  sendRequest({ "table": "pokemon_stats", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data !== false) {
        console.log("Pokemon:", data);
      } else {
        conosle.log("Failed to get pokemon stats data.");        
      }
    }
    var pokemon = [];
    var amounts = [];
    var obj = JSON.parse(data);
    obj.forEach(stat => {
      if (stat.date === dateFilter && (pokeFilter === stat.pokemon_id || pokeFilter === "all")) {
        pokemon.push(pokedex[stat.pokemon_id]);
        amounts.push(stat.count);
      }
    });

    clearChartData(chart);
    chart.data = createChartData("Seen", pokemon, amounts);
    chart.update();
    console.log("Pokemon chart updated");
  });
}

function updateRaidChart(chart, dateFilter, typeFilter) {
  console.log("Date:", dateFilter, "Type:", typeFilter);
  var tmp = createToken();
  sendRequest({ "table": "raid_stats", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data !== false) {
        console.log("Raids:", data);
      } else {
        conosle.log("Failed to get raid stats data.");
      }
    }
    var pokemon = [];
    var amounts = [];
    var obj = JSON.parse(data);
    obj.forEach(stat => {
      if (stat.date === dateFilter) {
        pokemon.push(pokedex[stat.pokemon_id] + " (Level " + stat.level + ")");
        amounts.push(stat.count);
      }
    });

    clearChartData(chart);
    chart.data = createChartData("Seen", pokemon, amounts);
    chart.update();
    console.log("Raid chart updated");
  });
}

function updateQuestChart(chart, dateFilter, rewardFilter) {
  console.log("Date:", dateFilter, "Reward:", rewardFilter);
  var tmp = createToken();
  sendRequest({ "table": "quest_stats", "token": tmp }, function(data, status) {
    tmp = null;
    if (debug) {
      if (data !== false) {
        console.log("Quests:", data);
      } else {
        console.log("Failed to get quest stats data.");
      }
    }
    var rewards = [];
    var amounts = [];
    var obj = JSON.parse(data);
    //removeDataset(chart);
    obj.forEach(stat => {
      if (stat.date === dateFilter) {
        //var color = getRandomColor();
        switch (parseInt(stat.reward_type)) {
          case 1: //Experience points
            rewards.push("XP");
            amounts.push(stat.count);
            break;
          case 3: //Stardust
            rewards.push("Stardust");
            amounts.push(stat.count);
            break;
          case 7: //Pokemon encounter
            //addDataset(chart, { data: [stat.count], backgroundColor: color, label: pokedex[stat.pokemon_id] });
            rewards.push(pokedex[stat.pokemon_id]);
            amounts.push(stat.count);
            break;
          default:
            //addDataset(chart, { data: [stat.count], backgroundColor: color, label: get_item(stat.item_id) });
            rewards.push(get_item(stat.item_id));
            amounts.push(stat.count);
            break;
        }
      }
    });

    clearChartData(chart);
    chart.data = createChartData("Scanned", rewards, amounts);
    chart.update();
    console.log("Quest chart updated");
  });
}

function createChartOptions(title, xAxesLabel, yAxesLabel, progress, canvasId) {
  var chartOptions = {
    responsive: true,
    title: { display: true, text: title, fontSize: 18, fontColor: "#111" },
    tooltips: { mode: "nearest", intersect: false, },
    hover: { mode: "nearest", intersect: true },
    scales: {
      xAxes: [{
        display: true,
        scaleLabel: { display: true, labelString: xAxesLabel }
      }],
      yAxes: [{
        display: true,
        scaleLabel: { display: true, labelString: yAxesLabel },
        ticks: { precision: 0, beginAtZero: true }
      }]
    },
    animation: {
      duration: 2000,
      onProgress: function(animation) {
        progress.value = animation.currentStep / animation.numSteps;
      },
      onComplete: function() {
        window.setTimeout(function() {
          progress.value = 0;
          progress.style.display = "none";
          $("#" + canvasId).show();
        }, 2000);
      }
    }
  };
  return chartOptions;
}

function createChartData(title, labels, data) {
  var colors = [];
  for (var i = 0; i < labels.length; i++) {
    colors.push(getRandomColor());
  }
  var chartData = {
    labels: labels,
    datasets : [{
      label: title,
      strokeColor: createArrayOfValue("<?=$config['ui']['charts']['colors']['stroke']?>", labels.length),
      highlightFill: createArrayOfValue("<?=$config['ui']['charts']['colors']['highlightFill']?>", labels.length),
      highlightStroke: createArrayOfValue("<?=$config['ui']['charts']['colors']['highlightStroke']?>", labels.length),
      backgroundColor: colors,
      borderColor: createArrayOfValue("<?=$config['ui']['charts']['colors']['border']?>", labels.length),
      hoverBackgroundColor: createArrayOfValue("<?=$config['ui']['charts']['colors']['hoverBackground']?>", labels.length),
      hoverBorderColor: createArrayOfValue("<?=$config['ui']['charts']['colors']['hoverBorder']?>", labels.length),
      data: data
    }]
  };
  return chartData;
}

function clearChartData(chart) {
  chart.data.labels.pop();
  chart.data.datasets.forEach((dataset) => {
    dataset.data.pop();
  });
}

function get_item($item_id) {
    switch (parseInt($item_id)) {
        case 1://Poke_Ball
            return "Poke Ball";
        case 2://Great_Ball
            return "Great Ball";
        case 3://Ultra_Ball
            return "Ultra Ball";
        case 4://Master_Ball
            return "Master Ball";
        case 5://Premier_Ball
            return "Premier Ball";
        case 101://Potion
            return "Potion";
        case 102://Super_Potion
            return "Super Potion";
        case 103://Hyper_Potion
            return "Hyper Potion";
        case 104://Max_Potion
            return "Max Potion";
        case 201://Revive
            return "Revive";
        case 202://Max_Revive
            return "Max Revive";
        case 301://Lucky_Egg
            return "Lucky Egg";
        case 401://Incense_Ordinary
            return "Incense";
        case 402://Incense_Spicy
            return "Incense Spicy";
        case 403://Incense_Cool
            return "Incense Cool";
        case 404://Incense_Floral
            return "Incense Floral";
        case 501://Troy_Disk
            return "Troy Disk";
        case 602://X_Attack
            return "X Attack";
        case 603://X_Defense
            return "X Defense";
        case 604://X_Miracle
            return "X Miracle";
        case 701://Razz_Berry
            return "Razz Berry";
        case 702://Bluk_Berry
            return "Bluk Berry";
        case 703://Nanab_Berry
            return "Nanab Berry";
        case 704://Wepar_Berry
            return "Wepar Berry";
        case 705://Pinap_Berry
            return "Pinap Berry";
        case 706://Golden_Razz_Berry
            return "Golden Razz Berry";
        case 707://Golden_Nanab_Berry
            return "Golden Nanab Berry";
        case 708://Golden_Pinap_Berry
            return "Golden Pinap Berry";
        case 701://Special_Camera
            return "Special Camera";
        case 901://Incubator_Basic_Unlimited
            return "Incubator (Unlimited)";
        case 902://Incubator_Basic
            return "Incubator";
        case 903://Incubator_Super
            return "Super Incubator";
        case 1001://Pokemon_Storage_Upgrade
            return "Pokemon Storage Upgrade";
        case 1002://Item_Storage_Upgrade
            return "Item Storage Upgrade";
        case 1101://Sun_Stone
            return "Sun Stone";
        case 1102://Kings_Rock
            return "Kings Rock";
        case 1103://Metal_Coat
            return "Metal Coat";
        case 1104://Dragon_Scale
            return "Dragon Scale";
        case 1105://Upgrade
            return "Upgrade";
        case 1201://Move_Reroll_Fast_Attack
            return "Move Reroll Fast Attack";
        case 1202://Move_Reroll_Special_Attack
            return "Move Reroll Special Attack";
        case 1301://Rare_Candy
            return "Rare Candy";
        case 1401://Free_Raid_Ticket
            return "Free Raid Ticket";
        case 1402://Paid_Raid_Ticket
            return "Paid Raid Ticket";
        case 1403://Legendary_Raid_Ticket
            return "Legendary Raid Ticket";
        case 1404://Star_Piece
            return "Star Piece";
        case 1405://Friend_Gift_Box
            return "Friend Gift Box";
        default:
            return "Unknown";
    }
}

function createToken() {
  //TODO: Secure
  <?php $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16)); ?>
  return "<?=$_SESSION['token']?>";
}
</script>