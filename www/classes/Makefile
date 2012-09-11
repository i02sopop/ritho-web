TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=controller databases exceptions utils view
SRCS=base.php template.php

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/classes
	@for i in $(SRCS); do \
		cp $$i $(WWW_ROOT)/classes ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/classes/$$i ; \
	done
