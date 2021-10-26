<?php declare(strict_types=1);

namespace WbpDeliveryReport\Subscriber;

use Psr\Container\ContainerInterface;
use Shopware\Storefront\Page\Checkout\Finish\CheckoutFinishPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WarehouseSubscriber implements EventSubscriberInterface
{

    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutFinishPageLoadedEvent::class => 'onCheckoutFinishPageLoadedEvent',
        ];
    }

    /**
     * @param CheckoutFinishPageLoadedEvent $event
     */
    public function onCheckoutFinishPageLoadedEvent(CheckoutFinishPageLoadedEvent $event): void
    {
        $items = $event->getPage()->getOrder()->getLineItems()->getElements();
        $customWarehouse = null;

        foreach ($items as $item) {
            if (array_key_exists('customFields', $item->getPayload())) {
                if (array_key_exists('custom_warehouse', $item->getPayload()['customFields'])) {
                    if (!$customWarehouse) {
                        $customWarehouse = $item->getPayload()['customFields']['custom_warehouse'];
                    } else {
                        $customWarehouse .= ', ' .$item->getPayload()['customFields']['custom_warehouse'];
                    }
                }
            }
        }

        $repository = $this->container->get('order.repository');
        $repository->update([
            [
                'id' => $event->getPage()->getOrder()->getId(),
                'customFields' => ['custom_warehouse' => $customWarehouse]
            ]
        ], $event->getContext());
    }
}
