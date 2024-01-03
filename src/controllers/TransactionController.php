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

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectAll(Request $request, Response $response)
    {
        $transactions = TransactionService::selectAll();

        if (!empty($transactions)) {
            $response->json($transactions);
        }
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function selectOne(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateID($id);
        $transaction = TransactionService::selectById($id);

        $response->json($transaction);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function insert(Request $request, Response $response)
    {
        $transaction = new TransactionModel();
        $transaction->setSenderId((int) $request->getParam('sender_id'));
        $transaction->setReceiverId((int) $request->getParam('receiver_id'));
        $transaction->setAmount((float) $request->getParam('amount'));

        TransactionValidation::validate($transaction);        
        TransactionService::insert($transaction);
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function update(Request $request, Response $response)
    {
    }

    // ********************************************************************************************
    // ********************************************************************************************

    public function delete(Request $request, Response $response)
    {
        $id = (int) $request->PathParam('id');

        Validation::validateID($id);
        TransactionService::deleteById($id);
    }

    // ********************************************************************************************
    // ********************************************************************************************    
}
