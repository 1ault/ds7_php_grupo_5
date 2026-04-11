all:
	@echo "Example: make debug input=laboratorio_1"
	@echo "Diagram: make debug <input>=<dir>"

debug:
	cd ./$(input) && composer dump-autoload -o
	rm -rf /usr/local/www/caddy/*
	cp -R ./$(input)/* /usr/local/www/caddy/
	chown -R www:www /usr/local/www/caddy/
