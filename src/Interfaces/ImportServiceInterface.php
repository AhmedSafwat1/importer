<?php

namespace Safwat\Importer\Interfaces;

interface ImportServiceInterface
{
    public function process(string $file_path): mixed;
}
