<?php
namespace Werkint\Alphasms\Response;

/**
 * StatusResponse.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class StatusResponse extends AbstractResponse
{
    protected $status;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data)
    {
        $this->status = isset($data['status']) ? $data['status'] : null;

        parent::__construct($data);
    }

    // -- Accessors ---------------------------------------

    /**
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }
}