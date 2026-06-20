<?php

namespace App\Http\Controllers\Api\Position;

use App\Application\Position\Search\Input\Builder\SearchInputBuilder;
use App\Application\Position\Search\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Dto\Api\Position\Search\Builder\SearchDtoBuilder;
use App\Http\Requests\Api\Position\Index\IndexRequest;
use Illuminate\Http\JsonResponse;

/**
 * 役職検索APIの入力をApplication層へ渡し、結果をJSONで返す。
 */
class PositionController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService,
        private readonly SearchInputBuilder $searchInputBuilder,
        private readonly SearchDtoBuilder $searchDtoBuilder,
    ) {}

    /**
     * 検証、入力変換、検索、API用DTO変換の順に処理する。
     */
    public function index(IndexRequest $request): JsonResponse
    {
        // FormRequestのルールを通過した値だけを検索処理へ渡す。
        $validated = $request->validated();
        $input = $this->searchInputBuilder->build($validated);
        $output = $this->searchService->execute($input);
        $dtos = $this->searchDtoBuilder->build($output);

        // JsonSerializableなDTOをLaravelのJSONレスポンスへ変換する。
        return response()->json($dtos);
    }
}
