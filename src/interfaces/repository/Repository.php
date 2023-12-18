<?php 

namespace src\interfaces\repository;

interface Repository
{    
    public static function selectAll();
    
    public static function selectById(int $id);   
    
    public static function insert(object $model);

    public static function update(object $model);

    public static function deleteById(int $id);    
}