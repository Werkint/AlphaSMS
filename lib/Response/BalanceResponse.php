<?php
namespace Werkint\Alphasms\Response;

/**
 * BalanceResponse.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class BalanceResponse extends AbstractResponse
{
    protected $balance;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data)
    {
        $this->balance = isset($data['balance']) ? (float)$data['balance'] : null;

        parent::__construct($data);
    }

    // -- Accessors ---------------------------------------

    /**
     * @return float|null
     */
    public function getBalance()
    {
        return $this->balance;
    }
}