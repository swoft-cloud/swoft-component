#!/bin/bash
#
# Tool for run unit test for swoft components
#

# import common functions
source "$(dirname $0)/script/shells/common-func.sh"

binName="./$(basename $0)"

if [[ -z "$1" ]] || [[ "$1" == "-h" ]]; then
    echo "Use for run phpunit for swoft components."
    echo ""
    echo "Usage:"
    echo "  $binName NAME(S)    Run phpunit for the given component in the src/NAME"
    echo "  $binName all        Run phpunit for all components at the src/*"
    echo "  $binName nodb       Run phpunit for all component, but exclude 'db' and 'redis'"
    echo ""
    echo "Example:"
    echo "  $binName db         Run phpunit for 'db' component"
    echo "  $binName db event   Run phpunit for 'db' and 'event' component"
    echo ""
    exit
fi

# for one or multi component
if [[ "$1" != "all" ]]; then
    if [[ "$1" == "nodb" ]]; then
        components=$(ls src | grep -v db | grep -v redis)
    else
        components=$@
    fi
else
    components=$(ls src/)
fi

colored_text "Will test components:"
echo ${components}
echo ""

# do run phpunit
# php run.php -c src/annotation/phpunit.xml
# set -ex
for lbName in ${components} ; do
    if [[ "${lbName}" == "component" ]]; then
        echo "======> Testing the【component】"
        echo "> php run.php -c phpunit.xml"
        php run.php -c phpunit.xml
        echo $?
    else
        if [[ ! -d "src/${lbName}" ]]; then
            echo "!! Skip invalid component: ${lbName}"
        else
          echo "======> Testing the component【${lbName}】"
          echo "> php run.php -c src/${lbName}/phpunit.xml"
          php run.php -c src/${lbName}/phpunit.xml
          echo $?
        fi
    fi
done

cyan_text "\nTest Completed!"
