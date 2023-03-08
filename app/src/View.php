<?php
namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    )
    {
        $this->view = $view;
    }

    public static function make(string $view, array $params = []) : static 
    {
        return new static($view, $params);
    }

    public function render()
    {
        $viewPath = __DIR__ . "/Views/$this->view.php";
        
        if(! file_exists($viewPath)) 
        {
            throw new ViewNotFoundException($this->view);
        }

        ob_start();

        include __DIR__ . "/Views/$this->view.php";
        
        return ob_get_clean();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __get($name)
    {
        return $this->params[$name] ?? null;
    }
}
