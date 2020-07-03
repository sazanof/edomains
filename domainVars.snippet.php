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

        $vars = $d->getGlobalVars($domain['id']);
        $plh = array();
        foreach ($vars as $var){
            if($var['value_value'] == ''){
                $var['v'] = $var['default_value'];
            }
            else {
                $var['v'] = $var['value_value'];
            }
            // типы переменной (текст. чанки и тд)
            switch (intval($var['type'])){
                case 1:
                    break;
            }
            $plh[$var['key']] = $var;
        }
        $modx->toPlaceholders($plh,'domain.');
        echo '<pre>';print_r($modx->placeholders);echo '</pre>';
    }

