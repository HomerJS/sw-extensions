<?php declare(strict_types=1);

namespace Ihor\Frame2\Core\Content\Example\SalesChannel;

use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Adapter\Cache\AbstractCacheTracer;
use Shopware\Core\Framework\Adapter\Cache\CacheCompressor;
use Shopware\Core\Framework\Adapter\Cache\CacheStateSubscriber;
use Shopware\Core\Framework\Adapter\Cache\CacheValueCompressor;
use Shopware\Core\Framework\DataAbstractionLayer\Cache\EntityCacheKeyGenerator;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Util\Json;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;

#[Route(defaults: ['_routeScope' => ['store-api']])]
class CachedExampleRoute extends AbstractExampleRoute
{
    private AbstractExampleRoute $decorated;

    private TagAwareAdapterInterface $cache;

    private EntityCacheKeyGenerator $generator;

    private AbstractCacheTracer $tracer;

    private array $states;

    private LoggerInterface $logger;

    public function __construct(
        AbstractExampleRoute $decorated,
        TagAwareAdapterInterface $cache,
        EntityCacheKeyGenerator $generator,
        AbstractCacheTracer $tracer,
        LoggerInterface $logger
    ) {
        $this->decorated = $decorated;
        $this->cache = $cache;
        $this->generator = $generator;
        $this->tracer = $tracer;

        // declares that this route can not be cached if the customer is logged in
        $this->states = [CacheStateSubscriber::STATE_LOGGED_IN];
        $this->logger = $logger;
    }

    public function getDecorated(): AbstractExampleRoute
    {
        return $this->decorated;
    }

    #[Route(path: '/store-api/example', name: 'store-api.example.search', defaults: ['_entity' => 'product'], methods: ['GET','POST'])]
    public function load(Criteria $criteria, SalesChannelContext $context, Request $request): ExampleRouteResponse
    {
        // The context is provided with a state where the route cannot be cached
        if ($context->hasState(...$this->states)) {
            return $this->getDecorated()->load($criteria, $context, $request);
        }

        $key = $this->generateKey($context, $criteria);

        if ($key === null) {
            return $this->getDecorated()->load($criteria, $context, $request);
        }

        $value = $this->cache->get($key, function (ItemInterface $item) use ($context, $criteria, $request) {
            $name = self::buildName();
            $response = $this->tracer->trace($name, fn () => $this->getDecorated()->load($criteria, $context, $request));

            $item->tag(array_merge(
            // get traced tags and configs
                $this->tracer->get(self::buildName()),
                [self::buildName()]
            ));

            return CacheValueCompressor::compress($response);
        });

        return CacheValueCompressor::uncompress($value);
    }

    public static function buildName(): string
    {
        return 'example-route';
    }

    private function generateKey(SalesChannelContext $context, Criteria $criteria): string
    {
        $parts = [
            self::buildName(),
            // generate a hash for the route criteria
            $this->generator->getCriteriaHash($criteria),
            // generate a hash for the current context
            $this->generator->getSalesChannelContextHash($context),
        ];

        return md5(Json::encode($parts));
    }
}