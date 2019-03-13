<?php
class Lawn {
    protected $_width;
    protected $_depth;

    public function __construct($width, $depth) {
        $this->setWidth($width);
        $this->setDepth($depth);
    }

    public function setWidth($width) {
        $this->_width = $this->_checkWidthDepth($width);
    }

    public function setDepth($depth) {
        $this->_depth = $this->_checkWidthDepth($depth);
    }

    public function getWidth() {
        return $this->_width;
    }

    public function getDepth() {
        return $this->_depth;
    }

    public function getDimensions() {
        return [
            $this->getWidth(),
            $this->getDepth()
        ];
    }

    protected function _checkWidthDepth($wd) {
        $wd = intval($wd);

        if ($wd <= 0) {
            throw new Exception("invalid width/depth: $wd");
        }

        return $wd;
    }
}