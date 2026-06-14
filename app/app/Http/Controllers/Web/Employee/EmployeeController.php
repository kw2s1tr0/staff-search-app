<?php

namespace App\Http\Controllers\Web\Employee;

use App\Application\Department\Search\Input\SearchInput as DepartmentSearchInput;
use App\Application\Department\Search\SearchService as DepartmentSearchService;
use App\Application\Employee\Search\Input\Builder\SearchInputBuilder;
use App\Application\Employee\Search\SearchService;
use App\Application\Position\Search\Input\SearchInput as PositionSearchInput;
use App\Application\Position\Search\SearchService as PositionSearchService;
use App\Enums\DepartmentOrderBy;
use App\Enums\OrderDirection;
use App\Enums\PositionOrderBy;
use App\Http\Controllers\Controller;
use App\Http\Dto\Web\Department\Search\Builder\SearchDtoBuilder as DepartmentSearchDtoBuilder;
use App\Http\Dto\Web\Employee\Search\Builder\SearchDtoBuilder;
use App\Http\Dto\Web\Position\Search\Builder\SearchDtoBuilder as PositionSearchDtoBuilder;
use App\Http\Requests\Web\Employee\Index\IndexRequest;
use Illuminate\Contracts\View\View;

/**
 * Web画面の社員検索リクエストを処理し、一覧表示に必要な値をViewへ渡す。
 */
class EmployeeController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService,
        private readonly SearchInputBuilder $searchInputBuilder,
        private readonly SearchDtoBuilder $searchDtoBuilder,
        private readonly DepartmentSearchService $departmentSearchService,
        private readonly DepartmentSearchDtoBuilder $departmentSearchDtoBuilder,
        private readonly PositionSearchService $positionSearchService,
        private readonly PositionSearchDtoBuilder $positionSearchDtoBuilder,
    ) {}

    /**
     * APIとは別の表示用DTOへ変換し、Bladeが必要とする項目だけを渡す。
     */
    public function index(IndexRequest $request): View
    {
        $validated = $request->validated();
        $input = $this->searchInputBuilder->build($validated);
        $output = $this->searchService->execute($input);
        $employees = $this->searchDtoBuilder->build($output);

        $departmentInput = new DepartmentSearchInput(
            orderBy: DepartmentOrderBy::Id,
            orderDirection: OrderDirection::Asc,
        );
        $departmentOutput = $this->departmentSearchService->execute($departmentInput);
        $departments = $this->departmentSearchDtoBuilder->build($departmentOutput);

        $positionInput = new PositionSearchInput(
            orderBy: PositionOrderBy::Id,
            orderDirection: OrderDirection::Asc,
        );
        $positionOutput = $this->positionSearchService->execute($positionInput);
        $positions = $this->positionSearchDtoBuilder->build($positionOutput);

        return view('employees.index', [
            'employees' => $employees,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }
}
