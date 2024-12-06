<?php declare(strict_types=1);

namespace Ihor\Frame2\Core\Content\Example\SalesChannel;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['store-api']])]
class ExampleRouteDecorator extends AbstractExampleRoute
{
    public function __construct(
        private readonly EntityRepository $exampleRepository,
        private readonly  AbstractExampleRoute $exampleRoute
    ) {
    }

    public function getDecorated(): AbstractExampleRoute
    {
        return $this->exampleRoute;
    }

    #[Route(path: '/store-api/example', name: 'store-api.example.search', defaults: ['_entity' => 'category'], methods: ['GET', 'POST'])]
    public function load(Criteria $criteria, SalesChannelContext $context): ExampleRouteResponse
    {
        // We must call this function when using the decorator approach
        $exampleResponse = $this->exampleRoute->load($criteria, $context);

        // do some custom stuff
        $exampleResponse->headers->add([ 'cache-control' => "max-age=10000" ]);

        return $exampleResponse;
    }
}