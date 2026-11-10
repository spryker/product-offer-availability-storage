<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery;
use Spryker\DecimalObject\Decimal;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferAvailabilityStorage\PHPMD)
 */
class ProductOfferAvailabilityStorageCommunicationTester extends Actor
{
    use _generated\ProductOfferAvailabilityStorageCommunicationTesterActions;

    protected function findProductOfferAvailabilityStorage(string $storeName, string $productOfferReference): ?SpyProductOfferAvailabilityStorage
    {
        return $this->getProductOfferAvailabilityStoragePropelQuery()
            ->filterByStore($storeName)
            ->findOneByProductOfferReference($productOfferReference);
    }

    public function getProductOfferAvailability(string $storeName, string $productOfferReference): Decimal
    {
        $productOfferAvailabilityStorageEntity = $this->findProductOfferAvailabilityStorage($storeName, $productOfferReference);

        if (!$productOfferAvailabilityStorageEntity) {
            return new Decimal(0);
        }

        return new Decimal($productOfferAvailabilityStorageEntity->getData()[ProductOfferAvailabilityStorageTransfer::AVAILABILITY]);
    }

    public function countProductOfferAvailabilityStorageEntities(string $storeName, string $productOfferReference): int
    {
        return $this->getProductOfferAvailabilityStoragePropelQuery()
            ->filterByStore($storeName)
            ->filterByProductOfferReference($productOfferReference)
            ->count();
    }

    public function haveProductOfferStockInNewWarehouse(
        ProductOfferTransfer $productOfferTransfer,
        StoreTransfer $storeTransfer,
        int $quantity
    ): ProductOfferStockTransfer {
        $productOfferStockTransfer = $this->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferStockTransfer::QUANTITY => $quantity,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [
                        $storeTransfer->getIdStoreOrFail(),
                    ],
                ],
            ],
        ]);
        $this->updateStock($productOfferStockTransfer->getStockOrFail()->setIsActive(true));

        return $productOfferStockTransfer;
    }

    protected function getProductOfferAvailabilityStoragePropelQuery(): SpyProductOfferAvailabilityStorageQuery
    {
        return SpyProductOfferAvailabilityStorageQuery::create();
    }
}
