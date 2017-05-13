<?php
/**
 * Action dispatcher
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\App\Action;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\Profiler;

class Dispatcher implements DispatcherInterface
{
    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var ActionFlag
     */
    private $actionFlag;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Constructor
     *
     * @param ManagerInterface $eventManager
     * @param ActionFlag $actionFlag
     * @param ResponseInterface $response
     */
    public function __construct(ManagerInterface $eventManager, ActionFlag $actionFlag, ResponseInterface $response)
    {
        $this->eventManager = $eventManager;
        $this->actionFlag = $actionFlag;
        $this->response = $response;
    }

    /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @param ActionInterface $action
     * 
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function dispatch(RequestInterface $request, ActionInterface $action)
    {
        $profilerKey = 'CONTROLLER_ACTION:' . $request->getFullActionName();
        $eventParameters = ['controller_action' => $action, 'request' => $request];
        $this->eventManager->dispatch('controller_action_predispatch', $eventParameters);
        $this->eventManager->dispatch('controller_action_predispatch_' . $request->getRouteName(), $eventParameters);
        $this->eventManager->dispatch(
            'controller_action_predispatch_' . $request->getFullActionName(),
            $eventParameters
        );
        Profiler::start($profilerKey);

        $result = null;
        if ($request->isDispatched() && !$this->actionFlag->get('', ActionInterface::FLAG_NO_DISPATCH)) {
            Profiler::start('action_body');
            $result = $action->execute();
            Profiler::start('postdispatch');
            if (!$this->actionFlag->get('', ActionInterface::FLAG_NO_POST_DISPATCH)) {
                $this->eventManager->dispatch(
                    'controller_action_postdispatch_' . $request->getFullActionName(),
                    $eventParameters
                );
                $this->eventManager->dispatch(
                    'controller_action_postdispatch_' . $request->getRouteName(),
                    $eventParameters
                );
                $this->eventManager->dispatch('controller_action_postdispatch', $eventParameters);
            }
            Profiler::stop('postdispatch');
            Profiler::stop('action_body');
        }
        Profiler::stop($profilerKey);

        return $result ?: $this->response;
    }
}