TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=c404.php cindex.php controller.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/classes/controller
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/classes/controller ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/controller/$$i ; \
	done
