<?php
// origin is a point (x, y) and a direction
class Origin {
    public const NORTH = 'N';
    public const SOUTH = 'S';
    public const EAST  = 'E';
    public const WEST  = 'W';

    public const DIRECTIONS = [
        self::NORTH,
        self::SOUTH,
        self::EAST,
        self::WEST
    ];

    protected $_x;
    protected $_y;
    protected $_direction;

    public function __construct($x, $y, $direction) {
        $this->setX($x);
        $this->setY($y);
        $this->setDirection($direction);
    }

    public function setX($x) {
        $this->_x = $this->_checkXY($x);
    }

    public function setY($y) {
        $this->_y = $this->_checkXY($y);
    }

    public function setXY($x, $y) {
        $this->setX($x);
        $this->setY($y);
    }

    public function setDirection($direction) {
        if (!in_array($direction, self::DIRECTIONS)) {
            throw new Exception("invalid direction: $direction");
        }

        $this->_direction = $direction;
    }

    public function getX() {
        return $this->_x;
    }

    public function getY() {
        return $this->_y;
    }

    public function getDirection() {
        return $this->_direction;
    }

    public function equals($origin) {
        return $this->getX() === $origin->getX()
            && $this->getY() === $origin->getY()
            && $this->getDirection() === $origin->getDirection();
    }

    protected function _checkXY($xy) {
        $xy = intval($xy);

        if ($xy < 0) {
            throw new Exception("invalid coordinate: $xy");
        }

        return $xy;
    }
}