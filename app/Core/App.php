<?php
namespace Core;

class App
{
    protected $controller = 'App\\Controllers\\HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // Custom routes for ticket features
        if (isset($url[0]) && $url[0] == 'ticket' && isset($url[1])) {
            if ($url[1] == 'download' && isset($url[2])) {
                $this->controller = 'App\\Controllers\\TicketController';
                $this->method = 'download';
                $this->params = [$url[2]];
                call_user_func_array([$this->controller = new $this->controller, $this->method], $this->params);
                return;
            } elseif ($url[1] == 'certificate' && isset($url[2])) {
                $this->controller = 'App\\Controllers\\TicketController';
                $this->method = 'certificate';
                $this->params = [$url[2]];
                call_user_func_array([$this->controller = new $this->controller, $this->method], $this->params);
                return;
            }
        }

        // Controller
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerClass = "App\\Controllers\\" . $controllerName;

            if (class_exists($controllerClass)) {
                $this->controller = $controllerClass;
                unset($url[0]);
            }
        }

        $this->controller = new $this->controller;

        // Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Parameters
        $this->params = !empty($url) ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}

