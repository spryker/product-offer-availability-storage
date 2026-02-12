<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Expander;

use Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Client\ProductOfferAvailabilityStorageToStoreClientInterface;
use Spryker\Client\ProductOfferAvailabilityStorage\Reader\ProductOfferAvailabilityStorageReaderInterface;

class ProductOfferAvailabilityExpander implements ProductOfferAvailabilityExpanderInterface
{
    public function __construct(
        protected ProductOfferAvailabilityStorageReaderInterface $productOfferAvailabilityStorageReader,
        protected ProductOfferAvailabilityStorageToStoreClientInterface $storeClient,
    ) {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function expandProductOfferStorageTransfersWithAvailability(array $productOfferStorageTransfers): array
    {
        $productOfferReferences = $this->extractProductOfferReferences($productOfferStorageTransfers);
        if (!$productOfferReferences) {
            return $productOfferStorageTransfers;
        }

        $storeName = $this->storeClient->getCurrentStore()->getName();
        $productOfferAvailabilityStorageTransfers = $this->productOfferAvailabilityStorageReader
            ->getByProductOfferReferences($productOfferReferences, $storeName);

        $productOfferAvailabilityStorageTransfersIndexedByReference = $this->getProductOfferAvailabilityStorageTransfersIndexedByProductOfferReference(
            $productOfferAvailabilityStorageTransfers,
        );

        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $productOfferReference = $productOfferStorageTransfer->getProductOfferReference();
            $productOfferAvailabilityStorageTransfer = $productOfferAvailabilityStorageTransfersIndexedByReference[$productOfferReference] ?? null;

            if (!$productOfferReference || !$productOfferAvailabilityStorageTransfer) {
                continue;
            }

            $productOfferStorageTransfer
                ->setIsNeverOutOfStock($productOfferAvailabilityStorageTransfer->getIsNeverOutOfStock())
                ->setStockQuantity($productOfferAvailabilityStorageTransfer->getAvailability()?->toFloat() ?? 0);
        }

        return $productOfferStorageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<string>
     */
    protected function extractProductOfferReferences(array $productOfferStorageTransfers): array
    {
        $productOfferReferences = [];

        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $productOfferReferences[] = $productOfferStorageTransfer->getProductOfferReferenceOrFail();
        }

        return array_unique($productOfferReferences);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer> $productOfferAvailabilityStorageTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer>
     */
    protected function getProductOfferAvailabilityStorageTransfersIndexedByProductOfferReference(array $productOfferAvailabilityStorageTransfers): array
    {
        $availabilityStorageTransfersIndexedByReference = [];

        foreach ($productOfferAvailabilityStorageTransfers as $productOfferAvailabilityStorageTransfer) {
            $productOfferReference = $productOfferAvailabilityStorageTransfer->getProductOfferReferenceOrFail();
            $availabilityStorageTransfersIndexedByReference[$productOfferReference] = $productOfferAvailabilityStorageTransfer;
        }

        return $availabilityStorageTransfersIndexedByReference;
    }
}
