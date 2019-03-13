<?php
class Mower {
    protected $_origin;
    protected $_instructor;

    public function __construct($origin, $instructor) {
        $this->setOrigin($origin);
        $this->setInstructor($instructor);
    }

    public function setOrigin($origin) {
        $this->_origin = $origin;
    }

    public function setInstructor($instructor) {
        $this->_instructor = $instructor;
    }

    public function getOrigin() {
        return $this->_origin;
    }

    // applies the intructor's instructions to the mower for a given lawn
    public function mow($lawn) {
        // instructor is traversable instruction set
        foreach ($this->_instructor as $instruction) {
            switch ($instruction) {
                case Instructor::FORWARD:
                    $this->_move($lawn);

                    break;
                case Instructor::LEFT:
                case Instructor::RIGHT:
                    $this->_turn($instruction);

                    break;
                default:
                    throw new Exception("invalid instruction: $instruction");
            }
        }
    }

    // move the mower one square forward with respect to it's current direction
    protected function _move($lawn) {
        $origin    = $this->getOrigin();
        $x         = $origin->getX();
        $y         = $origin->getY();
        $direction = $origin->getDirection();
        $width     = $lawn->getWidth();
        $depth     = $lawn->getDepth();

        switch ($direction) {
            case Origin::NORTH:
                inc_if_less($y, $depth);

                break;
            case Origin::SOUTH:
                dec_if_greater($y);

                break;
            case Origin::EAST:
                inc_if_less($x, $width);

                break;
            case Origin::WEST:
                dec_if_greater($x);

                break;
            default:
                throw new Exception("invalid direction: $direction");
        }

        $this->getOrigin()->setXY($x, $y);
    }

    // turn the mower left/right with respect to it's current direction
    protected function _turn($instruction) {
        $left      = $instruction === Instructor::LEFT;
        $origin    = $this->getOrigin();
        $direction = $origin->getDirection();

        switch ($direction) {
            case Origin::NORTH:
                $direction = $left ? Origin::WEST : Origin::EAST;

                break;
            case Origin::SOUTH:
                $direction = $left ? Origin::EAST : Origin::WEST;

                break;
            case Origin::EAST:
                $direction = $left ? Origin::NORTH : Origin::SOUTH;

                break;
            case Origin::WEST:
                $direction = $left ? Origin::SOUTH : Origin::NORTH;

                break;
            default:
                throw new Exception("invalid direction: $direction");
        }

        $this->getOrigin()->setDirection($direction);
    }
}