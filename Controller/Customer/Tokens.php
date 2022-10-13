<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Customer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;

class Tokens extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    public function __construct(
        Context $context,
        Session $customerSession
    ) {
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Check customer authentication
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->_objectManager->get(Url::class)->getLoginUrl(); // @phpstan-ignore-line

        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true); // @phpstan-ignore-line
        }
        return parent::dispatch($request);
    }

    /**
     * Display downloadable links bought by customer
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Token Balance and Requests'));
        $this->_view->renderLayout();
    }
}
