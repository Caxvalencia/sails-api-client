<?php

namespace Sails\Api\Client\Exceptions;

class SailsFieldException extends SailsException
{
    const MESSAGE_REQUIRED_FORMAT = 'El campo %s es obligatorio';

    const MESSAGE_MAX_FORMAT = 'El campo %s excede el limite de %s caracteres';

    const TYPE_REQUIRED = 'required';

    const TYPE_MAX = 'max';

    /** @var array */
    protected $messages = [];

    /** @var string[] */
    protected $fields = [];

    /** @var string[] */
    protected $types = [];

    /**
     * @param string $message
     *
     * @return SailsFieldException
     */
    public static function init($message = "")
    {
        return new self($message);
    }

    /**
     * @param string|array $field
     * @param string       $type
     *
     * @return SailsException
     */
    public function addMessage($field, $type = SailsFieldException::TYPE_REQUIRED)
    {
        if (is_string($field)) {
            $field = [$field];
        }

        $message = vsprintf([
            self::TYPE_REQUIRED => self::MESSAGE_REQUIRED_FORMAT,
            self::TYPE_MAX => self::MESSAGE_MAX_FORMAT,
        ][$type], $field);

        $this->messages[] = $message;
        $this->addField($field);
        $this->addType($type);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $field
     */
    public function addField($field)
    {
        $this->fields[] = $field;
    }

    /**
     * @return string
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param string $type
     */
    public function addType($type)
    {
        $this->types[] = $type;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
