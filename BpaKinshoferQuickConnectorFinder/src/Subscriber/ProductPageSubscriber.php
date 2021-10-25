<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Subscriber;

use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelEntityLoadedEvent;
use Shopware\Core\System\SalesChannel\Event\SalesChannelProcessCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ProductPageSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductPageCriteriaEvent::class => 'onProductCriteriaPage',
            ProductPageLoadedEvent::class => 'onProductPage'
        ];
    }

    /**
     * @param ProductPageCriteriaEvent $event
     */
    public function onProductCriteriaPage(ProductPageCriteriaEvent $event)
    {
        $event->getCriteria()->addAssociation('bpaQcfGroups.group.excavators');
    }

    /**
     * @param ProductPageLoadedEvent $event
     */
    public  function onProductPage(ProductPageLoadedEvent $event)
    {
        $salesChannelProductRepository = $this->container->get('sales_channel.product.repository');

        if ($event->getPage()->getProduct()->getExtensions()['bpaQcfGroups'] && $event->getPage()->getProduct()->getExtensions()['bpaQcfGroups']->first()) {
            $basicSetProdId = $event->getPage()->getProduct()->getExtensions()['bpaQcfGroups']->first()->getBasicSetProductId();
            $safetySetProdId = $event->getPage()->getProduct()->getExtensions()['bpaQcfGroups']->first()->getSafetySetProductId();


            $salesChannelBaseProdCriteria = new Criteria();
            $salesChannelSafeProdCriteria = new Criteria();
            $salesChannelBaseProdCriteria->addFilter(new EqualsFilter('id', $basicSetProdId));
            $salesChannelSafeProdCriteria->addFilter(new EqualsFilter('id', $safetySetProdId));

            $basicSetProd = $salesChannelProductRepository->search($salesChannelBaseProdCriteria, $event->getSalesChannelContext())->first();
            $safetySetProd = $salesChannelProductRepository->search($salesChannelSafeProdCriteria, $event->getSalesChannelContext())->first();

            $setProd = [];
            if ($basicSetProd) {
                $basicSetProd->addExtension('typeProduct', new arrayEntity([
                    'setProduct' => 'basic'
                ]));
                $setProd[$basicSetProd->getId()] = $basicSetProd;
            }

            if ($safetySetProd) {
                $safetySetProd->addExtension('typeProduct', new arrayEntity([
                    'setProduct' => 'safety'
                ]));
                $setProd[$safetySetProd->getId()] = $safetySetProd;
            }

            $event->getPage()->getProduct()->addExtension('Accessoires', new arrayEntity([
                'setProduct' => $setProd
            ]));
        }
    }
}
