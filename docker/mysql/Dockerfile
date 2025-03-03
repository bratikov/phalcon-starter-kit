FROM mysql:8.4

RUN echo "[mysqld]" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "character-set-server    = utf8mb4" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "collation-server        = utf8mb4_general_ci" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "bulk_insert_buffer_size = 1024M" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "innodb_buffer_pool_size = 4G" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "max_allowed_packet      = 1024M" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "skip-log-bin" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "[client]" >> /etc/mysql/conf.d/mysql.cnf
RUN echo "default-character-set = utf8" >> /etc/mysql/conf.d/mysql.cnf