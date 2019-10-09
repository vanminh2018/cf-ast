#!/usr/bin/php
<?php
include_once "phpagi.php";
include_once "dbConnectionMysql.class.php";

$route_id = $argv[1];

$db = new dbConnectionMysql("127.0.0.1", "root", "", "asterisk");
$agi = new AGI();

$data = $db->sql("SELECT trunk_id FROM outbound_route_trunks WHERE route_id = ? ORDER BY seq", array($route_id), "rows");
$last_index = $db->sql("SELECT last_index, type_route FROM outbound_routes WHERE route_id = ?", array($route_id), "row");

if (isset($data[0]) && isset($last_index["type_route"])) {
    switch ($last_index["type_route"]) {
        case 1: // round robin
            if ($last_index["last_index"] >= count($data) - 1) {
                $newIndex = 0;
            } else {
                $newIndex = $last_index["last_index"] + 1;
            }
        
            $db->sql("UPDATE outbound_routes SET last_index = ? WHERE route_id = ?", array($newIndex, $route_id), "count");
            $agi->set_variable("ARG1", $data[$newIndex]["trunk_id"]);
            break;
        
        case 2: // random
            $randIndex = array_rand($data);
            $agi->set_variable("ARG1", $data[$randIndex]["trunk_id"]);
            break;
        
        default:
            break;
    }
    
}
