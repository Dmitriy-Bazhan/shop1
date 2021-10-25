<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1621241660Excavator extends MigrationStep
{

    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1621241660;
    }

    /**
     * @param Connection $connection
     * @return void
     */
    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `bpa_qcf_manufacturer` (
              `id` binary(16) NOT NULL,
              `name` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,

              PRIMARY KEY (`id`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `bpa_qcf_group` (
                `id` binary(16) NOT NULL,
                `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` datetime(3) NOT NULL,
                `updated_at` datetime(3) DEFAULT NULL,

              PRIMARY KEY (`id`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `bpa_qcf_excavator` (
                `id` binary(16) NOT NULL,
                `type` VARCHAR(500) COLLATE utf8mb4_unicode_ci NOT NULL,
                `bpa_qcf_manufacturer_id` BINARY(16) NOT NULL,
                `bpa_qcf_group_id` BINARY(16) NOT NULL,
                `created_at` datetime(3) NOT NULL,
                `updated_at` datetime(3) DEFAULT NULL,

                PRIMARY KEY (`id`, `bpa_qcf_group_id`, `bpa_qcf_manufacturer_id`),
                CONSTRAINT `fk.bpa_qcf_excavator.manufacturer_id` FOREIGN KEY (`bpa_qcf_manufacturer_id`)
                    REFERENCES `bpa_qcf_manufacturer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                CONSTRAINT `fk.bpa_qcf_excavator.group_id` FOREIGN KEY (`bpa_qcf_group_id`)
                    REFERENCES `bpa_qcf_group` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `bpa_qcf_group_product` (
                `id` binary(16) NOT NULL,
                `quick_coupler` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `product_number` VARCHAR(255) DEFAULT NULL,
                `load_hook` TINYINT(1) unsigned NOT NULL,
                `basic_set` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `safety_set` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                `bpa_qcf_group_id` binary(16) NOT NULL,
                `product_id` binary(16) NOT NULL,
                `basic_set_product_id` binary(16) DEFAULT NULL,
                `safety_set_product_id` binary(16) DEFAULT NULL,
                `created_at` datetime(3) NOT NULL,
                `updated_at` datetime(3) DEFAULT NULL,

                PRIMARY KEY (`product_id`, `bpa_qcf_group_id`),
                CONSTRAINT `fk.bpa_qcf_ep.product_id` FOREIGN KEY (`product_id`)
                    REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.bpa_qcf_ep.group_id` FOREIGN KEY (`bpa_qcf_group_id`)
                    REFERENCES `bpa_qcf_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

    }

    /**
     * @param Connection $connection
     * @return void
     */
    public function updateDestructive(Connection $connection): void
    {
    }
}
