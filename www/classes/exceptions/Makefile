TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=missingexception.php

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/classes/exceptions
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/classes/exceptions ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/exceptions/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
