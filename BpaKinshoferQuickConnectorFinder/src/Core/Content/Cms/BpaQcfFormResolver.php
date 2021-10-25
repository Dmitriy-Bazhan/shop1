<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Core\Content\Cms;

use Shopware\Core\Framework\Context;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;

/**
 * Class BpaQcfFormResolver
 * @package Bpa\KinshoferQuickConnectorFinder\Core\Content\Cms
 */
class BpaQcfFormResolver extends AbstractCmsElementResolver
{

    /**
     * @var EntityRepositoryInterface
     */
    private $bpaQcfManufacturerRepository;

    /**
     * BpaQcfFormResolver constructor.
     * @param EntityRepositoryInterface $bpaQcfManufacturerRepository
     */
    public function __construct(
        EntityRepositoryInterface $bpaQcfManufacturerRepository
    )
    {
        $this->bpaQcfManufacturerRepository = $bpaQcfManufacturerRepository;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'bpa-qcf-form-e';
    }

    /**
     * @param CmsSlotEntity $slot
     * @param ResolverContext $resolverContext
     * @return CriteriaCollection|null
     */
    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $collection = new CriteriaCollection();

        return $collection->all() ? $collection : null;
    }

    /**
     * @param CmsSlotEntity $slot
     * @param ResolverContext $resolverContext
     * @param ElementDataCollection $result
     */
    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $criteria = new Criteria;
        $criteria->addSorting(new FieldSorting('name', 'ASC'));

        $slot->assign([
            'manufacturers' => $this->bpaQcfManufacturerRepository->search($criteria, Context::createDefaultContext())->getEntities()
        ]);
    }

}
