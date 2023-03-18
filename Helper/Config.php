<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    public const CONFIG_ENABLE = 'cryptotoken/general/enable';
    public const CONFIG_DISCOUNT = 'cryptotoken/general/discount';
    public const CONFIG_MULTIPLIER = 'cryptotoken/general/multiplier';
    public const CONFIG_TOKEN_SYMBOL = 'cryptotoken/general/token_symbol';
    public const CONFIG_TOKEN_NAME = 'cryptotoken/general/token_name';
    public const CONFIG_TOKEN_ADDRESS = 'cryptotoken/general/token_address';
    public const CONFIG_CONTROL_CONTRACT_ADDRESS = 'cryptotoken/general/smart_contract_address';
    public const CONFIG_CONTROL_CONTRACT_ABI = 'cryptotoken/general/smart_contract_abi';

    private ScopeConfigInterface $scopeConfig;
    private StoreManagerInterface $storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_ENABLE, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }

    public function getTokenDiscount(): ?string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_DISCOUNT, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }

    public function getTokenMultiplier(): ?int
    {
        $configValue = (int) $this->scopeConfig->getValue(self::CONFIG_MULTIPLIER, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());

        if (!$configValue) {
            $configValue = 1;
        }

        return $configValue;
    }

    public function getTokenSymbol(): ?string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_TOKEN_SYMBOL, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }

    public function getTokenName(): ?string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_TOKEN_NAME, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }

    public function getTokenAddress(): ?string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_TOKEN_ADDRESS, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }

    public function getContractAddress(): ?string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_CONTROL_CONTRACT_ADDRESS, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }

    public function getContractAbi(): ?string
    {
        return (string) $this->scopeConfig->getValue(self::CONFIG_CONTROL_CONTRACT_ABI, ScopeInterface::SCOPE_STORES, $this->storeManager->getStore()->getId());
    }
}
