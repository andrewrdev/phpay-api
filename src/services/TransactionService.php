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

        return (!empty($transactions)) ? $transactions : Response::json(['message' => 'Transactions not found'], 404);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function selectById(int $id)
    {
        $transaction = TransactionRepository::selectById($id);

        return (!empty($transaction)) ? $transaction : Response::json(['message' => 'Transaction not found'], 404);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function insert(object $transaction)
    {
        self::checkIfSenderAndReceiverExists($transaction);
        self::checkIfSenderAndReceiverAreNotTheSame($transaction);
        self::checkIfSenderAreNotRetailer($transaction);
        self::checkIfSenderHaveBalance($transaction);
        self::checkIfTransactionIsAuthorized();

        if (TransactionRepository::insert($transaction)) {
            Response::json(['message' => 'Transaction created successfully'], 201);
        } else {
            Response::json(['message' => 'Transaction could not be created'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function update(object $transaction)
    {
        self::checkIfTransactionExists($transaction->getId());

        if (TransactionRepository::update($transaction)) {
            Response::json(['message' => 'Transaction updated successfully'], 200);
        } else {
            Response::json(['message' => 'Transaction could not be updated'], 500);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public static function deleteById(int $id)
    {
        self::checkIfTransactionExists($id);

        if (TransactionRepository::deleteById($id)) {
            Response::json(['message' => 'Transaction deleted successfully'], 200);
        } else {
            Response::json(['message' => 'Transaction could not be deleted'], 500);
        }
    }

    // ********************************************************************************************
    // ******************************************************************************************** 

    private static function checkIfTransactionExists(int $id): void
    {
        $transaction = TransactionRepository::selectById($id);

        if (empty($transaction)) {
            Response::json(['message' => 'Transaction not found'], 404);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfSenderHaveBalance(object $transaction): void
    {
        $sender = UserService::getBalance($transaction->getSenderId());

        if ($sender['balance'] < $transaction->getAmount()) {
            Response::json(['message' => 'Sender does not have enough balance'], 409);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfSenderAndReceiverAreNotTheSame(object $transaction): void
    {
        if ($transaction->getSenderId() === $transaction->getReceiverId()) {
            Response::json(['message' => 'Sender and receiver cannot be the same'], 409);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfSenderAreNotRetailer(object $transaction): void
    {
        $sender = UserService::getType($transaction->getSenderId());

        if ($sender['type'] === 'retailer') {
            Response::json(['message' => 'Retailer cannot send money'], 409);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfSenderAndReceiverExists(object $transaction): void
    {
        UserService::checkIfUserExists($transaction->getSenderId());
        UserService::checkIfUserExists($transaction->getReceiverId());
    }

    // ********************************************************************************************
    // ********************************************************************************************

    private static function checkIfTransactionIsAuthorized(): void
    {
        $response = ApiRequest::get('https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc');

        if (!empty($response)) {
            if ($response['message'] !== 'Autorizado') {
                Response::json(['message' => 'Transaction not authorized'], 401);
            }
        } else {
            Response::json(['message' => 'Transaction not authorized'], 401);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
