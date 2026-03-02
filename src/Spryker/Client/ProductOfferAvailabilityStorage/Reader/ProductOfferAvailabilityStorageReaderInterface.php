<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Reader;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;

interface ProductOfferAvailabilityStorageReaderInterface
{
    public function findByProductOfferReference(string $productOfferReference, string $storeName): ?ProductOfferAvailabilityStorageTransfer;

    /**
     * @param array<string> $productOfferReferences
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer>
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array;
}
