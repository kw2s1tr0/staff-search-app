<?php

namespace App\Http\Controllers\Api\Department;

use App\Application\Department\Search\Input\Builder\SearchInputBuilder;
use App\Application\Department\Search\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Dto\Api\Department\Search\Builder\SearchDtoBuilder;
use App\Http\Requests\Api\Department\Index\IndexRequest;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
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
