<?php declare(strict_types=1);

namespace Bpa\KinshoferVendors\Service;

use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class CustomerSynchronizationService
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function synchronization()
    {
        $defaultContext = \Shopware\Core\Framework\Context::createDefaultContext();

        $customerRepo = $this->container->get('customer.repository');
        $salesChannelRepo = $this->container->get('sales_channel.repository');
        $storeLocatorRepo = $this->container->get('neti_store_locator.repository');
        $locatorSalesChannelRepo = $this->container->get('neti_store_sales_channel.repository');

        $locatorCriteria = new Criteria();

        $customerActiveCriteria = new Criteria();
        $customerActiveCriteria->addAssociation('defaultBillingAddress');
        $customerActiveCriteria->addFilter(new EqualsFilter('active', true));
        $customerActiveCriteria->addFilter(new EqualsFilter('customFields.vendorSearchEnabled', '1'));

        $customerNoActiveCriteria = new Criteria();
        $customerNoActiveCriteria->addAssociation('defaultBillingAddress');
        $customerNoActiveCriteria->addFilter(new EqualsFilter('active', false ));
        $customerNoActiveCriteria->addFilter(new EqualsFilter('customFields.vendorSearchEnabled', '1'));

        $customerCriteria = new Criteria();
        $customerCriteria->addAssociation('defaultBillingAddress');
        $customerCriteria->addFilter(new EqualsFilter('customFields.vendorSearchEnabled', '1'));

        $locator = $storeLocatorRepo->search($locatorCriteria, $defaultContext)->getElements();
        $salesChannelLocator = $salesChannelRepo->search(new Criteria(),$defaultContext)->getElements();
        $dealersActive = $customerRepo->search($customerActiveCriteria, $defaultContext)->getElements();
        $dealersNoActive = $customerRepo->search($customerNoActiveCriteria, $defaultContext)->getElements();
        $allDealers = $customerRepo->search($customerCriteria, $defaultContext)->getElements();

        // dealers
        $add_data = array_filter( $dealersActive, function ( $val ) use ( $locator ) {
            return !array_key_exists( $val->getId(), $locator );
        });

        // dealers no active
        $dealer_no_active = array_filter( $dealersNoActive, function ( $val ) use ( $locator ) {
            return array_key_exists( $val->getId(), $locator );
        });

        // dealers active
        $dealer_active = array_filter( $dealersActive, function ( $val ) use ( $locator ) {
            return array_key_exists( $val->getId(), $locator );
        });

        //delete dealers
        $del_data = array_filter( $locator, function ( $val ) use ( $allDealers ) {
            return !array_key_exists( $val->getId(), $allDealers );
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
        foreach ($dealer_no_active as $dealer) {
            $id = $dealer->getId();

            $dealerInactive[] = [
                'id' => $id,
                'active' => false,
            ];
        }

        $dealerActive = [];
        foreach ($dealer_active as $dealer) {
            $id = $dealer->getId();

            $dealerInactive[] = [
                'id' => $id,
                'active' => true,
            ];
        }

        $deleteDealers = [];
        foreach ($del_data as $dealer) {
            $id = $dealer->getId();

            $deleteDealers[] = [
                'id' => $id,
            ];
        }

        // check change and update dealers address
        $checkDealers = $storeLocatorRepo->search(new Criteria(), $defaultContext);

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

        try {
            if ($dealerChangedAddress) {
                $storeLocatorRepo->delete($dealerChangedAddressDel, $defaultContext);
                $storeLocatorRepo->upsert($dealerChangedAddress, $defaultContext);

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
                $storeLocatorRepo->upsert($dealers, $defaultContext);

                $dealerss = $storeLocatorRepo->search(new Criteria(), $defaultContext)->getEntities()->getIds();
                $salesChannels = [];

                foreach ($sales_channels as $channel) {
                    foreach ($dealerss as $dealerId) {
                        $salesChannels[] = [
                            'storeId' => $dealerId,
                            'salesChannelId' => $channel->getId()
                        ];
                    }
                }

                $locatorSalesChannelRepo->upsert($salesChannels, $defaultContext);
            }

            if ($dealerInactive) $storeLocatorRepo->update($dealerInactive, $defaultContext);

            if ($dealerActive) $storeLocatorRepo->update($dealerActive, $defaultContext);

            if ($deleteDealers) $storeLocatorRepo->delete($deleteDealers, $defaultContext);

        } catch (\Exception $e) {

        }
    }
}
