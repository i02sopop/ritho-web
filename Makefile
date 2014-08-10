TOPDIR?=$(realpath .)

include $(TOPDIR)/Config.mk

DIRS=conf www

.PHONY: rall all doc

all: start-environment doc

rall: start-environment doc

dirs:
	@if [ ! -d $(BUILD_DIR) ] ; then mkdir -p $(BUILD_DIR) ; fi
	@if [ ! -d $(SESSIONS_DIR) ] ; then mkdir -p $(SESSIONS_DIR) ; fi

start-environment: apache-start db-start

stop-environment: apache-stop db-stop

clean: clean-build

db-start: postgresql-start mysql-start
	@echo "\\033[1;35m+++ Starting db\\033[39;0m"

$(BUILD_DIR): install

mysql-start: $(BUILD_DIR)
	@echo -n "\\033[1;35m+++ Starting mysql\\033[39;0m "
	@if [ ! -f $(MYSQL_PID) ]; then \
		rm -rf $(MYSQL_DATA) > /dev/null; \
		$(USR_BIN)/mysql_install_db --user=$(USER) --ldata=$(MYSQL_DATA) > /dev/null 2> /dev/null; \
		mkdir -p $(MYSQL_LOGDIR); \
		$(USR_SBIN)/mysqld --defaults-extra-file=$(MYSQL_CONF) -P $(MYSQL_PORT) -h $(MYSQL_DATA) --socket=$(MYSQL_SOCKET) --pid-file=$(MYSQL_PID) > /dev/null 2>&1 & \
		ps_alive=0; \
		for i in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20; do \
                sleep 1; \
				if [ -f $(MYSQL_PID) ]; then pid=`cat $(MYSQL_PID)`; fi; \
				if [ -f $(MYSQL_PID) ] && `ps $${pid}` > /dev/null 2>&1; then ps_alive=1; break; fi; \
                echo -n "\\033[1;35m.\\033[39;0m"; \
        done; \
		if [ $${ps_alive} ]; then \
			$(USR_BIN)/mysqladmin --protocol=TCP -P $(MYSQL_PORT) -h $(MYSQL_HOST) -u root create $(DATABASE) ; \
			$(USR_BIN)/mysql --protocol=TCP -P $(MYSQL_PORT) -h $(MYSQL_HOST) -u root $(DATABASE) < $(MYSQL_SCHEMA) ; \
		elif [ ! $${ps_alive} ]; then \
			echo "\\033[1;35m+++ Failed to start mysql\\033[39;0m"; \
		fi; \
	fi;
	@echo;

postgresql-start: $(BUILD_DIR)
	@echo "\\033[1;35m+++ Starting postgres\\033[39;0m"
	@if [ ! -f $(BUILD_DIR)/postmaster.pid ]; then \
		rm -rf $(PGSQL_DATA) > /dev/null; \
		mkdir -p $(PGSQL_LOGDIR); \
		$(PGSQL_BIN)/initdb --pgdata=$(PGSQL_DATA) --auth="ident" > /dev/null; \
		$(PGSQL_BIN)/postmaster -h '' -k $(PGSQL_DATA) -D $(PGSQL_DATA) 1> $(PGSQL_LOG) < /dev/null 2>&1 & \
		echo $$! > $(BUILD_DIR)/postmaster.pid; \
		while ! $(USR_BIN)/psql -h $(PGSQL_DATA) -c "select current_timestamp" template1 > /dev/null 2>&1; do \
			/bin/sleep 1; \
			echo -n "\\033[1;35m.\\033[39;0m"; \
		done; \
		$(USR_BIN)/createdb -h $(PGSQL_DATA) $(DATABASE); \
		$(USR_BIN)/psql -q -h $(PGSQL_DATA) $(DATABASE) -f $(PGSQL_SCHEMA) > /dev/null 2>&1; \
	fi

apache-start: $(BUILD_DIR)
	@echo "\\033[1;35m+++ Starting HTTP daemon\\033[39;0m"
	@if [ ! -f $(HTTPD_PIDFILE) ]; then \
		if [ ! -d $(HTTPD_LOGDIR) ] ; then mkdir -p $(HTTPD_LOGDIR) ; fi; \
		echo "SetEnv PGDATA $(PGSQL_DATA)" >> $(HTTPD_CONFIG); \
		$(HTTPD) -f $(HTTPD_CONFIG); \
	fi

selenium-start:
	@$(MAKE) -C $(TOPDIR)/tests selenium-start

selenium-stop:
	@$(MAKE) -C $(TOPDIR)/tests selenium-stop

db-stop: mysql-stop postgresql-stop

mysql-stop:
	@echo "\\033[1;35m+++ Stopping mysql\\033[39;0m"
	@if [ -f $(MYSQL_PID) ]; then \
		kill -3 `cat $(MYSQL_PID)` 2>/dev/null; \
	fi

postgresql-stop:
	@if [ -f $(BUILD_DIR)/postmaster.pid ]; then \
		echo -n "\\033[1;35m+++ Stopping postgres\\033[39;0m "; \
		while kill -INT `cat $(BUILD_DIR)/postmaster.pid` 2>/dev/null; do echo -n "\\033[1;35m.\\033[39;0m "; sleep 1; done; echo; \
	fi

apache-stop:
	@echo "\\033[1;35m+++ Stopping HTTP daemon\\033[39;0m"
	@if [ -f $(RUN_DIR)/apache2.pid ]; then \
		$(HTTPD) -f $(CONF_DIR)/apache2.conf -k stop; \
	fi

clean-build: stop-environment
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
	@$(MAKE) -C $(TOPDIR)/www install

tests: install start-environment
	@echo "\\033[1;35m+++ Running tests\\033[39;0m"
	@$(MAKE) -C $(TOPDIR)/tests tests

doc:
	@mkdir -p $(DOC_DIR)/phpdoc/log
	@phpdoc

help:
	@echo "\033[1;35mmake all\\033[39;0m - build, install and bring up environment."
	@echo "\033[1;35mmake build\\033[39;0m - build up environment."
	@echo "\033[1;35mmake clean\\033[39;0m - bring down and remove environment."
	@echo "\033[1;35mmake install\\033[39;0m - install environment."
	@echo "\033[1;35mmake db-start\\033[39;0m - bring up db servers."
	@echo "\033[1;35mmake db-stop\\033[39;0m - bring down db servers."
	@echo "\033[1;35mmake mysql-start\\033[39;0m - bring up mysql server."
	@echo "\033[1;35mmake mysql-stop\\033[39;0m - bring down mysql server."
	@echo "\033[1;35mmake postgresql-start\\033[39;0m - bring up postgresql server."
	@echo "\033[1;35mmake postgresql-stop\\033[39;0m - bring down postgresql server."
	@echo "\033[1;35mmake apache-start\\033[39;0m - bring up apache server."
	@echo "\033[1;35mmake apache-stop\\033[39;0m - bring down apache server."
	@echo "\033[1;35mmake selenium-start\\033[39;0m - bring up selenium daemon."
	@echo "\033[1;35mmake selenium-stop\\033[39;0m - bring down selenium daemon."
	@echo "\033[1;35mmake start-environment\\033[39;0m - bring up environment."
	@echo "\033[1;35mmake stop-environment\\033[39;0m - bring down environment."

info:
	@echo "To connect to postgresql database: \033[1;35mpsql -h $(PGSQL_DATA) $(DATABASE)\\033[39;0m"
	@echo "To connect to mysql database (tcp): \033[1;35mmysql --protocol=TCP -P $(MYSQL_PORT) -u root $(DATABASE)\\033[39;0m"
	@echo "To connect to mysql database (socket): \033[1;35mmysql --socket=$(MYSQL_SOCKET) -u root $(DATABASE)\\033[39;0m"
	@echo "Development environment: \033[1;35mhttp://$(HOST):$(HTTP_PORT)\\033[39;0m"
	@echo "Development environment SSL: \033[1;35mhttps://$(HOST):$(HTTPS_PORT)\\033[39;0m"

rinfo:
	@echo "To connect to postgresql database: \033[1;35mpsql -h $(PGSQL_DATA) $(DATABASE)\\033[39;0m"
	@echo "To connect to mysql database (tcp): \033[1;35mmysql --protocol=TCP -P $(MYSQL_PORT) -u root $(DATABASE)\\033[39;0m"
	@echo "To connect to mysql database (socket): \033[1;35mmysql --socket=$(MYSQL_SOCKET) -u root $(DATABASE)\\033[39;0m"
	@echo "Development environment: \033[1;35mhttp://$(HOST):$(HTTP_PORT)\\033[39;0m"
	@echo "Development environment SSL: \033[1;35mhttps://$(HOST):$(HTTPS_PORT)\\033[39;0m"
