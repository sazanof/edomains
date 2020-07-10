<?php
use Edomains\Domains;
require_once __DIR__ . '/autoload.php';

    $d = new Domains();
    $ex = explode('.',$_SERVER['HTTP_HOST']);
    //var_dump($_SERVER['HTTP_HOST']);
    if(count($ex)>2){
        $domain = $d->getDomainByKey($ex[0]);
        $modx->config['site_url'] = $d->toDomainName($ex[0]);
        $modx->setPlaceholder('base_href',$d->toDomainName($ex[0]));
        //echo $d->toDomainName($ex[0]);
        $vars = $d->getGlobalVarsByDomain($domain['id']);

        //echo '<pre>';print_r($modx->placeholders);echo '</pre>';
    }
    else {
        $vars = $d->getGlobalVars();
    }
$plh = array();
$plh['domain'] = $d->getActiveDomain();
foreach ($vars as $var){
    if($var['value_value'] == ''){
        $var['v'] = $var['default_value'];
    }
    else {
        $var['v'] = $var['value_value'];
    }
    // типы переменной (текст. чанки и тд)
    switch (intval($var['type'])){
        case 3: // чанк
            $dl = DLTemplate::getInstance(evolutionCMS());
            $out = $dl->parseChunk($var['v']);
            $var['v'] = evolutionCMS()->parseDocumentSource($out);
            break;

    }
    $plh[$var['key']] = html_entity_decode($var['v']);

}

$modx->toPlaceholders($plh,'domain.');

