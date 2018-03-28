<?php

namespace Staempfli\Voucher\Library;

class Plugin{

    /** @var App */
    var $app = null;
    var $name = null;
    var $type = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getType(): array
    {
        return $this->type;
    }

    /**
     * @param array $type
     */
    public function setType(array $type)
    {
        $this->type = $type;
    }

}