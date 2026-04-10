# Project Context

## 概要
このリポジトリは Laravel アプリケーションを含む開発環境です。アプリ本体は `my-app/` 配下にあります。

## 主要ディレクトリ
- `my-app/`: Laravel アプリ本体。
- `Docker/`: Docker 開発環境の設定。
- `docs/`: AI と人間が参照する補助文書。

## 現在確認できている構成
- Laravel アプリ本体: `my-app/`
- PHP 依存関係: `my-app/composer.json`
- Node 依存関係: `my-app/package.json`
- Docker Compose: `Docker/docker-compose.yml`
- Apache DocumentRoot: `/ssapp/my-app/public`
- 現在の Web ルート: `GET /` が `welcome` view を返す初期構成

## Docker サービス
`Docker/docker-compose.yml` では、以下のサービスが定義されています。

- `php`: Apache/PHP 実行環境。
- `mysql`: MySQL 実行環境。
- `node`: Vite などフロントエンド開発用の Node 実行環境。

## 作業時の前提
- Laravel 関連コマンドは、原則として `my-app/` を作業ディレクトリにして実行する。
- ルート直下に `README.md` がない場合は、`my-app/README.md` を確認する。
- `docs/human_docs/` は人間向け正式文書用であり、AIの途中メモには使わない。
