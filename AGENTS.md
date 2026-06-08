# Repository Guidelines

## Project Structure & Module Organization

このリポジトリは、Laravel アプリケーションを `app/`、Docker 関連設定を `Docker/`、ドキュメントを `docs/` に配置しています。PHP の主要コードは `app/app/`、ルート定義は `app/routes/`、設定は `app/config/`、DB 関連は `app/database/` です。テストは `app/tests/Feature` と `app/tests/Unit`、フロントエンド入口は `app/resources/js` と `app/resources/css` です。

## Documentation Guidelines

`docs/` はプロジェクト資料の置き場です。`docs/human/` には人間向け資料を置きます。`docs/ai/context/` は人間が AI に与える背景資料や前提情報、`docs/ai/instruction/` は `AGENTS.md` から派生する AI 向け追加指示、`docs/ai/note/` は AI が人間向けに残す説明や報告資料の置き場です。`docs/ai/workbench/` は AI の作業場として、長いコンテキスト、実行計画、検討メモなどを記録します。

## Build, Test, and Development Commands

特記がない限り、コマンドは `app/` で実行します。

- `composer setup`: PHP/Node 依存関係のインストール、`.env` 作成、アプリキー生成、マイグレーション、アセットビルドを実行します。
- `composer dev`: Laravel サーバー、キューリスナー、ログ監視、Vite をまとめて起動します。
- `npm run dev`: Vite 開発サーバーのみを起動します。
- `npm run build`: 本番用フロントエンドアセットをビルドします。
- `composer test`: 設定キャッシュをクリアして PHPUnit テストを実行します。
- `composer qa`: フォーマット確認、PHPCS、PHPStan、テストをまとめて実行します。
- `cd Docker && docker compose up -d`: Apache/PHP と MySQL コンテナを起動します。

## Coding Style & Naming Conventions

Laravel の標準構成と `App\` 名前空間の PSR-4 オートロードに従います。PHP は 4 スペースインデントを使い、クラス名は StudlyCase、メソッド名と変数名は camelCase、テーブル名とカラム名は snake_case にします。Blade ビューは小文字のパス形式で命名してください。PHP 変更前後は `composer format` を実行し、確認のみの場合は `composer format:check` を使います。PHPCS は `app/phpcs.xml.dist`、静的解析は `app/phpstan.neon.dist` で設定されています。

## Testing Guidelines

テストは Laravel のテストランナー経由で PHPUnit を使用します。HTTP や画面遷移などの振る舞いは `tests/Feature`、独立したロジックは `tests/Unit` に配置します。テストファイル名は `*Test.php` で終えてください。実装詳細ではなく、レスポンスステータス、DB 状態、外部から観測できる振る舞いを検証します。通常は `composer test`、大きめの変更前後は `composer qa` を実行します。

## Commit & Pull Request Guidelines

最近のコミットは `docs更新`、`dockerfile作成`、`laravel初期構築` のように短く直接的な件名です。コミットタイトルは簡潔で作業内容が分かる表現にし、補足や移行手順が必要な場合だけ本文を追加します。プルリクエストには変更概要、テスト結果、関連 Issue、UI 変更がある場合はスクリーンショットを含めてください。

## Security & Configuration Tips

`.env`、シークレット、DB ダンプ、生成された依存ディレクトリはコミットしないでください。ローカルサービスの認証情報は、明示的なインフラ変更でない限り `Docker/docker-compose.yml` と合わせます。設定を変更する場合は、必要な環境変数を記録し、マイグレーションが再実行可能か確認してください。
