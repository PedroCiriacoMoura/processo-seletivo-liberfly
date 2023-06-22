<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Produto")
 * @OA\Info(
 *     title="API de Produtos",
 *     version="1.0.0",
 *     description="API para gerenciamento de produtos",
 *     @OA\Contact(
 *         name="Pedro Moura",
 *         email="pedrociriacomoura@yahoo.com.br"
 *     )
 * )
 */
class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/produto",
     *     summary="Get all products",
     *     tags={"Produto"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="number"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="string"),
     *             @OA\Property(property="category", type="string")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $products = $this->productService->getAll();

        return response()->json($products, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/produto",
     *     summary="Create a new product",
     *     tags={"Produto"},
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="category", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="number"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="string"),
     *             @OA\Property(property="category", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $product = $this->productService->create($input);
        return response()->json(['message' => 'Sucess', 'product' => $product]);
    }

    /**
     * @OA\Get(
     *     path="/produto/{id}",
     *     summary="Get a product by ID",
     *     tags={"Produto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *          @OA\JsonContent(
     *             @OA\Property(property="id", type="number"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="string"),
     *             @OA\Property(property="category", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $product = $this->productService->getById($id);
        return response()->json(['message' => 'Sucess', 'product' => $product]);
    }

    /**
     * @OA\Put(
     *     path="/produto/{id}",
     *     summary="Update a product",
     *     tags={"Produto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="category", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *          @OA\JsonContent(
     *             @OA\Property(property="id", type="number"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="price", type="string"),
     *             @OA\Property(property="category", type="string")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = $this->productService->update($request, $id);
        return response()->json(['message' => 'Sucess', 'product' => $product]);
    }

    /**
     * @OA\Delete(
     *     path="/produto/{id}",
     *     summary="Delete a product",
     *     tags={"Produto"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->productService->delete($id);
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
