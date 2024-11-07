<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateVoucherStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vouchers:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = Carbon::now();

        $vouchers = Voucher::whereNotNull('end_date')
                           ->where(function($query) use ($currentDate) {
                               $query->where('end_date', '<', $currentDate)
                                     ->orWhere('usage_limit', '<=', 0); // Kiểm tra hết lượt sử dụng
                           })
                           ->where('status', 1) // Chỉ cập nhật các voucher đang hoạt động
                           ->get();

        foreach ($vouchers as $voucher) {
            // Cập nhật trạng thái của voucher
            $voucher->status = 0;
            $voucher->save();
        }
        //test
        // php artisan vouchers:update-status
        $this->info('Cập nhật trạng thái voucher thành công.');
    }
}
