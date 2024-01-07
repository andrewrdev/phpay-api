<?php

declare(strict_types=1);

namespace src\controllers;

use src\app\http\Request;
use src\app\http\Response;
use src\models\TransactionModel;
use src\services\TransactionService;
use src\validations\TransactionValidation;
use src\validations\Validation;

class TransactionController
{
    /**************************************************************************
     * Retrieves all transactions using the TransactionService.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     *************************************************************************/

    public function selectAll(Request $request, Response $response)
    {
        $transactions = TransactionService::selectAll();

        if (!empty($transactions)) {
            $response->json($transactions);
        }
    }

    /**************************************************************************
     * Selects one transaction by ID.
     *
     * @param Request $request the request object
     * @param Response $response the response object
     *************************************************************************/

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateID($id);
        $transaction = TransactionService::selectById($id);

        $response->json($transaction);
    }

    /**************************************************************************
     * Inserts a new transaction into the database.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.     
     * @return void
     *************************************************************************/

    public function insert(Request $request, Response $response): void
    {
        $transaction = new TransactionModel();
        $transaction->setSenderId((int) $request->getParam('sender_id'));
        $transaction->setReceiverId((int) $request->getParam('receiver_id'));
        $transaction->setAmount((float) $request->getParam('amount'));

        TransactionValidation::validate($transaction);
        TransactionService::insert($transaction);
    }

    /**************************************************************************
     * Updates the data using the given request and response objects.
     *
     * @param Request  $request  the request object containing the data 
     * to update
     * 
     * @param Response $response the response object to send back after 
     * updating the data     
     * 
     * @return void
     *************************************************************************/

    public function update(Request $request, Response $response): void
    {
    }


    /**************************************************************************
     * Deletes a transaction by ID.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object. 
     * @return void
     *************************************************************************/

    public function delete(Request $request, Response $response): void
    {
        $id = (int) $request->PathParam('id');

        Validation::validateID($id);
        TransactionService::deleteById($id);
    }
}
