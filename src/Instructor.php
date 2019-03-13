<?php
// instructor is an iterable instruction set for a mower
class Instructor implements Iterator {
    public const FORWARD = 'F';
    public const LEFT    = 'L';
    public const RIGHT   = 'R';

    public const INSTRUCTIONS = [
        self::FORWARD,
        self::LEFT,
        self::RIGHT
    ];

    protected $_instructions;
    protected $_index;

    public function __construct($instructions) {
        $this->setInstructions($instructions);
        $this->rewind();
    }

    public function setInstructions($instructions) {
        $instructions = str_split($instructions);

        array_walk($instructions, function ($instruction) {
            if (!in_array($instruction, self::INSTRUCTIONS)) {
                throw new Exception("invalid instruction: $instruction");
            }
        });

        $this->_instructions = $instructions;
    }

    public function getInstructions() {
        return $this->_instructions;
    }

    public function getInstruction($index) {
        if (!$this->valid()) {
            throw new Exception("no instruction at: $index");
        }

        return $this->getInstructions()[$index];
    }

    public function current() {
        return $this->getInstruction($this->_index);
    }

    public function next() {
        $this->_index++;
    }

    public function key() {
        return $this->_index;
    }

    public function valid() {
        return $this->_index < count($this->getInstructions());
    }

    public function rewind() {
        $this->_index = 0;
    }
}