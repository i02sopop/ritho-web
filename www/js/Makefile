TOPDIR?=..
include $(TOPDIR)/Config.mk

DIRS=
SRCS=jquery.js modernizr-1.7.min.js ritho.js

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/js
	@cp $(SRCS) $(WWW_ROOT)/js
	@for i in $(SRCS); do \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_ROOT)/js/$$i ; \
	done
