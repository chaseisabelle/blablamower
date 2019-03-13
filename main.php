<?php
/*
 * this is just a simple "script" solution for the mower challenge
 * please see /src for the oop solution
 */

// fail on any error
set_error_handler(function ($code, $message, $file, $line) {
    print "$file:$line $message\n";
    exit(1);
}, E_ALL | E_NOTICE | E_WARNING | E_STRICT);

// usage vars
$lawn    = [];
$mowers  = [];
$results = [];

// track the line count
$count = 0;

// watch for results
$result = false;

// read input
while (!feof(STDIN)) {
    // fetch line from stdin
    $line = input($count++);

    // parse the line appropriately
    switch (true) {
        case $line === 'result': //<< is this the result line?
            $result = true;

            break;
        case $result: //<< are we parsing results?
            $matches = regex('/^(\d+)\s+(\d+)\s+([NSEW])$/', $line);

            $results[] = [
                'x'         => intval($matches[1]),
                'y'         => intval($matches[2]),
                'direction' => $matches[3]
            ];

            break;
        case $count === 1: //<< is this the first line that has lawn dimensions?
            $matches = regex('/^(\d+)\s+(\d+)$/', $line);

            $lawn = [
                'width' => intval($matches[1]),
                'depth' => intval($matches[2])
            ];

            break;
        case $count % 2 === 0: //<< is this a mower origin line?
            $matches = regex('/^(\d+)\s+(\d+)\s+([NSEW])$/', $line);

            $mower = [
                'origin' => [
                    'x'         => intval($matches[1]),
                    'y'         => intval($matches[2]),
                    'direction' => $matches[3]
                ]
            ];

            // the next line should be the instructions
            $line = input($count++);

            regex('/^[FLR]+$/', $line);

            $mower['instructions'] = str_split($line);

            $mowers[] = $mower;

            break;
        default: //<< oh boy, what happened here?
            trigger_error("invalid line: $line");
    }
}

// check some things
if (count($mowers) !== count($results)) {
    trigger_error('mower and result count mismatch');
}

// mow the lawn
foreach ($mowers as $index => $mower) {
    $origin = $mower['origin'] ?? null;

    if (!$origin) {
        trigger_error("no mower origin: $index");
    }

    foreach ($mower['instructions'] as $instruction) {
        switch ($instruction) {
            case 'F':
                switch ($origin['direction']) {
                    case 'N':
                        $origin['y']++;

                        break;
                    case 'S':
                        $origin['y']--;

                        break;
                    case 'E':
                        $origin['x']++;

                        break;
                    case 'W':
                        $origin['x']--;

                        break;
                    default:
                        trigger_error("invalid direction: $origin[direction]");
                }

                if ($origin['x'] < 0) {
                    $origin['x'] = 0;
                }

                if ($origin['x'] > $lawn['width']) {
                    $origin['x'] = $lawn['width'];
                }

                if ($origin['y'] < 0) {
                    $origin['y'] = 0;
                }

                if ($origin['y'] > $lawn['depth']) {
                    $origin['y'] = $lawn['depth'];
                }

                break;
            case 'L':
                switch ($origin['direction']) {
                    case 'N':
                        $origin['direction'] = 'W';

                        break;
                    case 'S':
                        $origin['direction'] = 'E';

                        break;
                    case 'E':
                        $origin['direction'] = 'N';

                        break;
                    case 'W':
                        $origin['direction'] = 'S';

                        break;
                    default:
                        trigger_error("invalid direction: $origin[direction]");
                }

                break;
            case 'R':
                switch ($origin['direction']) {
                    case 'N':
                        $origin['direction'] = 'E';

                        break;
                    case 'S':
                        $origin['direction'] = 'W';

                        break;
                    case 'E':
                        $origin['direction'] = 'S';

                        break;
                    case 'W':
                        $origin['direction'] = 'N';

                        break;
                    default:
                        trigger_error("invalid direction: $origin[direction]");
                }

                break;
            default:
                trigger_error("invalid instruction: $instruction");
        }

        $mowers[$index]['origin'] = $origin;
    }
}

// check the results
foreach ($mowers as $index => $mower) {
    $origin = $mower['origin'] ?? null;
    $result = $results[$index] ?? null;

    if (!$origin) {
        trigger_error("no origin: $index");
    }

    if (!$result) {
        trigger_error("no result: $result");
    }

    switch (true) {
        case $result['x'] !== $origin['x']:
            trigger_error("failure: $index $result[x] !== $origin[x]");

            break;
        case $result['y'] !== $origin['y']:
            trigger_error("failure: $index $result[y] !== $origin[y]");

            break;
        case $result['direction'] !== $origin['direction']:
            trigger_error("failure: $index $result[direction] !== $origin[direction]");

            break;
        default:
            print "success: $index\n";
    }
}

// get funcy
function input($count = '?') {
    // fetch line from stdin
    $line = fgets(STDIN);

    // check if failure
    if ($line === false) {
        trigger_error("failed to read line: $count");
    }

    // trim the hedges
    return trim($line);
}

function regex($pattern, $line) {
    if (!preg_match($pattern, $line, $matches)) {
        trigger_error("failed to parse result: $line");
    }

    return $matches;
}