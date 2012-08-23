GENPORTOFF?=0
genport = $(shell expr ${GENPORTOFF} + \( $(shell id -u) - \( $(shell id -u) / 100 \) \* 100 \) \* 200 + 20000 + 0${1})

VERSION=0.0.1
USR_BIN=/usr/bin
USR_SBIN=/usr/sbin

TOP_DIR?=$(shell pwd)
BUILD_DIR=$(TOP_DIR)/dev-env
CONF_DIR=$(BUILD_DIR)/conf
TMP_DIR=$(BUILD_DIR)/tmp
LOG_DIR=$(BUILD_DIR)/log
RUN_DIR=$(BUILD_DIR)

SSL_DIR=$(CONF_DIR)/ssl
SSL_CERT=$(SSL_DIR)/server.crt
SSL_KEY=$(SSL_DIR)/priv/server.key
SSL_CONFIG=$(SSL_DIR)/openssl.cnf

SERVER_ROOT=$(BUILD_DIR)
WWW_ROOT=$(BUILD_DIR)/www
CSS_DIR=$(WWW_ROOT)/css

DATABASE=ritho-web
SCRIPTS_DIR=$(TOP_DIR)/scripts
DB_SCRIPTS_DIR=$(SCRIPTS_DIR)/db

PGSQL_HOST=$(BUILD_DIR)/data
PGSQL_PORT=$(call genport,10)
PGSQL_USER=$(shell id -un)
PGSQL_PASSWD=

PGSQL_VERSION=9.1
PGSQL_DATA=$(PGSQL_HOST)
PGSQL_DIR=$(BUILD_DIR)/pgsql
PGSQL_BIN=/usr/lib/postgresql/9.1/bin
PGSQL_LOGDIR=$(LOG_DIR)
PGSQL_LOG=$(PGSQL_LOGDIR)/pgsql.log
PGSQL_SCHEMA=$(DB_SCRIPTS_DIR)/schema-postgresql-$(VERSION).sql

MYSQL_USER=$(shell id -un)
MYSQL_PORT=$(call genport,20)
MYSQL_BASEDIR=$(BUILD_DIR)
MYSQL_DATA=$(MYSQL_BASEDIR)/mysql
MYSQL_SOCKET=$(MYSQL_DATA)/my.sock
MYSQL_PID=$(MYSQL_BASEDIR)/mysql.pid
MYSQL_CONF=$(CONF_DIR)/my.cnf
MYSQL_LOGDIR=$(LOG_DIR)/mysql
MYSQL_LOG=$(MYSQL_LOGDIR)/mysql.log
MYSQL_LOG_BIN=$(MYSQL_LOGDIR)/mysql-bin.log
MYSQL_LOG_QSLOW=$(MYSQL_LOGDIR)/mysql-slow.log
MYSQL_SCHEMA=$(DB_SCRIPTS_DIR)/schema-mysql-$(VERSION).sql

USER=$(shell id -un)

HTTPD=/usr/sbin/apache2
HTTPD_USER=$(shell id -un)
HTTPD_GROUP=$(shell id -gn)
HTTPD_SYSCONFIG=/etc/apache2
HTTPD_LOGDIR=$(LOG_DIR)/apache2
HTTPD_CONFIG=$(CONF_DIR)/apache2.conf
HTTPD_PIDFILE=$(RUN_DIR)/apache2.pid

HOST=$(shell hostname -f | head -1)
HTTP_PORT=$(call genport,1)
HTTPS_PORT=$(call genport,2)

SUPPORT_EMAIL=palvarez@ritho.net

SESSIONS_DIR=$(BUILD_DIR)/tmp

export

# prod
# MYSQL_SOCKET=/var/run/mysqld/mysqld.sock
# MYSQL_USER=mysql
# MYSQL_PID=/var/run/mysqld/mysqld.pid
# MYSQL_BASEDIR=/usr
# MYSQL_DATA=/var/lib/mysql
# MYSQL_CONF=$(CONF_DIR)/my.cnf
# MYSQL_LOGDIR=/var/log/mysql
# MYSQL_LOG=$(MYSQL_LOGDIR)/mysql.log
# MYSQL_LOG_BIN=$(MYSQL_LOGDIR)/mysql-bin.log
# MYSQL_LOG_QSLOW=$(MYSQL_LOGDIR)/mysql-slow.log
# TMP_DIR=/tmp
