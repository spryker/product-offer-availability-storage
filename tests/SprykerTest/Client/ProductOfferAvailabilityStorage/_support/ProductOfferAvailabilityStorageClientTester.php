<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferAvailabilityStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStorageClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStoreClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageDependencyProvider;
use Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface getClient(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Client\ProductOfferAvailabilityStorage\PHPMD)
 */
class ProductOfferAvailabilityStorageClientTester extends Actor
{
    use _generated\ProductOfferAvailabilityStorageClientTesterActions;

    protected const string TEMPLATE_STORAGE_KEY = '%s:%s:%s';

    /**
     * @var array<string, string>
     */
    protected array $storageData = [];

    /**
     * @param string $currentStore
     *
     * @return void
     */
    public function mockStoreClient(string $currentStore): void
    {
        $storeTransfer = (new StoreTransfer())->setName($currentStore);

        $storeClientMock = $this->createStoreClientMockImplementingBothInterfaces($storeTransfer);

        $this->setDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORE, $storeClientMock);
        $this->setDependency(StorageDependencyProvider::CLIENT_STORE, $storeClientMock);
    }

    public function mockStorageClient(): void
    {
        $storageClientMock = Stub::makeEmpty(
            ProductOfferAvailabilityStorageToStorageClientInterface::class,
            [
                'getMulti' => function (array $keys) {
                    $result = [];
                    foreach ($keys as $key) {
                        if (isset($this->storageData[$key])) {
                            $result[$key] = $this->storageData[$key];
                        }
                    }

                    return $result;
                },
            ],
        );

        $this->setDependency(ProductOfferAvailabilityStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }

    protected function createStoreClientMockImplementingBothInterfaces(StoreTransfer $storeTransfer): object
    {
        return new class ($storeTransfer) implements ProductOfferAvailabilityStorageToStoreClientInterface, StorageToStoreClientInterface {
            public function __construct(protected StoreTransfer $storeTransfer)
            {
            }

            public function getCurrentStore(): StoreTransfer
            {
                return $this->storeTransfer;
            }

            public function isCurrentStoreDefined(): bool
            {
                return true;
            }
        };
    }

    /**
     * @param string $productOfferReference
     * @param array<string, mixed> $payload
     * @param string $store
     *
     * @return void
     */
    public function mockProductOfferAvailabilityStorageData(string $productOfferReference, array $payload, string $store): void
    {
        $productOfferAvailabilityKey = sprintf(
            static::TEMPLATE_STORAGE_KEY,
            ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_RESOURCE_NAME,
            strtolower($store),
            $productOfferReference,
        );

        $this->storageData[$productOfferAvailabilityKey] = json_encode($payload);
    }
}
