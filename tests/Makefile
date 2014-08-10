TOPDIR?=$(realpath ..)

include $(TOPDIR)/Config.mk

DIRS=
SRCS=bootstrap.php DBTest.php TemplateTest.php  SeleniumTest.php
TESTS=DBTest.php TemplateTest.php SeleniumTest.php

install:
	@echo "\\033[1;35m+++ Installing tests\\033[39;0m"
	@mkdir -p $(TESTS_ROOT)
	@for i in $(SRCS); do \
		cp $$i $(TESTS_ROOT) ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(TESTS_ROOT)/$$i ; \
	done

tests:install
	@echo "\\033[1;35m+++ Running tests\\033[39;0m"
	@$(MAKE) selenium-start
	@for i in $(TESTS); do \
		echo "\\033[1;35m+++ Running test $$i\\033[39;0m" ; \
		phpunit --bootstrap $(TESTS_ROOT)/bootstrap.php $(TESTS_ROOT)/$$i ; \
	done
	@$(MAKE) selenium-stop

selenium-start:
	@echo -n "\\033[1;35m+++ Starting Selenium\\033[39;0m "
	@if [ ! -f $(BUILD_DIR)/selenium.pid ]; then \
		java -jar $(TOPDIR)/lib/selenium-server-standalone-2.25.0.jar -port $(SELENIUM_PORT) > /dev/null 2>&1 & echo $$! > $(BUILD_DIR)/selenium.pid ; \
		for i in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20; do \
                sleep 1; \
				if [ -f $(BUILD_DIR)/selenium.pid ]; then pid=`cat $(BUILD_DIR)/selenium.pid`; fi; \
				if [ -f $(BUILD_DIR)/selenium.pid ] && `ps $${pid}` > /dev/null 2>&1; then break; fi; \
                echo -n "\\033[1;35m.\\033[39;0m"; \
        done; \
	fi
	@echo ""

selenium-stop: 
	@echo "\\033[1;35m+++ Stoping Selenium\\033[39;0m"
	@if [ -f $(BUILD_DIR)/selenium.pid ]; then \
		kill -9 `cat $(BUILD_DIR)/selenium.pid` 2>/dev/null; \
		rm -f $(BUILD_DIR)/selenium.pid; \
	fi
