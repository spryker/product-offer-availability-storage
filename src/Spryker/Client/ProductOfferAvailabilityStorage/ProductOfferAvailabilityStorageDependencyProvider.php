<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientBridge;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStoreClientBridge;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceBridge;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceBridge;

class ProductOfferAvailabilityStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';

    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addStoreClient($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductOfferAvailabilityStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductOfferAvailabilityStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductOfferAvailabilityStorageToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductOfferAvailabilityStorageToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
