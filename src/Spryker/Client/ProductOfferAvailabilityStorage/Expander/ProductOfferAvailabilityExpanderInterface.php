<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Expander;

interface ProductOfferAvailabilityExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function expandProductOfferStorageTransfersWithAvailability(array $productOfferStorageTransfers): array;
}
