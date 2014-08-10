TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS=404.html center.html footer.html header.html head.html index.html left.html right.html section.html

build: install

install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)/templates
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR)/templates ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/templates/$$i ; \
	done
