<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;
use Crypto\MagentoToken\Helper\Config;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;


    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var Quote
     */
    protected $quote;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    private Config $tokenHelper;

    public function __construct(
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager,
        CheckoutSession $checkoutSession,
        Config $tokenHelper
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->tokenHelper = $tokenHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = [
            'totalsData' => [
                'magentotoken' => [
                    'token_value' => (int) $this->getQuote()->getUsedMagentoTokens() // @phpstan-ignore-line
                ]
            ]
        ];
        return $config;
    }

    /**
     * Check if customer balance is available
     *
     * @return bool
     */
    protected function isAvailable()
    {
        if (!$this->customerSession->getCustomerId()) {
            return false;
        }

        if (!$this->tokenHelper->isEnabled()) {
            return false;
        }

        if (!(int) $this->getQuote()->getUsedMagentoTokens()) { // @phpstan-ignore-line
            return false;
        }

        return true;
    }

    /**
     * Retrieve Quote object
     *
     * @return Quote
     */
    protected function getQuote()
    {
        if (!$this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }

        return $this->quote;
    }
}
