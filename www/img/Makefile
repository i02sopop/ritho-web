TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS=
SRCS  = agplv3-155x51.png
SRCS += agplv3-88x31.png
SRCS += not-fd.svg

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(IMG_DIR)
	@install $(SRCS) $(IMG_DIR)

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
