<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model;

use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Crypto\MagentoToken\Api\TokenBalanceRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Model\ResourceModel\TokenBalance as TokenBalanceResource;
use Crypto\MagentoToken\Model\Data\TokenBalanceFactory;
use Crypto\MagentoToken\Model\Data\TokenBalance as TokenBalanceEntity;

class TokenBalanceRepository implements TokenBalanceRepositoryInterface
{

    private TokenBalanceResource $tokenBalanceResource;
    private TokenBalanceFactory $tokenBalanceFactory;

    public function __construct(
        TokenBalanceResource $tokenBalanceResource,
        TokenBalanceFactory $tokenBalanceFactory
    ) {
        $this->tokenBalanceResource = $tokenBalanceResource;
        $this->tokenBalanceFactory = $tokenBalanceFactory;
    }

    public function getById(int $tokenBalanceId): TokenBalanceInterface
    {
        /** @var TokenBalanceEntity $tokenBalance */
        $tokenBalance = $this->tokenBalanceFactory->create();
        $this->tokenBalanceResource->load($tokenBalance, $tokenBalanceId);

        if (!$tokenBalance->getTokenBalanceId()) {
            throw new NoSuchEntityException(__('The token balance with the "%1" ID doesn\'t exist.', $tokenBalanceId));
        }

        return $tokenBalance;
    }

    public function getByCustomerEmail(string $customerEmail): TokenBalanceInterface
    {
        /** @var TokenBalanceEntity $tokenBalance */
        $tokenBalance = $this->tokenBalanceFactory->create();
        $this->tokenBalanceResource->load($tokenBalance, $customerEmail, TokenBalanceInterface::CUSTOMER_EMAIL);

        if (!$tokenBalance->getTokenBalanceId()) {
            throw new NoSuchEntityException(__('The token balance with customer email "%1" doesn\'t exist.', $customerEmail));
        }

        return $tokenBalance;
    }

    public function getByCustomerId(int $customerId): TokenBalanceInterface
    {
        /** @var TokenBalanceEntity $tokenBalance */
        $tokenBalance = $this->tokenBalanceFactory->create();
        $this->tokenBalanceResource->load($tokenBalance, $customerId, TokenBalanceInterface::CUSTOMER_ID);

        if (!$tokenBalance->getTokenBalanceId()) {
            throw new NoSuchEntityException(__('The token balance with customer ID "%1" doesn\'t exist.', $customerId));
        }

        return $tokenBalance;
    }

    public function getByCustomerIdOrEmail(?string $customerId, ?string $customerEmail): ?TokenBalanceInterface
    {
        $tokenBalance = null;

        try {
            if ($customerId) {
                $tokenBalance = $this->getByCustomerId((int) $customerId);
            }

            if ($customerEmail) {
                $tokenBalance = $this->getByCustomerEmail($customerEmail);
            }
        } catch (\Exception $e) {
            //just skip
        }

        if (!$tokenBalance) {
            throw new NoSuchEntityException(__('The token balance not found', $customerId));
        }

        return $tokenBalance;
    }

    public function save(TokenBalanceInterface $tokenBalance): TokenBalanceInterface
    {
        try {
            $this->tokenBalanceResource->save($tokenBalance); // @phpstan-ignore-line
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $tokenBalance;
    }
}
