DATE=$(shell date +%I:%M%p)
CHECK=\033[32m✔\033[39m
HR=\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#


#
# BUILD MATTHEWDUNHAM.COM
#

build:
	@echo "\n${HR}"
	@echo "Building MatthewDunham.com..."
	@echo "${HR}\n"
	@jshint js/*.js --config js/.jshintrc
	@echo "Running JSHint on javascript...             ${CHECK} Done"
	-@rm app/webroot/js/compiled.js
	-@rm app/webroot/js/compiled.min.js
	-@rm app/webroot/css/styles.css
	-@rm -rf app/webroot/images
	-@rm -rf app/webroot/js/vendor
	@echo "Cleaning build directory...       ${CHECK} Done"
	-@mkdir app/webroot/images
	-@recess --compile less/styles.less > app/webroot/css/styles.css
	@echo "Compiling LESS with Recess...               ${CHECK} Done"
	@cp -r images/* app/webroot/images
	@cat js/*.js > app/webroot/js/compiled.js
	@cp -r js/vendor app/webroot/js
	@uglifyjs -nc app/webroot/js/compiled.js > app/webroot/js/compiled.min.js
	@echo "Compiling and minifying javascript...       ${CHECK} Done"
	@echo "\n${HR}"
	@echo "MatthewDunham successfully built at ${DATE}."
	@echo "${HR}\n"
	@echo "I <3 Build Scripts\n"
