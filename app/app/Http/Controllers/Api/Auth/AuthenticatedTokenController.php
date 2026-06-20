<?php

namespace App\Http\Controllers\Api\Auth;

use App\Application\Auth\ApiToken\Issue\Input\Builder\IssueTokenInputBuilder;
use App\Application\Auth\ApiToken\Issue\IssueTokenService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * APIトークンの発行、利用者確認、失効を受け持つ。
 */
class AuthenticatedTokenController extends Controller
{
    public function __construct(
        private readonly IssueTokenInputBuilder $inputBuilder,
        private readonly IssueTokenService $issueTokenService,
    ) {}

    /**
     * メールアドレスとパスワードを検証し、APIトークンをJSONで返す。
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // 検証済みの入力だけをDTOへ変換し、認証処理をServiceへ任せる。
        $validated = $request->validated();
        $ipAddress = $request->ip();
        $input = $this->inputBuilder->build($validated, $ipAddress);
        $output = $this->issueTokenService->execute($input);
        $expiresAt = $output->expiresAt->toIso8601String();

        // access_tokenの平文は再取得できないため、CLIはこのレスポンスを安全に保存する必要がある。
        return response()->json([
            'access_token' => $output->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Sanctumがトークンから特定した、現在のユーザー情報を返す。
     */
    public function show(Request $request): JsonResponse
    {
        // auth:sanctum通過後なので、ここでは認証済みUserを取得できる。
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * リクエストに使われたトークンだけを削除し、利用できない状態にする。
     */
    public function destroy(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        // currentAccessTokenはAuthorizationヘッダーで提示されたトークンを表す。
        $accessToken = $user->currentAccessToken();
        // 他の端末用トークンは削除せず、現在の1本だけをDBから削除する。
        $accessToken->delete();

        return response()->noContent();
    }
}
