<?php

namespace Safwat\Importer\Interfaces;

interface ProductRepositoryInterface
{
    public function findByName(string $name): mixed;
}
