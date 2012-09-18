MatthewDunham.com
=================

This is the personal web site of Matthew Dunham a software developer that has a passion for building solid web applications. Please enjoy browsing the source code, and if you like me send me an email.


Quick start
-----------

Clone the repo, `git clone git@github.com:mdunham/MatthewDunham.com.git`, or [download the latest release](https://github.com/mdunham/MatthewDunham.com/zipball/master).


Versioning
----------

For transparency and insight into our release cycle, and for striving to maintain backward compatibility, MErcury will be maintained under the Semantic Versioning guidelines as much as possible.

Releases will be numbered with the follow format:

`<major>.<minor>.<patch>`
 
And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bumps the patch

For more information on SemVer, please visit http://semver.org/.


Developers
----------

We have included a makefile with convenience methods for working with the Mercury library.

+ **dependencies**
The makefile requires you have the following: recess, uglifyjs, and jshint. To install these locally, `cd` to the base directory in terminal, and run:

```
$ npm install
```

+ **build** - `make`
Runs the recess compiler and other tools to compile the sources into production.


Copyright and license
---------------------

Copyright 2012 MatthewDunham.com all rights reserved.


	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


