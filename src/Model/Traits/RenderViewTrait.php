<?php

namespace Yanntyb\App\Model\Traits;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

trait RenderViewTrait {

    private Logger $logger;

    public function __construct() {
        $this->logger =  new Logger("log");
        $this->logger->pushHandler(new StreamHandler($_SERVER["DOCUMENT_ROOT"] . "/log/log.log", logger::DEBUG));
    }

    public function render(string $view, string $title, $var = null) {
        ob_start();
        require_once $_SERVER['DOCUMENT_ROOT'] . "/View/$view.view.php";
        $html = ob_get_clean();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/View/_partials/base.view.php';
    }

    public function log(string $message, array $value){

    }
}