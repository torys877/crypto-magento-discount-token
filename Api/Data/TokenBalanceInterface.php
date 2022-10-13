<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api\Data;

interface TokenBalanceInterface
{
    /**
     * String constants for property names
     */
    const TOKEN_BALANCE_ID = "token_balance_id";
    const CUSTOMER_ID = "customer_id";
    const CUSTOMER_EMAIL = "customer_email";
    const AMOUNT = "amount";

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
     * Getter for CustomerId.
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Setter for CustomerId.
     *
     * @param int|null $customerId
     *
     * @return self
     */
    public function setCustomerId(?int $customerId): self;

    /**
     * Getter for CustomerEmail.
     *
     * @return string|null
     */
    public function getCustomerEmail(): ?string;

    /**
     * Setter for CustomerEmail.
     *
     * @param string|null $customerEmail
     *
     * @return self
     */
    public function setCustomerEmail(?string $customerEmail): self;

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
