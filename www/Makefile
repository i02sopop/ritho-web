TOPDIR?=$(realpath ..)
include $(TOPDIR)/Config.mk

DIRS=classes css img include js templates
SRCS=index.php configuration.php configuration.php.new

build:
	@echo "\\033[1;35m+++ Building up www\\033[39;0m"

install: $(SRCS)
	@echo "\\033[1;35m+++ Installing www\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@mkdir -p $(WWW_DIR)
	@for i in $(SRCS); do \
		cp $$i $(WWW_DIR) ; \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(WWW_DIR)/$$i ; \
	done

configuration.php: configuration.php.new
	@echo "\\033[1;35m+++ Generating configuration\\033[39;0m"
	@cp $< $@
	@perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $@ ;
