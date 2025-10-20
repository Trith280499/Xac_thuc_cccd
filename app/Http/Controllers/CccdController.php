<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CccdController extends Controller
{
    public function process(Request $request)
    {
        if (!$request->hasFile('cccd')) {
            return response()->json(['error' => 'No image uploaded.'], 400);
        }

        $file = $request->file('cccd');
        $path = $file->getPathName();

        // Make sure exif extension is available
        if (!function_exists('exif_read_data')) {
            return response()->json(['error' => 'EXIF not supported on this server.'], 500);
        }

        try {
            $exif = @exif_read_data($path, 0, true);
            if (!$exif) {
                return response()->json(['error' => 'Cannot read EXIF metadata.']);
            }

            $make       = $exif['IFD0']['Make']  ?? 'Unknown';
            $model      = $exif['IFD0']['Model'] ?? 'Unknown';
            $dateTaken  = $exif['EXIF']['DateTimeOriginal'] ?? null;

            $today       = now()->format('Y-m-d');
            $photoDate   = $dateTaken ? date('Y-m-d', strtotime($dateTaken)) : null;
            $takenToday  = ($photoDate === $today);
            $device      = trim("$make $model");
            $isIphone    = stripos($device, 'iphone') !== false;

            return response()->json([
                'status'      => 'success',
                'device'      => $device,
                'date_taken'  => $dateTaken,
                'taken_today' => $takenToday,
                'is_iphone'   => $isIphone,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
