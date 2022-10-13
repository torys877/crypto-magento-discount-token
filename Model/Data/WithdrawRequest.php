<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model\Data;

use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;
use Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class WithdrawRequest extends AbstractModel implements WithdrawRequestInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'cryptom2_token_withdraw_request_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Getter for RequestId.
     *
     * @return int|null
     */
    public function getRequestId(): ?int
    {
        return $this->getData(self::REQUEST_ID) === null ? null
            : (int) $this->getData(self::REQUEST_ID);
    }

    /**
     * Setter for RequestId.
     *
     * @param int|null $requestId
     *
     * @return self
     */
    public function setRequestId(?int $requestId): self
    {
        $this->setData(self::REQUEST_ID, $requestId);

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
     * Getter for Status.
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS) === null ? null
            : (int)$this->getData(self::STATUS);
    }

    /**
     * Setter for Status.
     *
     * @param int|null $status
     *
     * @return self
     */
    public function setStatus(?int $status): self
    {
        $this->setData(self::STATUS, $status);

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
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return (string) $this->getData(self::CREATED_AT);
    }

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->setData(self::CREATED_AT, $createdAt);

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

    public function getSignedMessage(): ?string
    {
        return $this->getData(self::SIGNED_MESSAGE) === null ? null
            : (string)$this->getData(self::SIGNED_MESSAGE);
    }

    public function setSignedMessage(?string $signedMessage): self
    {
        $this->setData(self::SIGNED_MESSAGE, $signedMessage);

        return $this;
    }

    public function getRecipientAddress(): ?string
    {
        return $this->getData(self::RECIPIENT_ADDRESS) === null ? null
            : (string)$this->getData(self::RECIPIENT_ADDRESS);
    }

    public function setRecipientAddress(?string $recipientAddress): self
    {
        $this->setData(self::RECIPIENT_ADDRESS, $recipientAddress);

        return $this;
    }

    public function getNonce(): ?string
    {
        return $this->getData(self::NONCE) === null ? null
        : (string)$this->getData(self::NONCE);
    }

    public function setNonce(?string $nonce): self
    {
        $this->setData(self::NONCE, $nonce);

        return $this;
    }

    public function getMessageHash(): ?string
    {
        return $this->getData(self::MESSAGE_HASH) === null ? null
            : (string)$this->getData(self::MESSAGE_HASH);
    }

    public function setMessageHash(?string $messageHash): self
    {
        $this->setData(self::MESSAGE_HASH, $messageHash);

        return $this;
    }
}
