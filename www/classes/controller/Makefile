TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS  = controller.php
SRCS += cindex.php
SRCS += c404.php

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/classes/controller
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/classes/controller; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/controller/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
