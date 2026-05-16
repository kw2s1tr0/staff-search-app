# 役割リファレンス

中・大タスク、subagent を使う場合、またはレビュー/テスト責務を明確に分ける必要がある場合に読む。

## アーキテクト

- ユーザー依頼を外部から観測できる成功条件に変換する。
- 影響する routes、controllers、requests、models、migrations、views、assets、config、tests、公開インターフェースを特定する。
- タスク規模を小・中・大から選ぶ。
- DB、auth、config、外部 I/O、queue、event、frontend build への影響を判断する。
- 大タスクでは、実装をレビューとテスト可能な段階に分割する。

## 実装者

- アーキテクトが決めた範囲と方針に従う。
- 新しい抽象化より Laravel 標準と既存パターンを優先する。
- 編集は最小限かつまとまりのある単位に保つ。
- 命名、validation、relationship、policy、route、view のパターンを変える前に近接コードを読み直す。
- 実装が計画や既存コードと矛盾したら、推測で進めず事実確認に戻る。

## レビュアー

- style より defect を優先する。
- 振る舞いの回帰、セキュリティ、認可、validation、DB 整合性、migration の安全性、config/env 影響、frontend build 影響を確認する。
- 無関係な編集、過剰な refactor、重複ロジック、根拠の弱い抽象化を指摘する。
- 報告では、可能な限りファイル/行に紐づけて findings を出す。

## テスター

- response status、表示、redirect、validation error、DB 状態、event/job、file、外部 I/O 境界など、外部から観測できる結果を検証する。
- request や UI flow は Feature test を優先する。
- Unit test は独立した domain logic に限定する。
- タスク規模と触った subsystem に応じて検証コマンドを選ぶ。
- 実行しなかったチェックと残リスクを明確に報告する。

## Subagent 利用

- subagent は active instructions が delegation を許可している場合だけ使う。
- この skill では `.codex/agents/architect.toml`、`.codex/agents/implementer.toml`、`.codex/agents/reviewer.toml`、`.codex/agents/tester.toml` を標準定義とし、対応する agent type は `architect`、`implementer`、`reviewer`、`tester` とする。
- 各 subagent には、1つの明確な役割と具体的な出力を渡す。
- code-edit subagent には、担当ファイルまたは担当モジュールを明示し、他者の変更を戻さないよう指示する。
- review/test subagent には、隠れた結論、期待回答、疑っている root cause、望ましい修正案を渡さない。
- 渡す情報は、関連ファイル、diff、log、失敗した command、ユーザー依頼などの生データを基本にする。
- 最終判断と統合責任はメイン agent が持つ。
