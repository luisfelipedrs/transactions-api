<?php

declare(strict_types=1);

namespace App\Infra\Command;

use App\Application\Service\Transaction\SendNotificationService;
use Hyperf\Command\Command as HyperfCommand;

class RetryNotificationCommand extends HyperfCommand
{
    /**
     * The command
     *
     * @var string
     */
    public function __construct(
        private readonly SendNotificationService $sendNotificationService
    ) {
        parent::__construct('send:notification');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Send transaction notification to users');
    }
    
    public function handle()
    {
        $this->sendNotificationService->sendNotifications();
    }
}
