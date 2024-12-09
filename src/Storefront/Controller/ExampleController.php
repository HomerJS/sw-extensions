<?php declare(strict_types=1);

namespace Ihor\Frame2\Storefront\Controller;

use Ihor\Frame2\Core\Content\Example\SalesChannel\AbstractExampleRoute;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class ExampleController extends StorefrontController
{
    public function __construct(
        private readonly AbstractExampleRoute $route
    ) {
    }

    #[Route(path: '/example', name: 'frontend.example.search', defaults: ['XmlHttpRequest' => 'true', '_entity' => 'product'], methods: ['GET', 'POST'])]
    public function load(Criteria $criteria, SalesChannelContext $context, Request $request): Response
    {
        return $this->route->load($criteria, $context, $request);
    }
}