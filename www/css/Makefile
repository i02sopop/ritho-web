TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=blue-three-columns
SRCS=

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/css
	@for i in $(SRCS); do \
		cp $$i $(WWW_ROOT)/css ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/css/$$i ; \
	done
