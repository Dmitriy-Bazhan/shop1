<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

/**
 * Class KinshoferQuickConnectorFinder
 * @package Bpa\KinshoferQuickConnectorFinder
 */
class BpaKinshoferQuickConnectorFinder extends Plugin
{

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);

        $connection->executeQuery('DROP TABLE IF EXISTS `bpa_qcf_excavator_product`');
        $connection->executeQuery('DROP TABLE IF EXISTS `bpa_qcf_excavator`');
        $connection->executeQuery('DROP TABLE IF EXISTS `bpa_qcf_group`');
        $connection->executeQuery('DROP TABLE IF EXISTS `bpa_qcf_manufacturer`');
    }
}
