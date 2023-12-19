<?php 

declare(strict_types=1);    

namespace src\services;

use src\repositories\UserRepository;

class UserService
{
    public function __construct()
    {
        
    }

    public static function selectAll()
    {
        $users = UserRepository::selectAll();

        if(!empty($users)) {
            http_response_code(200);            
            return $users;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Users not found']);            
        }
    }

    public static function selectById(int $id) 
    {
        $user = UserRepository::selectById($id);
        if(!empty($user)) {
            http_response_code(200);
            return $user;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);            
        }
    } 

    public static function insert(object $user) 
    {   
        if(self::emailExists($user->getEmail())) {
            http_response_code(409);
            echo json_encode(['message' => 'User email already exists']);
            return;
        }

        if(self::cpfExists($user->getCpf())) {
            http_response_code(409);
            echo json_encode(['message' => 'User cpf already exists']);
            return;
        }

        if(UserRepository::insert($user)) {
            http_response_code(201);
            echo json_encode(['message' => 'User created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'User could not be created']);
        }
    }
    
    public static function update(object $user) 
    {        
        if(UserRepository::update($user)) {
            http_response_code(201);
            echo json_encode(['message' => 'User updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'User could not be updated']);
        }
    }

    public static function deleteById(int $id) 
    {        
        if(UserRepository::deleteById($id)) {
            http_response_code(200);
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'User could not be deleted']);
        }
    }

    public static function emailExists(string $email) : bool
    {    
        $user = UserRepository::selectByEmail($email);
        return (!empty($user)) ? true : false;
    }

    public static function cpfExists(string $cpf) : bool
    {
        $user = UserRepository::selectByCpf($cpf);
        return (!empty($user)) ? true : false;
    }

    




}