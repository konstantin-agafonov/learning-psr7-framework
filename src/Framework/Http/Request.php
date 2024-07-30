<?php

namespace Framework\Http;
class Request
{
    public function getQueryParams()
    {
        return $_GET;
    }

    public function getParsedBody()
    {
        return $_POST ?: null;
    }

    /*public function getBody()
    {
        return file_get_contents('php://input');
    }*/




    public function getCookies()
    {
        return $_COOKIE;
    }

    public function getServerParams()
    {
        return $_SERVER;
    }

    public function getRequestParams()
    {
        return $_REQUEST;
    }

    public function getFiles()
    {
        return $_FILES;
    }

}
