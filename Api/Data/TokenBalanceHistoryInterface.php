<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api\Data;

interface TokenBalanceHistoryInterface
{
    /**
     * String constants for property names
     */
    const TOKEN_HISTORY_ID = "token_history_id";
    const TOKEN_BALANCE_ID = "token_balance_id";
    const ACTION = "action";
    const AMOUNT = "amount";
    const DELTA = "delta";
    const UPDATED_AT = "updated_at";
    const ADDITIONAL_INFO = "additional_info";

    const ACTION_ADD = 1;
    const ACTION_WITHDRAW = 2;
    const ACTION_SUB_CANCEL = 3;

    /**
     * Getter for TokenHistoryId.
     *
     * @return int|null
     */
    public function getTokenHistoryId(): ?int;

    /**
     * Setter for TokenHistoryId.
     *
     * @param int|null $tokenHistoryId
     *
     * @return self
     */
    public function setTokenHistoryId(?int $tokenHistoryId): self;

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
     * Getter for Action.
     *
     * @return int|null
     */
    public function getAction(): ?int;

    /**
     * Setter for Action.
     *
     * @param int|null $action
     *
     * @return self
     */
    public function setAction(?int $action): self;

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
     * Getter for Delta.
     *
     * @return float|null
     */
    public function getDelta(): ?float;

    /**
     * Setter for Delta.
     *
     * @param float|null $delta
     *
     * @return self
     */
    public function setDelta(?float $delta): self;

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
     * Getter for AdditionalInfo.
     *
     * @return string|null
     */
    public function getAdditionalInfo(): ?string;

    /**
     * Setter for AdditionalInfo.
     *
     * @param string|null $additionalInfo
     *
     * @return self
     */
    public function setAdditionalInfo(?string $additionalInfo): self;
}
