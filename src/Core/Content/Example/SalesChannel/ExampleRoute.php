<?php declare(strict_types=1);

namespace Ihor\Frame2\Core\Content\Example\SalesChannel;

use Shopware\Core\Checkout\Customer\CustomerException;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\RateLimiter\Exception\RateLimitExceededException;
use Shopware\Core\Framework\RateLimiter\RateLimiter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['store-api']])]
class ExampleRoute extends AbstractExampleRoute
{
    public function __construct(
        private readonly RateLimiter $rateLimiter,
        private readonly RequestStack $requestStack
    ) {
    }

    public function getDecorated(): AbstractExampleRoute
    {
        throw new DecorationPatternException(self::class);
    }

    #[Route(path: '/store-api/example', name: 'store-api.example.search', defaults: ['_entity' => 'product'], methods: ['GET','POST'])]
    public function load(Criteria $criteria, SalesChannelContext $context, Request $request): ExampleRouteResponse
    {
        // Limit ip address for example
        if ($this->requestStack->getMainRequest() !== null) {
            $cacheKey = $this->requestStack->getMainRequest()->getClientIp();
            $this->rateLimiter->ensureAccepted('example_route', $cacheKey);
        }

        // if action was successfully, reset limit

        if (isset($cacheKey)) {
            //@$this->rateLimiter->reset('example_route', $cacheKey);
        }

        return new ExampleRouteResponse(new ProductEntity());
    }
}