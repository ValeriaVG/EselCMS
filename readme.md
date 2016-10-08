[![Code Climate](https://codeclimate.com/github/ValeriaVG/eselcms/badges/gpa.svg)](https://codeclimate.com/github/ValeriaVG/eselcms)

[![Test Coverage](https://codeclimate.com/github/ValeriaVG/eselcms/badges/coverage.svg)](https://codeclimate.com/github/ValeriaVG/eselcms/coverage)

#Esel - PHP 5.4+ TDD based atomic content management system

> Everything should be made as simple as possible, but not simpler - *Albert Einstein*

##Why yet another CMS?!

*Because there isn't anything like __THIS__ one.*

All of the rest are full of features that may be used one day, just like my mother's room.

###Why is it bad?

1. __It makes things much more complicated for both developers and CMS users.__
   Days and months you'll spend trying to master a CMS, being sure you'd do it faster in vanilla PHP

2. __It's perfomance is much lower than it should be__
   Not much to add: CPU and DB are working hard to maintain unused features

3. __It's practically impossible to test automatically__
   Most of the popular systems were made way before unit-tests became a mainstream.
   And its much easier to create well-tested app from ground up than cover existing one made without TDD

4. __It's not secure__
   In most cases it's so complex and huge that you will not be able to predict vulnerabilities before you bump into it.

##So what Esel offers?

1. __It always suits needs of your project__
   Basically Esel doesn't even needs admin panel!

2. __It can be extended to ANYTHING__
   Module system allows you to bring any functionality you need

3. __It is DRY__
  Make a module once and reuse it anytime

4. __It's made using (T)est (D)riven (D)evelopment__
  So you can be sure your brand new module broke nothing!

5. __Is uses database as it suppose to be used__
  No more bloated databases! Content is stored in **FILES** and database has it's index if it's needed for search!:astonished:

6. __It's secure__ :ok_hand:
  Esel uses [Twig](http://twig.sensiolabs.org/) as it's template engine and [Idiorm](https://github.com/j4mie/idiorm) as it's database layer. Also Esel has super global variables escaping via `sl::_get("name")`, `sl::_session('name')` etc. And every module is checked before loading to prevent code injections. :bangbang:

##Now to the bad part: Esel is currently under development

Current stage: *Alpha*

- [x] Core: unit testing setup
- [x] Core: template support
- [x] Core: routing
- [x] Core: web testing setup
- [x] Core: redirects
- [x] Core: modules system
- [x] Core: database support

- [ ] Module: admin panel
- [ ] Module: mailer
- [x] Module: web actions
- [ ] Module: goods
- [ ] Module: filter
- [ ] Module: search


*But stay tuned! It won't take long!*

Any help is appreciated, BTW :yum:
