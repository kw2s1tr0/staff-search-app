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

- `php`: Apache/PHP 実行環境。`Docker/php/Dockerfile` を見ると、`curl`、`unzip`、`git`、`default-mysql-client`、`composer` が利用可能で、Apache の `rewrite` モジュールが有効です。PHP 拡張として `pdo`、`pdo_mysql`、`zip`、`intl`、`xdebug` が入っています。
- `mysql`: MySQL 実行環境。
- `node`: Vite などフロントエンド開発用の Node 実行環境。

## コマンド実行時の前提
- Node は `php` とは別コンテナです。通常の開発作業やコマンド実行は、まず `php` サービスを基準に考えます。
- PHP、Laravel、Composer、Git、DB確認系のコマンドは、まず `php` サービス基準で考える。
- `php` サービスで利用できる前提を置きやすい主なコマンドは、`php`、`composer`、`git`、`curl`、`unzip`、`mysql` です。
- `php` サービスでは、`npm` や `node` は利用できる前提にしないでください。フロントエンド関連コマンドは `node` サービスで実行する前提で扱います。
- PHP 実行環境には `pdo`、`pdo_mysql`、`zip`、`intl`、`xdebug` が入っている前提で確認を進めてよい。
- Apache の `mod_rewrite` は有効です。
- Laravel 関連コマンドは、原則として `my-app/` を作業ディレクトリにして実行する。
- `npm`、`node`、Vite などのフロントエンド関連コマンドは `node` サービス前提で考える。`php` サービスから実行できる前提では扱わない。必要なコマンドの有無は対応する Dockerfile を確認する。
- ルート直下に `README.md` がない場合は、`my-app/README.md` を確認する。
- `docs/human_docs/` は人間向け正式文書用であり、AIの途中メモには使わない。
