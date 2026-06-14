<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>社員検索</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white text-slate-900 antialiased">
        <header class="bg-slate-950 text-white">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="flex size-8 items-center justify-center rounded-md border border-white/15 bg-white/10">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-4" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke-width="1.5" stroke-linecap="round"></path>
                            <circle cx="9" cy="7" r="4" stroke-width="1.5"></circle>
                            <path d="M19 8v6m3-3h-6" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </span>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="font-semibold">社員情報管理</span>
                        <span class="text-slate-500">/</span>
                        <span class="text-slate-300">社員</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden text-xs text-slate-300 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex h-8 items-center justify-center rounded-md border border-white/20 bg-white/10 px-3 text-xs font-semibold text-white hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-white/40"
                        >
                            ログアウト
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8 lg:py-9">
            <div class="mb-6 border-b border-slate-200 pb-5 sm:flex sm:items-end sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-md border border-slate-200 bg-slate-50 text-slate-500">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-5" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke-width="1.5" stroke-linecap="round"></path>
                            <circle cx="9" cy="7" r="4" stroke-width="1.5"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </span>
                    <div>
                        <h1 class="text-xl font-semibold tracking-tight text-slate-950">社員検索</h1>
                        <p class="mt-1 text-sm leading-6 text-slate-600">
                            氏名や社員番号、所属情報から社員を検索できます。
                        </p>
                    </div>
                </div>
                <p id="employee-count-summary" class="mt-4 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs text-slate-600 sm:mt-0">
                    <span class="mr-1 font-semibold tabular-nums text-slate-950">{{ count($employees) }}</span> 件
                </p>
            </div>

            <section>
                <div class="mb-4 rounded-md border border-slate-200 bg-slate-50 p-4">
                    <form
                        id="employee-search-form"
                        method="GET"
                        action="{{ route('employees.index') }}"
                        hx-get="{{ route('employees.results') }}"
                        hx-target="#employee-search-results"
                        hx-swap="outerHTML"
                    >
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-[minmax(18rem,2fr)_minmax(10rem,1fr)_minmax(10rem,1fr)_minmax(10rem,1fr)_auto] xl:items-end">
                            <div class="md:col-span-2 xl:col-span-1">
                                <label for="keyword" class="block text-xs font-semibold text-slate-700">
                                    フリーワード
                                </label>
                                <div class="relative mt-1.5">
                                    <svg
                                        class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400"
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        stroke="currentColor"
                                        aria-hidden="true"
                                    >
                                        <circle cx="8.5" cy="8.5" r="5.5" stroke-width="1.5"></circle>
                                        <path d="m12.5 12.5 4 4" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                    <input
                                        id="keyword"
                                        name="keyword"
                                        type="search"
                                        value="{{ request('keyword') }}"
                                        placeholder="氏名・社員番号・メールなど"
                                        class="block h-9 w-full rounded-md border border-slate-300 bg-white py-1.5 pl-9 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    >
                                </div>
                                @error('keyword')
                                    <p class="mt-1.5 text-xs text-red-600">フリーワードを正しく入力してください。</p>
                                @enderror
                            </div>

                            <div>
                                <label for="department_id" class="block text-xs font-semibold text-slate-700">
                                    部署
                                </label>
                                <select
                                    id="department_id"
                                    name="department_id"
                                    class="mt-1.5 block h-9 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                >
                                    <option value="">すべての部署</option>
                                    @foreach ($departments as $department)
                                        <option
                                            value="{{ $department->id }}"
                                            @selected((string) request('department_id') === (string) $department->id)
                                        >
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="mt-1.5 text-xs text-red-600">部署を正しく選択してください。</p>
                                @enderror
                            </div>

                            <div>
                                <label for="position_id" class="block text-xs font-semibold text-slate-700">
                                    役職
                                </label>
                                <select
                                    id="position_id"
                                    name="position_id"
                                    class="mt-1.5 block h-9 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                >
                                    <option value="">すべての役職</option>
                                    @foreach ($positions as $position)
                                        <option
                                            value="{{ $position->id }}"
                                            @selected((string) request('position_id') === (string) $position->id)
                                        >
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('position_id')
                                    <p class="mt-1.5 text-xs text-red-600">役職を正しく選択してください。</p>
                                @enderror
                            </div>

                            <div>
                                <label for="employment_status" class="block text-xs font-semibold text-slate-700">
                                    在籍状態
                                </label>
                                <select
                                    id="employment_status"
                                    name="employment_status"
                                    class="mt-1.5 block h-9 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                >
                                    <option value="">すべての状態</option>
                                    <option value="active" @selected(request('employment_status') === 'active')>在籍</option>
                                    <option value="leave" @selected(request('employment_status') === 'leave')>休職</option>
                                    <option value="retired" @selected(request('employment_status') === 'retired')>退職</option>
                                </select>
                                @error('employment_status')
                                    <p class="mt-1.5 text-xs text-red-600">在籍状態を正しく選択してください。</p>
                                @enderror
                            </div>

                            <div class="flex gap-2 md:col-span-2 xl:col-span-1">
                                <button
                                    type="button"
                                    data-employee-search-clear
                                    class="inline-flex h-9 flex-1 items-center justify-center rounded-md border border-slate-300 bg-white px-3 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 xl:flex-none"
                                >
                                    クリア
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex h-9 flex-1 items-center justify-center rounded-md border border-blue-700 bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 xl:flex-none"
                                >
                                    検索
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @include('employees.partials.results', ['employees' => $employees])
            </section>
        </main>
    </body>
</html>
