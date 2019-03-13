<?php
// simple autoloader to load files/classes
function autoloader($file) {
    require_once(realpath(__DIR__ . "/$file.php"));
}

// register the autoloader
if (!spl_autoload_register('autoloader')) {
    throw new Exception("failed to register autoload function");
}

// load misc functions
autoloader('functions');

// buffer input from stdin
$input = '';

while (!feof(STDIN)) {
    $input .= fgets(STDIN);
}

// create parser for input
$parser = new Parser($input);

// parse the input into objects
$lawn    = $parser->lawn();
$mowers  = $parser->mowers();
$results = $parser->results();

// iterate over the mowers
foreach ($mowers as $index => $mower) {
    // mow the lawn with the mower
    $mower->mow($lawn);

    // get the new origin and the respective result
    $origin = $mower->getOrigin();
    $result = $results[$index];

    // compare and report
    if ($origin->equals($result)) {
        println("success: $index");
    } else {
        println("failure: $index");
    }
}

// report completed count
$completed = $index + 1;

println("completed: $completed");