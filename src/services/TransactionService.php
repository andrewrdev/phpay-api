<?php

declare(strict_types=1);

namespace src\services;

use src\app\http\Response;
use src\classes\api\ApiRequest;
use src\models\NotificationModel;
use src\repositories\TransactionRepository;

class TransactionService
{
    /**************************************************************************
     * Retrieves all transactions.
     *
     * @return mixed Returns an array of transactions if found, 
     * or a JSON response if not found.
     *************************************************************************/

    public static function selectAll()
    {
        $transactions = TransactionRepository::selectAll();

        return (!empty($transactions)) ? $transactions : Response::json(['message' => 'Transactions not found'], 404);
    }

    /**************************************************************************
     * Selects a transaction from the database by its ID.
     *
     * @param int $id The ID of the transaction to select.
     * @return mixed The selected transaction if found, otherwise a JSON 
     * response with a 'Transaction not found' message and a 404 status code.
     *************************************************************************/

    public static function selectById(int $id)
    {
        $transaction = TransactionRepository::selectById($id);

        return (!empty($transaction)) ? $transaction : Response::json(['message' => 'Transaction not found'], 404);
    }

    /**************************************************************************
     * Insert a transaction into the database and send a notification.
     *
     * @param object $transaction The transaction object to be inserted.
     * @return void
     *************************************************************************/

    public static function insert(object $transaction)
    {
        self::checkIfSenderAndReceiverExists($transaction);
        self::checkIfSenderAndReceiverAreNotTheSame($transaction);
        self::checkIfSenderAreNotRetailer($transaction);
        self::checkIfSenderHaveBalance($transaction);
        self::checkIfTransactionIsAuthorized();

        if (TransactionRepository::insert($transaction)) {
            $notification = new NotificationModel();
            $notification->setSenderId($transaction->getSenderId());
            $notification->setReceiverId($transaction->getReceiverId());
            $notification->setMessage("transferred $ {$transaction->getAmount()} to");
            NotificationService::sendNotification($notification);
            Response::json(['message' => 'Transaction created successfully'], 201);
        } else {
            Response::json(['message' => 'Transaction could not be created'], 500);
        }
    }

    /**************************************************************************
     * Updates a transaction.
     *
     * @param object $transaction The transaction object to be updated.     * 
     * @return void
     *************************************************************************/

    public static function update(object $transaction)
    {
        self::checkIfTransactionExists($transaction->getId());

        if (TransactionRepository::update($transaction)) {
            Response::json(['message' => 'Transaction updated successfully'], 200);
        } else {
            Response::json(['message' => 'Transaction could not be updated'], 500);
        }
    }

    /**************************************************************************
     * Deletes a transaction by its ID.
     *
     * @param int $id The ID of the transaction to be deleted.     
     * @return void
     *************************************************************************/

    public static function deleteById(int $id)
    {
        self::checkIfTransactionExists($id);

        if (TransactionRepository::deleteById($id)) {
            Response::json(['message' => 'Transaction deleted successfully'], 200);
        } else {
            Response::json(['message' => 'Transaction could not be deleted'], 500);
        }
    }

    /**************************************************************************
     * Check if a transaction exists.
     *
     * @param int $id The ID of the transaction to check.     
     * @return void
     *************************************************************************/

    private static function checkIfTransactionExists(int $id): void
    {
        $transaction = TransactionRepository::selectById($id);

        if (empty($transaction)) {
            Response::json(['message' => 'Transaction not found'], 404);
        }
    }

    /**************************************************************************
     * Check if the sender has enough balance for the transaction.
     *
     * @param object $transaction The transaction object.     
     * @return void
     *************************************************************************/

    private static function checkIfSenderHaveBalance(object $transaction): void
    {
        $sender = UserService::getBalance($transaction->getSenderId());

        if ($sender['balance'] < $transaction->getAmount()) {
            Response::json(['message' => 'Sender does not have enough balance'], 409);
        }
    }

    /**************************************************************************
     * Check if the sender and receiver of the transaction are not the same.
     *
     * @param object $transaction The transaction object to check.     
     * @return void
     *************************************************************************/

    private static function checkIfSenderAndReceiverAreNotTheSame(object $transaction): void
    {
        if ($transaction->getSenderId() === $transaction->getReceiverId()) {
            Response::json(['message' => 'Sender and receiver cannot be the same'], 409);
        }
    }

    /**************************************************************************
     * Checks if the sender of the transaction is not a retailer.
     *
     * @param object $transaction The transaction object.     
     * @return void
     *************************************************************************/

    private static function checkIfSenderAreNotRetailer(object $transaction): void
    {
        $sender = UserService::getType($transaction->getSenderId());

        if ($sender['type'] === 'retailer') {
            Response::json(['message' => 'Retailer cannot send money'], 409);
        }
    }

    /**************************************************************************
     * Check if sender and receiver exist.
     *
     * @param object $transaction The transaction object.     
     * @return void
     *************************************************************************/

    private static function checkIfSenderAndReceiverExists(object $transaction): void
    {
        UserService::checkIfUserExists($transaction->getSenderId(), 'sender_id not found');
        UserService::checkIfUserExists($transaction->getReceiverId(), 'receiver_id not found');
    }

    /**************************************************************************
     * Checks if the transaction is authorized.
     *
     * @return void
     *************************************************************************/

    private static function checkIfTransactionIsAuthorized(): void
    {
        $response = ApiRequest::get('https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc');

        if (!empty($response)) {
            if ($response['message'] !== 'Autorizado') {
                Response::json(['message' => 'Transaction not authorized'], 503);
            }
        } else {
            Response::json(['message' => 'Transaction not authorized'], 503);
        }
    }
}
