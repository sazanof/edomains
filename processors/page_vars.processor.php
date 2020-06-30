<?php
if (!defined('MODULE_MODE')) die;
$content = isset($content) ? $content : '';
$global_vars = $d->getGlobalVars();
$vars_html = '';
foreach ($global_vars as $global_var){
    $global_var['type_text'] = $d->getVarType($global_var['type']);
    $vars_html .= $d->parseTpl('chunks/global_var_edit',$global_var);
}
$page_data = array(
    'did'=>intval($_GET['did']),
    'm.id'=>$_GET['id'],
    'domain'=>$d->getDomain(intval($_GET['did'])),
    'tab_content'=>$vars_html
);
if(!isset($_GET['var_type'])){
    $_GET['var_type'] = 'static';
}
switch ($_GET['var_type']){
    case 'static':
        $page_data['activeClass']['static'] = 'active';

        break;
    case 'dynamic':
        $page_data['activeClass']['dynamic'] = 'active';
        break;
}
$content = $d->parseTpl('pages/page_vars', $page_data);