#!/usr/bin/env bash
# some common functions

# Usage:
# user_confirm
# user_confirm "custom question"
function user_confirm() {
    local yes=0
    local msg="Confirm continue"

    # override default message
    [[ "$1" != "" ]] && msg=$1

    # read user input
    read -p "$msg? [y/n]" yn

    if [[ ${yn} == [Yy]* ]]; then
        return 0
    fi

    return 1
}

# Usage:
# colored_text "message text"
# colored_text "message text" "red"
# colored_text "message text" "blue"
function colored_text() {
    NOC='\033[0m' # No Color
    RED='\033[0;31m'
    BLUE='\033[0;34m'
    CYAN='\033[0;36m'
    GREEN='\033[0;32m'
    YELLOW='\033[0;33m'

#    [[ "$2" = "" ]] && $2="green"

    if [[ "$2" = "red" ]]; then
        echo -e "${RED}$1${NOC}"
    elif [[ "$2" = "blue" ]]; then
        echo -e "${BLUE}$1${NOC}"
    elif [[ "$2" = "cyan" ]]; then
        echo -e "${CYAN}$1${NOC}"
    elif [[ "$2" = "yellow" ]]; then
        echo -e "${YELLOW}$1${NOC}"
    else # default green
        echo -e "${GREEN}$1${NOC}"
    fi
}

# quick use color print text
function red_text() {
    colored_text "$1" red
}

function blue_text() {
    colored_text "$1" blue
}

function cyan_text() {
    colored_text "$1" cyan
}

function green_text() {
    colored_text "$1" green
}

function yellow_text() {
    colored_text "$1" yellow
}
