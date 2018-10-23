#!/bin/bash
# Use this file to fix the permissions before the first start.
# This will assure that the user www (user id 33 on the container) has write access to the cache, log and lock files.
sudo chown -R 33 src/log.txt src/updater.lock src/cache