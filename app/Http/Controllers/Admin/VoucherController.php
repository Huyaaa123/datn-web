<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cập nhật trạng thái voucher nếu hết hạn
        Voucher::where('end_date', '<', Carbon::now())
            ->where('status', 1)  // Chỉ cập nhật những voucher đang ở trạng thái active
            ->update(['status' => 0]);

        // Lấy giá trị từ form tìm kiếm và bộ lọc
        $search = $request->input('search');
        $filterStatus = $request->input('status');

        // Lấy voucher cùng với voucherDetails và thông tin người dùng đã sử dụng voucher
        $vouchers = Voucher::query()
            // Tìm kiếm theo tên voucher (hoặc các trường khác như mã voucher)
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%{$search}%");
            })
            // Lọc theo trạng thái
            ->when($filterStatus, function ($query, $filterStatus) {
                $query->where('status', $filterStatus);
            })
            // eager load voucherDetails và user (người sử dụng voucher)
            ->with('voucherDetail.user')
            // Sắp xếp theo ngày tạo mới nhất ở đầu
            ->orderBy('created_at', 'desc')
            ->paginate(5); // Phân trang kết quả

        return view('admin.vouchers', compact('vouchers', 'search', 'filterStatus'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crud.vouchers-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:vouchers,code|regex:/^[a-zA-Z0-9]+$/',
            'discount_type' => 'required|in:amount,percent',
            'discount_amount' => 'nullable|numeric',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'min_order_value' => 'required|numeric',
            'usage_limit' => 'required|numeric',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Tính toán trạng thái voucher
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $current_date = Carbon::now();

        // Xác định trạng thái
        if ($current_date < $start_date) {
            $status = 2; // Chưa bắt đầu
        } elseif ($current_date >= $start_date && $current_date <= $end_date) {
            $status = 1; // Còn hạn
        } else {
            $status = 0; // Hết hạn
        }

        // Tạo mảng dữ liệu để lưu voucher
        $voucherData = [
            'status' => $status,
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'min_order_value' => $validated['min_order_value'],
            'usage_limit' => $validated['usage_limit'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ];

        // Lưu giá trị giảm giá tùy theo loại discount_type
        if ($validated['discount_type'] === 'amount') {
            $voucherData['discount_amount'] = $validated['discount_amount'];
            $voucherData['discount_percent'] = null; // Không lưu phần trăm giảm giá
        } elseif ($validated['discount_type'] === 'percent') {
            $voucherData['discount_percent'] = $validated['discount_percent'];
            $voucherData['discount_amount'] = null; // Không lưu giá giảm cố định
        }

        // Lưu voucher vào cơ sở dữ liệu
        Voucher::create($voucherData);

        return redirect()->route('admin.vouchers.index')->with('success', 'Thêm mới mã giảm giá thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $voucher = Voucher::findOrFail($id);

        return view('admin.crud.vouchers-edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, $id)
    {
        // Lấy dữ liệu đã qua xác thực
        $validated = $request->validated();

        // Tìm voucher theo ID
        $voucher = Voucher::findOrFail($id);

        // Kiểm tra loại giảm giá và xử lý
        if ($validated['discount_type'] === 'percent') {
            $validated['discount_amount'] = null;  // Xóa số tiền khi chọn giảm giá theo phần trăm
        } elseif ($validated['discount_type'] === 'amount') {
            $validated['discount_percent'] = null;  // Xóa phần trăm khi chọn giảm giá theo số tiền
        }

        // Cập nhật voucher
        $voucher->update([
            'code' => $validated['code'],
            'discount_type' => $validated['discount_type'],
            'discount_amount' => $validated['discount_amount'],
            'discount_percent' => $validated['discount_percent'],
            'min_order_value' => $validated['min_order_value'],
            'usage_limit' => $validated['usage_limit'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        // Quay lại trang danh sách với thông báo thành công
        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Tìm voucher theo ID
        $voucher = Voucher::find($id);

        // Kiểm tra xem voucher có tồn tại không
        if (!$voucher) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Mã giảm giá không tồn tại.');
        }

        $hasUsed = $voucher->voucherDetail()->exists();

        if ($hasUsed) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Không thể xóa mã giảm giá vì đã có người sử dụng.');
        }

        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa mã giảm giá thành công!');
    }

}
