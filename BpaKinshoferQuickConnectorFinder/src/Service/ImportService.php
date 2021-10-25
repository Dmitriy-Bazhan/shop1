<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Service;

use Doctrine\DBAL\Connection;
use Exception;
use \RuntimeException;
use Shopware\Core\Framework\Context;
use InvalidArgumentException;
use Shopware\Core\Content\ImportExport\Processing\Reader\CsvReader;
use Shopware\Core\Content\ImportExport\Struct\Config;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Uuid\Uuid;
use \Doctrine\DBAL\DBALException;
use Shopware\Core\Content\ImportExport\Exception\UnexpectedFileTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImportService
 * @package Bpa\KinshoferQuickConnectorFinder\Service
 */
class ImportService
{

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $bpaQcfGroupRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $bpaQcfManufacturerRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $bpaQcfExcavatorRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $bpaQcfGroupProductRepository;

    /**
     * @var Context
     */
    private $defaultContext;

    /**
     * ImportService constructor.
     * @param Connection $connection
     * @param EntityRepositoryInterface $bpaQcfGroupRepository
     * @param EntityRepositoryInterface $bpaQcfManufacturerRepository
     * @param EntityRepositoryInterface $bpaQcfExcavatorRepository
     * @param EntityRepositoryInterface $bpaQcfGroupProductRepository
     */
    public function __construct(
        Connection $connection,
        EntityRepositoryInterface $bpaQcfGroupRepository,
        EntityRepositoryInterface $bpaQcfManufacturerRepository,
        EntityRepositoryInterface $bpaQcfExcavatorRepository,
        EntityRepositoryInterface $bpaQcfGroupProductRepository
    )
    {
        $this->connection = $connection;
        $this->bpaQcfGroupRepository = $bpaQcfGroupRepository;
        $this->bpaQcfManufacturerRepository = $bpaQcfManufacturerRepository;
        $this->bpaQcfExcavatorRepository = $bpaQcfExcavatorRepository;
        $this->bpaQcfGroupProductRepository = $bpaQcfGroupProductRepository;

        $this->defaultContext = Context::createDefaultContext();
    }

    /**
     * @param $resource
     * @return array
     */
    private function getRecords($resource): array
    {
        if (!\is_resource($resource)) {
            throw new InvalidArgumentException('Argument $resource is not a resource');
        }

        $reader = new CsvReader();
        $config = new Config([], [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape' => '\\',
            'withHeader' => true
        ]);

        $records = [];

        foreach ($reader->read($config, $resource, 0) as $row) {
            if (empty($row)) {
                continue;
            }

            $records[] = $row;
        }

        return $records;
    }

    /**
     * @return array
     */
    private function getManufacturersMaps(): array
    {
        try {
            $sql = 'SELECT LOWER(HEX(id)) as `id`, `name` FROM `bpa_qcf_manufacturer`;';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return array_column($stmt->fetchAll(), 'id', 'name');

        } catch (DBALException $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    private function getGroupsMaps(): array
    {
        try {
            $sql = 'SELECT LOWER(HEX(id)) as `id`, `name` FROM `bpa_qcf_group`;';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return array_column($stmt->fetchAll(), 'id', 'name');

        } catch (DBALException $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    private function getExcavatorsMaps(): array
    {
        try {
            $sql = 'SELECT LOWER(HEX(id)) as `id`, `type` FROM `bpa_qcf_excavator`;';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return array_column($stmt->fetchAll(), 'id', 'type');

        } catch (DBALException $e) {
            return [];
        }
    }

    /**
     * @return array
     */
    private function getProductMaps(): array
    {
        try {
            $sql = 'SELECT LOWER(HEX(id)) as `id`, `product_number` FROM `product`;';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            return array_column($stmt->fetchAll(), 'id', 'product_number');

        } catch (DBALException $e) {
            return [];
        }
    }

    /**
     * @param array $manufacturers
     * @return void
     */
    private function createManufacturers(array $manufacturers): void
    {
        $exists = $this->getManufacturersMaps();

        $upserts = [];
        foreach($manufacturers as $manufacturer) {
            if( !array_key_exists($manufacturer, $exists)) {
                $upserts[] = [
                    'id' => Uuid::randomHex(),
                    'name' => $manufacturer,
                    'createdAt' => date('Y-m-d H:i:s')
                ];
            }
        }

        if( !empty($upserts) ) {
            $this->bpaQcfManufacturerRepository->upsert($upserts, $this->defaultContext);
        }
    }

    /**
     * @param string $groupName
     * @return string|null
     */
    private function createGroup(string $groupName): ?string
    {
        try{
            $id = Uuid::randomHex();

            $this->bpaQcfGroupRepository->upsert([[
                'id' => $id,
                'name' => $groupName,
                'createdAt' => date('Y-m-d H:i:s')
            ]], $this->defaultContext);

            return $id;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param array $groups
     * @return void
     */
    private function createGroups(array $groups): void
    {
        $exists = $this->getGroupsMaps();

        $upserts = [];
        foreach($groups as $group) {
            if( !array_key_exists($group, $exists)) {
                $upserts[] = [
                    'id' => Uuid::randomHex(),
                    'name' => $group,
                    'createdAt' => date('Y-m-d H:i:s')
                ];
            }
        }

        if( !empty($upserts) ) {
            $this->bpaQcfGroupRepository->upsert($upserts, $this->defaultContext);
        }
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function detectType(UploadedFile $file): string
    {
        $extension = $file->guessClientExtension();

        if ($extension === 'csv' || $file->getClientOriginalExtension() === 'csv') {
            return 'text/csv';
        }

        return $file->getClientMimeType();
    }

    /**
     * @param array $records
     * @return array
     */
    private function prepareImport(array $records): array
    {
        if( !empty($records)) {
            $groups = array_unique(
                array_map(function($a) { return trim($a); },
                    array_column($records, 'Excavatorgroup'))
            );

            $manufacturers = array_unique(
                array_map(function($a) { return trim($a); },
                    array_column($records, 'Baggerhersteller'))
            );

            $this->createGroups($groups);
            $this->createManufacturers($manufacturers);
        }

        return [$this->getManufacturersMaps(), $this->getGroupsMaps()];
    }

    /**
     * @return void
     */
    private function clearExcavatorGroups(): void
    {
        $this->connection->exec('TRUNCATE TABLE `bpa_qcf_group_product`;');
    }

    /**
     * @param $resource
     * @return void
     */
    private function importExcavators($resource): void
    {
        $records = $this->getRecords($resource);

        list($manufacturerMaps, $groupMaps) = $this->prepareImport($records);
        $excavatorMaps = $this->getExcavatorsMaps();

        $upserts = [];
        foreach($records as $record) {
            if( isset($record['Baggertyp']) ) {
                $baggerType = trim($record['Baggertyp']);
                $baggerhersteller = trim($record['Baggerhersteller']);
                $baggerGroup = trim($record['Excavatorgroup']);

                if( !array_key_exists($baggerType, $excavatorMaps)) {
                    $id = Uuid::randomHex();
                    $field = 'createdAt';
                } else {
                    $id = $excavatorMaps[$baggerType];
                    $field = 'updatedAt';
                }

                $manufacturerId = $manufacturerMaps[$baggerhersteller] ?? null;
                $groupId = $groupMaps[$baggerGroup] ?? null;

                if($manufacturerId && $groupId) {
                    $upserts[] = [
                        'id' => $id,
                        'type' => $baggerType,
                        'manufacturerId' => $manufacturerId,
                        'groupId' => $groupId,
                        $field => date('Y-m-d H:i:s')
                    ];
                }
            }
        }

        if( !empty($upserts) ) {
            $this->bpaQcfExcavatorRepository->upsert($upserts, $this->defaultContext);
        }
    }

    /**
     * @param $resource
     * @return array
     */
    private function importExcavatorGroups($resource): array
    {
        $this->clearExcavatorGroups();

        $records = $this->getRecords($resource);
        $productMaps = $this->getProductMaps();
        $groupMaps = $this->getGroupsMaps();

        $upserts = [];
        $failsNoProduct = [];
        $failsNoGroup = [];

        foreach($records as $record) {
            if(isset($record['Excavatorgroup']) && isset($record['Artikelnummer'])) {
                if(isset($groupMaps[$record['Excavatorgroup']])) {
                    $groupId = $groupMaps[$record['Excavatorgroup']];
                } else {
                    $groupId = $this->createGroup($record['Excavatorgroup']);

                    if($groupId) {
                        $groupMaps[$record['Excavatorgroup']] = $groupId;
                    }
                }

                $productId = isset($productMaps[$record['Artikelnummer']]) ?
                $productMaps[$record['Artikelnummer']] : null;

                $basicSetProductId = isset($productMaps[$record['Installationsset Basic']]) ?
                $productMaps[$record['Installationsset Basic']] : null;

                $safetySetProductId = isset($productMaps[$record['Installationsset Safety']]) ?
                $productMaps[$record['Installationsset Safety']] : null;

                if( !$groupId || !$productId ) {
                    if(!$groupId) {
                        $failsNoGroup[] = $record['Excavatorgroup'];
                    }

                    if(!$productId) {
                        $failsNoProduct[] = $record['Artikelnummer'];
                    }

                    continue;
                }

                $upserts[] = [
                    'id' => Uuid::randomHex(),
                    'quickCoupler' => isset($record['Schnellwechsler']) ? (string)$record['Schnellwechsler'] : '',
                    'productNumber' => isset($record['Artikelnummer']) ? (string)$record['Artikelnummer'] : '',
                    'loadHook' => isset($record['Installationsset Basic']),
                    'basicSet' => isset($record['Installationsset Basic']) ? (string)$record['Installationsset Basic'] : '',
                    'safetySet' => isset($record['Installationsset Safety']) ? (string)$record['Installationsset Safety'] : '',
                    'groupId' => $groupId,
                    'productId' => $productId,
                    'basicSetProductId' => $basicSetProductId,
                    'safetySetProductId' => $safetySetProductId,
                    'createdAt' => date('Y-m-d H:i:s')
                ];

            }
        }

        $keyDelArray = [];
        $keyExistArray = [];

        foreach ($upserts as $key => $upsert) {
            $keyExist = $upsert['productId'] . $upsert['groupId'];

            if (in_array($keyExist, $keyExistArray)) {
                $keyDelArray[] = $key;
            } else {
                $keyExistArray[] = $keyExist;
            }
        }

        foreach ($keyDelArray as $array_key) {
            unset($upserts[$array_key]);
        }

        $upserts = array_values($upserts);

        if( !empty($upserts) ) {
            $this->bpaQcfGroupProductRepository->upsert($upserts, $this->defaultContext);
        }

        $fails = count($failsNoGroup) + count($failsNoProduct);

        return [
            'status' => $fails === 0,
            'fails' => [
                'count' => $fails,
                'noGroup' => $failsNoGroup,
                'noProduct' => $failsNoProduct
            ]
        ];
    }

    /**
     * @param UploadedFile $file
     * @param string $method
     * @return array|null
     */
    public function processImport(UploadedFile $file, string $method): ?array
    {
        if( !method_exists($this, $method) ) {
            throw new RuntimeException('Unknown import type');
        }

        $type = $this->detectType($file);
        if ($type !== 'text/csv') {
            throw new UnexpectedFileTypeException($file->getClientMimeType(), 'text/csv');
        }

        $resource = fopen($file->getRealPath(), 'r');

        try{
            if($resource) {
                $this->{$method}($resource);
                return [
                    'state' => 'succeeded',
                    'error' => null
                ];
            }

        } catch (Exception $e) {
            return [
                'state' => 'failed',
                'error' => $e->getMessage()
            ];
        } finally {
            fclose($resource);

            return [
                'state' => 'succeeded',
                'error' => null
            ];
        }
    }

}
