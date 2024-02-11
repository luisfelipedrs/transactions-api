<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Helper\Mapper\TransactionMapper;
use App\Constants\HttpStatus;
use App\Application\Usecase\Transaction\MakeTransactionUseCase;
use App\Infra\Validation\MakeTransactionValidator;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class TransactionController
{
    public function __construct(
        private readonly MakeTransactionUseCase $makeTransactionUseCase,
        private readonly TransactionMapper $transactionMapper
    ){}

    public function makeTransaction(MakeTransactionValidator $request, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $transaction = $this->makeTransactionUseCase->execute($request);
        return $response->json($transaction->export())->withStatus(HttpStatus::CREATED);
    }
}