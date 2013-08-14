<?php
namespace Werkint\Alphasms;

use Werkint\HttpClient\Link;

class Alphasms
{
    const BASEURL = 'https://alphasms.com.ua/api/http.php';
    const VERSION = '1.8';

    protected $login;
    protected $password;
    protected $key;

    protected $link;

    public function __construct($login, $password, $key)
    {
        $this->login = $login;
        $this->password = $password;
        $this->key = $key;

        $this->link = new Link(static::BASEURL);
    }

    /**
     * Отправляет сообщение
     * @param string $from     Имя отправителя
     * @param string $to       Номер клиента
     * @param string $message  Текст сообщения
     * @param int    $sendDate Время/дата отправки
     * @param string $wap      Wap-Push ссылка
     * @param int    $flash    Flash-сообщение
     * @return Response
     */
    public function sendMessage($from, $to, $message, $sendDate = 0, $wap = '', $flash = 0)
    {
        if (!$sendDate) {
            $sendDate = date('Y-m-d H:i:s');
        }
        $sendDate = is_numeric($sendDate) ? $sendDate : strtotime($sendDate);
        $sendDate = date(DATE_ISO8601, $sendDate);
        $data = $this->execute('send', [
            'from'          => $from,
            'to'            => $to,
            'message'       => $message,
            'ask_date'      => $sendDate,
            'wap'           => $wap,
            'flash'         => $flash,
            'class_version' => static::VERSION,
        ]);
        $response = new Response(
            isset($data['errors']) ? $data['errors'] : null
        );
        if (isset($data['id'])) {
            $response->setId($data['id']);
        }

        return $response;
    }

    /**
     * Получает статус SMS
     * @param int $id
     * @return Response
     */
    public function getMessageStatus($id)
    {
        $data = $this->execute('receive', [
            'id' => $id,
        ]);
        $response = new Response(
            isset($data['errors']) ? $data['errors'] : null
        );
        if (isset($data['status'])) {
            $response->setStatus($data['status']);
        }

        return $response;
    }

    /**
     * Возвращает баланс
     * @return Response
     */
    public function getBalance()
    {
        $data = $this->execute('balance');
        $response = new Response(
            isset($data['errors']) ? $data['errors'] : null
        );
        if (isset($data['balance'])) {
            $response->setBalance($data['balance']);
        }

        return $response;
    }


    protected function execute($command, $params = [])
    {
        $this->populateQuery($params);
        $params['command'] = $command;

        $params = $this->encodeData($params);
        $data = $this->link->postRaw('', 'html', $params);
        curl_setopt($this->link->getConnection(), CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->link->getConnection(), CURLOPT_SSL_VERIFYHOST, false);

        $data = @unserialize($this->base64_url_decode($data->getData()));
        if (!is_array($data)) {
            throw new \Exception('Alphasms query problem');
        }

        return $data;
    }

    protected function populateQuery(array &$query)
    {
        $query['login'] = $this->login;
        $query['password'] = $this->password;
        $query['key'] = $this->key;
    }

    protected function encodeData(array $data)
    {
        $params_url = '';
        foreach ($data as $key => $value) {
            $params_url .= '&' . $key . '=' . $this->base64_url_encode($value);
        }
        return $params_url;
    }

    public function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    public function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_,', '+/='));
    }


}
