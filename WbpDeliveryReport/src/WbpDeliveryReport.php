<?php declare(strict_types=1);

namespace WbpDeliveryReport;

require_once(realpath(__DIR__ . '/../vendor/autoload.php'));

use Doctrine\DBAL\Connection;
use Exception;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Doctrine\RetryableQuery;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class WbpDeliveryReport extends Plugin
{
    private const CUSTOM_FIELD_SET_WAREHOUSE = 'order_reports_warehouse_set';
    private const CUSTOM_FIELD_SET_ORDER_REPORTS = 'order_reports_set';

    /**
     * @param InstallContext $installContext
     * @throws Exception
     */
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);

        try {
            $this->createCustomFields($installContext->getContext());
            $directory = $this->container->get('kernel')->getProjectDir() . '/files/wbporderreport';
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param UninstallContext $uninstallContext
     * @throws Exception
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        try {
            $this->deleteCustomFields(self::CUSTOM_FIELD_SET_WAREHOUSE);
            $this->deleteCustomFields(self::CUSTOM_FIELD_SET_ORDER_REPORTS);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

    /**
     * @param Context $context
     * @return array
     */
    private function createCustomFields(Context $context)
    {
        $customFieldsRepository = $this->container->get('custom_field_set.repository');

        $customFieldsRepository->create([
            [
                'name' => self::CUSTOM_FIELD_SET_WAREHOUSE,
                'config' => [
                    'label' => [
                        'en-GB' => 'Product warehouse',
                        'de-DE' => 'Produktlager',
                    ],
                ],
                'relations' => [['entityName' => 'product']],
                'customFields' => [
                    [
                        'name' => 'custom_warehouse',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Warehouse',
                                'de-DE' => 'Lagerhaus'
                            ],
                            'customFieldPosition' => 1
                        ],
                    ]
                ]
            ],
            [
                'name' => self::CUSTOM_FIELD_SET_ORDER_REPORTS,
                'config' => [
                    'label' => [
                        'en-GB' => 'Order reports',
                        'de-DE' => 'Berichte bestellen',
                    ],
                ],
                'relations' => [['entityName' => 'order']],
                'customFields' => [
                    [
                        'name' => 'custom_driver1',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Driver first',
                                'de-DE' => 'Fahrer zuerst'
                            ],
                            'customFieldPosition' => 1
                            ]
                    ], [
                        'name' => 'custom_driver2',
                        'type' => CustomFieldTypes::TEXT,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Driver second',
                                'de-DE' => 'Fahrer zweiter'
                            ],
                            'customFieldPosition' => 1
                        ]
                    ], [
                        'name' => 'custom_driver1_start',
                        'type' => CustomFieldTypes::DATETIME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Driver first start date',
                                'de-DE' => 'Erstes Startdatum des Fahrers'
                            ],
                            'customFieldPosition' => 3
                        ]
                    ], [
                        'name' => 'custom_driver1_end',
                        'type' => CustomFieldTypes::DATETIME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Driver first end date',
                                'de-DE' => 'Erstes Enddatum des Fahrers'
                            ],
                            'customFieldPosition' => 4
                        ]
                    ], [
                        'name' => 'custom_driver2_start',
                        'type' => CustomFieldTypes::DATETIME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Driver second start date',
                                'de-DE' => 'Zweiter Starttermin des Fahrers'
                            ],
                            'customFieldPosition' => 5
                        ]
                    ], [
                        'name' => 'custom_driver2_end',
                        'type' => CustomFieldTypes::DATETIME,
                        'config' => [
                            'label' => [
                                'en-GB' => 'Driver second end date',
                                'de-DE' => 'Zweites Enddatum des Fahrers'
                            ],
                            'customFieldPosition' => 6
                        ]
                    ]
                ]
            ]
        ], $context);
    }

    /**
     * @param $customFieldSet
     */
    private function deleteCustomFields($customFieldSet)
    {
        $connection = $this->container->get(Connection::class);

        $query = new RetryableQuery(
            $connection->prepare("DELETE FROM `custom_field_set` WHERE `name` = :name")
        );

        $query->execute(['name' => $customFieldSet]);
    }
}
