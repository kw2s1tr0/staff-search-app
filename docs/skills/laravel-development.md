# Laravel Development

## 作業ディレクトリ
このリポジトリでは Laravel アプリ本体が `my-app/` 配下にあるため、Laravel 関連コマンドは原則として `my-app/` で実行する。

例:

```bash
cd my-app
php artisan test
```

## 変更前の確認
- `AGENTS.md` と関連する `docs/` 文書を確認する。
- `git status` で既存差分を確認する。
- 変更対象に関連する routes、controllers、models、views、config、tests を確認する。
- `composer.json` と `package.json` で利用可能なスクリプトを確認する。

## 検証コマンド候補
実際に使うコマンドは、変更内容と利用可能なスクリプトに応じて選ぶ。

- `composer test`
- `php artisan test`
- `npm run build`

## 実装時の注意
- 既存の Laravel 標準構成と命名に寄せる。
- ルーティング、バリデーション、認可、セッション、設定値への影響を確認する。
- Blade やフロントエンドを変更する場合は、参照先やビルド対象との整合を確認する。
- 抽象化や共通化は、必要性が明確な場合に限る。
