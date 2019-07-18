#!/bin/sh

export EXE_DATE=`date "+%Y%m%d_%H%M%S"`
export THIS_PATH=`dirname $0`
export PATH=$PATH:$THIS_PATH

export PROJECT=${THIS_PATH}/../../../
export PHP_DIR=${PROJECT}/trunk/tool/test_script/
export PHP_FILE=test_post_json
export DAT_DIR=${PROJECT}/trunk/tool/test_script/test_data/
export LOG_DIR=${PROJECT}/evidence/test_post_json/
export XML_FILE=${LOG_DIR}/${EXE_DATE}_${PHP_FILE}.xml

TESTS=0
FAILURES=0
ERRORS=0
for fullname in `find ${DAT_DIR} -name "*.csv"`; do
    filename="${fullname#${DAT_DIR}}"
    filename=`echo $filename | sed -e 's/\//-/g' `
    filename="${filename%.*}.log"

    LOG_FILE=${LOG_DIR}/${EXE_DATE}_${filename}
    echo ${PHP_DIR}/${PHP_FILE}.php ${fullname}
    php ${PHP_DIR}/${PHP_FILE}.php ${fullname} > ${LOG_FILE}

    TCOUNTS=`cat ${LOG_FILE} | wc -l`
    TESTS=$(( TESTS + TCOUNTS ))

    FCOUNTS=`grep -c FAILED ${LOG_FILE}`
    FAILURES=$(( FAILURES + FCOUNTS ))

    echo "COUNTS:"${FCOUNTS}" FAILURES:"${FCOUNTS}" ERRORS:"${ERRORS}
done

echo '<?xml version="1.0" ?>' >> ${XML_FILE}
echo '<testsuite name="'${PHP_FILE}'" tests="'${TESTS}'" errors="'${ERRORS}'" failures="'${FAILURES}'">' >> ${XML_FILE}

for fullname in `find ${LOG_DIR} -name "${EXE_DATE}_*.log"`; do
    filename="${fullname##*/}"

    while read line
    do
	name=`echo "${line}" | strings`
	name="${name//\"/}"
	name="${name%%,*}"
	echo '<testcase classname="'$filename'" name="'$name'">' >> ${XML_FILE}

	COUNTS=`echo "$line" | grep -c FAILED`
	if [ $COUNTS -ne 0 ]; then
	    echo '<failure message="FAILED">'$line'</failure>' >> ${XML_FILE}
	fi
	echo '</testcase>' >> ${XML_FILE}
    done < ${fullname}
done

echo '<system-out><![CDATA[sdtout!]]></system-out>' >> ${XML_FILE}
echo '<system-err><![CDATA[stderr!]]></system-err>' >> ${XML_FILE}
echo '</testsuite>' >> ${XML_FILE}

#if [ $FAILURES -ne 0 ]; then
#    echo 1
#fi

echo "ALL COUNTS:"${FCOUNTS}" FAILURES:"${FAILURES}" ERRORS:"${ERRORS}
exit $FAILURES
