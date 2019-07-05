#!/usr/bin/env bash
#
# TODO with release message

set -e

script_usage() {
    cat <<EOF
  -T <minutes>          Estimated job length in minutes, used to auto-set queue name
  -q <queuename>        Possible values for <queuename> are "verylong.q", "long.q"
                        and "short.q". See below for details
                        Default is "long.q".
EOF
    exit 0
}

# 显示帮助
[[ "$1" = "" || "$1" = "-h" || "$1" = "--help" ]] && script_usage

#getopt 命令的选项说明：
#-a 使getopt长选项支持"-"符号打头，必须与-l同时使用
#-l 后面接getopt支持长选项列表
#-n program如果getopt处理参数返回错误，会指出是谁处理的这个错误，这个在调用多个脚本时，很有用
#-o 后面接短参数选项，这种用法与getopts类似，
#-u 不给参数列表加引号，默认是加引号的（不使用-u选项），例如在加不引号的时候 --longopt "select * from db1.table1" $2只会取到select ，而不是完整的SQL语句。
# 示例：
# TEMP=`getopt -o ab:c:: -a -l apple,banana:,cherry:: -n "test.sh" -- "$@"`
# a 后没有冒号，表示没有参数
# b 后跟一个冒号，表示有一个必要参数
# c 后跟两个冒号，表示有一个可选参数(可选参数必须紧贴选项)
# -n 出错时的信息
# -- 也是一个选项，比如 要创建一个名字为 -f 的目录，会使用 mkdir -- -f ,
#    在这里用做表示最后一个选项(用以判定 while 的结束)
# $@ 从命令行取出参数列表(不能用用 $* 代替，因为 $* 将所有的参数解释成一个字符串
#                         而 $@ 是一个参数数组)

TEMP=`getopt -o ab:c:: --long apple,banana:,cherry:: -- "$@"`

# 判定 getopt 的执行时候有错，错误信息输出到 STDERR
if [[ $? != 0 ]]; then
	echo "Terminating....."
	exit 1
fi

# 重新排列参数的顺序
# 使用eval 的目的是为了防止参数中有shell命令，被错误的扩展。
eval set -- "${TEMP}"

# 处理具体的选项
while true; do
    echo $1

	case "$1" in
		-a|--apple)
			echo "option a"
			shift ;;
		-b|--banana)
			echo "option b, argument $2"
			shift 2 ;;
		-c|--cherry)
			case "$2" in
				"") # 选项 c 带一个可选参数，如果没有指定就为空
					echo "option c, no argument"
					shift 2 ;;
				*)
					echo "option c, argument $2"
					shift 2 ;;
			esac ;;
		--) echo "$1"; shift ; break ;;
		*) echo "Internal error!"; exit 1 ;;
		esac
done

echo "END"
exit 0
echo $@

#显示除选项外的参数(不包含选项的参数都会排到最后)
# arg 是 getopt 内置的变量 , 里面的值，就是处理过之后的 $@(命令行传入的参数)
for arg do
   echo '--> '"$arg" ;
done
