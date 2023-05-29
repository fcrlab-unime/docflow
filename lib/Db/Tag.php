<?php

namespace OCA\Docflow\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Tag extends Entity implements JsonSerializable {

    protected $tagId;
    protected $tagString;
    protected $label;

    public function __construct() {
        $this->addType('id','integer');
    }

    public function jsonSerialize() {
        return [
            'tag_id' => $this->tagId,
            'tag_string' => $this->tagString,
            'label' => $this->label
        ];
    }
}