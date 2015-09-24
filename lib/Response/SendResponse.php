<?php
namespace Werkint\Alphasms\Response;

/**
 * SendResponse.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SendResponse extends AbstractResponse
{
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;

        parent::__construct($data);
    }

    // -- Accessors ---------------------------------------

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}