---
name: laravel-implementation-workflow
description: "このリポジトリの Laravel 実装ワークフロー。/ssapp/app で Laravel、PHP、Blade、Vite の実装、修正、リファクタ、テスト追加、QA を行うときに使う。タスク規模の判定、アーキテクト・実装者・レビュアー・テスターの役割確認、検証コマンド選択を標準化する。"
---

# Laravel 実装ワークフロー

この skill は、Laravel の実装依頼を、調査・設計・実装・レビュー・検証まで一貫した作業に落とし込むために使う。詳細は必要になった時だけ参照ファイルを読む。

## 開始

1. 作業起点は `/ssapp` とし、Laravel コマンドは原則 `/ssapp/app` で実行する。Docker や repo ルート固有のコマンドだけ例外にする。
2. `AGENTS.md` を確認し、`rg` / `rg --files` で関連ファイルと既存パターンを読む。
3. 編集前にタスクを小・中・大のいずれかに分類する。判断に迷う場合は `references/task-sizing.md` を読む。
4. `.codex/agents/architect.toml`、`.codex/agents/implementer.toml`、`.codex/agents/reviewer.toml`、`.codex/agents/tester.toml` を、この skill の標準 subagent 定義として扱う。
5. active instructions が subagent 利用を許可している場合、この skill を指定した Laravel 実装依頼では、タスク規模と並列化できる作業に応じて上記 subagent を使う。許可がない場合だけ、メイン agent がアーキテクト、実装者、レビュアー、テスターの役割を順番に実行する。

## 役割パス

中・大タスク、subagent を使う場合、または責務を明確に分ける必要がある場合は `references/roles.md` を読む。

## Subagent 標準運用

この skill で subagent を使える場合は、`.codex/agents` 以下の定義に対応する agent type を指定する。

- `architect`: 読み取り専用。実装前に成功条件、影響範囲、設計方針、検証方針を整理する。中・大タスクでは原則使う。
- `implementer`: workspace-write。アーキテクト方針に沿って、明確に分離できる実装範囲だけを担当する。メイン agent の直近作業をブロックしない範囲に限る。
- `reviewer`: 読み取り専用。実装差分を defect 優先で確認する。中・大タスク、auth/DB/config/shared 処理、またはリスクが高い変更では原則使う。
- `tester`: workspace-write。検証方針、テスト追加、コマンド実行結果、残リスクを整理する。大タスク、テスト追加が必要な中タスク、または QA 判断が必要な変更では原則使う。

subagent を起動するときは、具体的な担当範囲、入力情報、期待する出力、編集してよいファイル範囲を明示する。複数の subagent を使う場合は、書き込み範囲が重ならないようにする。最終判断、差分統合、ユーザーへの報告責任はメイン agent が持つ。

## 規模判定

小・中・大の判断に迷う場合、または複数レイヤー、DB、auth、外部 I/O、共有処理に触る場合は `references/task-sizing.md` を読む。

## Laravel 確認

DB/auth/config/frontend に触る前、またはテスト配置・検証コマンド選択で迷う場合は `references/laravel-checks.md` を読む。

## 最終報告

必要な内容だけを短く報告する。

- 変更概要
- 実行したテスト/チェックと結果
- 未実行のチェックと理由
- 残リスクまたは follow-up
