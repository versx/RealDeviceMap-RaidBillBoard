<?php

//Configuration options
$config = [
  "core" => [
    "timeZone" => "America/Denver", //Time zone for PHP pages and MySql data
    "fromTimeZoneOffset" => "UTC", //Time zone offset to convert database time from e.g. UTC, -03:00, +01:00
    "dateTimeFormat" => "m-d-Y h:i:s A", //Date & Time format http://php.net/manual/en/function.date.php
    "showFooter" => false, //Show bottom footer
    "showCopyright" => false, //Show Nintendo/Niantic/Pokemon copyright footer
    "showErrors" => true, //Show PHP errors
    "showDebug" => true, //Shows debug output
    "startupLocation" => [1,1], //Default location to startup any map objects at
    "startupZoom" => 11, //Default zoom for any map objects
    "maxPokemon" => 495, //Maximum amount of pokemon for Pokemon stats page
    "lastNestMigration" => "2019-03-07T01:00:00Z" //UTC time of last nest migration
  ],
  "db" => [
    "host" => "127.0.0.1", //Database host name or IP address
    "port" => "3306", //Database port. (default: 3306)
    "user" => "root", //Database username.
    "pass" => "password", //Database user password.
    "dbname" => "rdmdb", //Database name.
    "charset" => "utf8mb4" //Database character set. (default: utf8mb4)
  ],
  "discord" => [
    "enabled" => false, //Enable or disable Discord auth login
    "botToken" => "", //Discord bot token
    "botClientId" => 0, //Discord bot client ID
    "botClientSecret" => "", //Discord bot client secret
    "botRedirectUri" => "https://example.com/discord-callback.php", //Callback uri
    "inviteLink" => "", //Optional Discord invite link to preset the user if not in the guild
    "guildIds" => [], //Guild ID(s) the user should be in
    "roleIds" => [], //Role ID(s) the user should have
    "logUsers" => false //Log Discord users that have logged in
  ],
  "google" => [
    "analyticsId" => "", //Google Analytics Id. e.g. UA-XXXXX-Y
    "adSenseId" => "", //Google AdSense Id. e.g. ca-pub-XXXXXXXX
    "maps" => "https://maps.google.com/maps?q=%s,%s"
  ],
  "ui" => [
    "title" => "RDM-opole", //Website title
    "locale" => "en", //Set the language (e.g. `en` for English)
    "unknownValue" => "Unknown",
    "table" => [
      "style" => "light", //light/dark
      "headerStyle" => "dark", //light/dark
      "striped" => true, //true/false
      "refreshRateS" => 60, //Refresh raids table data every x seconds.
      "forceRaidCards" => false //Forces new display style on Desktop and Mobile if true, otherwise tables on Desktop and new style on Mobile if false.
    ],
    "charts" => [
      "type" => "bar",
      "colors" => [
        "stroke" => "rgba(220,220,220,0.8)",
        "highlightFill" => "rgba(220,220,220,0.75)",
        "highlightStroke" => "rgba(220,220,220,1)",
        "border" => "rgba(200, 200, 200, 0.75)",
        "hoverBackground" => "rgba(200, 200, 200, 1)",
        "hoverBorder" => "rgba(200, 200, 200, 1)"
      ]
    ],
    "noRaidsAvailableMessage" => "<b>No raids available</b>, come back tomorrow after 5am.", //Notice message that's shown when no raids are available for the day.
    "pages" => [
      "dashboard" => [
        "enabled" => true, //Shows/hides dashboard page
        "discordRoles" => [] //Requires specific discord role, otherwise leave empty as []
      ],
      "pokemon" => [
        "enabled" => true, //Shows/hides pokemon page
        "discordRoles" => [] //Requires specific discord role, otherwise leave empty as []
      ],
      "raids" => [
        "enabled" => true, //Shows/hides raids page
        "discordRoles" => [], //Requires specific discord role, otherwise leave empty as []
        "ignoreUnknown" => false
      ],
      "gyms" => [
        "enabled" => true, //Shows/hides gyms page
        "discordRoles" => [], //Requires specific discord role, otherwise leave empty as []
        "ignoreUnknown" => false
      ],
      "quests" => [
        "enabled" => true, //Shows/hides gyms page
        "discordRoles" => [], //Requires specific discord role, otherwise leave empty as []
        "ignoreUnknown" => false
      ],
      "pokestops" => [
        "enabled" => true, //Shows/hides gyms page
        "discordRoles" => [] //Requires specific discord role, otherwise leave empty as []
      ],
      "nests" => [
        "enabled" => true, //Shows/hides gyms page
        "discordRoles" => [], //Requires specific discord role, otherwise leave empty as []
        "ignoreUnknown" => false,
        "type" => "osm", //osm/pmsf
        "db" => [ //PMSF manualdb information
          "host" => "127.0.0.1", //PMSF database host name or IP address
          "port" => "3306", //PMSF database port. (default: 3306)
          "user" => "root", //PMSF database username.
          "pass" => "password", //PMSF database user password.
          "dbname" => "manualdb", //PMSF database name.
          "charset" => "utf8mb4" //PMSF database character set. (default: utf8mb4)
        ]
      ],
      "stats" => [
        "enabled" => true, //Shows/hides gyms page
        "discordRoles" => [] //Requires specific discord role, otherwise leave empty as []
      ]
    ],
    "navBarIconSize" => [24, 24] //NavBar image icon size e.g. [Width, Height]
  ],
  "urls" => [
    "map" => "", //RealDeviceMap/PMSF/Other url
    "images" => [
      /* Pokemon images url 
        Supports PHP format strings: %s, %d, etc.
        e.g. http://map.example.com/static/img/pokemon/%s.png for RDM backend images
        e.g. http://map.example.com/static/img/pokemon/%03d.png for 3 digit file names
      */
      "pokemon" => "http://example.com/images/pokemon/%s.png",
      /* Raid egg images url
        Supports PHP format strings: %s, %d, etc.
        e.g. http://map.example.com/static/img/egg/%s.png for RDM backend images
      */
      "egg" => "http://example.com/images/egg/%s.png"
    ],
    "paypal" => "", //PayPal.me link e.g https://paypal.me/username
    "venmo" => "", //Venmo link e.g. https://venmo.com/username
    "patreon" => "" //Patreon link e.g. https://patreon.com/username
  ]
];

//Error reporting
ini_set("error_reporting", $config['core']['showErrors']);
if ($config['core']['showErrors']) {
  error_reporting(E_ALL|E_STRCT);
}
?>