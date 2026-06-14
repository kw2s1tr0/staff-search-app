@include('employees.partials.results', ['employees' => $employees])

<p
    id="employee-count-summary"
    class="mt-4 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs text-slate-600 sm:mt-0"
    hx-swap-oob="outerHTML"
>
    <span class="mr-1 font-semibold tabular-nums text-slate-950">{{ count($employees) }}</span> 件
</p>
