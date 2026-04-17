#!/bin/bash
set -e
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR/marketplace-frontend"
PORT="${PORT:-3000}" node .output/server/index.mjs
