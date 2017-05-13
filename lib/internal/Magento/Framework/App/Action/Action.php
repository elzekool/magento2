<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\App\Action;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\ForwardInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Extend from this class to create actions controllers in frontend area of your application.
 * It contains standard action behavior (event dispatching, flag checks)
 * Action classes that do not extend from this class will lose this behavior and might not function correctly
 *
 * TODO: Remove this class. Allow implementation of Action Controllers by just implementing Action Interface.
 *
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class Action extends AbstractAction implements DispatchableInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Namespace for session.
     * Should be defined for proper working session.
     *
     * @var string
     */
    protected $_sessionNamespace;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $_redirect;

    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    protected $_view;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ForwardInterface
     */
    protected $forward;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
        $this->_objectManager = $context->getObjectManager();
        $this->_eventManager = $context->getEventManager();
        $this->_url = $context->getUrl();
        $this->_actionFlag = $context->getActionFlag();
        $this->_redirect = $context->getRedirect();
        $this->_view = $context->getView();
        $this->messageManager = $context->getMessageManager();
        $this->forward = $context->getForward();
        $this->dispatcher = $context->getDispatcher();
    }

    /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        return $this->dispatcher->dispatch($request, $this);
    }

    /**
     * Throw control to different action (control and module if was specified).
     *
     * @param string $action
     * @param string|null $controller
     * @param string|null $module
     * @param array|null $params
     * @return void
     */
    protected function _forward($action, $controller = null, $module = null, array $params = null)
    {
        $this->forward->forward($action, $controller, $module, $params);
    }

    /**
     * Set redirect into response
     *
     * @param   string $path
     * @param   array $arguments
     * @return  ResponseInterface
     */
    protected function _redirect($path, $arguments = [])
    {
        $this->_redirect->redirect($this->getResponse(), $path, $arguments);
        return $this->getResponse();
    }

    /**
     * @return \Magento\Framework\App\ActionFlag
     */
    public function getActionFlag()
    {
        return $this->_actionFlag;
    }
}
