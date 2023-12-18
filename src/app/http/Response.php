<?php 

declare(strict_types=1);

namespace src\app\http;

class Response 
{    
    // ********************************************************************************************
    // ********************************************************************************************

    public function json(array $data) : void
    {   
        header('Content-Type: application/json');     
        echo json_encode($data);
    }

    // ********************************************************************************************
    // ********************************************************************************************
}