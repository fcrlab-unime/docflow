<?php

namespace OCA\Docflow\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class MethodsData extends Entity implements JsonSerializable {

    protected $methodsDataId;
    protected $sensitiveDataId;
    protected $methodsId;
    protected $path;
    protected $default;
    protected $tag;

    public function __construct() {
        $this->addType('id','integer');
    }

    public function jsonSerialize() {
        return [
            'methods_data_id' => $this->methodsDataId,
            'sensitive_data_id' => $this->sensitiveDataId,
            'methods_id' => $this->methodsId,
            'path' => $this->path,
            'default' => $this->default,
            'tag' => $this->tag
        ];
    }
}