<?php
include './vendor/autoload.php';
include './includes/GeofenceService.php';

$geofenceSrvc = new GeofenceService();

$filters = "
<div class='container'>
  <div class='row'>
    <div class='input-group mb-3'>
      <div class='input-group-prepend'>
        <label class='input-group-text' for='filter-gym'>Gym</label>
      </div>
      <input type='text' id='filter-gym' class='form-control input-lg' placeholder='Search by gym...' title='Type in a gym name'></input>
    </div>
    <div class='input-group mb-3'>
      <div class='input-group-prepend'>
        <label class='input-group-text' for='filter-team'>Team</label>
      </div>
      <select id='filter-team' class='custom-select'>
        <option disabled selected>Select</option>
        <option value=''>All</option>
        <option value='0'>Neutral</option>
        <option value='1'>Mystic</option>
        <option value='2'>Valor</option>
        <option value='3'>Instinct</option>
      </select>
    </div>
    <div class='input-group mb-3'>
      <div class='input-group-prepend'>
        <label class='input-group-text' for='filter-slots'>Available Slots</label>
      </div>
      <select id='filter-slots' class='custom-select'>
        <option disabled selected>Select</option>
        <option value=''>All</option>
        <option value='0'>Full</option>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>Empty</option>
      </select>
    </div>
    <div class='input-group mb-3'>
      <div class='input-group-prepend'>
        <label class='input-group-text' for='filter-battle'>In Battle Status</label>
      </div>
      <select id='filter-battle' class='custom-select'>
        <option disabled selected>Select</option>
        <option value=''>All</option>
        <option value='1'>Yes</option>
        <option value='0'>No</option>
      </select>
    </div>
    <div class='input-group mb-3'>
      <div class='input-group-prepend'>
        <label class='input-group-text' for='filter-city'>City</label>
      </div>
      <select id='filter-city' class='custom-select'>
        <option disabled selected>Select</option>
        <option value=''>All</option>
        <option value='" . $config['ui']['unknownValue'] . "'>" . $config['ui']['unknownValue'] . "</option>";
        $count = count($geofenceSrvc->geofences);
        for ($i = 0; $i < $count; $i++) {
            $geofence = $geofenceSrvc->geofences[$i];
            $filters .= "<option value='".$geofence->name."'>".$geofence->name."</option>";
        }
        $filters .= "
      </select>
    </div>
  </div>
</div>
";

$modal = "
<h2 class='page-header text-center'>Team gyms</h2>
<div class='btn-group btn-group-sm float-right'>
  <button type='button' class='btn btn-dark' data-toggle='modal' data-target='#filtersModal'>
    <i class='fa fa-fw fa-filter' aria-hidden='true'></i>
  </button>
  <button type='button' class='btn btn-dark' data-toggle='modal' data-target='#columnsModal'>
    <i class='fa fa-fw fa-columns' aria-hidden='true'></i>
  </button>
</div>
<p>&nbsp;</p>
<div class='modal fade' id='filtersModal' tabindex='-1' role='dialog' aria-labelledby='filtersModalLabel' aria-hidden='true'>
  <div class='modal-dialog' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='filtersModalLabel'>Gym Filters</h5>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>" . $filters . "</div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-danger' id='reset-filters'>Reset Filters</button>
        <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
      </div>
    </div>
  </div>
</div>
<div class='modal fade' id='columnsModal' tabIndex='-1' role='dialog' aria-labelledby='columnsModalLabel' aria-hidden='true'>
  <div class='modal-dialog' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='columnsModalLabel'>Show Columns</h5>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>    
      <div class='modal-body'>
        <div id='chkColumns'>
          <p><input type='checkbox' name='team'/>&nbsp;Team</p>
          <p><input type='checkbox' name='slots'/>&nbsp;Available Slots</p>
          <p><input type='checkbox' name='guard'/>&nbsp;Guarding Pokemon</p>
          <p><input type='checkbox' name='battle'/>&nbsp;In Battle</p>
          <p><input type='checkbox' name='city'/>&nbsp;City</p>
          <p><input type='checkbox' name='updated'/>&nbsp;Updated</p>
        </div>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
      </div>
    </div>
  </div>
</div>
<div id='no-more-tables'>
  <table id='gym-table' class='table table-".$config['ui']['table']['style']." ".($config['ui']['table']['striped'] ? 'table-striped' : null)." display' border='1' style='width:100%''>
    <thead class='thead-".$config['ui']['table']['headerStyle']."'>
      <tr class='text-nowrap'>
        <th></th>
        <th>Gym</th>
        <th>Team</th>
        <th>Available Slots</th>
        <th>Guarding Pokemon</th>
        <th>In Battle</th>
        <th>City</th>
        <th>Updated</th>
      </tr>
    </thead>
    <tbody class='text-nowrap'>
    </tbody>
  </table>
</div>
";
echo $modal;
?>

<script type="text/javascript" src="./static/js/pokedex.js"></script>
<script type="text/javascript">
var tmp = createToken();
var dt = $("#gym-table").DataTable({
  "columnDefs": [
    {
      "targets": ["name","team","slots","guard","city"],
      "type": "string",
      "orderable": true,
      "searchable": true
    }
  ],
  "columns": [
    {
      "class":          "details-control",
      "orderable":      false,
      "searchable":     false,
      "data":           "null",
      "defaultContent": ""
    },
    { "data": "name",   "searchable": true },
    { "data": "team",   "searchable": true },
    { "data": "slots",  "searchable": true },
    { "data": "guard",  "searchable": true },
    { "data": "battle", "searchable": true },
    { "data": "city",   "searchable": true },
    { "data": "updated" }
  ],
  "initComplete": function () {

  },
  //"bFilter": true,
  /*
  "createdRow": function (row, data, dataIndex) {
    if (data["city"].toLowerCase() !== $("filter-city").val()"upland") {
      //console.log(data["city"]);
      $(row).show();
    } else {
      $(row).hide();
    }
  },
  */
  //"dom": "Bfrtip", //"lftipr", 
  "info": false,
  "lengthMenu": [[25, 50, 75, 100, 150, -1], [25, 50, 75, 100, 150, "All"]],
  //"order": [[1, 'asc']],
  "orderMulti": true,
  "paging": true,
  "pagingType": "simple_numbers", //simple, full, numbers, simple_numbers, full_numbers, first_last_numbers
  "pageLength": -1,
  "processing": true,
  "renderer": "bootstrap",
  "responsive": true,
  /*
  "rowCallback": function(row, data) {
    $('td:eq(2)', row).html('<img src="./static/images/teams/' + data.team.toLowerCase() + '.png" height=32 width=32 />&nbsp;' + data.team);
    $('td:eq(4)', row).html('<img src="' + sprintf("<?=$config['urls']['images']['pokemon']?>", data.guard) + '" height=32 width=32 />&nbsp;' + pokedex[data.guard]);
  },
  */
  "search.caseInsensitive": true,
  "searching": true,
  "serverSide": true,
  "ajax": {
    url: "api.php",
    method: "POST",
    data: { "type": "gyms", "token": tmp },
    dataSrc: function(json) {
      var data = [];
      var city = $('#filter-city').val();
      for (var i =0; i < json.data.length; i++) {
        if (json.data[i].city.toLowerCase().trim() === city) {
          console.log(json.data[i].city);
          data.push(json.data[i]);
        }
      }
      return data;
    },
    //dataSrc: "data",
    /*
    dataFilter: function(data) {
      var json = jQuery.parseJSON(data);
      return JSON.stringify(json);
    },
    */
    error: function(data) {
      console.log("ERROR:", data);
    }
  }
});

dt.on('search.dt', function() {
  $('tr').each(function() {
    var tr = $(this).closest('tr');
    var row = dt.row(tr);
    if (row.child.isShown()) {
      row.child.hide();
      tr.removeClass('parent');
    };
  });
});

$('#filter-gym').on('change', function() {
  dt.column(1).search(this.value).draw();
});
$('#filter-team').on('change', function() {
  dt.column(2).search(this.value).draw();
});
$('#filter-slots').on('change', function() {
  dt.column(3).search(this.value).draw();
});
$('#filter-battle').on('change', function() {
  dt.column(5).search(this.value).draw();
});
$('#filter-city').change(function() {
  /*
  $.fn.dataTable.ext.search.push(
    function(settings, searchData, index) {
      console.log("Test");
      return false;
    }
  );
  dt.draw();
  //$.fn.dataTable.ext.search.pop();
  */
  filter_gyms();
});

// On each draw, loop over the `detailRows` array and show any child rows
dt.on('draw', function() {
  $.each(detailRows, function(i, id) {
    $('#' + id + ' td.details-control').trigger('click');
  });
});

function format(d) {
  return 'Test';
}

// Array to track the ids of the details displayed rows
var detailRows = [];

$('#gym-table tbody').on('click', 'tr td.details-control', function() {
  var tr = $(this).closest('tr');
  var row = dt.row(tr);
  var idx = $.inArray(tr.attr('id'), detailRows);
  if (row.child.isShown()) {
    tr.removeClass('details');
    row.child.hide();

    // Remove from the 'open' array
    detailRows.splice(idx, 1);
  } else {
    tr.addClass('details');
    row.child(format(row.data())).show();

    // Add to the 'open' array
    if ( idx === -1) {
      detailRows.push(tr.attr('id'));
    }
  }
});

$(document).on("click", ".delete", function(){
  $(this).parents("tr").remove();
  $(".add-new").removeAttr("disabled");
});

var checkbox = $("#chkColumns input:checkbox"); 
var tbl = $("#gym-table");
var tblHead = $("#gym-table th");
checkbox.prop('checked', true); 
checkbox.click(function () {
  var colToHide = tblHead.filter("." + $(this).attr("name"));
  var index = $(colToHide).index();
  tbl.find('tr :nth-child(' + (index + 1) + ')').toggle();
});

if (get("gyms-filter-team") !== false) {
  $('#filter-team').val(get("gyms-team"));
}
if (get("gyms-filter-slots") !== false) {
  $('#filter-slots').val(get("gyms-filter-slots"));
}
if (get("gyms-filter-battle") !== false) {
  $('#filter-battle').val(get("gyms-filter-battle"));
}
if (get("gyms-filter-city") !== false) {
  $('#filter-city').val(get("gyms-filter-city"));
}
if (get("gyms-filter-gym") !== false) {
  $('#filter-gym').val(get("gyms-filter-gym"));
}

//filter_gyms();

$('#reset-filters').on('click', function() {
  if (confirm("Are you sure you want to reset the gym filters?")) {
    $('#filter-team').val('All');
    $('#filter-slots').val('All');
    $('#filter-battle').val('All');
    $('#filter-city').val('All');
    $('#filter-gym').val('');
    filter_gyms();
  }
});

function createToken() {
  //TODO: Secure
  <?php $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16)); ?>
  return "<?php echo $_SESSION['token']; ?>";
}
</script>
<style>
td.details-control {
  background: url('./static/images/details_open.png') no-repeat center center;
  cursor: pointer;
}
tr.details td.details-control {
  background: url('./static/images/details_close.png') no-repeat center center;
}
</style>