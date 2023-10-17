#!/bin/bash

./vendor/bin/sail npm run check-format
PRETTIER_EXIT_CODE=$?

if [ $PRETTIER_EXIT_CODE -ne 0 ]
then
    ./vendor/bin/sail npm run format
fi


./vendor/bin/sail composer check-format
CS_FIXER_EXIT_CODE=$?

if [ $CS_FIXER_EXIT_CODE -ne 0 ]
then
    ./vendor/bin/sail composer format
fi


./vendor/bin/sail composer analyse
PHP_STAN_EXIT_CODE=$?


#if [ $PRETTIER_EXIT_CODE -ne 0 ] || [ $CS_FIXER_EXIT_CODE -ne 0 ] || [ $PHP_STAN_EXIT_CODE -ne 0 ]
#then
#    exit -1
#fi
