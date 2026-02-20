#!/bin/bash

# Define full paths to both projects
PROJECT_ROOT="C:/laragon/www"
PROJECT_2EARN="$PROJECT_ROOT/core"
PROJECT_AUTH="$PROJECT_ROOT/auth"

# Run both Vite servers in parallel
(cd "$PROJECT_2EARN" && npm run dev) &
(cd "$PROJECT_AUTH" && npm run dev) &
wait
