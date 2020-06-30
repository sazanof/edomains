<?php
if (!defined('MODULE_MODE')) die;
$page_data = array();
if (intval($_GET['gvid'])) {
    $gvar = $d->getGlobalVar(intval($_GET['gvid']));
    $page_data['fields'] = $gvar;
    $page_data['fields']['type_' . $gvar['type']] = 'selected';
}
switch ($_POST['formid']) {
    case 'loadVarType':
        $gvid = intval($_POST['gvid']);
        $type = intval($_POST['type']);
        $gvar = $d->getGlobalVar($gvid);
        if (!$gvar) {
            $value = $_SESSION['edomains']['default_value'];
        } else {
            $value = $gvar['default_value'];
        }
        switch ($type) {
            case 1 :
                $out = '<label>Текстовое значение</label><input type="text" name="default_value" value="' . $value . '">';
                break;
            case 2 :
                $out = '<label>Визуальные редактор</label><textarea name="default_value">' . $value . '</textarea>';
                break;
            case 3 :
                $out = '<label>Название чанка</label><input type="text" name="default_value" required value="' . $value . '" placeholder="Имя чанка в системе">';
                break;
            case 4 :
                $sys_vars = array(
                    'pagetitle' => 'Заголовок',
                    'longtitle' => 'Расширенный заголовок',
                    'description' => 'Описание',
                    'alias' => 'Псевдоним',
                    'introtext' => 'Аннотация',
                    'content' => 'Содержание',
                );
                $out = '<label>Значение системного поля</label>
                <select type="text" name="default_value" required>';
                foreach ($sys_vars as $key => $val) {
                    $out .= '<option value="' . $key . '" ';
                    if ($value == $key) {
                        $out .= 'selected';
                    }
                    $out .= '>' . $val . ' (' . $key . ')' . '</option>';
                }
                $out .= '</select>';
                break;
        }
        $_SESSION['edomains']['default_value'] = '';
        echo json_encode(array('output' => $out));
        exit;
        break;
    case 'globalVarForm' :
        $_SESSION['edomains']['default_value'] = htmlspecialchars($_POST['default_value']);
        $page_data['fields'] = array(
            'id' => intval($_POST['id']),
            'key' => $_POST['key'],
            'default_value' => $_POST['default_value'],
            'type' => intval($_POST['type']),
            'type_' . intval($_POST['type']) => 'selected',
        );
        $toDb = array(
            'key' => evolutionCMS()->db->escape($_POST['key']),
            'type' => intval($_POST['type']),
            'default_value' => evolutionCMS()->db->escape($_POST['default_value'])
        );
        if (!in_array('', $toDb)) {
            if ($d->addGlobalVar($toDb)) {
                evolutionCMS()->sendRedirect($_SERVER['REQUEST_URI']);
            }
        }
        break;
}

$content = isset($content) ? $content : '';
$editOrCreate = isset($_GET['gvid']) ? 'Редактирование переменной' : 'Добавление переменной';
$page_data['editOrCreate'] = $editOrCreate;
$vars = $d->getGlobalVars();
$table = '';
foreach ($vars as $var) {
    $type = $d->getVarType($var['type']);
    $table .= '<tr>
    <td><b>' . $var['key'] . '</b></td>
    <td>' . $type . '</td>
    <td>' . $var['default_value'] . '</td>
    <td width="120">
    <a href="' . MODX_MANAGER_URL . '?a=112&id=' . $_GET['id'] . '&ed_action=global_vars&gvid=' . $var['id'] . '" class="btn btn-secondary">Ред</a>
    <a href="' . MODX_MANAGER_URL . '?a=112&id=' . $_GET['id'] . '&ed_action=global_vars&delgvid=' . $var['id'] . '" class="btn btn-danger" data-confirm="Вы уверены, что хотите удалить глобальную переменную ' . $var['key'] . ' и все ее значения?">Уд</a>
    </td>
    </tr>';
}
$page_data['table_content'] = $table;
$content = $d->parseTpl('pages/global_vars', $page_data);