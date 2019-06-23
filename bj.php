<?php

$base_json = '{
  "@context": {
    "dc": "http://purl.org/dc/elements/1.1/",
    "ex": "http://example.org/vocab#",
    "xsd": "http://www.w3.org/2001/XMLSchema#",
    "ex:contains": {
      "@type": "@id"
    }
  },
  "@graph": [
    {
      "@id": "http://example.org/library",
      "@type": "ex:Library",
      "ex:contains": "http://example.org/library/the-republic"
    },
    {
      "@id": "http://example.org/library/the-republic",
      "@type": "ex:Book",
      "dc:creator": "Plato",
      "dc:title": "The Republic",
      "ex:contains": "http://example.org/library/the-republic#introduction"
    },
    {
      "@id": "http://example.org/library/the-republic#introduction",
      "@type": "ex:Chapter",
      "dc:description": "An introductory chapter on The Republic.",
      "dc:title": "The Introduction"
    }
  ]
}';

$json = json_decode($base_json);
if ( $json == null ) die("Unable to parse context");

$base_context = '{
  "dc": "http://purl.org/dc/elements/1.1/",
  "ex": "http://example.org/vocab#",
  "xsd": "http://www.w3.org/2001/XMLSchema#",
  "ex:contains": {
    "@type": "@id"
  }
}';

$context = json_decode($base_context);

if ( $context == null ) die("Unable to parse context");

