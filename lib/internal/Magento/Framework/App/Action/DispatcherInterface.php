<?php
/**
 * Action dispatcher Interface
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\App\Action;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;

interface DispatcherInterface
{
    /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @param ActionInterface $action
     *
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function dispatch(RequestInterface $request, ActionInterface $action);
}