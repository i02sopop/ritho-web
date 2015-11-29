TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS  =
SRCS  = log.php
SRCS += template.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/classes/utils
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/classes/utils ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/utils/$$i ; \
	done
