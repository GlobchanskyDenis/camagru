#!/bin/bash
mysql -u root < setup.sql
php setup.php
