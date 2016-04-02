
JSON-LD Performance Sucks for API Specs
---------------------------------------

This is a very simple test simulating parsing of a JSON-only document versus a JSON-LD
document.  The code is super-simple.  Since JSON-LD requires the document be first parsed
with JSON and then augmented by JSON-LD to run an A/B performance test we simply turn 
on and off the additional required JSON-LD step and time it.

This code uses the JSON-LD PHP library from Manu Sporny at:

https://github.com/digitalbazaar/php-json-ld

I use the profile sample JSON-LD for the Product at:

http://json-ld.org/playground/

Methodology of the code - it is qute simple:

    require_once "jsonld.php";

    $x = file_get_contents('product.json');
    $result = array();
    for($i=0;$i<1000;$i++) {
       $y = json_decode($x);
       $y = jsonld_compact($y, "http://schema.org/");
       $result[] = $y;
    }

To run the JSON-only version simply comment out the `jsonld_compact` call.
We reuse the $y variable to make sure we don't double store any memory 
and accumulate the 1000 parsed results in an array to get a sense of whether
or not there is a different memory size for JSON or JSON-LD.

I used `/usr/bin/time` on my MacBook Pro 15 as the test.

Output of the test runs
-----------------------

    si-csev15-mbp:php-json-sucks-02 csev$ /usr/bin/time -l php j-test.php
            0.09 real         0.08 user         0.00 sys
      17723392  maximum resident set size
             0  average shared memory size
             0  average unshared data size
             0  average unshared stack size
          4442  page reclaims
             0  page faults
             0  swaps
             0  block input operations
             6  block output operations
             0  messages sent
             0  messages received
             0  signals received
             0  voluntary context switches
             6  involuntary context switches
    si-csev15-mbp:php-json-sucks-02 csev$ 
    si-csev15-mbp:php-json-sucks-02 csev$ /usr/bin/time -l php jl-test.php
          167.58 real         4.94 user         0.51 sys
      17534976  maximum resident set size
             0  average shared memory size
             0  average unshared data size
             0  average unshared stack size
          4428  page reclaims
             0  page faults
             0  swaps
             0  block input operations
             0  block output operations
         14953  messages sent
         24221  messages received
             0  signals received
          2998  voluntary context switches
          6048  involuntary context switches


Interpreting the results
------------------------

* Memory usage is equivalent - actually slightly lower for the JSON-LD - 
that is kind of impressive and probably leads to a small net benefit for 
ling-lived document-style data.   Supporting multiple equivalent 
serialized forms may save space at the cost of processing.

* Real time for the JSON-LD is nearly 2000X more costly than JSON - well beyond
three orders of magnitude

* CPU time for the JSON-LD is about 70X more costly - almost 2 orders of 
magnitude more costly

Some notes for the "Fans of JSON-LD"
------------------------------------

* Of course the extra order of magnitude increase in real-time 
is due to the many repeated re-retrievals of the context documents.
JSON-LD evangelists will talk about "caching" - this of course is an irrelevant argument
because virtually all of the shared hosting PHP servers do not allow caching so at 
least in PHP the "caching fixes this" is a useless argument.  Any normal PHP application 
*will* be forced to re-retrieve and re-parse the context documents on every 
request / response cycle.

* The two orders of magnitude increase in the CPU time is harder to explain away.
The evangelists will claim that a caching solution would cache the post-parsed 
versions of the document - but given that the original document is one JSON document
and there are five context documents - the additional parsing from string to JSON
would only explain a 5X increase in CPU time - not a 70X increase in CPU time.  My guess
is that even with pre-parsed documents the additional order of magnitude is due to the
need to loop through the structures over and over, to dectect many levels of *potential*
indirtection between prefixes, contexts, and possible aliases for prefixes or aliases.

Conclusion
----------

JSON-LD sucks for APIs - period.   Its out of the box performance is abhorrent.  

Some of the major performance suckage can be explained away if we could magically 
improve hosting plans, and make the most magical of JSON-LD implementation - but 
even with this there is over an order of magintude of performance cost to parse 
JSON-LD than to parse JSON because of the requirment to transform an infinite number 
of equivalent forms into a single cannonical form.

Ultimately it means if a large scale operator started using JSON-LD bases APIs heavily 
to enable a distributed LMS - so we get to the point where the core servers are spending
more time servicing standards-based API calls rather than generating UI markup - it will
require somewhere between 10 and 100 times more compute power to support JSON-LD than simply
supporting JSON.









