#!/usr/bin/env bash
#
#

#set -e

# import common functions
source "$(dirname $0)/common-func.sh"

function show_help() {
    binName="bash $(basename $0)"

    cat <<EOF
Release all sub-repo to new tag version and push to remote repo

Usage:
    ${binName} [-t VERSION] [NAME ...]

Options:
  -a                Release all components
  -t <version>      Specifies the version number to be published,
                    without specifying the automatic calculation of the next version.
                     eg: v2.0.4
  -y                No confirmation required
  -h, --help        Display the help information

Example:
   ${binName} -a                        Release all components, will auto calc next version
   ${binName} -t v2.0.4 -a              Release all components, use user input version
   ${binName} -t v2.0.4 event           Release one component
   ${binName} -t v2.0.4 event stdlib    Release multi components

EOF
    exit 0
}

# 显示帮助
[[ "$1" = "" || "$1" = "-h" || "$1" = "--help" ]] && show_help

RELEASE_TAG=AUTO
NEED_CONFIRM=Y

# parse input options
# ref https://www.cnblogs.com/yxzfscg/p/5338775.html
while getopts "t:ahy" arg; do #选项后面的冒号表示该选项需要参数
    case ${arg} in
        a)
            COMPONENTS=$(ls src/) ;;
        h)
            show_help ;;
        y)
            NEED_CONFIRM=N ;;
        t)
            RELEASE_TAG=$OPTARG ;;
        ?)  #当有不认识的选项的时候arg为?
            echo "Missing argument"
            exit 1
        ;;
    esac
done

shift $(($OPTIND - 1))

TARGET_BRANCH=master
CURRENT_BRANCH=`git rev-parse --abbrev-ref HEAD`
#CURRENT_BRANCH=master

[[ -n "$@" ]] && COMPONENTS=$@

if [[ -z "${COMPONENTS}" ]]; then
    colored_text "Please input want released component names or use option: -a" red
    exit 1
fi

echo "Will released version: ${RELEASE_TAG}"
echo "Will released projects:"
echo " " ${COMPONENTS}
#colored_text "${COMPONENTS}"

if [[ "$NEED_CONFIRM" = "Y" ]]; then
    if ! user_confirm "Continue"; then
        colored_text "Good Bye"
        exit 0
    fi
fi

TMP_DIR="/tmp/release-components-git"

yellow_text "> rm -rf ${TMP_DIR} && mkdir ${TMP_DIR}"
rm -rf ${TMP_DIR} && mkdir ${TMP_DIR};
#pwd
yellow_text "> cp -R $(pwd)/. ${TMP_DIR}"
cp -R $(pwd)/. ${TMP_DIR}
#cd ${TMP_DIR} && git checkout . && pwd;
cd ${TMP_DIR} && git checkout . && pwd;

for LIB_NAME in ${COMPONENTS} ; do
    colored_text "\n====== Releasing the component:【${LIB_NAME}】" cyan

    # REMOTE_URL=`git remote get-url ${LIB_NAME}`
    # REMOTE_URL="git@github.com:swoft-cloud/swoft-${LIB_NAME}.git"

    colored_text "Check sub-component remote"
    yellow_text "> git remote -v | grep ${LIB_NAME}"
    REMOTE_INFO=`git remote -v | grep ${LIB_NAME}`

    if [[ -z "$REMOTE_INFO" ]]; then
        red_text "Not found remote for the component: ${LIB_NAME}"
        continue
    fi

    yellow_text "> git pull ${LIB_NAME}"
    git pull ${LIB_NAME};

    NEW_BRANCH=${LIB_NAME}-master

    yellow_text "> git checkout -b ${NEW_BRANCH} ${LIB_NAME}/master"
    git checkout -b ${NEW_BRANCH} ${LIB_NAME}/master;

    yellow_text "> git pull ${LIB_NAME} ${TARGET_BRANCH}"
    git pull ${LIB_NAME} ${TARGET_BRANCH};

    # like: v2.0.0
    LAST_RELEASE=$(git describe --tags $(git rev-list --tags --max-count=1))

    # this is first release
    if [[ -z "$LAST_RELEASE" ]]; then
        if [[ "$RELEASE_TAG" = "AUTO" ]]; then
            read -p "Please input release tag version: " RELEASE_TAG
        fi

        colored_text "There has not been any releases. Releasing $RELEASE_TAG";
    else
        # auto find next version tag
        if [[ "$RELEASE_TAG" = "AUTO" ]]; then
            RELEASE_TAG=$(php dtool.php git:tag --only-tag --next-tag ${LAST_RELEASE})
        fi

        echo "Last release $LAST_RELEASE";

        CHANGES_SINCE_LAST_RELEASE=$(git log --oneline --decorate "$LAST_RELEASE"...master)

        if [[ ! -z "$CHANGES_SINCE_LAST_RELEASE" ]]; then
            colored_text "There are changes since last release. Releasing $RELEASE_TAG";
        else
            blue_text "No any change since last release. Skip release";
            continue
        fi
    fi

    # git tag $1 -s -m "Release $RELEASE_TAG"
    yellow_text "> git tag -a $1 -m \"Release $RELEASE_TAG\""
    git tag -a ${RELEASE_TAG} -m "Release $RELEASE_TAG";

    yellow_text "> git push $LIB_NAME $RELEASE_TAG"
    git push ${LIB_NAME} ${RELEASE_TAG};
done

yellow_text "> git checkout ${CURRENT_BRANCH}"
git checkout ${CURRENT_BRANCH}

colored_text "\nRelease Completed!"
