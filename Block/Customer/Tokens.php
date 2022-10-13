<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;
use Crypto\MagentoToken\Model\WithdrawRequestRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Crypto\MagentoToken\Helper\Config;

/**
 * @method int|null getTokenBalanceAmount()
 * @method self setTokenBalanceAmount(int $amount)
 */
class Tokens extends Template
{
    protected CurrentCustomer $currentCustomer;
    protected TokenBalanceRepository $tokenBalanceRepository;
    protected WithdrawRequestRepository $withdrawRequestRepository;
    protected ?TokenBalanceInterface $tokenBalance = null;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected Config $tokenHelper;

    public function __construct(
        Context $context,
        CurrentCustomer $currentCustomer,
        TokenBalanceRepository $tokenBalanceRepository,
        WithdrawRequestRepository $withdrawRequestRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Config $tokenHelper,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->withdrawRequestRepository = $withdrawRequestRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->tokenHelper = $tokenHelper;

        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        if (!$this->currentCustomer->getCustomerId()) {
            return;
        }

        try {
            $this->tokenBalance = $this->tokenBalanceRepository->getByCustomerId((int) $this->currentCustomer->getCustomerId());
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            $this->_logger->error($e->getTraceAsString());
        }

        try {
            $this->tokenBalance = $this->tokenBalanceRepository->getByCustomerEmail((string)$this->currentCustomer->getCustomer()->getEmail());
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            $this->_logger->error($e->getTraceAsString());
        }

        $amount = 0;

        if ($this->tokenBalance) {
            $amount = $this->tokenBalance->getAmount();
        }

        $this->setTokenBalanceAmount((int) $amount);
    }

    public function getTokenBalance(): ?TokenBalanceInterface
    {
        return $this->tokenBalance;
    }

    public function getWithdrawRequests(): array
    {
        if (!$this->getTokenBalance()) {
            return [];
        }

        try {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(WithdrawRequestInterface::TOKEN_BALANCE_ID, $this->getTokenBalance()->getTokenBalanceId())
                ->create();

            $requestCollection = $this->withdrawRequestRepository->getList($searchCriteria);
            $requestCollection->setOrder(WithdrawRequestInterface::REQUEST_ID, 'DESC');

            if ($requestCollection->count()) {
                return $requestCollection->getItems();
            }
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            $this->_logger->error($e->getTraceAsString());
        }

        return [];
    }

    public function getRequestStatusText(int $status): string
    {
        $statusText = '';
        switch ($status) {
            case WithdrawRequestInterface::STATUS_NEW:
                $statusText = __('new');
                break;
            case WithdrawRequestInterface::STATUS_REJECTED:
                $statusText = __('rejected');
                break;
            case WithdrawRequestInterface::STATUS_APPROVED:
                $statusText = __('approved');
                break;
        }

        return (string) $statusText;
    }

    public function getTokenHelper(): Config
    {
        return $this->tokenHelper;
    }
}
