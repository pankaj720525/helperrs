#!/bin/bash

# ============================================================
#   DO-PRO Marketplace — Railway Entry Point
# ============================================================
#   Targets: marketplace-frontend (Nuxt 3, SSR)
#   Port:    ${PORT:-3000}
# ============================================================

set -e

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo ""
echo "============================================================"
echo "  DO-PRO Marketplace — Railway Deployment"
echo "============================================================"
echo ""

# ── Install dependencies ──────────────────────────────────────
echo "  [1/3] Installing dependencies (marketplace-frontend)..."
cd "$ROOT_DIR/marketplace-frontend"
npm install

# ── Build ─────────────────────────────────────────────────────
echo ""
echo "  [2/3] Building marketplace-frontend..."
npm run build

# ── Start ─────────────────────────────────────────────────────
echo ""
echo "  [3/3] Starting marketplace-frontend on port ${PORT:-3000}..."
echo ""
echo "============================================================"
echo "  ✔  marketplace-frontend → http://0.0.0.0:${PORT:-3000}"
echo "============================================================"
echo ""

# Nuxt 3 production server respects the PORT / NITRO_PORT env var
PORT="${PORT:-3000}" node .output/server/index.mjs
