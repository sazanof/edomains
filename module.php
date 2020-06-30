<?php

use Edomains\Domains;
use Edomains\DomainsInstall;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';
define('MODULE_MODE', true);
$content = '';
DomainsInstall::install();

$d = new \Edomains\Domains();
$title = 'Управление доменами';
if (!isset($_GET['ed_action'])) {
    $_GET['ed_action'] = 'index_table';
}
switch ($_GET['ed_action']) {
    case 'index_table':
        require_once __DIR__ . '/processors/index_table.processor.php';
        break;
    case 'page_vars':
        $title = 'Управление переменными поддомена';
        require_once __DIR__ . '/processors/page_vars.processor.php';
        break;
    case 'global_vars':
        $title = 'Управление глобальными переменными';
        require_once __DIR__ . '/processors/global_vars.processor.php';
        break;
}

$data = array(
    'm.id'=>$_GET['id'],
    'm.title' => $title,
    'm.content' => $content
);

return $d->parseTpl('module', $data);