<?php
/**
 * Description
 *
 * @category Youwe
 * @package  Youwe_Etm
 * @author Sergey Gozhedrianov <s.gozhedrianov@youwe.nl>
 */

namespace Magento\Framework\App\Test\Unit\Response;


use Magento\Framework\App\Response\Forward;
use \Magento\Framework\App\Request\Http;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;


class ForwardTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Action name
     */
    const ACTION_NAME = 'someaction';

    /**
     * Controller name
     */
    const CONTROLLER_NAME = 'controller';

    /**
     * @var Http |\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var Forward | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $forward;

    /**
     * Module name
     */
    const MODULE_NAME = 'module';

    public static $actionParams = ['param' => 'value'];

    /**
     * @var ObjectManagerHelper
     */
    protected $objectManagerHelper;

    protected function setUp()
    {
        $this->requestMock = $this->getMockBuilder(Http::class)->disableOriginalConstructor()->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);

        $this->forward = $this->objectManagerHelper->getObject(
            Forward::class,
            [
                'request' => $this->requestMock
            ]
        );
    }

    public function testForward()
    {
        // _forward expectations

        $this->requestMock->expects($this->once())->method('initForward');
        $this->requestMock->expects($this->once())->method('setParams')->with(self::$actionParams);
        $this->requestMock->expects($this->once())->method('setControllerName')->with(self::CONTROLLER_NAME);
        $this->requestMock->expects($this->once())->method('setModuleName')->with(self::MODULE_NAME);
        $this->requestMock->expects($this->once())->method('setActionName')->with(self::ACTION_NAME);
        $this->requestMock->expects($this->once())->method('setDispatched')->with(false);

        $this->assertEmpty($this->forward->forward(self::ACTION_NAME, self::CONTROLLER_NAME, self::MODULE_NAME, self::$actionParams));
    }
}
