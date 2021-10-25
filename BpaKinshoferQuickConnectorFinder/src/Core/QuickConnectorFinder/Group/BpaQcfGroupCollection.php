<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class BpaQcfGroupCollection
 * @package Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\Group
 */
class BpaQcfGroupCollection extends EntityCollection
{

    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BpaQcfGroupEntity::class;
    }

}
