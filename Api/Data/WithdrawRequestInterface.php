<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api\Data;

interface WithdrawRequestInterface
{
    /**
     * String constants for property names
     */
    const REQUEST_ID = "request_id";
    const TOKEN_BALANCE_ID = "token_balance_id";
    const STATUS = "status";
    const AMOUNT = "amount";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const SIGNED_MESSAGE = "signed_message";
    const RECIPIENT_ADDRESS = "recipient_address";
    const NONCE = "nonce";
    const MESSAGE_HASH = "message_hash";

    const STATUS_NEW = 1;
    const STATUS_REJECTED = 2;
    const STATUS_APPROVED = 3;
    const STATUS_CLAIMED = 4;

    /**
     * Getter for RequestId.
     *
     * @return int|null
     */
    public function getRequestId(): ?int;

    /**
     * Setter for RequestId.
     *
     * @param int|null $requestId
     *
     * @return self
     */
    public function setRequestId(?int $requestId): self;

    /**
     * Getter for TokenBalanceId.
     *
     * @return int|null
     */
    public function getTokenBalanceId(): ?int;

    /**
     * Setter for TokenBalanceId.
     *
     * @param int|null $tokenBalanceId
     *
     * @return self
     */
    public function setTokenBalanceId(?int $tokenBalanceId): self;

    /**
     * Getter for Status.
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Setter for Status.
     *
     * @param int|null $status
     *
     * @return self
     */
    public function setStatus(?int $status): self;

    /**
     * Getter for Amount.
     *
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * Setter for Amount.
     *
     * @param int|null $amount
     *
     * @return self
     */
    public function setAmount(?int $amount): self;

    /**
     * Getter for CreatedAt.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Setter for CreatedAt.
     *
     * @param string|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?string $createdAt): self;

    /**
     * Getter for UpdatedAt.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Setter for UpdatedAt.
     *
     * @param string|null $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(?string $updatedAt): self;

    /**
     * Getter for SignedMessage.
     *
     * @return string|null
     */
    public function getSignedMessage(): ?string;

    /**
     * Setter for SignedMessage.
     *
     * @param string|null $signedMessage
     *
     * @return self
     */
    public function setSignedMessage(?string $signedMessage): self;

    /**
     * @return string|null
     */
    public function getRecipientAddress(): ?string;

    /**
     * @param string|null $recipientAddress
     * @return self
     */
    public function setRecipientAddress(?string $recipientAddress): self;

    /**
     * @return string|null
     */
    public function getNonce(): ?string;

    /**
     * @param string|null $nonce
     * @return self
     */
    public function setNonce(?string $nonce): self;

    /**
     * @return string|null
     */
    public function getMessageHash(): ?string;

    /**
     * @param string|null $messageHash
     * @return self
     */
    public function setMessageHash(?string $messageHash): self;
}
