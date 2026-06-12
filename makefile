INPUT = semestral

all:
	@echo "Example: make debug input=laboratorio_1"
	@echo "make debug input=laboratorio_4"
	@echo "make debug input=laboratorio_6"
	@echo "make debug input=parcial_1"
	@echo "Diagram: make debug <input>=<dir>"

own:
	su tuzz -c "chown -R tuzz:tuzz $(PWD) && git push origin main"

git_push_file:
	git add -A
	git commit -F ./tmp/.gitmessage.md
	git push origin semestral

search:
	ls /usr/local/www/web1/

debug:
	cd ./$(INPUT) && su tuzz -c 'cd $(PWD)/$(INPUT) && composer dump-autoload -o'
	rm -rf /usr/local/www/web1/*
	cp -R ./$(INPUT)/* /usr/local/www/web1/
	chown -R www:www /usr/local/www/web1/

debug2:
	cd ./$(INPUT) && su tuzz -c 'cd $(PWD)/$(INPUT) && composer dump-autoload -o'
	rm -rf /usr/local/www/web2/*
	cp -R ./$(INPUT)/* /usr/local/www/web2/
	chown -R www:www /usr/local/www/web2/
