TOPDIR?=../..
include $(TOPDIR)/Config.mk

DIRS=
SRCS=index.txt openssl.cnf serial

install: build
	@echo "\\033[1;35m+++ Installing SSL\\033[39;0m"
	@mkdir -p $(SSL_DIR)/priv/
	@for i in $(DIRS) ; do $(MAKE) -C $$i install ; done
	@cp $(SRCS) $(SSL_DIR)
	@for i in $(SRCS); do \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(SSL_DIR)/$$i ; \
	done
	@+make ssl-cert
	@echo "\\033[1;35m+++ SSL installed\\033[39;0m"

build:
	@echo "\\033[1;35m+++ Building up SSL\\033[39;0m"
	@for i in $(DIRS) ; do $(MAKE) -C $$i build ; done
	@echo "\\033[1;35m+++ SSL built\\033[39;0m"

.PHONY=ssl-cert
ssl-cert: $(SSL_CERT)

$(SSL_KEY):
	openssl genrsa -out $@ 4096

$(SSL_CERT): $(SSL_KEY)
	openssl req -new -x509 -days 365 -key $< -out $@ -config $(SSL_CONFIG) -batch -subj /C=ES/ST=Madrid/O=Ritho/localityName=Madrid/commonName=$(HOST)/emailAddress=$(SUPPORT_EMAIL)/
