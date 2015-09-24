<?php
namespace Werkint\Alphasms\Tests;

use Werkint\Alphasms\Sender;

/**
 * SenderTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SenderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $obj = new Sender();
        $this->assertNotEmpty($obj);
    }

    public function testSend()
    {
        $obj = $this->createObject();
        $this->assertNotEmpty($obj->getGuzzle());
        // TODO: sending test
    }

    /**
     * @param array $ret
     * @param array|null $expected
     * @return Sender
     */
    protected function createObject($ret = [], $expected = null)
    {
        $obj = new Sender();
        $client = $this->getMock('Guzzle\Http\Client');
        $obj->setGuzzle($client);
        return $obj;
    }
}