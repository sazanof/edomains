<?php
if (!defined('MODULE_MODE')) die;
$content = isset($content) ? $content : '';

$page_data = array(
    'did' => intval($_GET['did']),
    'm.id' => $_GET['id'],
    'domain' => $d->getDomain(intval($_GET['did']))
);
if (!isset($_GET['var_type'])) {
    $_GET['var_type'] = 'static';
}
switch ($_GET['var_type']) {
    case 'static':
        $page_data['activeClass']['static'] = 'active';
        $global_vars = $d->getGlobalVarsByDomain(intval($_GET['did']));
        $vars_html = '';
        foreach ($global_vars as $global_var) {
            $global_var['domain'] = $page_data['domain'];
            $global_var['type_text'] = $d->getVarType($global_var['type']);
            $vars_html .= $d->parseTpl('chunks/global_var_edit', $global_var);
        }
        $page_data['tab_content'] = $vars_html;
        break;
    case 'dynamic':
        $page_data['activeClass']['dynamic'] = 'active';
        break;
}
if ($_GET['did'] && intval($_POST['domain_id'])) {
    $key_id = intval($_POST['key_id']);
    $domain_id = intval($_GET['did']);
    $var_id = intval($_POST['var_id']);
    $var_value = htmlspecialchars($_POST['var_value']);
    if (!empty($var_id) && empty($var_value)) {
        // delete var value
        if($d->deleteGvValue($var_id)){
            echo json_encode(array('delete'=>$var_id));
            exit;
        }
    }
    else {
        $data = array(
            'domain_id'=>$domain_id,
            'key_id'=>$key_id,
            'value'=>$var_value
        );
        if(empty($var_id)){
            // create var value
            $id = $d->createGvValue($data);
            if($id){
                echo json_encode(array('create'=>$id));
                exit;
            }
        }
        else {
            // edit var value
            if($d->editGvValue($var_id,$data)){
                echo json_encode(array('update'=>true));
                exit;
            }
        }
    }
}
$content = $d->parseTpl('pages/page_vars', $page_data);