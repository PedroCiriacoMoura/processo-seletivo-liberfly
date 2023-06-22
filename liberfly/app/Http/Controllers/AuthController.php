<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

        /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Autenticação"},
     *     summary="Autentica o usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", example="pedromoura@mail.com"),
     *                 @OA\Property(property="password", type="string", example="123456")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciais inválidas")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $result = $this->authService->login($credentials);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 401);
        }

        return response()->json(['token' => $result['token']]);
    }

        /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Autenticação"},
     *     summary="Desconecta o usuário autenticado",
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Desconectado com sucesso")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

        /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Autenticação"},
     *     summary="Registra um novo usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Pedro Moura"),
     *                 @OA\Property(property="email", type="string", example="pedromoura@mail.com"),
     *                 @OA\Property(property="password", type="string", example="123456")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->authService->register($request->all());
        return response()->json(['token' => $result['token']], 201);
    }

        /**
     * @OA\Get(
     *     path="/me",
     *     tags={"Autenticação"},
     *     summary="Obtém informações do usuário autenticado",
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
    public function me()
    {
        $user = $this->authService->getUser();
        return response()->json(['user' => $user]);
    }
}
