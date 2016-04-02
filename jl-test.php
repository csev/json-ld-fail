<?php

require_once "jsonld.php";

$x = file_get_contents('product.json');
$result = array();
for($i=0;$i<1000;$i++) {
   $y = json_decode($x);
   $y = jsonld_compact($y, "http://schema.org/");
    $result[] = $y;
}
