<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TransactionController extends Controller
{
    #[OA\Get(
        path: "/transactions",
        summary: "List all transactions",
        description: "Get a list of all transactions for the authenticated user, ordered by transaction date.",
        tags: ["Transactions"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of transactions retrieved successfully",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "user_id", type: "integer", example: 1),
                            new OA\Property(property: "category_id", type: "integer", example: 1),
                            new OA\Property(property: "type", type: "string", example: "expense"),
                            new OA\Property(property: "amount", type: "number", format: "float", example: 15000.00),
                            new OA\Property(property: "description", type: "string", example: "Beli Kopi"),
                            new OA\Property(property: "transaction_date", type: "string", format: "date", example: "2026-06-07"),
                            new OA\Property(property: "created_at", type: "string", format: "date-time"),
                            new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                            new OA\Property(property: "category", type: "object")
                        ]
                    )
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
        $transactions = $request->user()
            ->transactions()
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transactions);
    }

    #[OA\Post(
        path: "/transactions",
        summary: "Create a new transaction",
        description: "Add a new transaction (income/expense) for the authenticated user.",
        tags: ["Transactions"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["category_id", "type", "amount", "transaction_date"],
                properties: [
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "type", type: "string", enum: ["income", "expense"], example: "expense"),
                    new OA\Property(property: "amount", type: "number", format: "float", example: 15000.00),
                    new OA\Property(property: "description", type: "string", example: "Beli Kopi"),
                    new OA\Property(property: "transaction_date", type: "string", format: "date", example: "2026-06-07")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Transaction created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Transaksi berhasil ditambahkan"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0',
            'description'      => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction = $request->user()->transactions()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data'    => $transaction->load('category')
        ], 201);
    }

    #[OA\Get(
        path: "/transactions/{transaction}",
        summary: "Get transaction details",
        description: "Retrieve details of a specific transaction by ID.",
        tags: ["Transactions"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "transaction",
                in: "path",
                description: "The ID of the transaction",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Transaction details retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "user_id", type: "integer", example: 1),
                        new OA\Property(property: "category_id", type: "integer", example: 1),
                        new OA\Property(property: "type", type: "string", example: "expense"),
                        new OA\Property(property: "amount", type: "number", format: "float", example: 15000.00),
                        new OA\Property(property: "description", type: "string", example: "Beli Kopi"),
                        new OA\Property(property: "transaction_date", type: "string", format: "date", example: "2026-06-07"),
                        new OA\Property(property: "category", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized access to this transaction"
            ),
            new OA\Response(
                response: 404,
                description: "Transaction not found"
            )
        ]
    )]
    public function show(Transaction $transaction, Request $request)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($transaction->load('category'));
    }

    #[OA\Put(
        path: "/transactions/{transaction}",
        summary: "Update a transaction",
        description: "Update details of an existing transaction.",
        tags: ["Transactions"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "transaction",
                in: "path",
                description: "The ID of the transaction to update",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["category_id", "type", "amount", "transaction_date"],
                properties: [
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "type", type: "string", enum: ["income", "expense"], example: "expense"),
                    new OA\Property(property: "amount", type: "number", format: "float", example: 20000.00),
                    new OA\Property(property: "description", type: "string", example: "Beli Kopi Susu"),
                    new OA\Property(property: "transaction_date", type: "string", format: "date", example: "2026-06-07")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Transaction updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Transaksi berhasil diupdate"),
                        new OA\Property(property: "data", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized access to this transaction"
            ),
            new OA\Response(
                response: 404,
                description: "Transaction not found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0',
            'description'      => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diupdate',
            'data'    => $transaction->load('category')
        ]);
    }

    #[OA\Delete(
        path: "/transactions/{transaction}",
        summary: "Delete a transaction",
        description: "Delete a specific transaction by ID.",
        tags: ["Transactions"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "transaction",
                in: "path",
                description: "The ID of the transaction to delete",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Transaction deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Transaksi berhasil dihapus")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized access to this transaction"
            ),
            new OA\Response(
                response: 404,
                description: "Transaction not found"
            )
        ]
    )]
    public function destroy(Transaction $transaction, Request $request)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction->delete();
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus'
        ]);
    }
}