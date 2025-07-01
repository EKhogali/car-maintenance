<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaintenanceRecord;
use Filament\Notifications\Notification;

class NotifyUpcomingService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-upcoming-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     //
    // }

    public function handle()
{
    $records = MaintenanceRecord::dueSoon()->get();

    foreach ($records as $record) {
        Notification::make()
            ->title('خدمة قادمة للسيارة')
            ->body("السيارة رقم: {$record->car->license_plate} تحتاج لصيانة في تاريخ: {$record->next_due_date->format('Y-m-d')}")
            ->icon('heroicon-o-calendar')
            ->sendToDatabase(auth()->user()); // or send to specific user(s)
    }

    $this->info('تم إرسال إشعارات السيارات التي اقترب موعد صيانتها.');
}
}
