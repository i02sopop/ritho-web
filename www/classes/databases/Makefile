TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=db.php mydb.php pgdb.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/classes/databases
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/classes/databases ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/databases/$$i ; \
	done
