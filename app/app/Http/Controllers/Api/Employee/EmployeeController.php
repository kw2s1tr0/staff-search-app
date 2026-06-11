<?php

namespace App\Http\Controllers\Api\Employee;

use App\Application\Employee\Search\Builder\SearchInputBuilder;
use App\Application\Employee\Search\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Dto\Api\Employee\Search\SearchDtoBuilder;
use App\Http\Requests\Api\Employee\Index\IndexRequest;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
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
