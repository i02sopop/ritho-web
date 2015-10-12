GENPORTOFF?=0
genport = $(shell expr ${GENPORTOFF} + \( $(shell id -u) - \( $(shell id -u) / 100 \) \* 100 \) \* 200 + 20000 + 0${1})

VERSION=0.0.1

ifdef PROD_BUILD

BUILD_DIR=/var/www/ritho-web
CONF_DIR=/etc/ritho-web
TMP_DIR=/tmp
LOG_DIR=/var/log
RUN_DIR=/var/run

SCRIPTS_DIR=$(BUILD_DIR)/scripts
DB_SCRIPTS_DIR=$(SCRIPTS_DIR)/db

USER=www-data
HOST=ritho.net

HTTP_PORT=80
HTTPS_PORT=443
HTTPD_USER=www-data
HTTPD_GROUP=www-data

PGSQL_HOST=localhost
PGSQL_PORT=5432
PGSQL_USER=postgres
PGSQL_PASSWD=

PGSQL_DATA=$(PGSQL_HOST)
PGSQL_DIR=$(BUILD_DIR)/pgsql
PGSQL_BIN=/usr/lib/postgresql/9.4/bin
PGSQL_LOGDIR=$(LOG_DIR)
PGSQL_LOG=$(PGSQL_LOGDIR)/pgsql.log
PGSQL_SCHEMA=$(DB_SCRIPTS_DIR)/schema-postgresql-$(VERSION).sql

MYSQL_HOST=$(HOST)
MYSQL_PORT=3306
MYSQL_USER=mysql
MYSQL_BASEDIR=/usr
MYSQL_DATA=/var/lib/mysql
MYSQL_SOCKET=/var/run/mysqld/mysqld.sock
MYSQL_PID=/var/run/mysqld/mysqld.pid
MYSQL_LOGDIR=/var/log/mysql
MYSQL_SCHEMA=$(DB_SCRIPTS_DIR)/schema-mysql-$(VERSION).sql

SUPPORT_EMAIL=palvarez@ritho.net

else

TOP_DIR?=$(realpath .)
BUILD_DIR=$(TOP_DIR)/dev-env
CONF_DIR=$(BUILD_DIR)/conf
TMP_DIR=$(BUILD_DIR)/tmp
LOG_DIR=$(BUILD_DIR)/log
RUN_DIR=$(BUILD_DIR)

SCRIPTS_DIR=$(TOP_DIR)/scripts
DB_SCRIPTS_DIR=$(SCRIPTS_DIR)/db

USER=$(shell id -un)
HOST=$(shell hostname -f | head -1)

HTTP_PORT=$(call genport,1)
HTTPS_PORT=$(call genport,2)
HTTPD_USER=$(shell id -un)
HTTPD_GROUP=$(shell id -gn)

PGSQL_HOST=$(BUILD_DIR)/data
PGSQL_PORT=$(call genport,10)
PGSQL_USER=$(shell id -un)
PGSQL_PASSWD=

PGSQL_DATA=$(PGSQL_HOST)
PGSQL_DIR=$(BUILD_DIR)/pgsql
PGSQL_BIN=/usr/lib/postgresql/9.4/bin
PGSQL_LOGDIR=$(LOG_DIR)
PGSQL_LOG=$(PGSQL_LOGDIR)/pgsql.log
PGSQL_SCHEMA=$(DB_SCRIPTS_DIR)/schema-postgresql-$(VERSION).sql

MYSQL_HOST=127.0.0.1
MYSQL_PORT=$(call genport,20)
MYSQL_USER=$(shell id -un)
MYSQL_BASEDIR=$(BUILD_DIR)
MYSQL_DATA=$(MYSQL_BASEDIR)/mysql
MYSQL_SOCKET=$(MYSQL_DATA)/my.sock
MYSQL_PID=$(MYSQL_BASEDIR)/mysql.pid
MYSQL_CONF=$(CONF_DIR)/my.cnf
MYSQL_LOGDIR=$(LOG_DIR)/mysql
MYSQL_SCHEMA=$(DB_SCRIPTS_DIR)/schema-mysql-$(VERSION).sql

SUPPORT_EMAIL=palvarez@ritho.net

endif

USR_BIN=/usr/bin
USR_SBIN=/usr/sbin

SSL_DIR=$(CONF_DIR)/ssl
SSL_CERT=$(SSL_DIR)/server.crt
SSL_KEY=$(SSL_DIR)/priv/server.key
SSL_CONFIG=$(SSL_DIR)/openssl.cnf

SERVER_ROOT=$(BUILD_DIR)
WWW_DIR=$(SERVER_ROOT)/www
CSS_DIR=$(WWW_DIR)/css
TESTS_ROOT=$(SERVER_ROOT)/tests

DATABASE=ritho-web

LOG_FILE=ritho.log
LOG_PREFIX=ritho_

PGSQL_VERSION=$(shell psql -V | awk -F' ' '{ print $$3 }' | awk -F'.' '{ if ($$2 != null) print $$1"."$$2 }')

MYSQL_CONF=$(CONF_DIR)/my.cnf
MYSQL_LOG=$(MYSQL_LOGDIR)/mysql.log
MYSQL_LOG_BIN=$(MYSQL_LOGDIR)/mysql-bin.log
MYSQL_LOG_QSLOW=$(MYSQL_LOGDIR)/mysql-slow.log

DB_ENGINE=mysql
DB_HOST=$(HOST)
DB_PORT=$(MYSQL_PORT)

HTTPD=/usr/sbin/apache2
HTTPD_SYSCONFIG=/etc/apache2
HTTPD_LOGDIR=$(LOG_DIR)/apache2
HTTPD_CONFIG=$(CONF_DIR)/apache2.conf
HTTPD_PIDFILE=$(RUN_DIR)/apache2.pid

SELENIUM_PORT=$(call genport,30)

SESSIONS_DIR=$(TMP_DIR)

export
