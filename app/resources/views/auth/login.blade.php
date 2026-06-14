<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ログイン | 社員情報管理</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <header class="bg-slate-950 text-white">
            <div class="mx-auto flex h-16 max-w-7xl items-center px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <span class="flex size-8 items-center justify-center rounded-md border border-white/15 bg-white/10">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-4" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" stroke-width="1.5" stroke-linecap="round"></path>
                            <circle cx="9" cy="7" r="4" stroke-width="1.5"></circle>
                            <path d="M19 8v6m3-3h-6" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold">社員情報管理</span>
                </div>
            </div>
        </header>

        <main class="flex min-h-[calc(100vh-4rem)] items-start justify-center px-4 py-12 sm:items-center sm:py-16">
            <section class="w-full max-w-md">
                <div class="mb-6 text-center">
                    <span class="mx-auto flex size-11 items-center justify-center rounded-md border border-slate-200 bg-white text-slate-500 shadow-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="size-5" aria-hidden="true">
                            <rect x="5" y="10" width="14" height="10" rx="2" stroke-width="1.5"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </span>
                    <h1 class="mt-4 text-xl font-semibold tracking-tight text-slate-950">ログイン</h1>
                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        社員情報管理を利用するにはログインしてください。
                    </p>
                </div>

                <div class="rounded-md border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700">
                                メールアドレス
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus
                                class="mt-1.5 block h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                placeholder="name@example.com"
                            >
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700">
                                パスワード
                            </label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="mt-1.5 block h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm hover:border-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                            >
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input
                                name="remember"
                                type="checkbox"
                                value="1"
                                @checked(old('remember'))
                                class="size-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            >
                            ログイン状態を保持する
                        </label>

                        <button
                            type="submit"
                            class="inline-flex h-10 w-full items-center justify-center rounded-md border border-blue-700 bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            ログイン
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
