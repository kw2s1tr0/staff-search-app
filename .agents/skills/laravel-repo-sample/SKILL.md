---
name: laravel-repo-sample
description: このリポジトリで Laravel 開発を支援するスキルです。`my-app/` 配下の routes、controllers、models、Blade views、config、tests など Laravel 関連の変更を行うときに使います。ローカルの文書参照順、確認観点、検証候補に従って作業してください。
---

# Laravel Repo Sample

このリポジトリで Laravel 関連の変更を行うときに使う。

## はじめに行うこと
- 変更前に `AGENTS.md`、`docs/instructions/ai-working-rules.md`、`docs/shared/project-context.md` を確認する。
- Laravel 関連コマンドは `my-app/` を作業ディレクトリにして実行する。
- 編集前に `git status` を確認し、既存差分を把握する。
- 新しい流儀を持ち込む前に、既存の Laravel 構成と命名を優先して踏襲する。

## 実装時の確認
- 関連する `routes/`、`app/`、`resources/views/`、`config/`、`tests/` を先に読む。
- 変更は必要最小限にし、無関係なリファクタリングを混ぜない。
- ルーティング、バリデーション、認可、セッション、テンプレート参照への影響を確認する。
- リポジトリ固有の前提を追加で確認したいときだけ `references/repo-context.md` を読む。

## 検証候補
変更内容に応じて必要なコマンドだけ選ぶ。

- 品質系コマンドも `my-app/` を作業ディレクトリにして実行する。
- 整形は `Pint` を主に使い、`phpcs/phpcbf` は規約確認と補助修正、`Larastan` は Laravel 向け静的解析に使う。
- `composer format`
- `composer format:check`
- `composer lint`
- `composer lint:fix`
- `composer analyse`
- `composer qa`
- `composer test`
- `php artisan test`
- `npm run build`
