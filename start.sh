#!/bin/bash
set -e

# start all the services
/usr/local/bin/supervisord -n
