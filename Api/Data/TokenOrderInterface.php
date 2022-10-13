<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api\Data;

interface TokenOrderInterface
{
    /**
     * String constants for property names
     */
    const TOKEN_ORDER_ID = "token_order_id";
    const TOKEN_BALANCE_ID = "token_balance_id";
    const INCREMENT_ID = "increment_id";
    const AMOUNT = "amount";

    /**
     * Getter for TokenOrderId.
     *
     * @return int|null
     */
    public function getTokenOrderId(): ?int;

    /**
     * Setter for TokenOrderId.
     *
     * @param int|null $tokenOrderId
     *
     * @return self
     */
    public function setTokenOrderId(?int $tokenOrderId): self;

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
     * Getter for IncrementId.
     *
     * @return string|null
     */
    public function getIncrementId(): ?string;

    /**
     * Setter for IncrementId.
     *
     * @param string|null $incrementId
     *
     * @return self
     */
    public function setIncrementId(?string $incrementId): self;

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
}
