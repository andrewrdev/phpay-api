<?php

declare(strict_types=1);

namespace src\app\router;

use src\app\http\Request;
use src\app\http\Response;


class Router
{
    /**************************************************************************
     * Parses the given URL and returns an array of its path segments.
     *
     * @param string $url The URL to be parsed.
     * @return array The array of path segments from the URL.
     *************************************************************************/

    private static function parseURL(string $url): array
    {
        $url = filter_var(trim($url), FILTER_SANITIZE_URL);
        $url = parse_url($url, PHP_URL_PATH);
        $url = explode("/", $url);
        $url[0] = '/';
        $url = array_filter($url);
        return $url;
    }

    /**************************************************************************
     * Runs a controller method based on the provided controller string.
     *
     * @param string $controller The controller string in the format "Controller@method".
     * @param Request $request The request object.
     * @param Response $response The response object.     
     * @return void
     *************************************************************************/

    private static function runController(string $controller, Request $request, Response $response): void
    {
        $controllerParts = explode('@', $controller);
        $controller = $controllerParts[0];
        $method = $controllerParts[1];

        $class = 'src\\controllers\\' . $controller;
        $controller = new $class();
        $controller->$method($request, $response);
    }

    /**************************************************************************
     * Initializes the routing for the given route and controller.
     *
     * @param string $route The route to be initialized.
     * @param string $controller The controller to be initialized.
     *************************************************************************/

    private static function initRouting(string $route, string $controller)
    {
        $route = self::parseURL($route);
        $requestURI = self::parseURL($_SERVER["REQUEST_URI"]);

        if (count($requestURI) === count($route)) {
            $request = new Request();
            $response = new Response();
            $requestParams = [];

            for ($i = 0; $i < count($route); $i++) {
                if ($route[$i][0] === '{') {
                    $requestParams[str_replace(["{", "}"], "", $route[$i])] = $requestURI[$i];
                    unset($route[$i]);
                    unset($requestURI[$i]);
                }
            }

            $request->setParamsFromURL($requestParams);

            $routeEqualsUrl = 0;

            foreach ($requestURI as $requestURIValue) {
                foreach ($route as $routeValue) {
                    if ($requestURIValue === $routeValue) {
                        $routeEqualsUrl++;
                    }
                }
            }

            if ($routeEqualsUrl === count($requestURI)) {
                $json_data = file_get_contents("php://input");
                $data = json_decode($json_data, true) ?? [];
                $request->setParamsFromRequest($data);

                self::runController($controller, $request, $response);
            }
        }
    }

    /**************************************************************************
     * Retrieves a specific resource using the GET method.
     *
     * @param string $route The route of the resource.
     * @param string $controller The controller responsible for handling the resource.     
     * @return void
     *************************************************************************/
    public static function get(string $route, string $controller): void
    {
        if (mb_strtoupper($_SERVER["REQUEST_METHOD"]) === "GET") {
            self::initRouting($route, $controller);
        }
    }

    /**************************************************************************
     * Handles the HTTP POST method for a specific route and controller.
     *
     * @param string $route The route to be matched.
     * @param string $controller The controller to be executed.
     * @throws \Some_Exception_Class Description of exception (if any).
     * @return void
     *************************************************************************/

    public static function post(string $route, string $controller): void
    {
        if (mb_strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {
            self::initRouting($route, $controller);
        }
    }

    /**************************************************************************
     * Handles HTTP PUT requests for a specific route.
     *
     * @param string $route The route to handle.
     * @param string $controller The controller to invoke for the route.     
     * @return void
     *************************************************************************/

    public static function put(string $route, string $controller): void
    {
        if (mb_strtoupper($_SERVER["REQUEST_METHOD"]) === "PUT") {
            self::initRouting($route, $controller);
        }
    }


    /**************************************************************************
     * Deletes a route and its associated controller if the request method is DELETE.
     *
     * @param string $route The route to be deleted.
     * @param string $controller The controller associated with the route.     
     * @return void
     *************************************************************************/

    public static function delete(string $route, string $controller): void
    {
        if (mb_strtoupper($_SERVER["REQUEST_METHOD"]) === "DELETE") {
            self::initRouting($route, $controller);
        }
    }
}
