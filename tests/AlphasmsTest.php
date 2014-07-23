<?php
namespace Werkint\Alphasms\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Werkint\Alphasms\Alphasms;

/**
 * AlphasmsTest.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class AlphasmsTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $obj = new Alphasms('testkey');
        $this->assertNotEmpty($obj);
        $this->assertInternalType('object', $obj->getSender());
    }

    public function testSendCall()
    {
        $obj = $this->createObject();
        $ret = $obj->sendMessage('+123456789', 'test1', 'test2');
        $this->assertEquals(0, count($ret->getErrors()));
        $this->assertFalse($ret->hasErrors());
        $this->assertNull($ret->getId());
        $date = new \DateTime();
        $obj = $this->createObject(['errors' => ['test'], 'id' => 5], [
            'from'     => 'test2',
            'to'       => '+123456789',
            'key'      => 'testkey',
            'command'  => 'send',
            'message'  => 'test1',
            'flash'    => true,
            'ask_date' => $date->format(DATE_ISO8601),
        ]);
        $ret = $obj->sendMessage('+123456789', 'test1', 'test2', $date, true);
        $this->assertEquals(5, $ret->getId());
        $this->assertEquals(1, count($ret->getErrors()));
        $this->assertTrue($ret->hasErrors());
    }

    public function testBalanceCall()
    {
        $obj = $this->createObject();
        $ret = $obj->getBalance();
        $this->assertEquals(0, count($ret->getErrors()));
        $this->assertNull($ret->getBalance());
        $obj = $this->createObject(['errors' => ['test'], 'balance' => 5], [
            'key'     => 'testkey',
            'command' => 'balance',
        ]);
        $ret = $obj->getBalance();
        $this->assertEquals(5, $ret->getBalance());
        $this->assertEquals(1, count($ret->getErrors()));
        $this->assertTrue($ret->hasErrors());
    }

    public function testStatusCall()
    {
        $obj = $this->createObject();
        $ret = $obj->getMessageStatus(0);
        $this->assertEquals(0, count($ret->getErrors()));
        $this->assertNull($ret->getStatus());
        $obj = $this->createObject(['errors' => ['test'], 'status' => 5], [
            'id'      => 0,
            'key'     => 'testkey',
            'command' => 'receive',
        ]);
        $ret = $obj->getMessageStatus(0);
        $this->assertEquals(5, $ret->getStatus());
        $this->assertEquals(1, count($ret->getErrors()));
        $this->assertTrue($ret->hasErrors());
    }

    /**
     * @param array $ret
     * @param array|null $expected
     * @return Alphasms
     */
    protected function createObject($ret = [], $expected = null)
    {
        $obj = new Alphasms('testkey');
        $client = $this->getMock('Werkint\Alphasms\Sender');
        $client->expects($this->exactly(1))
            ->method('execute')
            ->with($expected === null ? $this->isType('array') : $this->equalTo($expected))
            ->will($this->returnValue($ret));
        $obj->setSender($client);
        return $obj;
    }
}