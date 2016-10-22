[![Code Climate](https://codeclimate.com/github/ValeriaVG/eselcms/badges/gpa.svg)](https://codeclimate.com/github/ValeriaVG/eselcms) [![Build Status](https://travis-ci.org/ValeriaVG/eselcms.svg?branch=master)](https://travis-ci.org/ValeriaVG/eselcms) [![Test Coverage](https://codeclimate.com/github/ValeriaVG/eselcms/badges/coverage.svg)](https://codeclimate.com/github/ValeriaVG/eselcms/coverage)

#Esel - PHP 5.4+ TDD based atomic content management system

> Everything should be made as simple as possible, but not simpler - *Albert Einstein*

*Because there isn't anything like __THIS__ one.*

All others are full of features that may be used one day, just like my mother's room.

### Why is it bad?

1.  **It makes things much more complicated both for developers and CMS user:** You spend days, months trying to master a CMS, but being sure you'd do it faster in vanilla PHP

2.  **It's perfomance is much lower than it should be:** Not much to add: CPU and DB are working hard to maintain unused features

3.  **It's practically impossible to test automatically:** Most of the popular systems were made way before unit-tests turned to be mainstream. And its much easier to create well-tested app from ground up than cover existing one made without TDD

4.  **It's not secure:** In most cases it's so complex and huge that you will not be able to predict vulnerabilities before you bump into it.

So what Esel offers?
--------------------

1.  **It always suits needs of your project:** Basically Esel doesn't even needs admin panel!

2.  **It can be extended to ANYTHING** Module system allows you to bring any functionality you need.

3.  **It is DRY:** Make a module once and reuse it anytime

4.  **It's made using (T)est (D)riven (D)evelopment:** So you can be sure your brand new module broke nothing!

5.  **Is uses database as it supposed to be used:** No more bloated databases! Content is stored in **FILES** and database has it's index if it's needed for search!:astonished:

6.  **It's secure** :ok_hand: Esel uses [Twig](http://twig.sensiolabs.org/) as it's template engine and [Idiorm](https://github.com/j4mie/idiorm) as it's database layer. Also every module is checked before loading to prevent code injections. :bangbang:

Now to the bad part: Esel is currently under development
--------------------------------------------------------

Current stage: *Alpha*

Roadmap
=======
- [x] Core functionality
  - [x] Unit testing setup
  - [x] Template support
  - [x] Routing
  - [x] Web testing setup
  - [x] Redirects
  - [x] Modules system
  - [x] Database support

- [ ] Modules
  - [x] Files paginator
  - [ ] Admin panel
    - [x] Resources tree
    - [x] CRUD pages
    - [ ] CRUD templates
    - [x] Custom widgets support
  - [ ] Events
  - [x] Mailer
  - [x] Web actions
  - [ ] Goods
  - [ ] Gallery
  - [ ] Cart
  - [ ] Filter
  - [ ] Search
  - [ ] Multi-language

- [ ] Installer
- [ ] Documentation


*But stay tuned! It won't take long!*

Any help is appreciated, BTW :yum:
