<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Observer;

use Magento\Framework\Event\ObserverInterface;
use Crypto\MagentoToken\Helper\Config;
use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Magento\Sales\Model\Order;
use Magento\Framework\Event\Observer;
use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Crypto\MagentoToken\Model\Data\TokenBalanceFactory;

use Crypto\MagentoToken\Model\Data\TokenHistory;
use Crypto\MagentoToken\Model\Data\TokenHistoryFactory;
use Crypto\MagentoToken\Api\TokenHistoryRepositoryInterface;

use Crypto\MagentoToken\Model\Data\TokenOrder;
use Crypto\MagentoToken\Model\Data\TokenOrderFactory;
use Crypto\MagentoToken\Api\TokenOrderRepositoryInterface;
use Crypto\MagentoToken\Api\Data\TokenBalanceHistoryInterface;
use Crypto\MagentoToken\Api\Data\TokenOrderInterface;
use Psr\Log\LoggerInterface;

class ProcessOrderTokenObserver implements ObserverInterface
{
    private Config $configHelper;
    private TokenBalanceRepository $tokenBalanceRepository;
    private TokenBalanceFactory $tokenBalanceFactory;
    private TokenHistoryRepositoryInterface $tokenHistoryRepository;
    private TokenOrderRepositoryInterface $tokenOrderRepository;
    private TokenHistoryFactory $tokenHistoryFactory;
    private TokenOrderFactory $tokenOrderFactory;
    private LoggerInterface $logger;

    public function __construct(
        Config $configHelper,
        TokenBalanceRepository $tokenBalanceRepository,
        TokenBalanceFactory $tokenBalanceFactory,
        TokenHistoryRepositoryInterface $tokenHistoryRepository,
        TokenOrderRepositoryInterface $tokenOrderRepository,
        TokenHistoryFactory $tokenHistoryFactory,
        TokenOrderFactory $tokenOrderFactory,
        LoggerInterface $logger
    ) {
        $this->configHelper = $configHelper;
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->tokenBalanceFactory = $tokenBalanceFactory;
        $this->tokenHistoryRepository = $tokenHistoryRepository;
        $this->tokenOrderRepository = $tokenOrderRepository;
        $this->tokenHistoryFactory = $tokenHistoryFactory;
        $this->tokenOrderFactory = $tokenOrderFactory;
        $this->logger = $logger;
    }

    public function execute(Observer $observer): void
    {
        if (!$this->configHelper->isEnabled()) {
            return;
        }

        try {
            /** @var Order $order */
            $order = $observer->getEvent()->getOrder(); // @phpstan-ignore-line
            $quote = $observer->getEvent()->getQuote(); // @phpstan-ignore-line
            $tokenDiscountDelta = (($this->configHelper->getTokenDiscount() * $order->getGrandTotal()) / 100) *
                $this->configHelper->getTokenMultiplier();

            $tokenBalance = $this->getTokenBalance($order);
            $tokenBalance->setAmount((int) round($tokenBalance->getAmount() + $tokenDiscountDelta));

            $this->saveTokenBalance($order, $tokenBalance, $tokenDiscountDelta);

            if ($quote->getUsedMagentoTokens()) {
                $tokenBalance->setAmount((int) round($tokenBalance->getAmount() - $quote->getUsedMagentoTokens()));
                $this->saveTokenBalance($order, $tokenBalance, -$quote->getUsedMagentoTokens());
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }
    }

    protected function getTokenBalance(Order $order): TokenBalanceInterface
    {
        $customerId = $order->getCustomerId();
        $customerEmail = $order->getCustomerEmail();
        $tokenBalance = null;

        if ($customerId) {
            try {
                $tokenBalance = $this->tokenBalanceRepository->getByCustomerId((int) $customerId);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
            }
        }

        if ($customerEmail && !$tokenBalance) {
            try {
                $tokenBalance = $this->tokenBalanceRepository->getByCustomerEmail((string) $customerEmail);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $this->logger->error($e->getTraceAsString());
            }
        }

        if (!$tokenBalance) {
            /** @var TokenBalanceInterface $tokenBalance */
            $tokenBalance = $this->tokenBalanceFactory->create();
            $tokenBalance->setAmount(0);
        }

        return $tokenBalance;
    }

    protected function saveTokenBalance(Order $order, TokenBalanceInterface $tokenBalance, float $tokenDiscountDelta): void
    {
        //set token balance data and save
        $tokenBalance
            ->setCustomerEmail($order->getCustomerEmail())
            ->setCustomerId($order->getCustomerId());

        $tokenBalance = $this->tokenBalanceRepository->save($tokenBalance);
        $date = new \DateTime();
        $dateStr = $date->format('Y-m-d H:i:s');

        //save history of token balance action
        /** @var TokenBalanceHistoryInterface $tokenHistory */
        $tokenHistory = $this->tokenHistoryFactory->create();
        $tokenHistory
            ->setTokenBalanceId($tokenBalance->getTokenBalanceId())
            ->setAmount((int) $tokenBalance->getAmount())
            ->setAction(TokenBalanceHistoryInterface::ACTION_ADD)
            ->setDelta($tokenDiscountDelta)
            ->setAdditionalInfo(__('Tokens for Order: ' . $order->getIncrementId()))
            ->setUpdatedAt($dateStr);

        $this->tokenHistoryRepository->save($tokenHistory);

        //set token order and save to bind order with token balance entity
        /** @var TokenOrderInterface $tokenOrder */
        $tokenOrder = $this->tokenOrderFactory->create();
        $tokenOrder
            ->setTokenBalanceId($tokenBalance->getTokenBalanceId())
            ->setAmount((int) round($tokenDiscountDelta))
            ->setIncrementId($order->getIncrementId());

        $this->tokenOrderRepository->save($tokenOrder);
    }
}
