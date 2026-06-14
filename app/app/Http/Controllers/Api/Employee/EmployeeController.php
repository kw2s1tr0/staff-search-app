<?php

namespace App\Http\Controllers\Api\Employee;

use App\Application\Employee\Search\Input\Builder\SearchInputBuilder;
use App\Application\Employee\Search\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Dto\Api\Employee\Search\Builder\SearchDtoBuilder;
use App\Http\Requests\Api\Employee\Index\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * APIの社員検索リクエストをアプリケーション層へ渡し、JSONで返す。
 */
class EmployeeController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService,
        private readonly SearchInputBuilder $searchInputBuilder,
        private readonly SearchDtoBuilder $searchDtoBuilder,
    ) {}

    /**
     * 検証済み入力を検索用の型へ変換し、API用DTOとして応答する。
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $input = $this->searchInputBuilder->build($validated);
        $output = $this->searchService->execute($input);
        $dtos = $this->searchDtoBuilder->build($output);

        return response()->json($dtos);
    }
}
