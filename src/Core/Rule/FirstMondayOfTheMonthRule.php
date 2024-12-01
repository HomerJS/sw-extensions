<?php declare(strict_types=1);

namespace Ihor\Frame2\Core\Rule;

use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Rule\RuleScope;
use Symfony\Component\Validator\Constraints\Type;

class FirstMondayOfTheMonthRule extends Rule
{
    protected bool $isFirstMondayOfTheMonth;

    public function __construct()
    {
        parent::__construct();

        // Will be overwritten at runtime. Reflects the expected value.
        $this->isFirstMondayOfTheMonth = false;
    }

    public function getName(): string
    {
        return 'first_monday';
    }

    public function match(RuleScope $scope): bool
    {
        $isFirstMondayOfTheMonth = $this->isCurrentlyFirstMondayOfTheMonth(date("Y-m-d") );

        // Checks if the shop owner set the rule to "First monday => Yes"
        if ($this->isFirstMondayOfTheMonth) {
            // Shop administrator wants the rule to match if there's currently the first monday of the month.
            return $isFirstMondayOfTheMonth;
        }

        // Shop administrator wants the rule to match if there's currently NOT the first monday of the month.
        return !$isFirstMondayOfTheMonth;
    }

    public function getConstraints(): array
    {
        return [
            'isFirstMondayOfTheMonth' => [new Type('bool')]
        ];
    }

    private function isCurrentlyFirstMondayOfTheMonth($dateString)
    {
        $date = new \DateTime($dateString);
        $dayOfWeek = (int) $date->format('w');

        // Check if it's Monday (1 is Monday)
        if ($dayOfWeek !== 1) {
            return false;
        }

        // Check if the date is within the first seven days of the month
        $dayOfMonth = (int) $date->format('j');
        if ($dayOfMonth > 7) {
            return false;
        }

        // If it passed both checks, it's the first Thursday of the month
        return true;
    }
}