<?php
require_once "base_json.php";
require_once "jsonld.php";
require_once "colors.php";

// http://stackoverflow.com/questions/4356289/php-random-string-generator
$rs = str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
shuffle($color_list);
$first = substr($rs,0,rand(3,15));
$second = substr($rs,15,rand(3,15));

$nc = str_replace('"dc"','"'.$first.'"', $base_context);
$nc = str_replace('"ex"','"'.$second.'"', $nc);

$ncj = json_decode($nc);
$y = jsonld_compact($json, $ncj);
$z = json_encode($y, JSON_PRETTY_PRINT);

$z = str_replace($first,'<span style="color:'.$color_list[0].';">'.$first.'</span>',$z);
$z = str_replace($second,'<span style="color:'.$color_list[1].';">'.$second.'</span>',$z);
echo($z);
