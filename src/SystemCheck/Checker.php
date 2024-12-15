<?php declare(strict_types=1);

namespace Ihor\Frame2\SystemCheck;

use Shopware\Core\Framework\SystemCheck\BaseCheck;
use Shopware\Core\Framework\SystemCheck\Check\Category;
use Shopware\Core\Framework\SystemCheck\Check\Result;
use Shopware\Core\Framework\SystemCheck\Check\Status;
use Shopware\Core\Framework\SystemCheck\Check\SystemCheckExecutionContext;

class Checker extends BaseCheck
{
    public function __construct(
        private readonly string $adapterType,
        private readonly string $installationPath,
        private readonly int $warningThresholdInMb
    ) {
    }

    public function category(): Category
    {
        // crucial for the system to function at all.
        return Category::SYSTEM;
    }

    public function name(): string
    {
        return 'myChecker';
    }

    protected function allowedSystemCheckExecutionContexts(): array
    {   // a potentially long-running check, because it has an IO operation.
        return SystemCheckExecutionContext::longRunning();
    }

    public function run(): Result
    {
        if ($this->adapterType !== 'local') {
            return new Result(name: $this->name(), status: Status::SKIPPED, message: 'Disk space check is only available for local file systems.', healthy: true);
        }

        $availableSpaceInMb = 30;
        if ($availableSpaceInMb < $this->warningThresholdInMb) {
            return new Result(name: $this->name(), status: Status::WARNING, message: sprintf('Available disk space is below the warning threshold of %s.', $this->warningThresholdInMb), healthy: true);
        }

        return new Result(name: $this->name(), status: Status::OK, message: 'Disk space is sufficient.', healthy: true);
    }
}