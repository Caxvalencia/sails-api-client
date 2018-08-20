<?php

namespace Sails\Api\Client\Exceptions;

class SailsException extends \Exception
{
    private $response;

    /**
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     *
     * @return SailsException
     */
    public static function instance($message, $code = 0, $previous = null)
    {
        return new self($message, $code, $previous);
    }

    /**
     * @param array $fields
     *
     * @return SailsFieldException
     */
    public static function initFieldException(array $typeFields)
    {
        $fieldException = SailsFieldException::init();

        foreach ($typeFields as $type => $fields) {
            foreach ($fields as $field) {
                $fieldException->addMessage($field, $type);
            }
        }

        return $fieldException;
    }

    /**
     * @param $response
     *
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isFieldException()
    {
        return $this instanceof SailsFieldException;
    }
}
