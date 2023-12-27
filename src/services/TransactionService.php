<?php

declare(strict_types=1);

namespace src\services;

use src\app\http\Response;
use src\classes\api\ApiRequest;
use src\repositories\TransactionRepository;

class TransactionService
{

    // ********************************************************************************************
    // ********************************************************************************************
    public static function selectAll()
    {
        $transactions = TransactionRepository::selectAll();
        if (!empty($transactions)) {
            return $transactions;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Transactions not found', 'statusCode' => 404]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectById(int $id)
    {
        $transaction = TransactionRepository::selectById($id);
        if (!empty($transaction)) {
            return $transaction;
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Transaction not found', 'statusCode' => 404]);
            exit;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function insert(object $transaction)
    {      

        self::checkIfTransactionIsAuthorized();

        if (!UserService::IdExists($transaction->getSenderId()) && !UserService::IdExists($transaction->getReceiverId())) {
            http_response_code(409);
            echo json_encode(['message' => 'Sender or receiver id not found', 'statusCode' => 409]);
            exit;            
        }  

        if ($transaction->getSenderId() === $transaction->getReceiverId()) {
            http_response_code(409);
            echo json_encode(['message' => 'Sender and receiver cannot be the same', 'statusCode' => 409]);
            exit;                      
        }

        $senderBalance = UserService::getBalance($transaction->getSenderId());

        if($senderBalance['balance'] < $transaction->getAmount())
        {
            http_response_code(409);
            echo json_encode(['message' => 'Sender does not have enough balance', 'statusCode' => 409]);
            exit;
        }

        $senderType = UserService::getType($transaction->getSenderId());

        if($senderType['type'] === 'retailer')
        {
            http_response_code(409);
            echo json_encode(['message' => 'Retailer cannot send money', 'statusCode' => 409]);
            exit;
        }

        if (TransactionRepository::insert($transaction)) {            
            http_response_code(201);
            echo json_encode(['message' => 'Transaction created successfully', 'statusCode' => 201]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Transaction could not be created', 'statusCode' => 500]);
            exit;
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function update(object $transaction)
    {
        if (!self::IdExists($transaction->id)) {
            http_response_code(404);
            echo json_encode(['message' => 'Transaction not found', 'statusCode' => 404]);
        }

        if (TransactionRepository::update($transaction)) {
            http_response_code(200);
            echo json_encode(['message' => 'Transaction updated successfully', 'statusCode' => 200]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Transaction could not be updated', 'statusCode' => 500]);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deleteById(int $id)
    {
        if (!self::IdExists($id)) {
            http_response_code(404);
            echo json_encode(['message' => 'Transaction not found', 'statusCode' => 404]);
            exit;
        }

        if (TransactionRepository::deleteById($id)) {
            http_response_code(200);
            echo json_encode(['message' => 'Transaction deleted successfully', 'statusCode' => 200]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Transaction could not be deleted', 'statusCode' => 500]);
        }
    }

    // ********************************************************************************************
    // ******************************************************************************************** 

    private static function IdExists(int $id): bool
    {
        $transaction = TransactionRepository::selectById($id);
        return (!empty($transaction)) ? true : false;
    }

    // ********************************************************************************************
    // ********************************************************************************************    

    private static function checkIfTransactionIsAuthorized(): void
    {
        $data = ApiRequest::get('https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc');
        
        if(!empty($data))
        {
            if (!$data['message'] === 'Autorizado')
            {
                Response::json(['message' => 'Transaction not authorized'], 401);            
            }  
        }           
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
