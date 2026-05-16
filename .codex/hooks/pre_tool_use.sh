#!/usr/bin/env bash
set -euo pipefail

python3 -c '
import json
import re
import sys

payload = sys.stdin.read()
try:
    event = json.loads(payload) if payload.strip() else {}
except json.JSONDecodeError:
    sys.exit(0)

tool_input = event.get("tool_input") or event.get("input") or {}
command = ""
if isinstance(tool_input, dict):
    command = tool_input.get("cmd") or tool_input.get("command") or ""
elif isinstance(tool_input, str):
    command = tool_input

command = command.strip()
if not command:
    sys.exit(0)

blocked_patterns = [
    r"\brm\s+-rf\b",
    r"\bgit\s+reset\b",
    r"\bgit\s+clean\b",
    r"\bgit\s+checkout\s+--\b",
    r"\bmkfs\b",
    r"\bdd\s+if=",
]

for pattern in blocked_patterns:
    if re.search(pattern, command):
        print(f"Blocked by project PreToolUse policy: {command}", file=sys.stderr)
        sys.exit(2)

review_patterns = [
    r"\bcomposer\s+install\b",
    r"\bnpm\s+install\b",
    r"\bdocker\s+compose\s+build\b",
    r"\bphp\s+artisan\s+migrate\b",
    r"\bphp\s+artisan\s+db:",
]

for pattern in review_patterns:
    if re.search(pattern, command):
        print(f"Project policy: confirm this command before running: {command}", file=sys.stderr)
        sys.exit(0)

sys.exit(0)
'
