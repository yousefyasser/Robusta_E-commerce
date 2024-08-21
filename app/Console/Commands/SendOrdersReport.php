<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Order;
use App\Mail\OrdersReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

class SendOrdersReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-orders-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send orders report to admin users by email daily.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $io = new SymfonyStyle($this->input, new ConsoleOutput);

        $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
        $orders = Order::where('created_at', '>=', now()->subDay())
            ->with('user')->get();

        if (!$adminEmails) {
            $io->error('No admin users found!');
            return;
        }

        if (!$orders->count()) {
            $io->error('No orders found!');
            return;
        }

        $this->info('Admin users found: ' . implode(', ', $adminEmails));
        $this->info('Orders found: ' . $orders->count());

        $io->info('Generating orders report...');
        $reportSpreadsheet = Excel::raw(new OrdersExport, \Maatwebsite\Excel\Excel::XLSX);

        $io->info('Sending orders report...');
        Mail::to($adminEmails)->send(new OrdersReport($orders, $reportSpreadsheet));
        $io->success('Orders report sent successfully!');
    }
}
