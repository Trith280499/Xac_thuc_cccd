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
}