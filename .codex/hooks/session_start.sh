#!/usr/bin/env bash
set -euo pipefail

cat <<'EOF'
Repository guidance:
- Read AGENTS.md before changing code.
- Use docs/ai/context for human-provided AI context.
- Use docs/ai/instruction for derived AI instructions.
- Use docs/ai/note for AI-authored human-facing notes.
- Use docs/ai/workbench for long-running plans, context notes, and work logs.
EOF
