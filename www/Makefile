TOPDIR?=$(realpath ..)
include $(TOPDIR)/Config.mk

DIRS=classes css img include js templates
SRCS=index.php configuration.php

install: $(SRCS)
	@echo "\\033[1;35m+++ Installing www\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i install ; done
	@mkdir -p $(WWW_DIR)
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR) ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/$$i ; \
	done

clean:
	@echo "\\033[1;35m+++ Cleaning www\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean ; done
