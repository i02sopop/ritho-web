TOPDIR?=..
include $(TOPDIR)/Config.mk

SRCS=apache2-vhosts.conf apache2.conf postgresql.conf

install: build
	mkdir -p $(BUILD_DIR)/conf
	cp $(SRCS) $(BUILD_DIR)/conf/
	@for i in $(SRCS); do \
		perl -i -pne 's/%(\w+?)%/$$ENV{$$1}/ge' $(BUILD_DIR)/conf/$$i ; \
	done
	mkdir -p $(BUILD_DIR)/conf/ssl/priv
	cp $(SSL_CERT) $(SERVER_CRT)
	cp $(SSL_KEY) $(SERVER_KEY)

build: $(SSL_CERT) $(SSL_KEY)

$(SSL_KEY):
	openssl genrsa -out $@ 4096

$(SSL_CERT): $(SSL_KEY)
	openssl req -new -x509 -days 365 -key $< -out $@ -config $(SSL_CONFIG) -batch -subj /C=ES/ST=Madrid/O=Ritho/localityName=Madrid/commonName=$(HOST)/emailAddress=$(SUPPORT_EMAIL)/
