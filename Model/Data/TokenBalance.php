<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\Data;

use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenBalance as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class TokenBalance extends AbstractModel implements TokenBalanceInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cryptom2_token_balance_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }


    /**
     * Getter for TokenBalanceId.
     *
     * @return int|null
     */
    public function getTokenBalanceId(): ?int
    {
        return $this->getData(self::TOKEN_BALANCE_ID) === null ? null
            : (int)$this->getData(self::TOKEN_BALANCE_ID);
    }

    /**
     * Setter for TokenBalanceId.
     *
     * @param int|null $tokenBalanceId
     *
     * @return self
     */
    public function setTokenBalanceId(?int $tokenBalanceId): self
    {
        $this->setData(self::TOKEN_BALANCE_ID, $tokenBalanceId);

        return $this;
    }

    /**
     * Getter for CustomerId.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->getData(self::CUSTOMER_ID) === null ? null
            : (int)$this->getData(self::CUSTOMER_ID);
    }

    /**
     * Setter for CustomerId.
     *
     * @param int|null $customerId
     *
     * @return self
     */
    public function setCustomerId(?int $customerId): self
    {
        $this->setData(self::CUSTOMER_ID, $customerId);

        return $this;
    }

    /**
     * Getter for CustomerEmail.
     *
     * @return string|null
     */
    public function getCustomerEmail(): ?string
    {
        return (string) $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Setter for CustomerEmail.
     *
     * @param string|null $customerEmail
     *
     * @return self
     */
    public function setCustomerEmail(?string $customerEmail): self
    {
        $this->setData(self::CUSTOMER_EMAIL, $customerEmail);

        return $this;
    }

    /**
     * Getter for Amount.
     *
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->getData(self::AMOUNT) === null ? null
            : (int)$this->getData(self::AMOUNT);
    }

    /**
     * Setter for Amount.
     *
     * @param int|null $amount
     *
     * @return self
     */
    public function setAmount(?int $amount): self
    {
        $this->setData(self::AMOUNT, $amount);

        return $this;
    }
}
