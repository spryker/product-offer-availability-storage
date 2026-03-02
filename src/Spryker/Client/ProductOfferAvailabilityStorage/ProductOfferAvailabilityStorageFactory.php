<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStoreClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service\ProductOfferAvailabilityStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Expander\ProductOfferAvailabilityExpander;
use Spryker\Client\ProductOfferAvailabilityStorage\Expander\ProductOfferAvailabilityExpanderInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReader;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReaderInterface;

class ProductOfferAvailabilityStorageFactory extends AbstractFactory
{
    public function createProductOfferAvailabilityStorageReader(): ProductOfferAvailabilityStorageReaderInterface
    {
        return new ProductOfferAvailabilityStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getUtilEncodingService(),
        );
    }

    public function createProductOfferAvailabilityExpander(): ProductOfferAvailabilityExpanderInterface
    {
        return new ProductOfferAvailabilityExpander(
            $this->createProductOfferAvailabilityStorageReader(),
            $this->getStoreClient(),
        );
    }

    public function getStorageClient(): ProductOfferAvailabilityStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORAGE);
    }

    public function getStoreClient(): ProductOfferAvailabilityStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORE);
    }

    public function getSynchronizationService(): ProductOfferAvailabilityStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    public function getUtilEncodingService(): ProductOfferAvailabilityStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilityStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
