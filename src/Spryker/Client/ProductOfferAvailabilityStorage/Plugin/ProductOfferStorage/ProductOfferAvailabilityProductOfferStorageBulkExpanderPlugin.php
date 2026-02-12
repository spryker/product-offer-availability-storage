<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Plugin\ProductOfferStorage;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageBulkExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageFactory getFactory()
 */
class ProductOfferAvailabilityProductOfferStorageBulkExpanderPlugin extends AbstractPlugin implements ProductOfferStorageBulkExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Fetches availability data by product offer references.
     * - Expands product offer storage transfers with `isNeverOutOfStock` and `stockQuantity` properties.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function expand(array $productOfferStorageTransfers): array
    {
        return $this->getFactory()
            ->createProductOfferAvailabilityExpander()
            ->expandProductOfferStorageTransfersWithAvailability($productOfferStorageTransfers);
    }
}
