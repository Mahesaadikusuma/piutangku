<?php

namespace App\Console\Commands;

use App\Enums\StatusType;
use App\Mail\PiutangReminder as MailPiutangReminder;
use App\Models\Piutang;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PiutangReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:piutang-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder Piutang';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $piutangs = Piutang::whereDate('tanggal_jatuh_tempo', '<=', Carbon::now()->addDays(15))
            ->whereNull('tanggal_lunas')
            ->where('status_pembayaran', '=', StatusType::PENDING->value)
            ->get()
            ->groupBy('user_id');

        foreach ($piutangs as $userId => $userPiutangs) {
            $user = $userPiutangs->first()->user;
            // Log::info('Mengirim email reminder ke user', [
            //     'user_id' => $userId,
            //     'jumlah_piutang' => $userPiutangs->count()
            // ]);
            Mail::to($user->email)->send(new MailPiutangReminder($userPiutangs));
        }
    }
}
