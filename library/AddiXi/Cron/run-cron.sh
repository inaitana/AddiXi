# /bin/sh
FILENAME=$1`date +%d%m%y%H%M%S`.log
wget http://$1/cron -O $FILENAME
if [ `stat -c%s $FILENAME` = 0 ]
then rm $FILENAME
fi
