GENPORTOFF?=0
genport = $(shell expr ${GENPORTOFF} + \( $(shell id -u) - \( $(shell id -u) / 100 \) \* 100 \) \* 200 + 20000 + 0${1})

TOP_DIR?=$(shell pwd)

CONF_DIR=$(TOP_DIR)/conf
SSL_DIR=$(CONF_DIR)/ssl
SSL_CERT=$(SSL_DIR)/server.crt
SSL_KEY=$(SSL_DIR)/priv/server.key
SSL_CONFIG=$(SSL_DIR)/openssl.cnf
SERVER_CRT=$(BUILD_DIR)/conf/ssl/server.crt
SERVER_KEY=$(BUILD_DIR)/conf/ssl/priv/server.key

BUILD_DIR=$(TOP_DIR)/dev-env

SERVER_ROOT=$(BUILD_DIR)
WWW_ROOT=$(BUILD_DIR)/www
CSS_DIR=$(WWW_ROOT)/css

PGSQL_HOST=$(BUILD_DIR)/data
PGSQL_PORT=$(call genport,10)
PGSQL_USER=$(shell id -un)
PGSQL_PASSWD=
PGSQL_DBNAME=chuponMordac

PGSQL_VERSION=9.1
PGSQL_DATA=$(PGSQL_HOST)
PGSQL_DIR=$(BUILD_DIR)/pgsql

MYSQL_PORT=$(call genport,20)
MYSQL_DATA=$(BUILD_DIR)/mysql
MYSQL_SOCKET=$(MYSQL_DATA)/my.sock

USER=$(shell id -un)

HTTPD=/usr/sbin/apache2
HTTPD_USER=$(shell id -un)
HTTPD_GROUP=$(shell id -gn)
HTTPD_SYSCONF_DIR=/etc/apache2

HOST=$(shell hostname -f | head -1)
HTTP_PORT=$(call genport,1)
HTTPS_PORT=$(call genport,2)

SUPPORT_EMAIL=palvarez@ritho.net

LOG_DIR=$(BUILD_DIR)/log
RUN_DIR=$(BUILD_DIR)

SESSIONS_DIR=$(BUILD_DIR)/tmp

export
