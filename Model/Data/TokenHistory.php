<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\Data;

use Crypto\MagentoToken\Api\Data\TokenBalanceHistoryInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenHistory as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class TokenHistory extends AbstractModel implements TokenBalanceHistoryInterface
{
    protected $_eventPrefix = 'cryptom2_token_balance_history_model';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Getter for TokenHistoryId.
     *
     * @return int|null
     */
    public function getTokenHistoryId(): ?int
    {
        return $this->getData(self::TOKEN_HISTORY_ID) === null ? null
            : (int)$this->getData(self::TOKEN_HISTORY_ID);
    }

    /**
     * Setter for TokenHistoryId.
     *
     * @param int|null $tokenHistoryId
     *
     * @return self
     */
    public function setTokenHistoryId(?int $tokenHistoryId): self
    {
        $this->setData(self::TOKEN_HISTORY_ID, $tokenHistoryId);

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
     * Getter for Action.
     *
     * @return int|null
     */
    public function getAction(): ?int
    {
        return $this->getData(self::ACTION) === null ? null
            : (int)$this->getData(self::ACTION);
    }

    /**
     * Setter for Action.
     *
     * @param int|null $action
     *
     * @return self
     */
    public function setAction(?int $action): self
    {
        $this->setData(self::ACTION, $action);

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

    /**
     * Getter for Delta.
     *
     * @return float|null
     */
    public function getDelta(): ?float
    {
        return $this->getData(self::DELTA) === null ? null
            : (float)$this->getData(self::DELTA);
    }

    /**
     * Setter for Delta.
     *
     * @param float|null $delta
     *
     * @return self
     */
    public function setDelta(?float $delta): self
    {
        $this->setData(self::DELTA, $delta);

        return $this;
    }

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return (string) $this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->setData(self::UPDATED_AT, $updatedAt);

        return $this;
    }

    /**
     * Getter for AdditionalInfo.
     *
     * @return string|null
     */
    public function getAdditionalInfo(): ?string
    {
        return (string) $this->getData(self::ADDITIONAL_INFO);
    }

    /**
     * Setter for AdditionalInfo.
     *
     * @param string|null $additionalInfo
     *
     * @return self
     */
    public function setAdditionalInfo(?string $additionalInfo): self
    {
        $this->setData(self::ADDITIONAL_INFO, $additionalInfo);

        return $this;
    }
}
