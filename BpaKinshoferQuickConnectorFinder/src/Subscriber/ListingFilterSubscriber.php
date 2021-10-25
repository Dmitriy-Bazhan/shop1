<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Subscriber;

use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\EntityAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Content\Product\Events\ProductListingCriteriaEvent;
use Shopware\Core\Content\Product\Events\ProductListingCollectFilterEvent;

/**
 * Class ListingFilterSubscriber
 * @package Bpa\KinshoferQuickConnectorFinder\Subscriber
 */
class ListingFilterSubscriber implements EventSubscriberInterface
{

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProductListingCollectFilterEvent::class => 'onProductListingCollectFilter',
            ProductListingCriteriaEvent::class => 'onProductListingCriteria'
        ];
    }

    /**
     * @param ProductListingCollectFilterEvent $event
     * @return Filter
     */
    private function filterManufacturer(ProductListingCollectFilterEvent $event): Filter
    {
        $ids = [];

        $manufacturersTerm = $event->getRequest()->query->get('bpaQcfManufacturer', null);
        if($manufacturersTerm) {
            $ids = explode('|', $manufacturersTerm);
        }

        return new Filter(
            'bpaQcfManufacturer',
            !empty($ids),
            [
                new FilterAggregation(
                    'bpaQcfManufacturer',
                    new EntityAggregation(
                        'bpaQcfManufacturer',
                        'product.bpaQcfGroups.group.excavators.manufacturer.id',
                        'bpa_qcf_manufacturer'
                    ),
                    []
                )
            ],
            new EqualsAnyFilter('product.bpaQcfGroups.group.excavators.manufacturer.id', $ids),
            $ids
        );
    }

    /**
     * @param ProductListingCollectFilterEvent $event
     * @return Filter
     */
    private function filterType(ProductListingCollectFilterEvent $event): Filter
    {
        $ids = [];

        $typesTerm = $event->getRequest()->query->get('bpaQcfType', null);
        if($typesTerm) {
            $ids = explode('|', $typesTerm);
        }

        return new Filter(
            'bpaQcfType',
            !empty($ids),
            [
                new FilterAggregation(
                    'bpaQcfType',
                    new EntityAggregation(
                        'bpaQcfType',
                        'product.bpaQcfGroups.group.excavators.id',
                        'bpa_qcf_excavator'
                    ),
                    []
                )
            ],
            new EqualsAnyFilter('product.bpaQcfGroups.group.excavators.id', $ids),
            $ids
        );
    }

    /**
     * @param ProductListingCollectFilterEvent $event
     */
    public function onProductListingCollectFilter(ProductListingCollectFilterEvent $event): void
    {
        $filters = $event->getFilters();

        $filters->add($this->filterManufacturer($event));
        $filters->add($this->filterType($event));
    }

    /**
     * @param ProductListingCriteriaEvent $event
     */
    public function onProductListingCriteria(ProductListingCriteriaEvent $event): void
    {
        $event->getCriteria()->addAssociation('bpaQcfGroups.group.excavators');
    }
}
