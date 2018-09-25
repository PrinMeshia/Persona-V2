<?php
namespace Framework\Lib\Interfaces;
interface ResponseInterface
{
    public function __construct($statusCode = 200, $headers = [], $body = null);
    public function setStatusCode($statusCode);
    public function setHeader($header, $value);
    public function setBody($body);
    public function send();
}