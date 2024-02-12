<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Helper\Mapper\TransactionMapper;
use App\Constants\HttpStatus;
use App\Application\Usecase\Transaction\MakeTransactionUseCase;
use App\Infra\Validation\MakeTransactionValidator;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use OpenApi\Annotations as OA;

class TransactionController
{
    public function __construct(
        private readonly MakeTransactionUseCase $makeTransactionUseCase,
        private readonly TransactionMapper $transactionMapper
    ){}

    /**
     * @OA\Post(
     *     path="/transactions",
     *     summary="Realiza uma nova transação",
     *     description="Realiza uma nova transação com base nos parâmetros fornecidos",
     *     @OA\RequestBody(
     *         description="Objeto de transação",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="payer", type="string", format="uuid", description="ID do pagador"),
     *                 @OA\Property(property="payee", type="string", format="uuid", description="ID do beneficiário"),
     *                 @OA\Property(property="value", type="string", format="decimal", description="Valor da transação (por exemplo: '10.00')")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\Schema(ref="#/components/schemas/TransactionResultObject")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso não encontrado"
     *     )
     * )
     */
    public function makeTransaction(MakeTransactionValidator $request, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $transaction = $this->makeTransactionUseCase->execute($request);
        return $response->json($transaction->export())->withStatus(HttpStatus::CREATED);
    }
}