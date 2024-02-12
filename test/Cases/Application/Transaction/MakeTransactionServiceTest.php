<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Application\Usecase\Transaction\MakeTransactionUseCase;
use App\Application\Usecase\User\CreateUserAccountUseCase;
use App\Application\Usecase\User\FindUserAccountUseCase;
use App\Domain\Aggregate\UserAccount;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\User;
use App\Domain\Entity\Wallet;
use App\Domain\Repository\AuthorizationRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\WalletRepository;
use App\Domain\ValueObject\Shared\ExternalIdValueObject;
use App\Domain\ValueObject\User\DocumentType;
use App\Domain\ValueObject\User\UserDocument;
use App\Domain\ValueObject\User\UserEmail;
use App\Domain\ValueObject\User\UserId;
use App\Domain\ValueObject\User\UserName;
use App\Domain\ValueObject\User\UserPassword;
use App\Domain\ValueObject\User\UserType;
use App\Domain\ValueObject\Wallet\WalletBalance;
use App\Domain\ValueObject\Wallet\WalletId;
use App\Domain\ValueObject\Wallet\WalletUserId;
use Hyperf\DbConnection\Db;

class MakeTransactionServiceTest extends TestCase
{
    private User $payer;
    private Wallet $payerWallet;
    private UserAccount $payerAccount;

    private User $sellerPayer;
    private Wallet $sellerPayerWallet;
    private UserAccount $sellerPayerAccount;

    private User $payee;
    private Wallet $payeeWallet;
    private UserAccount $payeeAccount;

    private UserRepository $userRepository;
    private WalletRepository $walletRepository;

    private Transaction $transaction;
    private MakeTransactionUseCase $makeTransactionService;

    private AuthorizationRepository $authorizationRepository;

    private array $transactionRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = \Hyperf\Support\make(UserRepository::class);
        $this->walletRepository = \Hyperf\Support\make(WalletRepository::class);

        // payer
        $this->payer = new User(
            UserType::from('common'),
            new UserName('Luis'),
            new UserEmail('email1@email.com'),
            new UserPassword('123'),
            DocumentType::from('cnpj'),
            new UserDocument('53038812000192', 'cnpj'),
            new ExternalIdValueObject('141b4b25-fb52-4ca2-a813-e4e55d67c991'),
            new UserId(98)
        );
        $this->userRepository->save($this->payer);

        $this->payerWallet = new Wallet(
            new WalletUserId($this->payer->id->value),
            new WalletBalance("20.00"),
            new WalletId(98)
        );
        $this->walletRepository->save($this->payerWallet);
        $this->payerAccount = new UserAccount($this->payer, $this->payerWallet);

        // seller payer
        $this->sellerPayer = new User(
            UserType::from('seller'),
            new UserName('Luis Felipe'),
            new UserEmail('email3@email.com'),
            new UserPassword('123'),
            DocumentType::from('cnpj'),
            new UserDocument('75234126000120', 'cnpj'),
            new ExternalIdValueObject('2fe18a43-954f-4644-bc85-30470247676b'),
            new UserId(100)
        );
        $this->userRepository->save($this->sellerPayer);

        $this->sellerPayerWallet = new Wallet(
            new WalletUserId($this->sellerPayer->id->value),
            new WalletBalance("20.00"),
            new WalletId(100)
        );
        $this->walletRepository->save($this->sellerPayerWallet);
        $this->sellerPayerAccount = new UserAccount($this->sellerPayer, $this->sellerPayerWallet);
        
        // payee
        $this->payee = new User(
            UserType::from('common'),
            new UserName('Felipe'),
            new UserEmail('email2@email.com'),
            new UserPassword('123'),
            DocumentType::from('cnpj'),
            new UserDocument('76678827000110', 'cnpj'),
            new ExternalIdValueObject('6ca94151-40b5-46fa-926a-30bb933fe031'),
            new UserId(99)
        );
        $this->userRepository->save($this->payee);
        
        $this->payeeWallet = new Wallet(
            new WalletUserId($this->payee->id->value),
            new WalletBalance("20.00"),
            new WalletId(99)
        );
        $this->walletRepository->save($this->payeeWallet);
        $this->payeeAccount = new UserAccount($this->payee, $this->payeeWallet);

        $this->makeTransactionService = \Hyperf\Support\make(MakeTransactionUseCase::class);
    }

    public function testSuccessfulTransaction(): void
    {
        $this->transactionRequest = [
            "payer" => $this->payerAccount->user->externalId->value,
            "payee" => $this->payeeAccount->user->externalId->value,
            "value" => "10.00"
        ];
        
        $this->transaction = $this->makeTransactionService->execute($this->transactionRequest);
        $this->assertInstanceOf(Transaction::class, $this->transaction);

        Db::table('notifications')->where('transaction_id', $this->transaction->id->value)->delete();
        Db::table('transactions')->delete($this->transaction->id->value);
    }

    public function testFailedTransaction(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Saldo insuficiente para realizar a transação.");

        $this->transactionRequest = [
            "payer" => $this->payerAccount->user->externalId->value,
            "payee" => $this->payeeAccount->user->externalId->value,
            "value" => "30.00"
        ];

        $this->transaction = $this->makeTransactionService->execute($this->transactionRequest);
        $this->assertNull($this->transaction);
    }

    public function testUnauthorizedTransaction(): void
    {
        $this->authorizationRepository = \Hyperf\Support\make(AuthorizationRepository::class);

        if (!$this->authorizationRepository->isAuthorized()) {
            $this->assertFalse(!$this->authorizationRepository->isAuthorized());
        } else {
            $this->assertTrue($this->authorizationRepository->isAuthorized());
        }
    }

    public function testSellerTransaction(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Usuários lojistas não podem realizar transferências.");

        $this->transactionRequest = [
            "payer" => $this->sellerPayerAccount->user->externalId->value,
            "payee" => $this->payeeAccount->user->externalId->value,
            "value" => "10.00"
        ];

        $this->transaction = $this->makeTransactionService->execute($this->transactionRequest);
        $this->assertNull($this->transaction);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Db::table('users')->delete($this->payer->id->value);
        Db::table('users')->delete($this->sellerPayer->id->value);
        Db::table('users')->delete($this->payee->id->value);
    }
}