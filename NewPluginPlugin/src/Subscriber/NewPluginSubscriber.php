<?php

namespace NewPluginPlugin\Subscriber;

use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Storefront\Page\Product\ProductPageCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class NewPluginSubscriber implements EventSubscriberInterface
{
    private $productRepository;

    /**
     * @param EntityRepositoryInterface $productRepository
     */
    public function __construct(EntityRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
//        $this->container = $container;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductPageLoadedEvent::class => 'onProductPage',
            ProductPageCriteriaEvent::class => 'onProductCriteriaPage',
        ];
    }

    /**
     * @param ProductPageLoadedEvent $event
     */
    public function onProductPage(ProductPageLoadedEvent $event)
    {
        $context = $event->getContext();
        $productId = $event->getPage()->getProduct()->id;
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $productId));

        $products = $this->productRepository->search($criteria, $context)->getElements();

        foreach ($products as $key => $product){
            $newPrice = $product->cheapestPrice->getPrice()->first()->getGross() * 2.25;
        }

        $event->getPage()->addExtension('my_things', new ArrayStruct([
            'new_price' => $newPrice]));
    }

    /**
     * @param ProductPageCriteriaEvent $event
     */
    public function onProductCriteriaPage(ProductPageCriteriaEvent $event)
    {
//        $event->getCriteria()->addAssociation('');
//        dump($event->getCriteria()->getAssociations());
//        die();
    }
}