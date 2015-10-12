TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=missingexception.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/classes/exceptions
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/classes/exceptions ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/exceptions/$$i ; \
	done
