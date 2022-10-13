<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class RequestActions extends Column
{
    private const URL_PATH_SIGN = 'cryptotoken/withdrawrequest/sign';

    private UrlInterface $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data source for the action column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['request_id'])) {
                    $params = [
                        'request_id' => $item['request_id']
                    ];

                    $item[$this->getData('name')] = [
                        'sign' => [
                            'href' => $this->urlBuilder->getUrl(self::URL_PATH_SIGN, $params),
                            'request_data' => $item,
//                            'customhref' => $this->urlBuilder->getUrl(self::URL_PATH_SIGN, $params),
                            'label' => __('Sign Request'),
//                            'post' => true,
                            'isAjax' => true,
                            'confirm' => [
                                'title' => __('Sign this request?'),
                                'message' => __('Are you sure you want to sign this request?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
