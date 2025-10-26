<?php
namespace App\Http\Controllers;

use App\Models\XetDuyet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CccdVerifyController extends Controller
{
    public function checkCccdStatus(Request $request)
    {
        try {
            $cccd = $request->input('cccd');

            // Kiểm tra xem CCCD đã tồn tại trong bảng xet_duyet chưa
            $xet_duyet = XetDuyet::where('cccd_input', $cccd)->first();

            if ($xet_duyet) {
                return response()->json([
                    'exists' => true,
                    'status' => $xet_duyet->trang_thai,
                    'data' => [
                        'mssv' => $xet_duyet->mssv_input,
                        'cccd' => $xet_duyet->cccd_input,
                        'reason' => $xet_duyet->ghi_chu
                    ]
                ]);
            } else {
                return response()->json([
                    'exists' => false
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'error' => 'Lỗi khi kiểm tra trạng thái CCCD: ' . $e->getMessage()
            ], 500);
        }
    }
    public function submitApproval(Request $request)
    {
        try {
            $mssv = $request->input('mssv');
            $cccd = $request->input('cccd');
            $imageUrl = $request->input('imageUrl');

            if (empty($mssv)) {
                return response()->json([
                    'success' => false,
                    'message' => 'MSSV không được để trống.'
                ], 400);
            }

            // Kiểm tra xem CCCD đã tồn tại chưa trước khi tạo mới
            $existingRecord = XetDuyet::where('cccd_input', $cccd)->first();
            
            if ($existingRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số CCCD này đã được gửi yêu cầu xét duyệt trước đó.'
                ], 400);
            }

            // Tạo bản ghi xét duyệt
            XetDuyet::create([
                'mssv_input' => $mssv,
                'cccd_input' => $cccd,
                'trang_thai' => 'pending',
                'anh_cccd' => $imageUrl,
                'ghi_chu' => 'Chờ xét duyệt thông tin MSSV và CCCD'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu xét duyệt thành công. Vui lòng chờ xét duyệt từ quản trị viên.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gửi yêu cầu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Lấy tất cả các yêu cầu xét duyệt
    public function getAllApprovals()
    {
        try {
            $approvals = XetDuyet::all();
            return response()->json($approvals);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi khi lấy danh sách xét duyệt: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật trạng thái xét duyệt
    public function updateApprovalStatus(Request $request)
    {
        try {
            $id = $request->input('id');
            $newStatus = $request->input('status');
            $reason = $request->input('reason', '');

            $approval = XetDuyet::find($id);
            if (!$approval) {
                return response()->json([
                    'success' => false,
                    'message' => 'Yêu cầu xét duyệt không tồn tại.'
                ], 404);
            }

            $approval->trang_thai = $newStatus;
            if ($newStatus === 'rejected') {
                $approval->ghi_chu = $reason;
            } else {
                $approval->ghi_chu = '';
            }
            $approval->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái xét duyệt thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách các CCCD đã được kích hoạt
     */
    public function getActivatedCccds(Request $request)
    {
        try {
            // Lấy các bản ghi có trạng thái là 'approved' (đã duyệt)
            $activatedCccds = XetDuyet::where('trang_thai', 'approved')
                ->select('id', 'mssv_input', 'cccd_input', 'anh_cccd', 'ghi_chu', 'created_at', 'updated_at')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $activatedCccds,
                'total' => $activatedCccds->count(),
                'message' => 'Lấy danh sách CCCD đã kích hoạt thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách CCCD đã kích hoạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách các CCCD đã được kích hoạt với phân trang
     */
    public function getActivatedCccdsPaginated(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
            $page = $request->input('page', 1);

            $activatedCccds = XetDuyet::where('trang_thai', 'approved')
                ->select('id', 'mssv_input', 'cccd_input', 'anh_cccd', 'ghi_chu', 'created_at', 'updated_at')
                ->orderBy('updated_at', 'desc') // Sắp xếp theo thời gian cập nhật mới nhất
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $activatedCccds->items(),
                'pagination' => [
                    'current_page' => $activatedCccds->currentPage(),
                    'per_page' => $activatedCccds->perPage(),
                    'total' => $activatedCccds->total(),
                    'last_page' => $activatedCccds->lastPage(),
                    'from' => $activatedCccds->firstItem(),
                    'to' => $activatedCccds->lastItem(),
                ],
                'message' => 'Lấy danh sách CCCD đã kích hoạt thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách CCCD đã kích hoạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm kiếm CCCD đã kích hoạt theo MSSV hoặc CCCD
     */
    public function searchActivatedCccds(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            $perPage = $request->input('per_page', 10);

            $query = XetDuyet::where('trang_thai', 'approved')
                ->select('id', 'mssv_input', 'cccd_input', 'anh_cccd', 'ghi_chu', 'created_at', 'updated_at');

            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('mssv_input', 'like', '%' . $searchTerm . '%')
                      ->orWhere('cccd_input', 'like', '%' . $searchTerm . '%');
                });
            }

            $activatedCccds = $query->orderBy('updated_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $activatedCccds->items(),
                'pagination' => [
                    'current_page' => $activatedCccds->currentPage(),
                    'per_page' => $activatedCccds->perPage(),
                    'total' => $activatedCccds->total(),
                    'last_page' => $activatedCccds->lastPage(),
                    'from' => $activatedCccds->firstItem(),
                    'to' => $activatedCccds->lastItem(),
                ],
                'message' => 'Tìm kiếm CCCD đã kích hoạt thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tìm kiếm CCCD đã kích hoạt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin chi tiết của một CCCD đã kích hoạt
     */
    public function getActivatedCccdDetail($id)
    {
        try {
            $activatedCccd = XetDuyet::where('trang_thai', 'approved')
                ->where('id', $id)
                ->select('id', 'mssv_input', 'cccd_input', 'anh_cccd', 'ghi_chu', 'created_at', 'updated_at')
                ->first();

            if (!$activatedCccd) {
                return response()->json([
                    'success' => false,
                    'message' => 'CCCD đã kích hoạt không tồn tại.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $activatedCccd,
                'message' => 'Lấy thông tin chi tiết CCCD đã kích hoạt thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin chi tiết CCCD: ' . $e->getMessage()
            ], 500);
        }
    }
}