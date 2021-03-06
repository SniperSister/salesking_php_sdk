<?php
namespace Salesking\PHPSDK;

/**
 * This file brings in the Salesking exception class
 * @version     1.0.0
 * @package     SalesKing PHP SDK
 * @license     MIT License; see LICENSE
 * @copyright   Copyright (C) 2012 David Jardin
 * @link        http://www.salesking.eu
 */

/**
 * SaleskingException class
 * @since 2.0.0
 * @package SalesKing PHP SDK
 */
class Exception extends \Exception
{
    /**
     * internal error code
     * @var string internal error code
     * @since 2.0.0
     */
    protected $code = null;

    /**
     * error message
     * @var string error message
     * @since 2.0.0
     */
    protected $message = null;

    /**
     * additional error information
     * @var mixed additional error information (http response code, api errors messages..)
     * @since 2.0.0
     */
    protected $errors = null;

    /**
     * constructs a new salesking exception
     * @param string $code error code
     * @param string $message error message
     * @param mixed $errors additional error information
     * @since 2.0.0
     */
    public function __construct($code, $message, $errors = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->errors = $errors;

        parent::__construct($message);
    }

    /**
     * Return additional error information
     * @return mixed
     * @since 2.0.0
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
