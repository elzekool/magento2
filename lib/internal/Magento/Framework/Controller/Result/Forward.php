<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Controller\Result;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\ForwardInterface;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\Controller\AbstractResult;

class Forward extends AbstractResult
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var ForwardInterface
     */
    protected $forward;

    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param RequestInterface $request
     * @param ForwardInterface $forward
     */
    public function __construct(
        RequestInterface $request,
        ForwardInterface $forward = null
    )
    {
        $this->request = $request;
        $this->forward = $forward !== null ? $forward :
            ObjectManager::getInstance()->get(ForwardInterface::class);
    }

    /**
     * @param string $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function forward($action)
    {
        $this->forward->forward($action, $this->controller, $this->module, $this->params);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function render(HttpResponseInterface $response)
    {
        return $this;
    }
}
