<?php
/**
 * Forward redirector
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\App\Response;

class Forward implements ForwardInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(\Magento\Framework\App\RequestInterface $request)
    {
        $this->request = $request;
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
    public function forward($action, $controller = null, $module = null, array $params = null)
    {
        $this->request->initForward();

        if (isset($params)) {
            $this->request->setParams($params);
        }

        if (isset($controller)) {
            $this->request->setControllerName($controller);

            // Module should only be reset if controller has been specified
            if (isset($module)) {
                $this->request->setModuleName($module);
            }
        }

        $this->request->setActionName($action);
        $this->request->setDispatched(false);
    }

}