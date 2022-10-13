<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Ui\Component\Form;

use Crypto\MagentoToken\Model\ResourceModel\TokenBalance\CollectionFactory as TokenBalanceCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    public function __construct(
        TokenBalanceCollectionFactory $tokenBalanceCollectionFactory, // @phpstan-ignore-line
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $tokenBalanceCollectionFactory->create(); // @phpstan-ignore-line
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}
