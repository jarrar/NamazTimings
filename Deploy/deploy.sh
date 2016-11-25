#!/usr/local/bin/bash
# AUTHOR:
#set -o nounset
readonly SCRIPT=${0##*/}

declare -A PLAYBOOKS

function die() { echo $@; exit 1; }
function cleanup() { return 0;}

trap cleanup EXIT

function show_help()
{
    cat <<-HELP
    $SCRIPT [-d | -i | -h | -p]

    An ansible based script to deploy all relevant files to the public_html/prayer
    dirrectory.

    -d | --debug          prints details logs of ansible-playbook.
    -i | --inventory      inventory file for an organization to deploy to.
                          it is a required field and is relative to deploy dir.
                          -i iabat.inv
                           means deploy/iabat.inv must exists.

    -p | --play-book      A playbook file:
                            -) to deploy files.
                          the play book must exists in deploy dir
                          -p deploy-org-site.yml
                           means deploy/deploy-org-site.yml must exists.

    -h | --help           shows this help.
HELP
}

function top_dir()
{
    TOPDIR=$(git rev-parse --show-toplevel)
}

function getopt_path()
{
   local os=$(uname)
   if [[ $os == "Darwin" ]]
   then
        GETOPT=/usr/local/Cellar/gnu-getopt/1.1.6/bin/getopt
   else
        GETOPT=$(which getopt)
   fi
}

function playbooks_init()
{
    PLAYBOOKS["deploy-org-site.yml"]="yes"
}

function init()
{
   getopt_path
   playbooks_init
   top_dir
}

function is_playbook_valid()
{
    [[ ${PLAYBOOKS[${1##*/}]} == "yes" ]]
}

#
# main
#
init

DEBUG=""
INVENTORY=""
PLAYBOOK="deploy-org-site.yml"


TEMP=$($GETOPT -o di:p:h --long debug,inventory:,playbook:,help -n $SCRIPT -- "$@")
eval set -- "$TEMP"

# extract options and their arguments into variables.
while true ; do
    case "$1" in
        -d|--debug)         DEBUG="-vvvv" ; shift 1 ;;
        -i|--inventory)     INVENTORY=$2  ; shift 2 ;;
        -p|--playbook)      PLAYBOOK=$2   ; shift 2 ;;
        -h|--help)          show_help     ; exit  0 ;;
        --)                 shift         ; break   ;;
        *)         echo "Internal error!" ; exit 1  ;;
    esac
done

[[ -z $INVENTORY ]] && die "Missing inventory file"
#is_playbook_valid "$PLAYBOOK" || die "Invalid playbook: $PLAYBOOK"

if [[ $PLAYBOOK == "deploy-codeigniter.yml" ]]
then
    COPYFILE_DISABLE=1 tar -czvf web.tar.gz web
fi

time ansible-playbook $DEBUG -i $TOPDIR/deploy/$INVENTORY $TOPDIR/deploy/$PLAYBOOK
