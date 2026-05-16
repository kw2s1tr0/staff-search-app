# タスク規模リファレンス

影響範囲を安全に扱える最小の規模を選ぶ。レイヤーをまたぐ、データ形状を変える、共有処理に触る場合は規模を引き上げる。

## 小

典型例:

- 文言、copy、局所的な Blade/CSS 変更。
- 単一メソッドや狭いバグ修正。
- schema、auth、共有処理に影響しない単一 route/controller/model 修正。
- 既に理解済みの振る舞いに対する test-only 変更。

標準対応:

- メイン agent が全役割を実行する。
- 直接関係するファイルと近接テストだけを確認する。
- 新しい抽象化は避ける。
- PHP 変更では関連テストと `composer format:check` を実行する。
- frontend assets や Vite 出力に影響する場合だけ `npm run build` を実行する。

## 中

典型例:

- 複数ファイルまたは Laravel の複数レイヤーにまたがる変更。
- フォーム、validation、request、policy、model relationship、DB 読み書き、job、event、notification、API response、画面遷移。
- Feature/Unit test の新規追加または意味のある更新。

標準対応:

- 編集前に短い実装分解を書く。
- route/controller/request/model/view/test の流れを end-to-end で確認する。
- 必要に応じて validation、authorization、not-found、duplicate、empty state などの失敗系を含める。
- `composer test` を実行し、frontend assets に触った場合は `npm run build` を追加する。
- 共有領域に触る、または局所リスクと言い切れない場合は `composer qa` を優先する。

## 大

典型例:

- migration や schema 設計変更。
- 認証、認可、権限、アカウント境界。
- 外部サービス、file storage、queue、scheduled job、integration。
- 複数画面/機能、公開 API contract 変更、互換性懸念、共有アーキテクチャ。

標準対応:

- 実装前に段階計画を作る。
- in-scope と out-of-scope を明確にする。
- migration 再実行可能性、既存データ影響、config/env 変更、rollback 観点、互換性を明記する。
- 可能な単位ごとに review と test を挟む。
- `composer qa` を実行し、frontend 変更では `npm run build`、自動化できない箇所は手動確認を追加する。

## 引き上げ条件

次に触る場合は、少なくとも1段階規模を引き上げる。

- auth、authorization、security、payments、personal data、tenant/account isolation。
- migration、破壊的 data change、不可逆操作。
- shared helper、base class、middleware、service provider、config。
- 既存テストがない振る舞い。
- 誤った仮定がユーザー visible な挙動を作る曖昧な要件。
