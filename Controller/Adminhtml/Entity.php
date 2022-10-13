<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Adminhtml;

use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Crypto\MagentoToken\Model\TokenHistoryRepository;
use Crypto\MagentoToken\Model\TokenOrderRepository;
use Crypto\MagentoToken\Model\WithdrawRequestRepository;
use Crypto\MagentoToken\Model\Data\TokenBalanceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

abstract class Entity extends Action
{
    protected TokenBalanceRepository $tokenBalanceRepository;
    protected TokenHistoryRepository $tokenHistoryRepository;
    protected TokenOrderRepository $tokenOrderRepository;
    protected WithdrawRequestRepository $withdrawRequestRepository;
    protected TokenBalanceFactory $tokenBalanceFactory;
    protected JsonFactory $jsonFactory;

    public function __construct(
        Context $context,
        TokenBalanceRepository $tokenBalanceRepository,
        TokenHistoryRepository $tokenHistoryRepository,
        TokenOrderRepository $tokenOrderRepository,
        WithdrawRequestRepository $withdrawRequestRepository,
        TokenBalanceFactory $tokenBalanceFactory,
        JsonFactory $jsonFactory
    ) {
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->tokenHistoryRepository = $tokenHistoryRepository;
        $this->tokenOrderRepository = $tokenOrderRepository;
        $this->withdrawRequestRepository = $withdrawRequestRepository;
        $this->tokenBalanceFactory = $tokenBalanceFactory;
        $this->jsonFactory = $jsonFactory;

        parent::__construct($context);
    }
}
