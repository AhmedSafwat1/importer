<?php

use PHPUnit\Framework\TestCase;
use Safwat\Importer\Classes\SqlLiteDbAdapter;

class SqlLiteDbAdapterTest extends TestCase
{
    public function testConnectToSQLiteSuccess()
    {
        $database = new SqlLiteDbAdapter(__DIR__.'/../db/db.db');
        $this->assertTrue(
            $database->connect()
        );
    }

    public function testInsertSQLiteSuccess()
    {
        $product = [
            'id' => '1',
            'name' => 'test',
            'price' => 5,
        ];

        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($product)
            ->willReturn(true);

        $pdo = $this->createMock('PDO');
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn($product['id']);

        $database = new SqlLiteDbAdapter(__DIR__.'/../db/db.db');
        $database->setConnection($pdo);
        $database->insert('products', $product);
    }
}
