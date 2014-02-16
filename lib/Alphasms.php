<?php
namespace Werkint\Alphasms;

/**
 * Alphasms.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class Alphasms implements
    AlphasmsInterface
{
    const VERSION = '1.5';

    protected $key;
    protected $sender;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->sender = new Sender();
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(
        $target,
        $message,
        $sender,
        \DateTime $sendDate = null,
        $flash = false
    ) {
        if (!$sendDate) {
            $sendDate = new \DateTime();
        }
        $data = $this->execute('send', [
            'from'     => $sender,
            'to'       => $target,
            'message'  => $message,
            'ask_date' => $this->formatDate($sendDate),
            'flash'    => (bool)$flash,
            //'class_version' => static::VERSION,
        ]);

        return new Response\SendResponse($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageStatus($id)
    {
        $data = $this->execute('receive', [
            'id' => $id,
        ]);

        return new Response\StatusResponse($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        $data = $this->execute('balance');

        return new Response\BalanceResponse($data);
    }

    // -- Getters ---------------------------------------

    /**
     * @param Sender $sender
     * @return $this
     */
    public function setSender(Sender $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return Sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    // -- Helpers ---------------------------------------

    /**
     * @param string $command
     * @param array $params
     * @return array
     */
    public function execute($command, array $params = [])
    {
        $this->populateQuery($params);
        $params['command'] = $command;
        return $this->sender->execute($params);
    }

    /**
     * @param \DateTime $date
     * @return string
     */
    protected function formatDate(\DateTime $date)
    {
        return $date->format(DATE_ISO8601);
    }

    /**
     * @param array $query
     */
    protected function populateQuery(array &$query)
    {
        $query['key'] = $this->key;
    }
}
