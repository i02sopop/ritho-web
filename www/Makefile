TOPDIR?=..
include $(TOPDIR)/Config.mk

DIRS=classes css img include js templates
SRCS=index.php configuration.php

build:
	@echo "\\033[1;35m+++ Building up www\\033[39;0m"

install:
	@echo "\\033[1;35m+++ Installing www\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)
	@for i in $(SRCS); do \
		cp $$i $(WWW_ROOT) ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/$$i ; \
	done
