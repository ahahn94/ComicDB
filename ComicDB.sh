#!/usr/bin/env bash
# Use this file if you want to start the server by cron.
COMICDB_PATH=foo # absolute path to your ComicDB-Installation.
COMPOSE_FILE=bar # name of your docker-compose file (armhf.yaml or amd64.yaml).
# If no option -> start server.
if [ $# -eq 0 ]; then
docker-compose -f $COMICDB_PATH/$COMPOSE_FILE up
else
if [ $1 = "start" ]; then
# If option is start -> start server.
    docker-compose -f $COMICDB_PATH/$COMPOSE_FILE up
    elif [ $1 = "stop" ]; then
    # If option is stop -> stop server.
    docker-compose -f $COMICDB_PATH/$COMPOSE_FILE stop
    elif [ $1 = "delete" ]; then
    # If option is delete -> stop server and delete containers.
    docker-compose -f $COMICDB_PATH/$COMPOSE_FILE down
fi
fi