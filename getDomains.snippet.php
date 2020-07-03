<?php
use Edomains\Domains;
require_once __DIR__ . '/autoload.php';
$out = '';
$dl = DLTemplate::getInstance(evolutionCMS());
$tpl = isset($tpl) ? $tpl : '@CODE:<div>[+domain.id+]:[+domain.domain+]:[+domain.title+]:[+domain.url+]</div>';
$d = new Domains();
$domains = $d->getDomains();
foreach ($domains as $domain){
    $name = $d->toDomainName($domain['domain']);
    $domain['host'] = $name;
    $domain['activeClass'] = is_numeric(strpos($_SERVER['HTTP_HOST'],$domain['domain'])) ? 'active' : '';
    if(evolutionCMS()->documentIdentifier == evolutionCMS()->getConfig('site_start')){
        $domain['host'] = rtrim($domain['host'],'/');
    }
    $out .= $dl->parseChunk($tpl,array('domain'=>$domain));
}
return $out;
