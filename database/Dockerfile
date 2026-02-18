FROM mysql:latest

COPY init-db.sh /usr/local/bin/init-db.sh
RUN chmod +x /usr/local/bin/init-db.sh

RUN mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chmod 777 /var/run/mysqld
# MariaDB needs write permission to this directory

ENTRYPOINT ["/usr/local/bin/init-db.sh"]