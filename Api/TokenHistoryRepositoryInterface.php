<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Api\Data\TokenBalanceHistoryInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenHistory\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TokenHistoryRepositoryInterface
{
    /**
     * @param int $tokenHistoryId
     * @return TokenBalanceHistoryInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $tokenHistoryId): TokenBalanceHistoryInterface;

    /**
     * @param int $tokenBalanceId
     * @return TokenBalanceHistoryInterface
     * @throws NoSuchEntityException
     */
    public function getByTokenBalanceId(int $tokenBalanceId): TokenBalanceHistoryInterface;

    /**
     * Save city landing
     *
     * @param TokenBalanceHistoryInterface $tokenHistory
     * @return TokenBalanceHistoryInterface
     * @throws CouldNotSaveException
     */
    public function save(TokenBalanceHistoryInterface $tokenHistory): TokenBalanceHistoryInterface;


    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return Collection
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Collection;
}
