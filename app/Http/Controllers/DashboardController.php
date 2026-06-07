<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    #[OA\Get(
        path: "/dashboard",
        summary: "Get dashboard statistics",
        description: "Retrieve financial statistics (incomes, expenses, balance, recent transactions) for the authenticated user.",
        tags: ["Dashboard"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Dashboard data loaded successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "total_income", type: "number", format: "float", example: 5000000.00),
                                new OA\Property(property: "total_expense", type: "number", format: "float", example: 2000000.00),
                                new OA\Property(property: "balance", type: "number", format: "float", example: 3000000.00),
                                new OA\Property(property: "monthly_income", type: "number", format: "float", example: 1500000.00),
                                new OA\Property(property: "monthly_expense", type: "number", format: "float", example: 500000.00),
                                new OA\Property(property: "monthly_balance", type: "number", format: "float", example: 1000000.00),
                                new OA\Property(property: "recent_transactions", type: "array", items: new OA\Items(type: "object")),
                                new OA\Property(property: "currency", type: "string", example: "Rp")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();

        // Total keseluruhan
        $totalIncome = $user->transactions()
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = $user->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // Bulan ini
        $monthlyIncome = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        $monthlyExpense = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $now->month)
            ->whereYear('transaction_date', $now->year)
            ->sum('amount');

        $monthlyBalance = $monthlyIncome - $monthlyExpense;

        // Transaksi 5 terbaru
        $recentTransactions = $user->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_income' => (float) $totalIncome,
                'total_expense' => (float) $totalExpense,
                'balance' => (float) $balance,

                'monthly_income' => (float) $monthlyIncome,
                'monthly_expense' => (float) $monthlyExpense,
                'monthly_balance' => (float) $monthlyBalance,

                'recent_transactions' => $recentTransactions,
                'currency' => 'Rp'
            ]
        ]);
    }
}