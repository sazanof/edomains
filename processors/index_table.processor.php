<?php
if (!defined('MODULE_MODE')) die;
$content = isset($content) ? $content : '';
$table_domains = '';
$domains = $d->getDomains();
if(count($domains) > 0){
    foreach ($domains as $domain){
        $status = $domain['status'] == 0 ? 'Активен' : 'Отключен';
        $table_domains .= '<tr data-row="'.$domain['id'].'">';
        $table_domains .= '<td>'.$domain['id'].'</td>';
        $table_domains .= '<td><b>'.$domain['domain'].'</b></td>';
        $table_domains .= '<td>'.$domain['title'].'</td>';
        $table_domains .= '<td>'. $status .'</td>';
        $table_domains .= '<td width="190">
                            <a href="/manager/index.php?a=112&id='.$_GET['id'].'&ed_action=page_vars&did='.$domain['id'].'" class="btn btn-secondary">Упр</a>
                            <a href="/manager/index.php?a=112&id='.$_GET['id'].'&did='.$domain['id'].'" class="btn btn-primary">Ред</a>
                            <a href="/manager/index.php?a=112&id='.$_GET['id'].'&ed_action=delete" class="btn btn-danger" data-confirm="Вы уверены, что хотите удалить домен '.$domain['domain'].'?">Уд</a>
                            </td>';
        $table_domains .= '</tr>';
    }
}
else {
    $table_domains .= '<tr><td colspan="5">нет данных</td></tr>';
}

$page_data = array(
    'domains'=>$table_domains,
    'editOrCreate'=>$_GET['did'] ? 'Редактирование поддомена' : 'Создание поддомена'
);
if($_GET['did']){
    $domain = $d->getDomain(intval($_GET['did']));
    $page_data['fields'] = $domain;
    $page_data['fields']['status_checked'] = $domain['status'] ? 'checked' : '';

}
if($_POST['formid'] === 'domainForm'){
    $page_data['fields'] = array(
        'id'=>evolutionCMS()->db->escape($_POST['id']),
        'domain'=>evolutionCMS()->db->escape($_POST['domain']),
        'title'=>evolutionCMS()->db->escape($_POST['title']),
        'status'=>evolutionCMS()->db->escape($_POST['status']) ? evolutionCMS()->db->escape($_POST['status']) : (string)0,
        'status_checked'=>evolutionCMS()->db->escape($_POST['status']) ? 'checked' : '',
    );
    $to_validate = array(
        'domain'=>$page_data['fields']['domain'],
        'title'=>$page_data['fields']['title'],
        'status'=>$page_data['fields']['status']
    );
    if(!in_array('',$to_validate)){

        if($_POST['id'] > 0){
            if($d->editDomain($_POST['id'])){
                evolutionCMS()->sendRedirect(explode('&did=',$_SERVER['REQUEST_URI'])[0]);
            }
        }
        else {
            //var_dump($to_validate);die;
            if($d->createDomain()){
                evolutionCMS()->sendRedirect($_SERVER['REQUEST_URI']);
            }
        }
    }
}
$content = $d->parseTpl('pages/index_table',$page_data);
