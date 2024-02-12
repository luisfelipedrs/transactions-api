<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\HttpStatus;
use App\Application\Helper\Mapper\UserMapper;
use App\Application\Usecase\User\CreateUserAccountUseCase;
use App\Application\Usecase\User\FindUserAccountUseCase;
use App\Application\Usecase\Wallet\DepositMoneyUseCase;
use App\Infra\Validation\CreateUserValidator;
use App\Infra\Validation\WalletOperationValidator;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Transactions Api",
 *     version="0.1"
 * ),
 * @OA\Server(url="http://localhost:9501")
 */
class UserController
{
    public function __construct(
        private readonly CreateUserAccountUseCase $createUserAccountUseCase,
        private readonly DepositMoneyUseCase $depositMoneyUseCase,
        private readonly FindUserAccountUseCase $findUserAccountUseCase,
        private readonly UserMapper $userMapper
    ){}

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Cria um novo usuário",
     *     description="Cria um novo usuário com base nos parâmetros fornecidos",
     *     @OA\RequestBody(
     *         description="Objeto de busca do lado do cliente",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="userType", type="string", description="Tipo de usuário (por exemplo: 'common')"),
     *                 @OA\Property(property="name", type="string", description="Nome do usuário"),
     *                 @OA\Property(property="email", type="string", format="email", description="Endereço de e-mail do usuário"),
     *                 @OA\Property(property="password", type="string", description="Senha do usuário"),
     *                 @OA\Property(property="documentType", type="string", description="Tipo de documento (por exemplo: 'cnpj')"),
     *                 @OA\Property(property="document", type="string", description="Número do documento (por exemplo: '32618735000170')")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\Schema(ref="#/components/schemas/SearchResultObject")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso não encontrado"
     *     )
     * )
     */
    public function createUser(CreateUserValidator $request, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $userAccount = $this->createUserAccountUseCase->execute($request);
        return $response->json($userAccount->export())->withStatus(HttpStatus::CREATED);
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/deposit",
     *     summary="Realiza uma nova transação",
     *     description="Realiza uma nova transação com base nos parâmetros fornecidos",
     *     @OA\RequestBody(
     *         description="Objeto de valor da transação",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="value", type="string", format="decimal", description="Valor da transação (por exemplo: '500.00')")
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
    public function handleDeposit(WalletOperationValidator $request, string $id, ResponseInterface $response): PsrResponseInterface
    {
        $request = $request->validated();
        $wallet = $this->depositMoneyUseCase->execute($id, $request);
        return $response->json($wallet->export())->withStatus(HttpStatus::OK);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     @OA\Response(
     *         response="200",
     *         description="The data"
     *     )
     * )
     */
    public function findUserById(string $id, ResponseInterface $response): PsrResponseInterface
    {
        $userAccount = $this->findUserAccountUseCase->execute($id);
        return $response->json($userAccount->export())->withStatus(HttpStatus::OK);
    }
}