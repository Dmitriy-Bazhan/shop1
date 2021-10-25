<?php declare(strict_types=1);

namespace Bpa\KinshoferVendors\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class SynchronizationTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'bpa_kinshofer_vendors.synhronize_customer';
    }

    public static function getDefaultInterval(): int
    {
        return 60000; //10min
    }
}
