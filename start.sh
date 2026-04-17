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

# ── Start ─────────────────────────────────────────────────────
echo "  Starting marketplace-frontend on port ${PORT:-3000}..."
echo ""
echo "============================================================"
echo "  ✔  marketplace-frontend → http://0.0.0.0:${PORT:-3000}"
echo "============================================================"
echo ""

# Nuxt 3 production server — build output produced by Railpack
# Respects PORT and NITRO_PORT environment variables
cd "$ROOT_DIR/marketplace-frontend"
PORT="${PORT:-3000}" node .output/server/index.mjs
