<?php

namespace Edomains;

use DLTemplate;

class Domains extends DomainsInstall
{
    public $dir = null;
    public $tplDir = null;
    protected $DL = null;

    public function __construct()
    {
        parent::__construct();
        $this->dir = MODX_BASE_PATH . 'assets/modules/edomains/';
        $this->tplDir = $this->dir . 'tpl/';
        $this->DL = DLTemplate::getInstance($this->evo);
    }

    public function parseTpl($tpl, $data)
    {
        $this->DL->setTemplatePath('assets/modules/edomains/tpl/');
        $this->DL->setTemplateExtension('.html');
        return $this->DL->parseChunk('@FILE:'.$tpl, $data,true);
    }

    /**
     * @return array|bool
     */
    public function getDomains()
    {
        return $this->db->makeArray($this->db->select('*',$this->tbl_domains));
    }

    /**
     * @param $id
     * @return array|bool|false|mixed|object|\stdClass
     */
    public function getDomain($id)
    {
        return $this->db->getRow($this->db->select('*',$this->tbl_domains,"id={$id}"));
    }

    /**
     * @param $where
     * @return bool|int|mixed|string
     */
    public function getDomainCount($where)
    {
        return $this->db->getRecordCount($this->db->select('*',$this->tbl_domains,$where));
    }

    /**
     * @return bool|int|mixed
     */
    public function createDomain(){
        $domain = $this->db->escape($_POST['domain']);
        if($this->getDomainCount("domain={$domain}") == 0){
            $data = array(
                'domain'=>$domain,
                'title'=>$this->db->escape($_POST['title']),
                'status'=>$this->db->escape($_POST['status'])
            );

            if($this->db->insert($data,$this->tbl_domains)){
                return $this->db->getInsertId();
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|\mysqli_result|resource|void
     */
    public function editDomain($id)
    {
        //проверяем есть ли с таким же доменом-ключем в БД еще кто-нить
        if($this->getDomainCount("domain={$_POST['domain']} AND id != {$id}") === 0){
            $data = array(
                'domain'=>$this->db->escape($_POST['domain']),
                'title'=>$this->db->escape($_POST['title']),
                'status'=>$this->db->escape($_POST['status'])
            );
            return $this->db->update($data,$this->tbl_domains,"id={$id}");
        }
        return false;

    }

    /**
     * @param array $ids
     * @return bool|\mysqli_result|resource|void
     */
    public function deleteDomains($ids = array())
    {
        $ids = is_array($ids) ? implode(',',$ids) : $ids;
        return $this->db->delete($this->tbl_domains,"id IN ($ids)");
    }

    public function getVarType($type){
        switch (intval($type)){
            case 1 :
                return 'Текстовое поле';
                break;
            case 2 :
                return 'Визуальный редактор';
                break;
            case 3 :
                return 'Чанк';
                break;
            case 4 :
                return 'Системное поле';
                break;
        }
    }

    public function getGlobalVars($domain_id = false){
        $sql = "SELECT g.id,g.key,g.type,g.description,g.default_value,v.id as value_id, v.value as value_value 
        FROM {$this->tbl_global_vars} as g 
        LEFT JOIN {$this->tbl_global_vars_values} as v
        ON g.id = v.key_id";
        if($domain_id){
            $sql .= " AND v.domain_id = {$domain_id}";
        }
        return $this->db->makeArray($this->db->query($sql));
    }

    public function getGlobalVar($id){
        return $this->db->getRow($this->db->select('*',$this->tbl_global_vars,"id={$id}"));
    }

    public function addGlobalVar($data){
        return $this->db->insert($data,$this->tbl_global_vars);
    }

    public function editGlobalVar($data,$id){
        return $this->db->update($data,$this->tbl_global_vars,"id={$id}");
    }


}