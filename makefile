all:
	@echo "Example: make debug input=laboratorio_1"
	@echo "make debug input=laboratorio_4"
	@echo "make debug input=laboratorio_6"
	@echo "make debug input=parcial_1"
	@echo "Diagram: make debug <input>=<dir>"

own:
	su tuzz -c "chown -R tuzz:tuzz $(PWD) && git push origin main"

git_debug_file:
	git add -A
	git commit -F ../.gitmessage
	git push origin debug

git_debug:
	git add -A
	git commit -m "update"
	git push origin debug	

debug:
	cd ./$(input) && su tuzz -c 'cd $(PWD)/$(input) && composer dump-autoload -o'
	rm -rf /usr/local/www/web1/*
	cp -R ./$(input)/* /usr/local/www/web1/
	chown -R www:www /usr/local/www/web1/

debug2:
	cd ./$(input) && su tuzz -c 'cd $(PWD)/$(input) && composer dump-autoload -o'
	rm -rf /usr/local/www/web2/*
	cp -R ./$(input)/* /usr/local/www/web2/
	chown -R www:www /usr/local/www/web2/
