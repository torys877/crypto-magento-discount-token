<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Api;

use Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;

interface WithdrawRequestRepositoryInterface
{
    /**
     * @param int $requestId
     * @return WithdrawRequestInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $requestId): WithdrawRequestInterface;

    /**
     * Save withdtaw request entity
     *
     * @param WithdrawRequestInterface $withdrawRequest
     * @return WithdrawRequestInterface
     * @throws CouldNotSaveException
     */
    public function save(WithdrawRequestInterface $withdrawRequest): WithdrawRequestInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return Collection
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Collection;
}
