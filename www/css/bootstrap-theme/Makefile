TOPDIR?=$(realpath ../../..)
include $(TOPDIR)/Config.mk

THEME=bootstrap-theme
DIRS=fonts
SRCS=style.css


install:
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(CSS_DIR)/$(THEME)
	@for i in $(SRCS); do \
		install $$i $(CSS_DIR)/$(THEME); \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(CSS_DIR)/$(THEME)/$$i ; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
	@echo "Cleaning css theme"
