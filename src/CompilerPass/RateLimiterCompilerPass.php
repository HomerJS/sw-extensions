<?php declare(strict_types=1);

namespace Ihor\Frame2\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class RateLimiterCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        /** @var array<string, array<string, string>> $rateLimiterConfig */
        $rateLimiterConfig = $container->getParameter('shopware.api.rate_limiter');

        $rateLimiterConfig += Yaml::parseFile(__DIR__ . '/../Resources/config/rate_limiter.yaml');

        $container->setParameter('shopware.api.rate_limiter', $rateLimiterConfig);
    }
}