#!/bin/bash
#
# Tool for run unit test for swoft components
#

#binName="sh $(basename $0)"
binName="./$(basename $0)"
components="component annotation aop bean config connection-pool i18n console db error framework http-message http-server
  log proxy redis rpc rpc-client rpc-server server stdlib task tcp-server websocket-server"

if [[ -z "$1" ]] || [[ "$1" == "-h" ]]; then
    echo "Use for run phpunit for swoft components."
    echo ""
    echo "Usage:"
    echo "  $binName NAME(S)    Run phpunit for the given component in the src/NAME"
    echo "  $binName all        Run phpunit for all components at the src/*"
    echo ""
    echo "Example:"
    echo "  $binName db         Run phpunit for 'db' component"
    echo "  $binName db event   Run phpunit for 'db' and 'event' component"
    echo "  $binName all"
    echo ""
    echo "All Components:"
    echo "  ${components}"
    exit
fi

# for one or multi component
if [ "$1" != "all" ]; then
    components=$@
fi

echo "Will test components:"
echo ${components}
echo ""

# do run phpunit
# php run.php -c src/annotation/phpunit.xml
# set -ex
for lbName in ${components} ; do
    if [ "${lbName}" == "component" ]; then
        echo "======> Testing the【component】"
        echo "> php run.php -c phpunit.xml"
        php run.php -c phpunit.xml
    else
        if [ ! -d "src/${lbName}" ]; then
            echo "!! Skip invalid component: ${lbName}"
        else
          echo "======> Testing the component【${lbName}】"
          echo "> php run.php -c src/${lbName}/phpunit.xml"
          php run.php -c src/${lbName}/phpunit.xml
        fi
    fi
done

echo "Completed!"
