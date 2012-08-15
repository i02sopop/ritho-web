TOPDIR?=..
include $(TOPDIR)/Config.mk

DIRS=
SRCS=log.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/classes/utils
	@cp $(SRCS) $(WWW_ROOT)/classes/utils
	@for i in $(SRCS); do \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/classes/utils/$$i ; \
	done
