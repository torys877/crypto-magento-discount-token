<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\Total\Quote;

use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address;
use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Crypto\MagentoToken\Helper\Config;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class TokenBalance extends AbstractTotal
{
    protected StoreManagerInterface $_storeManager;
    protected PriceCurrencyInterface $priceCurrency;
    protected TokenBalanceRepository $tokenBalanceRepository;
    protected Config $tokenHelper;

    public function __construct(
        StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency,
        TokenBalanceRepository $tokenBalanceRepository,
        Config $tokenHelper
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->_storeManager = $storeManager;
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->tokenHelper = $tokenHelper;
        $this->setCode('magentotoken');
    }

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ): self {

        if (!$this->tokenHelper->isEnabled()) {
            return $this;
        }

        $customerId = $quote->getCustomer()->getId(); // @phpstan-ignore-line
        $customerEmail = $quote->getCustomer()->getEmail(); // @phpstan-ignore-line

        if (!$customerId && !$customerEmail) {
            return $this;
        }

        try {
            /** @var TokenBalanceInterface $tokenBalance */
            $tokenBalance = $this->tokenBalanceRepository->getByCustomerIdOrEmail($customerId, $customerEmail);

            if (!$tokenBalance->getAmount()) {
                return $this;
            }

            $discountAmount = $tokenBalance->getAmount() / $this->tokenHelper->getTokenMultiplier();

            if ($discountAmount >= $total->getGrandTotal() && $total->getGrandTotal()) { // @phpstan-ignore-line
                $total->setGrandTotal(0); // @phpstan-ignore-line
                $quote->setUsedMagentoTokens((int) (round($total->getGrandTotal() * $this->tokenHelper->getTokenMultiplier()))); // @phpstan-ignore-line
                $quote->setUsedMagentoTokensBaseValue((int) (round($total->getGrandTotal()))); // @phpstan-ignore-line
            } else {
                $total->setGrandTotal($total->getGrandTotal() - $discountAmount); // @phpstan-ignore-line
                $quote->setUsedMagentoTokens((int) round($tokenBalance->getAmount())); // @phpstan-ignore-line
                $quote->setUsedMagentoTokensBaseValue((int) (round($tokenBalance->getAmount() / $this->tokenHelper->getTokenMultiplier()))); // @phpstan-ignore-line
            }
        } catch (\Exception $e) {
            return $this;
        }

        return $this;
    }

    public function fetch(Quote $quote, Total $total)
    {
        if ($this->tokenHelper->isEnabled() && $quote->getUsedMagentoTokensBaseValue()) { // @phpstan-ignore-line
            return [
                'code' => $this->getCode(),
                'title' => __('Magento Discount Token Used'),
                'value' => -$quote->getUsedMagentoTokensBaseValue() // @phpstan-ignore-line
            ];
        }

        return [];
    }
}
