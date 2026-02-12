<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferAvailabilityStorage\Plugin\ProductOfferStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\ProductOfferAvailabilityStorage\Plugin\ProductOfferStorage\ProductOfferAvailabilityProductOfferStorageBulkExpanderPlugin;
use SprykerTest\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferAvailabilityStorage
 * @group Plugin
 * @group ProductOfferStorage
 * @group ProductOfferAvailabilityProductOfferStorageBulkExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferAvailabilityProductOfferStorageBulkExpanderPluginTest extends Unit
{
    protected const string TEST_PRODUCT_OFFER_REFERENCE_1 = 'test-product-offer-reference-1';

    protected const string TEST_PRODUCT_OFFER_REFERENCE_2 = 'test-product-offer-reference-2';

    protected const string TEST_PRODUCT_OFFER_REFERENCE_3 = 'test-product-offer-reference-3';

    protected const string STORE_NAME_DE = 'DE';

    protected const string STORE_NAME_AT = 'AT';

    protected ProductOfferAvailabilityStorageClientTester $tester;

    /**
     * @dataProvider expandProductOfferStorageTransfersWithAvailabilityDataProvider
     *
     * @param array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param array<array<string, mixed>> $availabilityStorageData
     * @param string $storeName
     * @param array<array<string, mixed>> $expectedResults
     *
     * @return void
     */
    public function testExpandProductOfferStorageTransfersWithAvailability(
        array $productOfferStorageTransfers,
        array $availabilityStorageData,
        string $storeName,
        array $expectedResults,
    ): void {
        // Arrange
        $this->tester->mockStorageClient();
        $this->tester->mockStoreClient($storeName);

        foreach ($availabilityStorageData as $data) {
            $this->tester->mockProductOfferAvailabilityStorageData(
                $data['productOfferReference'],
                $data['payload'],
                $data['store'],
            );
        }

        $plugin = new ProductOfferAvailabilityProductOfferStorageBulkExpanderPlugin();
        $plugin->setClient($this->tester->getClient());

        // Act
        $expandedTransfers = $plugin->expand($productOfferStorageTransfers);

        // Assert
        $this->assertCount(count($expectedResults), $expandedTransfers);
        foreach ($expandedTransfers as $index => $expandedTransfer) {
            $expected = $expectedResults[$index];
            $this->assertSame($expected['productOfferReference'], $expandedTransfer->getProductOfferReference());
            $this->assertSame($expected['isNeverOutOfStock'], $expandedTransfer->getIsNeverOutOfStock());

            $stockQuantity = $expandedTransfer->getStockQuantity();
            if ($expected['stockQuantity'] === null) {
                $this->assertNull($stockQuantity);
            } else {
                $this->assertSame((float)$expected['stockQuantity'], $stockQuantity);
            }
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function expandProductOfferStorageTransfersWithAvailabilityDataProvider(): array
    {
        return [
            'empty array returns empty array' => [
                'productOfferStorageTransfers' => [],
                'availabilityStorageData' => [],
                'storeName' => static::STORE_NAME_DE,
                'expectedResults' => [],
            ],
            'no availability data exists returns unexpanded transfers' => [
                'productOfferStorageTransfers' => [
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1),
                ],
                'availabilityStorageData' => [],
                'storeName' => static::STORE_NAME_DE,
                'expectedResults' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'isNeverOutOfStock' => null,
                        'stockQuantity' => null,
                    ],
                ],
            ],
            'availability data exists for single product offer expands transfer' => [
                'productOfferStorageTransfers' => [
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1),
                ],
                'availabilityStorageData' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'payload' => [
                            'product_offer_reference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                            'is_never_out_of_stock' => true,
                            'availability' => 100,
                        ],
                        'store' => static::STORE_NAME_DE,
                    ],
                ],
                'storeName' => static::STORE_NAME_DE,
                'expectedResults' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'isNeverOutOfStock' => true,
                        'stockQuantity' => 100,
                    ],
                ],
            ],
            'multiple product offers with mixed availability expands only matching transfers' => [
                'productOfferStorageTransfers' => [
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1),
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_2),
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_3),
                ],
                'availabilityStorageData' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'payload' => [
                            'product_offer_reference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                            'is_never_out_of_stock' => true,
                            'availability' => 50,
                        ],
                        'store' => static::STORE_NAME_DE,
                    ],
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_3,
                        'payload' => [
                            'product_offer_reference' => static::TEST_PRODUCT_OFFER_REFERENCE_3,
                            'is_never_out_of_stock' => false,
                            'availability' => 25.5,
                        ],
                        'store' => static::STORE_NAME_DE,
                    ],
                ],
                'storeName' => static::STORE_NAME_DE,
                'expectedResults' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'isNeverOutOfStock' => true,
                        'stockQuantity' => 50,
                    ],
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_2,
                        'isNeverOutOfStock' => null,
                        'stockQuantity' => null,
                    ],
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_3,
                        'isNeverOutOfStock' => false,
                        'stockQuantity' => 25.5,
                    ],
                ],
            ],
            'availability data exists for different store returns unexpanded transfers' => [
                'productOfferStorageTransfers' => [
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1),
                ],
                'availabilityStorageData' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'payload' => [
                            'product_offer_reference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                            'is_never_out_of_stock' => true,
                            'availability' => 100,
                        ],
                        'store' => static::STORE_NAME_DE,
                    ],
                ],
                'storeName' => static::STORE_NAME_AT,
                'expectedResults' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'isNeverOutOfStock' => null,
                        'stockQuantity' => null,
                    ],
                ],
            ],
            'availability with zero stock and not never out of stock' => [
                'productOfferStorageTransfers' => [
                    (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1),
                ],
                'availabilityStorageData' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'payload' => [
                            'product_offer_reference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                            'is_never_out_of_stock' => false,
                            'availability' => 0,
                        ],
                        'store' => static::STORE_NAME_DE,
                    ],
                ],
                'storeName' => static::STORE_NAME_DE,
                'expectedResults' => [
                    [
                        'productOfferReference' => static::TEST_PRODUCT_OFFER_REFERENCE_1,
                        'isNeverOutOfStock' => false,
                        'stockQuantity' => 0,
                    ],
                ],
            ],
        ];
    }
}
