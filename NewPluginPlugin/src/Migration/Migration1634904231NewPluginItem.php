<?php declare(strict_types=1);

namespace NewPluginPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1634904231NewPluginItem extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1634904231;
    }

    public function update(Connection $connection): void
    {
        $query = '
                CREATE TABLE IF NOT EXISTS `a_new_plugin_item` (
                `id` binary(16) NOT NULL,
                `item` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` datetime(3) NOT NULL,
                `updated_at` datetime(3) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
