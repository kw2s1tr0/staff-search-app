<div id="employee-search-results" class="overflow-hidden rounded-md border border-slate-200 bg-white">
    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 sm:px-5">
        <h2 class="text-sm font-semibold text-slate-900">検索結果</h2>
        <p class="text-xs text-slate-500">表示件数 {{ count($employees) }} 件</p>
    </div>

    @if (count($employees) === 0)
        <div class="px-6 py-14 text-center">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="mx-auto size-7 text-slate-400" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke-width="1.5"></circle>
                <path d="m20 20-4-4" stroke-width="1.5" stroke-linecap="round"></path>
            </svg>
            <h3 class="mt-3 text-sm font-semibold text-slate-900">該当する社員が見つかりませんでした</h3>
            <p class="mt-1 text-sm text-slate-500">検索条件を変更して、もう一度お試しください。</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold text-slate-600 sm:px-5">
                            氏名
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold text-slate-600">
                            社員番号
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold text-slate-600">
                            メールアドレス
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold text-slate-600">
                            部署
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold text-slate-600">
                            役職
                        </th>
                        <th scope="col" class="whitespace-nowrap px-4 py-2.5 text-left text-xs font-semibold text-slate-600 sm:pr-5">
                            在籍状態
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach ($employees as $employee)
                        @php
                            [$statusLabel, $statusClasses] = match ($employee->employmentStatus) {
                                \App\Enums\EmploymentStatus::Active => ['在籍', 'bg-green-50 text-green-700 ring-green-600/20'],
                                \App\Enums\EmploymentStatus::Leave => ['休職', 'bg-yellow-50 text-yellow-700 ring-yellow-600/20'],
                                \App\Enums\EmploymentStatus::Retired => ['退職', 'bg-slate-100 text-slate-600 ring-slate-500/20'],
                            };
                        @endphp
                        <tr class="hover:bg-blue-50/50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-slate-950 sm:px-5">
                                {{ $employee->familyName }} {{ $employee->givenName }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-600">
                                {{ $employee->employeeNumber }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">
                                <a href="mailto:{{ $employee->email }}" class="text-blue-600 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                                    {{ $employee->email }}
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">
                                {{ $employee->departmentName }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">
                                {{ $employee->positionName }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm sm:pr-5">
                                <span class="inline-flex items-center rounded-sm px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
