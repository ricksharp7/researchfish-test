<?php

namespace App\Http\Controllers;

use \Log;
use App\Http\Resources\Publication as PublicationResource;
use Exception;
use Illuminate\Http\Request;
use PublicationCache;

class PublicationController extends Controller
{
    public function get(Request $request)
    {
        $this->validate($request, [
            'doi' => 'required|string',
        ]);

        $doi = $request->input('doi');

        try {
            $publications = PublicationCache::getPublication($doi);

            if (!$publications) {
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Not found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'publications' => PublicationResource::collection($publications),
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'failure',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
