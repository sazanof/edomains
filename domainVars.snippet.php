<?php
use Edomains\Domains;
require_once __DIR__ . '/autoload.php';

    $d = new Domains();
    $ex = explode('.',$_SERVER['HTTP_HOST']);
    if(count($ex)>2){
        $domain = $d->getDomainByKey($ex[0]);
        $modx->config['site_url'] = $d->toDomainName($ex[0]);
        $modx->setPlaceholder('base_href',$d->toDomainName($ex[0]));
        //echo $d->toDomainName($ex[0]);
        var_dump($modx->placeholders);
    }

