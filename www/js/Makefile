TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=jquery.js modernizr.js ritho.js less.js ember.js

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/js
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/js ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/js/$$i ; \
	done
