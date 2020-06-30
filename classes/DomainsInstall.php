<?php


namespace Edomains;


class DomainsInstall
{
    protected $evo = null;
    protected $db = null;
    protected $tbl_domains = null;
    protected $tbl_global_vars = null;
    protected $tbl_global_vars_values = null;
    protected static $instance = null;

    public function __construct()
    {
        $this->evo = evolutionCMS();
        $this->db = $this->evo->db;
        $this->tbl_domains = $this->evo->getFullTableName('edomains_domains');
        $this->tbl_global_vars = $this->evo->getFullTableName('edomains_global_vars');
        $this->tbl_global_vars_values = $this->evo->getFullTableName('edomains_global_vars_values');
    }

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function install(){

        $in = self::getInstance();

        $sql = "CREATE TABLE IF NOT EXISTS {$in->tbl_domains} 
        ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `domain` VARCHAR(255) NOT NULL , 
        `title` VARCHAR(255) NOT NULL , 
        `is_default` BOOLEAN NOT NULL DEFAULT 0, 
        `status` INT NOT NULL , PRIMARY KEY (`id`), 
        UNIQUE (`domain`)
        )";
        $in->db->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$in->tbl_global_vars}
        ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `key` VARCHAR(255) NOT NULL , 
        `type` VARCHAR(255) NOT NULL , 
        `default_value` TEXT NOT NULL , PRIMARY KEY (`id`),
        UNIQUE (`key`)
        )";
        $in->db->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$in->tbl_global_vars_values}
        ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `domain_id` INT NOT NULL, 
        `value` TEXT NOT NULL , PRIMARY KEY (`id`)
        )";
        $in->db->query($sql);

        return;
    }
}