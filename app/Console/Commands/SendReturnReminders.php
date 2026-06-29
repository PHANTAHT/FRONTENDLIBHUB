<?php

namespace App\Console\Commands;

use App\Mail\ReturnReminderMail;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReturnReminders extends Command
{
    protected $signature   = 'pustaka:send-reminders';
    protected $description = 'Kirim email reminder pengembalian buku (H-3, H-1, H-0)';

    public function handle(): int
    {
        foreach ([3, 1, 0] as $day) {
            $targetDate = now()->addDays($day)->toDateString();

            $loans = Loan::with(['user', 'items.book'])
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->whereDate('tanggal_tenggat', $targetDate)
                ->get();

            foreach ($loans as $loan) {
                Mail::to($loan->user->email)->send(new ReturnReminderMail($loan));
                $this->line("→ Reminder → {$loan->user->email} (tenggat: {$loan->tanggal_tenggat->format('d/m/Y')})");
            }
        }

        $this->info('✅ Selesai.');
        return self::SUCCESS;
    }
}