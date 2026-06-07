<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: "/categories",
        summary: "List all categories",
        description: "Get a list of all categories for the authenticated user.",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "user_id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Gaji"),
                            new OA\Property(property: "type", type: "string", example: "income"),
                            new OA\Property(property: "created_at", type: "string", format: "date-time"),
                            new OA\Property(property: "updated_at", type: "string", format: "date-time")
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
        $categories = $request->user()->categories;
        return response()->json($categories);
    }

    #[OA\Post(
        path: "/categories",
        summary: "Create a new category",
        description: "Create a new category for the authenticated user.",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "type"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Makanan"),
                    new OA\Property(property: "type", type: "string", enum: ["income", "expense"], example: "expense")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kategori berhasil dibuat"),
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
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $category = $request->user()->categories()->create($request->only(['name', 'type']));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data' => $category
        ], 201);
    }

    #[OA\Delete(
        path: "/categories/{category}",
        summary: "Delete a category",
        description: "Delete a specific category by ID.",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                description: "The ID of the category",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Category deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Kategori dihapus")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized access to this category"
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            )
        ]
    )]
    public function destroy(Category $category, Request $request)
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();
        return response()->json(['success' => true, 'message' => 'Kategori dihapus']);
    }
}