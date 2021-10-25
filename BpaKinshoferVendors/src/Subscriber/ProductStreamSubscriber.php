<?php declare(strict_types=1);

namespace Bpa\KinshoferVendors\Subscriber;

use Psr\Container\ContainerInterface;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedCriteriaEvent;
use Shopware\Core\Checkout\Order\Event\OrderStateChangeCriteriaEvent;
use Shopware\Core\Content\Product\Events\ProductSearchResultEvent;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Struct\ArrayEntity;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Event\StorefrontRenderEvent;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Confirm\CheckoutConfirmPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Offcanvas\OffcanvasCartPageLoadedEvent;
use Shopware\Storefront\Page\PageLoadedEvent;
use Shopware\Storefront\Page\Product\ProductPageCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductStreamSubscriber implements EventSubscriberInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var SystemConfigService
     */
    private $systemConfig;

    /**
     * @var array|bool|float|int|string|null
     */
    private $streamsIds;

    /**
     * @param ContainerInterface $container
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(ContainerInterface $container, SystemConfigService $systemConfigService)
    {
        $this->container = $container;
        $this->systemConfig = $systemConfigService;
        $this->streamsIds = $this->systemConfig->get('BpaKinshoferVendors.config.productStreamsOnlyAbout');
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            StorefrontRenderEvent::class => 'onStorefront',
            ProductEvents::PRODUCT_LOADED_EVENT => 'onProductStreamLoaded',
            ProductPageCriteriaEvent::class => 'onProductCriteria',
            OffcanvasCartPageLoadedEvent::class => 'onOffcanvasPageLoaded',
            CheckoutCartPageLoadedEvent::class => 'onCheckoutPageLoaded',
            CheckoutConfirmPageLoadedEvent::class => 'onConfirmPage'
        ];
    }

    /**
     * @param $event
     */
    public function onProductCriteria($event): void
    {
        $event->getCriteria()->addAssociation('streams');
    }

    /**
     * @param $event
     */
    public function onStorefront($event)
    {
       $channels = $this->getSalesChannels($event->getContext());

       $event->getParameters()['page']->addExtension('salesChannel', new arrayEntity($channels));
    }

    /**
     * @param $event
     */
    public function onProductStreamLoaded($event)
    {
        $productStreamRepo = $this->container->get('product_stream.repository');

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('id', $this->streamsIds));

        $productStreams = $productStreamRepo->search($criteria, $event->getContext());

//       dd($productStreams);
    }

    /**
     * @param PageLoadedEvent $event
     */
    public function onOffcanvasPageLoaded(PageLoadedEvent $event)
    {
        $cart = $event->getPage()->getCart();

        if ($event->getPage()->getCart()) {
            foreach ($event->getPage()->getCart()->getLineItems()->getElements() as $lineItem) {
                $this->addStatusOnlyRequest($event->getContext(), $lineItem->getId(), $lineItem, $cart);
            }
        }
    }

    /**
     * @param PageLoadedEvent $event
     */
    public function onCheckoutPageLoaded(PageLoadedEvent $event)
    {
        $cart = $event->getPage()->getCart();

        if ($cart) {
            foreach ($event->getPage()->getCart()->getLineItems()->getElements() as $lineItem) {
                $this->addStatusOnlyRequest($event->getContext(), $lineItem->getId(), $lineItem, $cart);
            }
        }
    }

    /**
     * @param PageLoadedEvent $event
     */
    public function onConfirmPage(PageLoadedEvent $event)
    {
        $salesChannelId = $event->getContext()->getSource()->getSalesChannelId();
        $channels = $this->getSalesChannels($event->getContext());
        $currentCustomerGroupId = $event->getSalesChannelContext()->getCurrentCustomerGroup()->getId();

        $dealers = $this->systemConfig->get('BpaKinshoferVendors.config.customerGroupDealers');
        $cart = $event->getPage()->getCart();

        if ($cart) {
            foreach ($event->getPage()->getCart()->getLineItems()->getElements() as $lineItem) {
                $this->addStatusOnlyRequest($event->getContext(), $lineItem->getId(), $lineItem, $cart);
            }
        }

        if (array_key_exists('onlyRequest', $cart->getExtensions())) {
            $response = new RedirectResponse("/checkout/cart");
            $response->send();
            exit;
        }

        if ($channels['Zapchasti'] !== $salesChannelId && !in_array($currentCustomerGroupId, $dealers)) {
            $response = new RedirectResponse("/checkout/cart");
            $response->send();
            exit;
        }
    }

    /**
     * @param $context
     * @param $productId
     * @param $lineItem
     * @param $cart
     */
    private function addStatusOnlyRequest($context, $productId, $lineItem, $cart)
    {
        $productRepository = $this->container->get('product.repository');

        $criteria = new Criteria();
        $criteria->addAssociation('streams');
        $criteria->addFilter(new EqualsFilter('id', $productId));
        $criteria->addFilter(new EqualsAnyFilter('product.streams.id', $this->streamsIds));

        $product = $productRepository->search($criteria, $context);

        if ($product->getEntities()->first()) {
            if ($productId === $product->getEntities()->first()->getId()) {
                $cart->addExtension('onlyRequest', new arrayEntity([
                    'status' => 'true',
                ]));

                $lineItem->addExtension('onlyRequest', new arrayEntity([
                    'status' => 'true',
                ]));
            }
        }
    }

    /**
     * @param $context
     * @return array
     */
    private function getSalesChannels($context)
    {
        $salesChannelRepo = $this->container->get('sales_channel.repository');
        $salesChannel = $salesChannelRepo->search(new Criteria(), $context);

        $channels = [];

        foreach ($salesChannel->getElements() as $key => $element) {
            $channels[$element->getName()] = $key;
        }

        return $channels;
    }
}
