<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Bpa\KinshoferQuickConnectorFinder\Core\QuickConnectorFinder\BpaQcfExcavatorCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;

/**
 * Class FinderService
 * @package Bpa\KinshoferQuickConnectorFinder\Service
 */
class FinderService
{

    /**
     * @var EntityRepositoryInterface
     */
    private $bpaQcfExcavatorRepository;

    /**
     * FinderService constructor.
     * @param EntityRepositoryInterface $bpaQcfExcavatorRepository
     */
    public function __construct(
        EntityRepositoryInterface $bpaQcfExcavatorRepository
    )
    {
        $this->bpaQcfExcavatorRepository = $bpaQcfExcavatorRepository;
    }

    /**
     * @param Context $context
     * @param string $manufacturerId
     * @return BpaQcfExcavatorCollection
     */
    public function findExcavatorByManufacturer(Context $context, string $manufacturerId): BpaQcfExcavatorCollection
    {
        $criteria = new Criteria();

        $criteria->addSorting(new FieldSorting('type', 'ASC'));
        $criteria->addFilter(new EqualsFilter('manufacturerId', $manufacturerId));

        return $this->bpaQcfExcavatorRepository->search($criteria, $context)->getEntities();
    }

}
