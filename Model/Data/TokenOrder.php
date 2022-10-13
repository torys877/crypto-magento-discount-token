<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\Data;

use Crypto\MagentoToken\Api\Data\TokenOrderInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenOrder as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class TokenOrder extends AbstractModel implements TokenOrderInterface
{
    protected $_eventPrefix = 'cryptom2_token_order_model';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Getter for TokenOrderId.
     *
     * @return int|null
     */
    public function getTokenOrderId(): ?int
    {
        return $this->getData(self::TOKEN_ORDER_ID) === null ? null
            : (int)$this->getData(self::TOKEN_ORDER_ID);
    }

    /**
     * Setter for TokenOrderId.
     *
     * @param int|null $tokenOrderId
     *
     * @return self
     */
    public function setTokenOrderId(?int $tokenOrderId): self
    {
        $this->setData(self::TOKEN_ORDER_ID, $tokenOrderId);

        return $this;
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
     * Getter for IncrementId.
     *
     * @return string|null
     */
    public function getIncrementId(): ?string
    {
        return (string) $this->getData(self::INCREMENT_ID);
    }

    /**
     * Setter for IncrementId.
     *
     * @param string|null $incrementId
     *
     * @return self
     */
    public function setIncrementId(?string $incrementId): self
    {
        $this->setData(self::INCREMENT_ID, $incrementId);

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
