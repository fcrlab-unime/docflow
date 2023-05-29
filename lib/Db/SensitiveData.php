<?php

namespace OCA\Docflow\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class SensitiveData extends Entity implements JsonSerializable {

    protected $sensitiveDataId;
    protected $data;
    protected $exclusivityS;

    public function __construct() {
        $this->addType('id','integer');
    }

    public function jsonSerialize() {
        return [
            'sensitive_data_id' => $this->sensitiveDataId,
            'data' => $this->data,
            'exclusivity_s' => $this->exclusivityS
        ];
    }
}