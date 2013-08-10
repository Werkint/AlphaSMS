<?php
namespace Werkint\Alphasms;

class Response
{

    protected $errors = [];
    protected $id;
    protected $balance;
    protected $status;

    public function __construct(array $errors = null)
    {
        if ($errors) {
            $this->errors = $errors;
        }
    }

    public function hasErrors()
    {
        return (bool)count($this->errors);
    }

    // -- Getters/Setters ---------------------------------------

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

}