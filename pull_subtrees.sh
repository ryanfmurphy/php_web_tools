#!/bin/sh
# this file is part of the Sneeze ORM project
# copyright (c) 2016 Ryan Murphy
# open source license
git subtree pull --prefix db_viewer https://github.com/ryanfmurphy/db_viewer.git master
git subtree pull --prefix dash https://github.com/ryanfmurphy/dash.git master
git subtree pull --prefix orm_router https://github.com/ryanfmurphy/php_orm_router.git master
