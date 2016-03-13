TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=functions.php

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/include
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/include; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/include/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
