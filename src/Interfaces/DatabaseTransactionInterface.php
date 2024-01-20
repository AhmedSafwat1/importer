<?php

namespace Safwat\Importer\Interfaces;

/**
 * DatabaseInterface interface
 */
interface DatabaseTransactionInterface
{
    /**
     * Begin transaction
     *
     * @return void
     */
    public function beginTransaction();

    /**
     * Commit transaction
     *
     * @return void
     */
    public function commit();

    /**
     * Rollback transaction
     *
     * @return void
     */
    public function rollback();
}
