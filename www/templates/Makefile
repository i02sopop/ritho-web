TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS  =
SRCS  = 404.html
SRCS += account.html
SRCS += center.html
SRCS += footer.html
SRCS += header.html
SRCS += head.html
SRCS += index.html
SRCS += left.html
SRCS += right.html
SRCS += section.html

install: $(SRCS)	
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(WWW_DIR)/templates
	@for i in $(SRCS); do \
		install $$i $(WWW_DIR)/templates; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/templates/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
