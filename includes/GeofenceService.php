<?php
use Location\Coordinate;
use Location\Polygon;

class Geofence {
    public $name;
    public $polygon;

    public function get_flat_coordinates() {
        $flatCoords = "";
        $length = count($polygon);
        for ($i = 0; $i < $length; $i++) {
            $coord = $polygon[$i];
            $flatCoords .= $coord[0] . " " . $coord[1];
            if ($i == $length - 1) {
                break;
            }
            $flatCoords .= ",";
        }
        return $flatCoords;
    }
}
class GeofenceService {
    public $geofences = [];
    private $dir = "./geofences/";

    function __construct() {
        $this->create_directory();
        $this->load_geofences();
    }

    function load_geofences() {
        $files = scandir($this->dir);
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i] === '.' || $files[$i] === '..')
                continue;
	  	  	
            $geofence = $this->load_geofence($this->dir . $files[$i]);
            if ($geofence != null) {
                array_push($this->geofences, $geofence);
            }
        }	
        return $this->geofences;
    }

    function load_geofence($file) {
        $lines = file($file);
        $name = "Unknown";
        if (count($lines) !== 0 && strpos($lines[0], '[') === 0) {
            $name = str_replace('[', "", $lines[0]);
            $name = str_replace(']', "", $name);
        }
  
        $geofence = new Geofence();
        $geofence->name = $name;
        $geofence->polygon = $this->build_polygon(array_slice($lines, 1));
        return $geofence;
    }

    function build_polygon($lines) {
        $polygon = new Polygon();
        $count = count($lines);
        for ($i = 0; $i < $count; $i++) {
            $line = $lines[$i];
            if (strpos($line, '[') === 0)
                continue;
	  	  	  
            $parts = explode(',', $line);
            $lat = $parts[0];
            $lon = $parts[1];
            $polygon->addPoint(new Coordinate((float)$lat,(float)$lon));
        }
        return $polygon;
    }

    public function get_geofence($lat, $lon) {
        for ($i = 0; $i < count($this->geofences); $i++) {
            if ($this->is_in_polygon($this->geofences[$i], (float)$lat, (float)$lon)) {
                return $this->geofences[$i];
            }
        }
        return null;
    }

    function is_in_polygon($geofence, $lat_x, $lon_y) {
        $point = new Coordinate($lat_x, $lon_y);
        return $geofence->polygon->contains($point);
    }

    function create_directory() {
        if (!file_exists($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }
}
?>