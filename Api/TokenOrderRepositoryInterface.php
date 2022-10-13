<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Api\Data\TokenOrderInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenOrder\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TokenOrderRepositoryInterface
{
    /**
     * @param int $tokenOrderId
     * @return TokenOrderInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $tokenOrderId): TokenOrderInterface;

    /**
     * @param int $tokenBalanceId
     * @return TokenOrderInterface
     * @throws NoSuchEntityException
     */
    public function getByTokenBalanceId(int $tokenBalanceId): TokenOrderInterface;

    /**
     * @param string $incrementId
     * @return TokenOrderInterface
     * @throws NoSuchEntityException
     */
    public function getByIncrementId(string $incrementId): TokenOrderInterface;

    /**
     * Save city landing
     *
     * @param TokenOrderInterface $tokenOrder
     * @return TokenOrderInterface
     * @throws CouldNotSaveException
     */
    public function save(TokenOrderInterface $tokenOrder): TokenOrderInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return Collection
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Collection;
}
