<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Scrapper\TrendAgent\TrendAgentScrapperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrendAgentScrapperController extends Controller
{
    public function __construct(
        private TrendAgentScrapperService $scrapperService
    ) {}


    public function scrapeCity(Request $request): JsonResponse
    {
        $request->validate([
            'city' => 'required|string|in:spb,msk,krd,nsk,rst,kzn,ekb'
        ]);

        try {
            $result = $this->scrapperService->scrapeCity($request->city);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => "Scraping started for city: {$request->city}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to start scraping'
            ], 500);
        }
    }


    public function getStatus(): JsonResponse
    {
        try {
            $stats = $this->scrapperService->getScraperStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getFailedUrls(): JsonResponse
    {
        try {
            $failedUrls = $this->scrapperService->getFailedUrls();

            return response()->json([
                'success' => true,
                'data' => $failedUrls,
                'count' => count($failedUrls)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

 
    public function retryFailedUrls(): JsonResponse
    {
        try {
            $this->scrapperService->retryFailedUrls();

            return response()->json([
                'success' => true,
                'message' => 'Failed URLs moved to retry queue'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function clearCache(): JsonResponse
    {
        try {
            $this->scrapperService->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
