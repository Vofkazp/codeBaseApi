<?php
class Router
{
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        $uri = $this->getURI();

        $segments = explode('/', $uri);

        $controllerName = array_shift($segments) . 'Api';
        $controllerName = ucfirst($controllerName);

        $controllerFile = ROOT . '/Api/' .
            $controllerName . '.php';

        if (file_exists($controllerFile)) {
            include_once($controllerFile);
        }

        try {
            $controllerObject = new $controllerName ();
            echo $controllerObject->run();
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
}
