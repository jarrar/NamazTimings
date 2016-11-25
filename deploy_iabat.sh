#!/bin/bash
# Copyright (c) 1999 Cisco Systems, Inc.  All rights reserved.
# AUTHOR:  Jarrar Jaffari (), jjaffari@cisco.com

DIR=${0%/*}

$DIR/Deploy/deploy.sh -i iabat.inv -p deploy-org-site.yml
