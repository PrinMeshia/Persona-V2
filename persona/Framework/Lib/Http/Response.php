<?php
namespace Framework\Lib\Http;

use Framework\Lib\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    protected $body;
    protected $headers;
    protected $statusCode;
    public function __construct($statusCode = 200, $headers = [], $body = null)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
    }
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
        return $this;
    }
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    public function send()
    {
        header('HTTP/1.0 '.$this->statusCode);
        foreach ($this->headers as $header => $value) {
            header(strtoupper($header).': '.$value);
        }
        echo $this->body;
    }
}