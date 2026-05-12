<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductOfferAvailabilityStorageRepositoryInterface
{
    /**
     * @param array<string> $productOfferStockIds
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function getProductOfferAvailabilityRequestsByProductOfferStockIds(array $productOfferStockIds): array;

    /**
     * @param array $productOfferIds
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function getProductOfferAvailabilityRequestsByProductOfferIds(array $productOfferIds): array;

    /**
     * @param array $omsProductOfferReservationIds
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function getProductOfferAvailabilityRequestsByOmsProductOfferReservationIds(array $omsProductOfferReservationIds): array;

    /**
     * @param array<int> $stockIds
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function getProductOfferAvailabilityRequestsByStockIds(array $stockIds): array;

    /**
     * @param array<string> $productOfferReferences
     *
     * @return array<string, array<string, \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage>> First level keys are product offer references, second level keys are store names.
     */
    public function getProductOfferAvailabilityStorageMapByProductOfferReferences(array $productOfferReferences): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SpyProductOfferAvailabilityStorageEntityTransfer>
     */
    public function getFilteredProductOfferAvailabilityStorageEntityTransfers(FilterTransfer $filterTransfer, array $ids): array;
}
