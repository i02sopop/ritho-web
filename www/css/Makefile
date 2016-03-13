TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=blue-three-columns bootstrap-theme
SRCS=

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/css
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/css; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/css/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
