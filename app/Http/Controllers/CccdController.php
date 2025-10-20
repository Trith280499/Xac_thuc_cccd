<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CccdController extends Controller
{
    // main function: decide based on input
    public function process(Request $request)
    {
        $url  = $request->input('url');
        $text = $request->input('text');

        if (!$url && !$text) {
            return response()->json([
                'error' => 'Please provide either url or text'
            ], 400);
        }

        if ($url) {
            return $this->authen($request);
        }

        if ($text) {
            return $this->check($request);
        }
    }

    // handle image authentication
    private function authen(Request $request)
    {
        $url = $request->input('url');

        return response()->json([
            'status' => 'success',
            'type'   => 'authen_cccd',
            'url'    => $url
        ]);
    }

    // handle text checking
    private function check(Request $request)
    {
        $text = $request->input('text');

        return response()->json([
            'status' => 'success',
            'type'   => 'check_cccd',
            'text'   => $text
        ]);
    }
}
