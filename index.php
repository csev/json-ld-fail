<html>
<head>
<?php
require_once "base_json.php";
require_once "jsonld.php";
?>
<script   src="https://code.jquery.com/jquery-1.12.2.js"   integrity="sha256-VUCyr0ZXB5VhBibo2DkTVhdspjmxUgxDGaLQx7qb7xY="   crossorigin="anonymous"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<title>An Infinite Number of Equivalent Serializable Forms Makes JSON-LD A Bad Idea for APIs</title>
</head>
<body>
<div class="container">
<h1>JSON-LD API Failground</h1>
<p>
This web site is designed to show the fatal flaw of 
<a href="https://www.w3.org/TR/json-ld/" target="_blank">JSON-LD</a> usage 
for 
<a href="http://manu.sporny.org/2014/json-ld-origins-2/" target="_blank">API</a>
specs.  The core problem with JSON-LD is that there are an infinite 
number of legitimate serializations for any JSON-LD document.  
As such it is 
<a href="http://www.dr-chuck.com/csev-blog/?p=4980"
target="_blank">more costly</a>
to parse an incoming JSON-LD document than a 
simple JSON document with predictable data elements.
</p>
<p>
I <b>really</b> like JSON-LD for lots of things - it is a very elegant specification for
representing data where the data model is continuously expanding and evolving.
It surpasses any previous Semantic Web or XML representation for this use case.
My only quarrel is that unconstrained JSON-LD is completely unsuitable for 
API specifications where performance matters.  Perhaps at some point in the future
unconstrained JSON-LD won't be a performance handicap for scalable APIs - but for now, we must
build best practices around JSON-LD for APIs that makes it possible to parse data using 
only JSON libraries or not use JSON-LD at all for API work.
</p>
<p>
To demonstrate the issue of "infinite number of legitimate serializations", every time this 
web site is visited or auto-refreshed 
every 5 seconds, we will generate yet another completely legitimate JSON-LD 
serialization for the same JSON.  We use the "library" example JSON-LD 
from the 
<a href="http://json-ld.org/playground/" target="_blank">JSON Playground</a>
and the 
<a href="https://github.com/digitalbazaar/php-json-ld" target="_blank">PHP JSON-Library
</a> written by 
<a href="https://twitter.com/manusporny" target="_blank">Manu Sporny</a>.
</p>
<?php

// http://stackoverflow.com/questions/4356289/php-random-string-generator
$rs = str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

$first = substr($rs,0,10);
$second = substr($rs,10,10);

$nc = str_replace('"dc"','"'.$first.'"', $base_context);
$nc = str_replace('"ex"','"'.$second.'"', $nc);

$ncj = json_decode($nc);
$y = jsonld_compact($json, $ncj);
$z = json_encode($y, JSON_PRETTY_PRINT);

$z = str_replace($first,'<span style="color:red;">'.$first.'</span>',$z);
$z = str_replace($second,'<span style="color:orange;">'.$second.'</span>',$z);

echo("<pre id=\"fail\">\n");
echo($z);
echo("</pre>\n");

?>
<p>
Original JSON:
<pre>
<? echo($base_json); ?>
</pre>
</p>
<!--
<p>
Context used to compact JSON-LD:
<pre>
<? echo($nc); ?>
</pre>
</p>
-->
</div>
<script>
function upd() {
    $.get('fail.php', '', function (data) {
        $('#fail').html(data);
        setTimeout(upd, 4000);
    });
}
$(document).ready(function() {
    upd();
});
</script>
</body>


