<?php 

namespace src\interfaces\repository;

interface Repository
{    
    public function selectAll();
    
    public function selectById(int $id);   
    
    public function insert(object $model);

    public function update(object $model);

    public function deleteById(int $id);    
}