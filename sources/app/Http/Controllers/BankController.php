<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\BankServiceInterface;
use App\Http\Requests\FilteredMortgagesRequest;
use App\Providers\AppServiceProvider;

/**
 * @see AppServiceProvider::registerBankService()
 * @see BankServiceInterface
 */
class BankController extends Controller
{
    /**
     * @param BankServiceInterface $bankService
     */
    public function __construct(protected BankServiceInterface $bankService)
    {

    }

    /**
     * @param FilteredMortgagesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getFilteredMortgages(FilteredMortgagesRequest $request)
    {
        $categories = $request->validated('categories');
        $banks = $request->validated('banks');
        $preferredPrice = $request->validated('preferred_price');
        $preferredYear = $request->validated('preferred_year');
        $preferredInitialFee = $request->validated('preferred_initial_fee');
        $sortingParameter = $request->validated('sorting_parameter');
        $sortingDirection = $request->validated('sorting_direction');

        if ($categories == null) {
            $categories = [];
        }

        if ($banks == null) {
            $banks = [];
        }

        $mortgageData = $this->bankService->getSortedMortgages($sortingParameter, $sortingDirection, $categories, $banks, $preferredPrice, $preferredYear, $preferredInitialFee);
        $views = [];

        foreach ($mortgageData as $mortgage) {
            $views[] = view('mortgage-calculator.plan.dropdown', $mortgage)->render();
        }

        return response()->json(['views' => $views]);
    }
}
