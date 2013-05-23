#!/bin/bash

# Params $1 [background, secondJob]

    nohup node messenger.js > ../logs/messenger.node.$DATE.log > ../logs/messenger.node.$DATE.err &
    nohup node call.js > ../logs/call.node.$DATE.log > ../logs/call.node.$DATE.err &
    nohup node basicchat.js > ../logs/basicchat.node.$DATE.log > ../logs/basichat.node.$DATE.err &