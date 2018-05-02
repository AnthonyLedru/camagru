<?php

Class Filter {

    private $filter_id = null;
    private $path = null;
    private $name = null;

    public function getFilterId() { return $this->filter_id; }
    public function getPath() { return $this->path; }
    public function getName() { return $this->name; }

    public static function getAll() {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't get filters");
        }
    }

    public static function isValidPath($path) {
        try {
            $filterQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM filter
            WHERE path = :path
SQL
            );
            $filterQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $filterQuery->execute(array('path' => $path));
            if (($filters = $filterQuery->fetchAll()) !== false)
                return true;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error => Can't check filter path");
        }
    }
}