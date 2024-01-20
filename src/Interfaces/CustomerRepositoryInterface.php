<?php

namespace Safwat\Importer\Interfaces;

interface CustomerRepositoryInterface
{
    public function findByName(string $name): mixed;

    public function add(array $data): mixed;
}
