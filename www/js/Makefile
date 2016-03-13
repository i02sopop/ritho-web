TOPDIR?=$(realpath ../..)
include $(TOPDIR)/Config.mk

DIRS  =
SRCS  = jquery.min.js
SRCS += modernizr.min.js
SRCS += ritho.min.js
SRCS += less.min.js
CHECK_JS = ritho.js

check-js: $(CHECK_JS)
	@for i in $(CHECK_JS); do \
		jshint $$i --config .jshintrc ; \
	done
	@echo "Running JSHint on javascript...             ${CHECK} Done"

%.min.js: %.js
	@+make check-js
	@java -jar $(LIB_DIR)/yuicompressor-2.4.7.jar --type js -o $@ $<
	@echo "Compiling and minifying javascript...       ${CHECK} Done"

install: $(SRCS)
	@for i in $(DIRS) ; do $(MAKE) -C $$i install; done
	@mkdir -p $(JS_DIR)
	@for i in $(SRCS); do \
		install $$i $(JS_DIR); \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(JS_DIR)/$$i; \
	done

clean:
	@for i in $(DIRS) ; do $(MAKE) -C $$i clean; done
	@rm -f *.min.js
