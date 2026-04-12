all:
	@echo "Example: make debug input=laboratorio_1"
	@echo "Diagram: make debug <input>=<dir>"

own:
	su tuzz -c "chown -R tuzz:tuzz $(PWD)"

debug:
	cd ./$(input) && su tuzz -c 'cd $(PWD) && composer dump-autoload -o'
	rm -rf /usr/local/www/caddy/*
	cp -R ./$(input)/* /usr/local/www/caddy/
	chown -R www:www /usr/local/www/caddy/
