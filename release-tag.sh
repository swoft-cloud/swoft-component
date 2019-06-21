#!/usr/bin/env bash
#
# TODO with release message

set -e

binName="bash $(basename $0)"

if [[ -z "$1" ]]
then
    echo "Release all sub-repo to new tag version"
    echo -e "Usage: $binName VERSION\n"
    echo "Example:"
    echo "  $binName v1.0.0        Tag has to be provided"
    exit 1
fi

#./subtree-push.sh

RELEASE_TAG=$1
TARGET_BRANCH=master
CURRENT_BRANCH=`git rev-parse --abbrev-ref HEAD`
SUB_REPOS=$(ls src/)

echo "Will released version: ${RELEASE_TAG}"
echo "Will released projects:"
echo ${SUB_REPOS}

TMP_DIR="/tmp/swoft-repos"

for LIB_NAME in ${SUB_REPOS} ; do
    echo ""
    echo "====== Releasing the component:【${LIB_NAME}】"
#    echo "> git subtree push --prefix=src/${LIB_NAME} git@github.com:swoft-cloud/swoft-${LIB_NAME}.git ${TARGET_BRANCH} --squash"

    # REMOTE_URL=`git remote get-url ${LIB_NAME}`
    REMOTE_URL="git@github.com:swoft-cloud/swoft-${LIB_NAME}.git"

    rm -rf ${TMP_DIR} && mkdir ${TMP_DIR};

    (
        cd ${TMP_DIR};
        git clone ${REMOTE_URL} . --depth=200
        git checkout ${CURRENT_BRANCH};

        # like: v2.0.0
        LAST_RELEASE=$(git describe --tags $(git rev-list --tags --max-count=1))

        if [[ -z "$LAST_RELEASE" ]]; then
            echo "There has not been any releases. Releasing $1";

            # git tag $1 -s -m "Release $1"
            git tag -a $1 -m "Release $1"
            git push origin --tags
        else
            echo "Last release $LAST_RELEASE";

            CHANGES_SINCE_LAST_RELEASE=$(git log --oneline --decorate "$LAST_RELEASE"...master)

            if [[ ! -z "$CHANGES_SINCE_LAST_RELEASE" ]]; then
                echo "There are changes since last release. Releasing $1";

                # git tag $1 -s -m "Release $1"
                git tag -a $1 -m "Release $1"
                git push origin --tags
            else
                echo "No change since last release.";
            fi
        fi
    )
done

echo ""
echo "Completed!"
exit
