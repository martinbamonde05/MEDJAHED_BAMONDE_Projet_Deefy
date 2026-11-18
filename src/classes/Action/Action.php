<?php
declare(strict_types=1);

namespace deefy\classes\Action;

abstract class Action {
    protected ?string $http_method = null;

    public function __construct() {
        $this->http_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    abstract public function execute(): string;
}