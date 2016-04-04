TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS  =
SRCS  = log.php
SRCS += account.php
SRCS += template.php

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/classes/utils
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/classes/utils; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/utils/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
