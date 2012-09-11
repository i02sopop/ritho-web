TOPDIR?=$(realpath ..)

include $(TOPDIR)/Config.mk

DIRS=
SRCS=TemplateTest.php

tests:
	@for i in $(SRCS); do\
		echo "\\033[1;35m+++ Running test $$i\\033[39;0m"; \
		phpunit $$i; \
	done
