<?php

namespace OCA\Docflow\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Methods extends Entity implements JsonSerializable {

    protected $methodsId;
    protected $desc;
    protected $exclusivityM;

    public function __construct() {
        $this->addType('id','integer');
    }

    public function jsonSerialize() {
        return [
            'methods_id' => $this->methodsId,
            'desc' => $this->desc,
            'exclusivity_m' => $this->exclusivityM
        ];
    }
}