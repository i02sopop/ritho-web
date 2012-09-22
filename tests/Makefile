TOPDIR?=$(realpath ..)

include $(TOPDIR)/Config.mk

DIRS=
SRCS=bootstrap.php DBTest.php TemplateTest.php
TESTS=DBTest.php TemplateTest.php

install:
	@echo "\\033[1;35m+++ Installing tests\\033[39;0m"
	@mkdir -p $(TESTS_ROOT)
	@for i in $(SRCS); do \
		cp $$i $(TESTS_ROOT) ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(TESTS_ROOT)/$$i ; \
	done

tests: install
	@echo "\\033[1;35m+++ Running tests\\033[39;0m"
	@for i in $(TESTS); do \
		echo "\\033[1;35m+++ Running test $$i\\033[39;0m" ; \
		phpunit --bootstrap $(TESTS_ROOT)/bootstrap.php $(TESTS_ROOT)/$$i ; \
	done
