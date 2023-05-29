<?php

namespace OCA\Docflow\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Anonymization extends Entity implements JsonSerializable {

    protected $sensitiveDataId;
    protected $data;
    protected $exclusivityS;
    protected $methods;

    public function __construct() {
        $this->addType('id','integer');
        $this->methods = [];
    }

    public function addMethods($methods){

        array_push($this->methods, $methods);
    }

    public function jsonSerialize() {
        return [
            'sensitive_data_id' => $this->sensitiveDataId,
            'data' => $this->data,
            'exclusivity_s' => $this->exclusivityS,
            'methods' => $this->methods
        ];
    }
}