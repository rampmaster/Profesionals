cd /home/web/stable
while [ true ]
do
    echo $(date) > app/logs/pushnotificationslastrequest.log
    php app/console admin:sendpn
    sleep 6
done

