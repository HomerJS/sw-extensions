<?php declare(strict_types=1);

namespace Ihor\Frame2\Core\Content\Example\SalesChannel;

use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\System\SalesChannel\StoreApiResponse;

/**
 * Class ExampleRouteResponse
 * @property EntitySearchResult<ProductCollection> $object
 */
class ExampleRouteResponse extends StoreApiResponse
{
    public function getExamples(): ProductCollection
    {
        return $this->object->getEntities();
    }
}