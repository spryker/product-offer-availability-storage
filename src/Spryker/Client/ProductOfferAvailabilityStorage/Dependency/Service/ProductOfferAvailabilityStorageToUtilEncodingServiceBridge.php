<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferAvailabilityStorage\Dependency\Service;

use InvalidArgumentException;

class ProductOfferAvailabilityStorageToUtilEncodingServiceBridge implements ProductOfferAvailabilityStorageToUtilEncodingServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct($utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc Deprecated: `false` is deprecated, always use `true` for array return.
     * @param int|null $depth
     * @param int|null $options
     *
     * @throws \InvalidArgumentException
     *
     * @return array<mixed>|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        if ($assoc === false) {
            throw new InvalidArgumentException('Param #2 `$assoc` must be `true` as return of type `object` is not accepted.');
        }

        /** @phpstan-var array<mixed>|null */
        return $this->utilEncodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }
}
