TOPDIR?=$(realpath ../../../..)
include $(TOPDIR)/Config.mk

FONTS_DIR = $(CSS_DIR)/default/fonts
DIRS =
SRCS =

install:
	@echo "Installing fonts..."
	@mkdir -p $(FONTS_DIR)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@for i in $(SRCS); do \
		install $$i $(FONTS_DIR); \
	done

clean:
	@echo "Cleaning directory..."
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
