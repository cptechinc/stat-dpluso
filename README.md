# Welcome to Distribution Plus Online / Dpluso

This document is in Markdown.

DPLUS CRM / SALES PORTAL is powered by Processwire read more at
https://github.com/processwire/processwire/blob/master/README.md


## Table of Contents

1. [About](#about-distribution-plus-crm)
2. [Installation](#installing-dplus-crm)
3. [Create Issue Requests](#issues)
4. [Developer](#developer)


## About Distribution Plus Dpluso
DPluso is a PHP based browser front-end for Distribution Plus.
Starting off as a tool for Salesreps, The software allows them to handle many aspects of their Day to Day

1. CRM 
   We give the user the ability to Manage their Customers view sales data about their customer and
review Quotes and Sales Orders. The User can also make notes and tasks regarding that customer.
User can also run reports, some which are formattable, that give them further insight into their customer such as sales history,
standing orders, open invoices etc.

2. Ordering
   Users can also create orders and quotes for their customers. They can add items to a cart and then decide if the items 
need to be saved a quote or proceed with a sales order. The user can also can edit existing sales orders and quotes if they have permissions.

3. Item Info
   Users can also can gain insight on the items they sell, run some formattable reports. They can view stock info, view sales history, view what lots the items is contained in.
   
   
4. Vendor Info



## Installing DPluso
1. Get the most recent copy of DPlus CRM on the [Soft Server](http://192.168.1.2/dpluso/)
and log in to the Processwire, click on Setup -> Export. Name it dpluso, get a zip/file download, leave the config properties checked.
2. Prepare the new server with directories each Dplus CRM installation needs these directories:
    * /var/www/html/orderfiles/
    * /var/www/html/files/
    * /var/www/html/files/json/
    * /var/www/html/data#  <- # should be the company # that the salesportal will be installed for
    * /var/www/html/dpluso/ <- default salesportal directory
3. Download Processwire from [Processwire](https://processwire.com/download/)
    * If this a new installation no others have been made, use the 3.0 master link
    * If not or if you want a for sure installation, use 2.8
    * Download the zip open it up, inside it stick the dpluso/ zip download stick the bottom most site-dpluso directory in the processwire directory which has other site-*
    * Upload the directory under processwire*master to /var/www/html/dpluso/
4. Install DPlus CRM
    * Go to that server IP or address with dpluso/ added to the url.
    * Go through the Processwire installation, choose site-dpluso as the profile
    * Follow the steps use cptecomm login as the database credentials, name the database dpluso
    * For the admin link use manage, and the color scheme warm
    * For the admin credentials use the rcapsys login

## Issues
* Issues can be reported in our [github](https://github.com/cptechinc/soft-crm/issues)
* You can also include emojis on your issues https://gist.github.com/rxaviers/7360908
* Include screenshots if you can, and then we can issue fixes based on those issues and track issues

## Developer
 For git commits use this [guide](https://github.com/sparkbox/standard/tree/master/style/git)

## Domains & Subdomains
* For domain access to work properly you must add the domain / subdomain to the `$config->httpHosts` array.




------

Copyright 2017 by CPTech
