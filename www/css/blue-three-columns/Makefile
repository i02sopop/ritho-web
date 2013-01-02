TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=style.css styleIE7.css styleIE.css styleIEOld.css stylePrint.css

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/css/blue-three-columns
	@for i in $(SRCS); do \
		cp $$i $(WWW_ROOT)/css/blue-three-columns ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/css/blue-three-columns/$$i ; \
	done
