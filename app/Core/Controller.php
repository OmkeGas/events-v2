<?php
namespace Core;
class Controller
{
    protected function model($model)
    {
        $modelClass = "App\\Models\\" . $model;

        $modelFile = __DIR__ . "/../Models/" . $model . ".php";
        // Debug information
        error_log("Trying to load model: " . $modelClass);
        error_log("File should be at: " . $modelFile);

        if (!class_exists($modelClass)) {
            if (file_exists($modelFile)) {
                require_once $modelFile;
            }
        }

        if (!class_exists($modelClass)) {
            throw new \Exception("Model class '$modelClass' not found. Please check if the file exists and namespace is correct.");
        }
        return new $modelClass();
    }

    public function appView($view, $data = [])
    {
        if (file_exists('../resources/views/layouts/app-layout.php')) {
            require_once '../resources/views/layouts/app-layout.php';
        } else {
            die("App layout does not exist: " . $view);
        }
    }

    public function authView($view, $data = [])
    {
        if (file_exists('../resources/views/layouts/auth-layout.php')) {
            require_once '../resources/views/layouts/auth-layout.php';
        } else {
            die("Auth layout does not exist: " . $view);
        }
    }

    public function dashboardView($view, $data = [])
    {
        if (file_exists('../resources/views/layouts/dashboard-layout.php')) {
            require_once '../resources/views/layouts/dashboard-layout.php';
        } else {
            die("Dashboard layout does not exist: " . $view);
        }
    }

    public function component($component)
    {
        require_once '../resources/views/components/' . $component . '.php';
        if (file_exists('../resources/views/components/' . $component . '.php')) {
            require_once '../resources/views/components/' . $component . '.php';
        } else {
            die("Component does not exist: " . $component);
        }
    }

    protected function isActive($path)
    {
        if(isset($_GET['url'])){
            $currentPath = rtrim($_GET['url'], '/');
            return $currentPath === $path;
        }
        return $path === '';
    }

    protected function isActiveSub($path)
    {
        if (isset($_GET['url'])) {
            $currentPath = rtrim($_GET['url'], '/');
            return strpos($currentPath, $path) === 0;
        }
        return $path === '';
    }


    public function redirect($url)
    {
        if(ob_get_level()) {
            ob_end_clean();
        }
        session_write_close();
        header('Location: ' . BASE_URL . $url);
        exit();
    }
}
