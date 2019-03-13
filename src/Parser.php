<?php
class Parser {
    // the input and result lines
    protected $_lines   = [];
    protected $_results = [];

    public function __construct($input) {
        // split the input into an array of lines
        $lines = explode("\n", $input);

        // check the return value
        if (!$lines) {
            throw new Exception("failed to explode input: $input");
        }

        // trim the lines of excess whitespace
        $lines = array_map(function ($line) {
            return trim($line);
        }, $lines);

        // filter any empty lines
        $lines = array_filter($lines, function ($line) {
            return $line !== '';
        });

        // are we parsing results or input?
        $results = false;

        // walk the lines
        foreach ($lines as $line) {
            if ($line === 'result') { //<< at results delimiter line?
                $results = true;
            } else if ($results) { //<< parsing results?
                $this->_results[] = $line;
            } else { //<< parsing input
                $this->_lines[] = $line;
            }
        }
    }

    // parse the lawn
    public function lawn() {
        $lines   = $this->_lines;
        $line    = array_shift($lines);
        $matches = $this->_applyRegex($line, '/^(\d+)\s+(\d+)$/');

        $width = $matches[1];
        $depth = $matches[2];

        $lawn = new Lawn($width, $depth);

        return $lawn;
    }

    // parse the mowers
    public function mowers() {
        // misc vars
        $mowers = [];
        $lines  = $this->_lines;

        // remove the first line (lawn)
        array_shift($lines);

        // chunk the origin and instruction lines together
        $chunks = array_chunk($lines, 2);

        // walk the mower input chunks
        foreach ($chunks as $chunk) {
            // check that we have both lines
            $size = count($chunk);

            if ($size !== 2) {
                throw new Exception("malformed mower input: $size");
            }

            // parse the origin
            $line   = array_shift($chunk);
            $origin = $this->_parseOrigin($line);

            // parse the instructions
            $line       = array_shift($chunk);
            $instructor = new Instructor($line);

            // build the mower
            $mower = new Mower($origin, $instructor);

            // append the mower
            $mowers[] = $mower;
        }

        return $mowers;
    }

    // parse and build the results
    public function results() {
        $results = $this->_results;

        $results = array_map(function ($line) {
            return $this->_parseOrigin($line);
        }, $results);

        return $results;
    }

    // parses an origin/result line
    protected function _parseOrigin($line) {
        $regex     = '/^(\d+)\s+(\d+)\s+([' . implode('', Origin::DIRECTIONS) . '])$/';
        $matches   = $this->_applyRegex($line, $regex);
        $x         = $matches[1];
        $y         = $matches[2];
        $direction = $matches[3];
        $origin    = new Origin($x, $y, $direction);

        return $origin;
    }

    // applies a regex to a single line and returns the matches
    protected function _applyRegex($line, $regex) {
        if (!preg_match($regex, $line, $matches)) {
            throw new Exception("failed to apply regex to line: $regex $line");
        }

        return $matches;
    }
}