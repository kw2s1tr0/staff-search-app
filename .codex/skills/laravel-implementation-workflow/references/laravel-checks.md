# Laravel 確認リファレンス

DB/auth/config/frontend に触る前、または検証コマンドを選ぶときに読む。

## リポジトリ既定

- Laravel app: `/ssapp/app`
- Docker config: `/ssapp/Docker`
- human/AI docs: `/ssapp/docs`
- PHP code: `app/app`
- routes: `app/routes`
- config: `app/config`
- database files: `app/database`
- tests: `app/tests/Feature` と `app/tests/Unit`
- frontend entrypoints: `app/resources/js` と `app/resources/css`

## コーディング規約

- `App\` namespace の PSR-4 に従う。
- PHP は 4 spaces indentation を使う。
- class は StudlyCase、method/variable は camelCase、table/column は snake_case にする。
- Blade view は lowercase path-style name にする。
- 新しいパターンより、Laravel 標準と既存 helper を優先する。
- `.env`、secret、DB dump、生成済み依存ディレクトリは作成・編集しない。

## テスト配置

- HTTP response、redirect、validation error、auth/authorization、rendered view、DB state、boundary で観測できる job/event、screen flow は Feature test を使う。
- request、database、Laravel integration surface を必要としない独立ロジックは Unit test を使う。
- status code、response content、database assertion、emit された job/event、file、外部から見える結果を検証する。

## コマンド

特記がない限り `app/` で実行する。

- `composer format`: PHP 変更を整形する。
- `composer format:check`: 書き換えずに formatting を確認する。
- `composer test`: config cache を clear して PHPUnit を実行する。
- `composer qa`: format check、PHPCS、PHPStan、tests を実行する。
- `npm run build`: 本番 frontend assets を build する。
- `npm run dev`: Vite のみ起動する。
- `composer dev`: Laravel server、queue listener、log monitor、Vite をまとめて起動する。
- `cd Docker && docker compose up -d`: Apache/PHP と MySQL containers を起動する。

## 検証既定

- 小さな PHP 変更: 関連テストと `composer format:check`。
- 中規模 application 変更: `composer test`。共有処理や静的解析リスクがある場合は `composer qa` を追加する。
- 大規模、共有処理、DB、auth、config 変更: `composer qa`。
- frontend/Vite 変更: `npm run build`。
- 自動化されていない UI 挙動: 画面、操作、期待結果を明記した手動確認メモを残す。
