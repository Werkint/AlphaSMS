<?php
namespace Werkint\Alphasms;

use Guzzle\Http\Client;

/**
 * Sender.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Sender
{
    const BASEURL = 'https://alphasms.ua/api/http.php';

    protected $link;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->link = new Client(static::BASEURL);
    }

    /**
     * @return Client
     */
    public function getGuzzle()
    {
        return $this->link;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setGuzzle(Client $client)
    {
        $this->link = $client;
        return $this;
    }

    /**
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function execute(array $params = [])
    {
        $params = $this->encodeData($params);
        $q = $this->link->post(null, null);
        /** @var \Guzzle\Http\Message\EntityEnclosingRequest $q */
        $q->setBody($params);
        $q->setHeader('Content-type', 'application/x-www-form-urlencoded');
        $data = $q->send()->getBody(true);

        return $this->decodeResponse($data);
    }

    // -- Helpers ---------------------------------------

    /**
     * @param array $data
     * @return string
     */
    protected function encodeData(array $data)
    {
        $params_url = '';
        foreach ($data as $key => $value) {
            $params_url .= '&' . $key . '=' . $this->base64Encode($value);
        }
        return $params_url;
    }

    /**
     * @param $input
     * @return string
     */
    protected function base64Encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    /**
     * @param $input
     * @return string
     */
    protected function base64Decode($input)
    {
        return base64_decode(strtr($input, '-_,', '+/='));
    }

    /**
     * @param string $ret
     * @throws \Exception
     * @return array
     */
    protected function decodeResponse($ret)
    {
        $data = unserialize($this->base64Decode($ret));
        if (!is_array($data)) {
            throw new \Exception('Alphasms query problem');
        }
        return $data;
    }
} 