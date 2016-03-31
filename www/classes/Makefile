TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS  = controller
DIRS += databases
DIRS += exceptions
DIRS += utils
SRCS  = base.php
SRCS += template.php
SRCS += ritho.php

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/classes
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/classes ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/classes/$$i ; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
