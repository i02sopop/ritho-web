TOPDIR?=..
include $(TOPDIR)/Config.mk

DIRS=
SRCS=functions.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/include
	@for i in $(SRCS); do \
		cp $$i $(WWW_ROOT)/include ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/include/$$i ; \
	done
