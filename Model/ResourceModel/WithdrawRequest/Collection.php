<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest;

use Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest as ResourceModel;
use Crypto\MagentoToken\Model\Data\WithdrawRequest as Model;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cryptom2_token_withdraw_request_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
