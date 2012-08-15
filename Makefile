TOPDIR?=$(shell pwd)

include $(TOPDIR)/Config.mk

DIRS=conf www

all: clean-build start-environment

dirs:
	@if [ ! -d $(BUILD_DIR) ] ; then mkdir -p $(BUILD_DIR) ; fi
	@if [ ! -d $(SESSIONS_DIR) ] ; then mkdir -p $(SESSIONS_DIR) ; fi

start-environment: db-start apache-start

stop-environment: db-stop apache-stop

clean: stop-environment clean-build

db-start: mysql-start postgresql-start
	@echo "\\033[1;35m+++ Starting db\\033[39;0m"

mysql-start:
	@echo "\\033[1;35m+++ Starting mysql\\033[39;0m"
	@if [ ! -f $(RUN_DIR)/mysql.pid ]; then \
		rm -rf $(MYSQL_DATA) > /dev/null; \
		/usr/bin/mysql_install_db --user=$(USER) --ldata=$(MYSQL_DATA) > /dev/null 2> /dev/null; \
		/usr/sbin/mysqld -P $(MYSQL_PORT) -h $(MYSQL_DATA) --socket=$(MYSQL_SOCKET) --pid-file=$(BUILD_DIR)/mysql.pid > /dev/null 2> /dev/null& \
	fi;

postgresql-start:
	@echo "\\033[1;35m+++ Starting postgres\\033[39;0m"
	@if [ ! -f $(RUN_DIR)/postmaster.pid ]; then \
		rm -rf $(PGSQL_DATA) > /dev/null; \
		/usr/lib/postgresql/9.1/bin/initdb --pgdata=$(PGSQL_DATA) --auth="ident" > /dev/null; \
		$(TOP_DIR)/scripts/db/pg_start.sh $(PGSQL_DATA) $(TOP_DIR) $(BUILD_DIR) > /dev/null 2> /dev/null; \
	fi

apache-start:
	@echo "\\033[1;35m+++ Starting HTTP daemon\\033[39;0m"
	@if [ ! -f $(RUN_DIR)/apache2.pid ]; then \
		if [ ! -d $(LOG_DIR) ] ; then mkdir -p $(LOG_DIR)/apache2 ; fi; \
		echo "SetEnv PGDATA $(PGSQL_DATA)" >> $(BUILD_DIR)/conf/apache2.conf; \
		$(HTTPD) -f $(BUILD_DIR)/conf/apache2.conf; \
	fi

db-stop: mysql-stop postgresql-stop

mysql-stop:
	@echo "\\033[1;35m+++ Stopping mysql\\033[39;0m"
	@if [ -f $(BUILD_DIR)/mysql.pid ]; then \
		kill -3 `cat $(BUILD_DIR)/mysql.pid` 2>/dev/null; \
	fi

postgresql-stop:
	@echo "\\033[1;35m+++ Stopping postgres\\033[39;0m"
	@if [ -f $(BUILD_DIR)/postmaster.pid ]; then \
		while kill -INT `cat $(BUILD_DIR)/postmaster.pid` 2>/dev/null; do echo -n "."; sleep 1; done ; echo; \
	fi

apache-stop:
	@echo "\\033[1;35m+++ Stopping HTTP daemon\\033[39;0m"
	@if [ -f $(RUN_DIR)/apache2.pid ]; then \
		$(HTTPD) -f $(BUILD_DIR)/conf/apache2.conf -k stop; \
	fi

clean-build:
	@echo "\\033[1;35m+++ Cleaning files and directories.\\033[39;0m"
	@rm -rf $(LOG_DIR)
	@rm -rf $(SESSIONS_DIR)
	@rm -rf $(BUILD_DIR)

build: dirs
	@echo "\\033[1;35m+++ Building up system\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@echo "\\033[1;35m+++ System built\\033[39;0m"

install: build
	@echo "\\033[1;35m+++ Installing system\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i install ; done
	@echo "\\033[1;35m+++ System installed\\033[39;0m"

rw: dirs
	@echo "\\033[1;35m+++ Installing www\\033[39;0m"
	@make -C $(TOP_DIR)/www install

help:
	@echo "make all - create and bring up environment"
	@echo "make clean - bring down and remove environment"
	@echo "------"
	@echo "make db-start db-stop - bring up and down environment db server"
	@echo "make mysql-start mysql-stop - bring up and down environment mysql server"
	@echo "make postgresql-start postgresql-stop - bring up and down environment postgresql server"
	@echo "make apache-start apache-stop - bring up and down environment apache server"
	@echo "------"
	@echo "\\033[1;35m> System commands\\033[39;0m"
	@echo "To connect to postgresql database: psql -h $(PGSQL_DATA) ritho-web"
	@echo "Development environment: http://$(HOST):$(HTTP_PORT)"
	@echo "Development environment SSL: https://$(HOST):$(HTTPS_PORT)"
