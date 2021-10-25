<?php declare(strict_types=1);

namespace Bpa\KinshoferVendors\ScheduledTask;

use Bpa\KinshoferVendors\Service\CustomerSynchronizationService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

class SynchronizationTaskHandler extends ScheduledTaskHandler
{

    private $synchronizationService;

    public function __construct(
        EntityRepositoryInterface $scheduledTaskRepository,
        CustomerSynchronizationService $synchronizationService
    )
    {
        parent::__construct($scheduledTaskRepository);
        $this->synchronizationService = $synchronizationService;
    }

    public static function getHandledMessages(): iterable
    {
        return [SynchronizationTask::class];
    }

    public function run(): void
    {
        $this->synchronizationService->synchronization();
    }
}
