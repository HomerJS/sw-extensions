<?php declare(strict_types=1);

namespace Ihor\Frame2\Subscriber;

use Ihor\Frame2\Core\Content\Example\SalesChannel\CachedExampleRoute;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Adapter\Cache\CacheInvalidator;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CacheInvalidationSubscriber implements EventSubscriberInterface
{
    private CacheInvalidator $cacheInvalidator;

    public function __construct(CacheInvalidator $cacheInvalidator)
    {
        $this->cacheInvalidator = $cacheInvalidator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // The EntityWrittenContainerEvent is a generic event that is always thrown when an entities are written. This contains all changed entities
            EntityWrittenContainerEvent::class => [
                ['invalidate', 2001]
            ],
        ];
    }

    public function invalidate(EntityWrittenContainerEvent $event): void
    {
        // check if own entity written. In some cases you want to use the primary keys for further cache invalidation
        $changes = $event->getPrimaryKeys(ProductDefinition::ENTITY_NAME);

        // no example entity changed? Then the cache does not need to be invalidated
        if (empty($changes)) {
            return;
        }

        $this->cacheInvalidator->invalidate([
            CachedExampleRoute::buildName()
        ]);
    }
}