<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;

interface TokenBalanceRepositoryInterface
{
    /**
     * @param int $tokenBalanceId
     * @return TokenBalanceInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $tokenBalanceId): TokenBalanceInterface;

    /**
     * @param string $customerEmail
     * @return TokenBalanceInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerEmail(string $customerEmail): TokenBalanceInterface;

    /**
     * @param int $customerId
     * @return TokenBalanceInterface
     * @throws NoSuchEntityException
     */
    public function getByCustomerId(int $customerId): TokenBalanceInterface;

    /**
     * @param string|null $customerId
     * @param string|null $customerEmail
     * @return TokenBalanceInterface|null
     * @throws NoSuchEntityException
     */
    public function getByCustomerIdOrEmail(?string $customerId, ?string $customerEmail): ?TokenBalanceInterface;

    /**
     * Save token balance entity
     *
     * @param TokenBalanceInterface $tokenBalance
     * @return TokenBalanceInterface
     * @throws CouldNotSaveException
     */
    public function save(TokenBalanceInterface $tokenBalance): TokenBalanceInterface;
}
