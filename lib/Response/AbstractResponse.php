<?php
namespace Werkint\Alphasms\Response;

/**
 * AbstractResponse.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
abstract class AbstractResponse
{

    protected $errors = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->errors = isset($data['errors']) ? $data['errors'] : null;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return (bool)count($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}