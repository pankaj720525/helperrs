#!/bin/bash

# ============================================================
#   DO-PRO Marketplace — Start / Stop Toggle
# ============================================================
#   Run once  →  STARTS  all projects
#   Run again →  STOPS   all projects
# ============================================================
#   marketplace-frontend  →  http://localhost:3000  (Nuxt 3)
#   marketplace-admin     →  http://localhost:3001  (Nuxt 3)
#   marketplace-api       →  http://localhost:8000  (Laravel)
# ============================================================

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PID_FILE="$ROOT_DIR/.marketplace.pids"

# ── STOP ─────────────────────────────────────────────────────
stop_all() {
  if [ ! -f "$PID_FILE" ]; then
    echo ""
    echo "  ⚠  No running projects found."
    echo ""
    exit 0
  fi

  echo ""
  echo "============================================================"
  echo "  Stopping DO-PRO Marketplace Projects..."
  echo "============================================================"

  while IFS='=' read -r name pid; do
    if kill -0 "$pid" 2>/dev/null; then
      kill "$pid" 2>/dev/null
      echo "  ✔  Stopped $name  (PID $pid)"
    else
      echo "  –  $name was not running  (PID $pid)"
    fi
  done < "$PID_FILE"

  rm -f "$PID_FILE"

  echo ""
  echo "  All projects stopped. Goodbye!"
  echo "============================================================"
  echo ""
  exit 0
}

# ── If PID file exists → STOP ─────────────────────────────────
if [ -f "$PID_FILE" ]; then
  stop_all
fi

# ── START ─────────────────────────────────────────────────────
echo ""
echo "============================================================"
echo "  Starting DO-PRO Marketplace Projects..."
echo "============================================================"
echo ""

# 1. marketplace-api (Laravel)
echo "  [1/3] marketplace-api       →  http://localhost:8000"
cd "$ROOT_DIR/marketplace-api"
php artisan serve --port=8000 > "$ROOT_DIR/marketplace-api.log" 2>&1 &
API_PID=$!
echo "api=$API_PID" >> "$PID_FILE"
echo "        PID: $API_PID  |  Log: marketplace-api.log"

# 2. marketplace-frontend (Nuxt 3)
echo "  [2/3] marketplace-frontend  →  http://localhost:3000"
cd "$ROOT_DIR/marketplace-frontend"
npm run dev > "$ROOT_DIR/marketplace-frontend.log" 2>&1 &
FRONTEND_PID=$!
echo "frontend=$FRONTEND_PID" >> "$PID_FILE"
echo "        PID: $FRONTEND_PID  |  Log: marketplace-frontend.log"

# 3. marketplace-admin (Nuxt 3)
echo "  [3/3] marketplace-admin     →  http://localhost:3001"
cd "$ROOT_DIR/marketplace-admin"
npm run dev > "$ROOT_DIR/marketplace-admin.log" 2>&1 &
ADMIN_PID=$!
echo "admin=$ADMIN_PID" >> "$PID_FILE"
echo "        PID: $ADMIN_PID  |  Log: marketplace-admin.log"

echo ""
echo "============================================================"
echo "  ✔  All projects are running!"
echo ""
echo "     Frontend  →  http://localhost:3000"
echo "     Admin     →  http://localhost:3001"
echo "     API       →  http://localhost:8000"
echo ""
echo "  Run this script again to STOP all projects."
echo "============================================================"
echo ""
