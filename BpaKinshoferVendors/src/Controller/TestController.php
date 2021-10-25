<?php

namespace Bpa\KinshoferVendors\Controller;

use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\StorefrontController;
use Bpa\KinshoferVendors\Service\CustomerSynchronizationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class TestController extends StorefrontController
{
    protected $container;

    protected $locator;
    protected $synchronizationService;


    public function __construct(
        ContainerInterface $container,
        CustomerSynchronizationService $synchronizationService
    ) {
        $this->container = $container;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @Route("/test", name="frontend.frontend.test_test", methods={"GET"})
     */
    public function test(Request $request, Context $context)
    {
        dd(1);

        $customerRepo = $this->container->get('customer.repository');
        $salesChannelRepo = $this->container->get('sales_channel.repository');
        $storeLocatorRepo = $this->container->get('neti_store_locator.repository');
        $locatorSalesChannelRepo = $this->get('neti_store_sales_channel.repository');

        $locatorCriteria = new Criteria();

        $customerActiveCriteria = new Criteria();
        $customerActiveCriteria->addAssociation('defaultBillingAddress');
        $customerActiveCriteria->addFilter(new EqualsFilter('active', true));
        $customerActiveCriteria->addFilter(new EqualsFilter('customFields.vendorSearchEnabled', '1'));

        $customerNoActiveCriteria = new Criteria();
        $customerNoActiveCriteria->addAssociation('defaultBillingAddress');
        $customerNoActiveCriteria->addFilter(new EqualsFilter('active', false ));
        $customerNoActiveCriteria->addFilter(new EqualsFilter('customFields.vendorSearchEnabled', '1'));

        $locator = $storeLocatorRepo->search($locatorCriteria, $context)->getElements();
        $salesChannelLocator = $salesChannelRepo->search(new Criteria(),$context)->getElements();
        $dealersActive = $customerRepo->search($customerActiveCriteria, $context)->getElements();
        $dealersNoActive = $customerRepo->search($customerNoActiveCriteria, $context)->getElements();

        // dealers
        $add_data = array_filter( $dealersActive, function ( $val ) use ( $locator ) {
            return !array_key_exists( $val->getId(), $locator );
        });

        // dealers no active
        $update_data = array_filter( $dealersNoActive, function ( $val ) use ( $locator ) {
            return array_key_exists( $val->getId(), $locator );
        });

        //delete dealers
        $del_data = array_filter( $locator, function ( $val ) use ( $dealersActive ) {
            return !array_key_exists( $val->getId(), $dealersActive );
        });

        //sales channels
        $sales_channels = array_filter($salesChannelLocator, function($val) {
            return $val->getName() !== 'Headless';
        });

        $dealers = [];
        foreach ($add_data as $dealer) {
            $id = $dealer->getId();
            $active = $dealer->getActive();
            $company = $dealer->getDefaultBillingAddress()->getCompany();
            $city = $dealer->getDefaultBillingAddress()->getCity();
            $street = $dealer->getDefaultBillingAddress()->getStreet();
            $zipCode = $dealer->getDefaultBillingAddress()->getZipcode();
            $phone = $dealer->getDefaultBillingAddress()->getPhoneNumber();
            $email = $dealer->getEmail();
            $countryId = $dealer->getDefaultBillingAddress()->getCountryId();
            $stateId = $dealer->getDefaultBillingAddress()->getCountryStateId();

            $dealers[] = [
                'id' => $id,
                'active' => $active,
                'label' => $company,
                'street' => $street,
                'zipCode' => $zipCode,
                'city' => $city,
                'phone' => $phone,
                'email' => $email,
                'countryId' => $countryId,
                'countryStateId' => $stateId,
                'showAlways' => 'no'
            ];
        }

        $dealerInactive = [];
        foreach ($update_data as $dealer) {
            $id = $dealer->getId();

            $dealerInactive[] = [
                'id' => $id,
                'active' => false,
            ];
        }

        $deleteDealers = [];
        foreach ($del_data as $dealer) {
            $id = $dealer->getId();

            $deleteDealers[] = [
                'id' => $id,
            ];
        }

        $checkDealers = $storeLocatorRepo->search(new Criteria(), $context);

        $dealerChangedAddress = [];
        $dealerChangedAddressDel = [];

        foreach ($checkDealers as $dealer) {

            if (array_key_exists($dealer->getId(), $dealersActive)) {
                if (
                    $dealersActive[$dealer->getId()]->getDefaultBillingAddress() &&
                    (($dealer->getCity() !== $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCity()) ||
                    ($dealer->getStreet() !== $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getStreet()) ||
                    ($dealer->getPhone() !== $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getPhoneNumber()) ||
                    ($dealer->getCountryId() !== $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCountryId()) ||
                    ($dealer->getCountryStateId() !== $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCountryStateId()) ||
                    ($dealer->getZipCode() !== $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getZipCode()) ||
                    ($dealer->getEmail() !== $dealersActive[$dealer->getId()]->getEmail()))
                ) {
                    $dealerChangedAddress[] = [
                        'id' => $dealer->getId(),
                        'active' => $dealersActive[$dealer->getId()]->getActive(),
                        'label' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCompany(),
                        'street' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getStreet(),
                        'zipCode' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getZipCode(),
                        'city' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCity(),
                        'phone' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getPhoneNumber(),
                        'email' => $dealersActive[$dealer->getId()]->getEmail(),
                        'countryId' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCountryId(),
                        'countryStateId' => $dealersActive[$dealer->getId()]->getDefaultBillingAddress()->getCountryStateId(),
                        'showAlways' => 'no'
                    ];

                    $dealerChangedAddressDel [] = [
                        'id' => $dealer->getId(),
                    ];
                }
            }
        }
//dd($dealers, $dealerChangedAddress, $dealerInactive, $dealerInactive);
        try {
            if ($dealerChangedAddress) {
                $storeLocatorRepo->delete($dealerChangedAddressDel, $context);
                $storeLocatorRepo->upsert($dealerChangedAddress, $context);

                $salesChannels = [];
                foreach ($sales_channels as $channel) {
                    foreach ($dealerChangedAddressDel as $dealerId) {
                        $salesChannels[] = [
                            'storeId' => $dealerId['id'],
                            'salesChannelId' => $channel->getId()
                        ];
                    }
                }
            }

            if ($dealers) {
                $storeLocatorRepo->upsert($dealers, $context);

                $dealerss = $storeLocatorRepo->search(new Criteria(), $context)->getEntities()->getIds();
                $salesChannels = [];

                foreach ($sales_channels as $channel) {
                    foreach ($dealerss as $dealerId) {
                        $salesChannels[] = [
                            'storeId' => $dealerId,
                            'salesChannelId' => $channel->getId()
                        ];
                    }
                }

                $locatorSalesChannelRepo->upsert($salesChannels, $context);
            }

            if ($dealerInactive) $storeLocatorRepo->update($dealerInactive, $context);

            if ($deleteDealers) $storeLocatorRepo->delete($deleteDealers, $context);

        } catch (\Exception $e) {
            dd($e);
        }

            dd(
                'hallo'
            );
    }

    /**
     * @Route("/tests", name="frontend.frontend.test_tests", methods={"GET"})
     */
    public function checkChangeAddress(Context $context)
    {
        $this->synchronizationService->synchronization();
        dd('hallo');
    }
}
