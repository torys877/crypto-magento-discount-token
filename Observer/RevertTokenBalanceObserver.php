<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Observer;

use Crypto\MagentoToken\Helper\Config;
use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Magento\Sales\Model\Order;
use Magento\Framework\Event\Observer;
use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Crypto\MagentoToken\Model\Data\TokenHistory;
use Crypto\MagentoToken\Model\Data\TokenHistoryFactory;
use Crypto\MagentoToken\Api\TokenHistoryRepositoryInterface;
use Crypto\MagentoToken\Model\Data\TokenOrder;
use Crypto\MagentoToken\Model\Data\TokenOrderFactory;
use Crypto\MagentoToken\Api\TokenOrderRepositoryInterface;
use Crypto\MagentoToken\Api\Data\TokenBalanceHistoryInterface;
use Crypto\MagentoToken\Api\Data\TokenOrderInterface;
use Psr\Log\LoggerInterface;

class RevertTokenBalanceObserver
{
    private Config $configHelper;
    private TokenBalanceRepository $tokenBalanceRepository;
    private TokenHistoryRepositoryInterface $tokenHistoryRepository;
    private TokenOrderRepositoryInterface $tokenOrderRepository;
    private TokenHistoryFactory $tokenHistoryFactory;
    private LoggerInterface $logger;

    public function __construct(
        Config $configHelper,
        TokenBalanceRepository $tokenBalanceRepository,
        TokenHistoryRepositoryInterface $tokenHistoryRepository,
        TokenOrderRepositoryInterface $tokenOrderRepository,
        TokenHistoryFactory $tokenHistoryFactory,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->tokenHistoryRepository = $tokenHistoryRepository;
        $this->tokenOrderRepository = $tokenOrderRepository;
        $this->tokenHistoryFactory = $tokenHistoryFactory;
        $this->logger = $logger;
    }

    public function execute(Observer $observer): void
    {
        if (!$this->configHelper->isEnabled()) {
            return;
        }

        /** @var Order $order */
        $order = $observer->getOrder(); // @phpstan-ignore-line

        try {
            $tokenOrder = $this->tokenOrderRepository->getByIncrementId($order->getIncrementId());

            if (!$tokenOrder->getTokenOrderId() || !$tokenOrder->getTokenBalanceId() || !$tokenOrder->getAmount()) {
                return;
            }

            $tokenBalance = $this->tokenBalanceRepository->getById($tokenOrder->getTokenBalanceId());

            if (!$tokenBalance->getTokenBalanceId() || $tokenBalance->getAmount() < $tokenOrder->getAmount()) {
                return;
            }

            $amountDelta = $tokenOrder->getAmount();
            $finalAmount = $tokenBalance->getAmount() - $tokenOrder->getAmount();

            $tokenBalance->setAmount((int) round($finalAmount));
            $this->tokenBalanceRepository->save($tokenBalance);

            $tokenOrder->setAmount(0);
            $this->tokenOrderRepository->save($tokenOrder);

            /** @var TokenBalanceHistoryInterface $tokenHistory */
            $tokenHistory = $this->tokenHistoryFactory->create();
            $date = new \DateTime();
            $dateStr = $date->format('Y-m-d H:i:s');

            $tokenHistory
                ->setAmount((int) round($finalAmount))
                ->setDelta((float) $amountDelta)
                ->setAction(TokenBalanceHistoryInterface::ACTION_SUB_CANCEL)
                ->setUpdatedAt($dateStr)
                ->setAdditionalInfo(__('Balance substraction in case order cancel: ' . $order->getIncrementId()));

            $this->tokenHistoryRepository->save($tokenHistory);

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
    }
}
