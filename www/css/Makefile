TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=blue-three-columns
SRCS=

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/css
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/css ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/css/$$i ; \
	done
