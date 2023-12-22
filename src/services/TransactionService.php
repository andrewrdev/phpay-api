<?php

declare(strict_types=1);

namespace src\services;

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
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function insert(object $transaction)
    {
        if (TransactionRepository::insert($transaction)) {
            http_response_code(201);
            echo json_encode(['message' => 'Transaction created successfully', 'statusCode' => 201]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Transaction could not be created', 'statusCode' => 500]);
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
}
