<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\ResourceModel;

use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WithdrawRequest extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cryptom2_token_withdraw_request_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('cryptom2_token_withdraw_request', WithdrawRequestInterface::REQUEST_ID);
        $this->_useIsObjectNew = true;
    }
}
