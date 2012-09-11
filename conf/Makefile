TOPDIR?=$(realpath ..)
include $(TOPDIR)/Config.mk

DIRS=ssl
SRCS=apache2-vhosts.conf apache2.conf postgresql.conf my.cnf

install: build
	@echo "\\033[1;35m+++ Installing conf\\033[39;0m"
	@mkdir -p $(CONF_DIR)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install ; done
	@for i in $(SRCS); do \
		cp $$i $(CONF_DIR) ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(CONF_DIR)/$$i ; \
	done
	@echo "\\033[1;35m+++ Conf installed\\033[39;0m"

build:
	@echo "\\033[1;35m+++ Building up conf\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@echo "\\033[1;35m+++ Conf built\\033[39;0m"
