<?php

Class Filter {

    private $filter_id = null;
    private $path = null;
    private $name = null;

    public function getFilterId() { return $this->filter_id; }
    public function getPath() { return $this->path; }
    public function getName() { return $this->name; }

    public static function getAll() {
        $filterQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM filter
SQL
        );
        $filterQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $filterQuery->execute();
        if (($filters = $filterQuery->fetchAll()) !== false)
            return $filters;
        return false;
    }
}