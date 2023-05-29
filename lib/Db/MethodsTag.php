<?php

namespace OCA\Docflow\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class MethodsTag extends Entity implements JsonSerializable {

    protected $methodsId;
    protected $desc;
    protected $exclusivityM;
    protected $default;
    protected $tagList;

    public function __construct() {
        $this->addType('id','integer');
    }

    public function jsonSerialize() {
        return [
            'methods_id' => $this->methodsId,
            'desc' => $this->desc,
            'exclusivity_m' => $this->exclusivityM,
            'default' => $this->default,
            'tag_list' => $this->tagList
        ];
    }
}