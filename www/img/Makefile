TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=agplv3-155x51.png agplv3-88x31.png not-fd.svg

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_ROOT)/img
	@cp $(SRCS) $(WWW_ROOT)/img
