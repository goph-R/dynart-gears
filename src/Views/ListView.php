<?php

namespace Dynart\Gears\Views;

use Dynart\Minicore\Framework;

class ListView implements \JsonSerializable {

    private $queryName = '';
    private $headers = [];
    private $options = [];
    private $fields = [];

    public function __construct(string $queryName) {
        $this->queryName = $queryName;
    }

    public function setOptions(array $options) {
        $this->options = $options;
    }

    public function setFields(array $fields) {
        $this->fields = $fields;
    }

    public function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    public function jsonSerialize() {
        $query = Framework::instance()->get($this->queryName);
        $records = $query->findAll($this->fields, $this->options);
        $count = $query->findAllCount($this->options);
        return [
            'items' => $records,
            'count' => $count,
            'headers' => $this->headers
        ];
    }
}