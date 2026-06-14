<?php

namespace App\Http\Controllers\Api\Position;

use App\Application\Position\Search\Input\Builder\SearchInputBuilder;
use App\Application\Position\Search\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Dto\Api\Position\Search\Builder\SearchDtoBuilder;
use App\Http\Requests\Api\Position\Index\IndexRequest;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService,
        private readonly SearchInputBuilder $searchInputBuilder,
        private readonly SearchDtoBuilder $searchDtoBuilder,
    ) {}

    public function index(IndexRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $input = $this->searchInputBuilder->build($validated);
        $output = $this->searchService->execute($input);
        $dtos = $this->searchDtoBuilder->build($output);

        return response()->json($dtos);
    }
}
