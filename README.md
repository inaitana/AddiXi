# AddiXi
AddiXi is a CMS for e-commerce, developed between 2010 and 2011, when there weren't many feature-rich alternatives. It was my first project with Zend Framework.

## Demo
An online demo will soon be available.

## Branches
The project has two branches, for two different versions:
- **production**: the last version that was used in production, for websites some of which are still active to this day;
- **development**: the latest developed version, with some new features that were never sent into production. 
 
## Project structure
The project had a modular structure, through the use of custom loader plugins, when Zend Framework didn't yet have a native modules implementation:
- **admin**: the administrative backend module, for catalog (products and categories), customers and orders management;
- **frontend**: the generic frontend module, with catalog and informative pages;
- **user**: the user area module, with account management and orders status;
- **common**: for common featerus used by all areas.

### Features
Though outdated and never completed, AddiXi had some features which were not that common at the time of its writing:

- **Deep linking** through *url hashing*, both in the backend and in the frontend, with ajax rich menus fully tracked and reachable through custom urls;
- **Page caching** of all generic frontend pages (catalog and product pages), and **data cache** of most used data from the database, through custom Zend Cache helpers and plugins;
- **Automatic base64 thumbnail** generation on images upload, with customizable sizes;
- **Sitemap ping** automation, for multiple search engines, both automatic and on request;
- **CKEditor** and **KCFinder** integration for rich html editing and media management;
- **AddThis** integration for social sharing;

#### PayPal integration
AddiXi never reached contextual payment on the website, but for a client a "Buy with PayPal" button was integrated in the confirmation email for the customer. The code is not included here since it was specific, mostly static and non-configurable.

##### Language
The project was developed for the Italian market, so all the static texts, comments and many variable names are in Italian.

### Database
The database was already designed for many future features the project was going to have, so it includes various tables which are empty and never referenced in the code.

# Other libraries
A few modular libraries were implemented for this project, and some were later fully or partially reused in other projects.

## Cron
With custom helpers and plugins, and a cli launcher, the system managed cronjob classes wich extended an abstract, with scheduling managed on the database. Some activities usually managed with cronjobs were bulk mail sending, thumbnails cleanup and synchronization, search engines sitemap ping.

## MicOffMenu
MicOffMenu, also known as MoM, was a set of components (tab, button, combobox, datepicker, checkbox, radio, select etc...) aimed at implementing a form-rich menu interface, all with deep linking, with a similar look&feel to a well known office automation suite.

## xTooltip
xTooltip was a custom jQuery plugin, implementing classic tooltips, image previews and ajax-loaded rich tooltips.
