TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=v404.php view.php vindex.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/classes/view
	@for i in $(SRCS); do \
		cp $$i $(WWW_ROOT)/classes/view ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/classes/view/$$i ; \
	done
