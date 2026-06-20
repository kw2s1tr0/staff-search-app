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
use App\Http\Dto\Web\Employee\Search\EmployeeSearchDto;
use App\Http\Dto\Web\Position\Search\Builder\SearchDtoBuilder as PositionSearchDtoBuilder;
use App\Http\Requests\Web\Employee\Index\IndexRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

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
        // 検証済み条件で社員を検索し、一覧表示用DTOへ変換する。
        $validated = $request->validated();
        $employees = $this->searchEmployees($validated);

        // 検索フォームの部署選択肢を、ID順ですべて取得する。
        $departmentInput = new DepartmentSearchInput(
            orderBy: DepartmentOrderBy::Id,
            orderDirection: OrderDirection::Asc,
        );
        $departmentOutput = $this->departmentSearchService->execute($departmentInput);
        $departments = $this->departmentSearchDtoBuilder->build($departmentOutput);

        // 検索フォームの役職選択肢を、ID順ですべて取得する。
        $positionInput = new PositionSearchInput(
            orderBy: PositionOrderBy::Id,
            orderDirection: OrderDirection::Asc,
        );
        $positionOutput = $this->positionSearchService->execute($positionInput);
        $positions = $this->positionSearchDtoBuilder->build($positionOutput);

        // Bladeが必要とする表示用データだけをViewへ渡す。
        return view('employees.index', [
            'employees' => $employees,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    /**
     * htmxからの検索要求へ、差し替え対象のHTMLだけを返す。
     */
    public function results(IndexRequest $request): Response
    {
        // htmxでも通常表示と同じ検索処理を再利用する。
        $validated = $request->validated();
        $employees = $this->searchEmployees($validated);
        $employeeIndexUrl = $this->buildEmployeeIndexUrl($validated);

        // 結果部分だけを返し、ブラウザのURLは検索条件を含む正規URLへ更新する。
        return response()
            ->view('employees.partials.search-response', [
                'employees' => $employees,
            ])
            ->header('HX-Replace-Url', $employeeIndexUrl);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return list<EmployeeSearchDto>
     */
    private function searchEmployees(array $validated): array
    {
        // HTTPの配列をApplication入力へ変換してから検索Serviceを実行する。
        $input = $this->searchInputBuilder->build($validated);
        $output = $this->searchService->execute($input);

        return $this->searchDtoBuilder->build($output);
    }

    /**
     * 空でない検索条件だけをクエリ文字列に含めた画面URLを作る。
     *
     * @param  array<string, mixed>  $validated
     */
    private function buildEmployeeIndexUrl(array $validated): string
    {
        // nullと空文字は未指定条件なので、URLから除外する。
        $query = array_filter(
            $validated,
            static fn (mixed $value): bool => $value !== null && $value !== '',
        );
        $employeeIndexUrl = route('employees.index', absolute: false);

        // 条件がなければ不要な「?」を付けず、一覧画面のパスだけを返す。
        if ($query === []) {
            return $employeeIndexUrl;
        }

        return $employeeIndexUrl.'?'.http_build_query($query);
    }
}
