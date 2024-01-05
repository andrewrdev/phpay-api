<?php

declare(strict_types=1);

namespace src\app\http;

class Request
{
    private array $urlParams;
    private array $requestParams;

    /* ************************************************************************ 
     *
     * Retrieves the value of a path parameter.
     *
     * @param string $param The name of the path parameter.
     * @return string The value of the path parameter.   
     *  
     * ***********************************************************************/

    public function PathParam(string $param = ''): string
    {
        return $this->urlParams[$param];
    }

    /* ************************************************************************ 
     * 
     * Sets the URL parameters for the current object.
     *
     * @param array $urlParams The URL parameters to be set.
     * @return void     
     *  
     * ***********************************************************************/

    public function setParamsFromURL(array $urlParams): void
    {
        $this->urlParams = $urlParams;
    }

    /* ************************************************************************ 
     * 
     * Sets the parameters from the request.
     *
     * @param array $requestParams The request parameters.
     * @return void
     *  
     * ***********************************************************************/

    public function setParamsFromRequest(array $requestParams): void
    {
        $this->requestParams = $requestParams;
    }

    /* ************************************************************************ 
     * 
     * Retrieves the value of a specified parameter from the request.
     *
     * @param string $param The name of the parameter to retrieve. 
     * Default is an empty string.     
     * @return mixed The value of the specified parameter.
     *  
     * ***********************************************************************/

    public function getParam(string $param = '')
    {
        if (!isset($this->requestParams[$param])) {
            Response::json(['message' => "{$param} is required"], 400);
        }

        return $this->requestParams[$param];
    }
}
