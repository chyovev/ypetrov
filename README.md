# Yosif Petrov website
A website in memory of the famous Bulgarian poet Yosif Petrov containing his works; press articles, videos and photographs of him, as well as other useful information about him.

![Yosif Petrov](public/resources/img/layout/favicon/favicon.png?raw=true)

#### Preview: [https://yosifpetrov.com](https://yosifpetrov.com)


## Back-end development:
- PHP 7.1 to 7.4
- MySQL 5.7
- [Doctrine ORM](#doctrine-orm)
- [PHPMailer library](#phpmailer)
- [Custom MVC](#mvc)
- [Smarty 3.1+](#smarty)

### Doctrine ORM
The general [recommendation](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md) that I stumbled upon while researching was **NOT** to include the `/vendor` folder in the repository, but rather let composer install all dependencies.

Therefore all custom Doctrine files are to be found in the `/resources/doctrine` folder. Still, [Doctrine](https://github.com/doctrine) doesn’t seem to fancy such harsh structural changes, so all classes had to be manually loaded in `autoload_doctrine_classes.php` (at least I couldn’t find a simpler way to do it).
> **NB!** Traits and Interfaces need to be loaded manually beforehand so all other classes which depend on them could actually work.

Doctrine’s mapping is done using `xml` files located in `/resources/doctrine/xml` instead of PHP annotations – I prefer to keep them separated so as to improve readability.

### Doctrine Classes
Apart from the single classes (such as `Gallery` or `PressArticle`), the essential part of this project are the poet’s works. Their content is being shared between the following classes:
- **Book:** all published books of the poet
- **Poem:** a repository of all poems (some poems belong to more than one book)
- **BookContent:** connecting Books with Poems.

Initially, the relation between **Book** and **Poem** was supposed to be of `many-to-many` type. However, such relations can only hold information about the foreign keys of the interconnected classes, but we need to be able to reorder or deactivate certain poems for certain books. Therefore, having other columns in the connecting table [turns it into a class of its own](https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/association-mapping.html#many-to-many-unidirectional) – **BookContent**, so two `one-to-many` relations are needed instead:

- **Book** → one-to-many → **BookContent**
- **Poem** → one-to-many → **BookContent**

Furthermore, most classes can have comments. Comments share the same structure and can consequently be stored in one and the same table using a separate column indicator such as `entity_class`. Unfortunately, Doctrine doesn’t offer a simple way to implement `one-to-many` unidirectional associations with additional static JOIN columns. A possible solution could be to have separate `one-to-many` **Class** → **Comment** associations with separate tables which seems kind of clumsy. 

As a result, comments get stored in the same table, but as a trade-off they must be loaded separately using **CommentRepository** and passing the main entity as parameter. Not the best solution, but it works for now.

> **NB!** Classes which can be commented on should implement the **CommentableInterface**.

### PHPMailer
Currently data is mostly read _from_ the database. The only classes which support dynamical creation of new records are **Comment** and **ContactMessage**, both of which have their own event subscribers which listen for two events:
- **prePersist** – to do some basic validation
- **postPersist** – to send an email notification to the administrators about new messages using the [PHPMailer library](https://github.com/PHPMailer/PHPMailer) (*administrators’ email addresses are stored in a separate table, `configs`*).

> SMTP settings are stored in the `config.php` file having separate declarations for development and production environments. If there’s a mail failure, the end user does not get notified about it – instead, the error gets logged using a simple error loggng class – [Logger](#logger).

### MVC
The project uses some sort of a simplified MVC design pattern inspired by [this](https://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873) article:

- **Models** are found in `/resources/doctrine/src` folders
- **Controllers** are all php files in the `/public` folder
- **Views** are located in `/resources/templates`

**Routes** are declared in the root `.htaccess` file which is not that flexible, but works for smaller projects like this one.

### Smarty
To improve readability in the **Views** part of the MVC architectural pattern, all templates use [Smarty](https://github.com/smarty-php/smarty) PHP template engine.
> **NB!** Since Smarty requires PHP version no greater than 7.4, the same applies to the current project, too.


### Error logging
There’s a simple custom `Logger` class which takes care of logging major (but not fatal) errors which may occur during execution, such as:
- Potential database failure
- Page not found (like trying to access an article marked as inactive)
- AJAX errors
- PHPMailer errors

Error log files have a maximum file size of 10MB. Once this size is reached, a new log file gets created in the same folder having a consecutive number as a suffix preceding the extension: `errors.log`, `errors1.log`, `errors2.log`, etc.

### 301 redirects
Previosly the website has used exclusively URLs written in the Cyrillic script. This is no longer the case (*copy-pasted URLs with cyrillic characters in them are not the prettiest of sights*), so `301 redirects` must be set up for the sake of search engine rankings, but also for the end users’ benefit.

Requests starting with a cyrillic letter are routed via the root `.htaccess` to the redirector which has already loaded up an array with all old addresses and their respective relocations. If the requested URL is present in this array, a 301 redirect to the new address is issued; otherwise error page 404 gets shown.

## Front-end development:
- HTML5
- LESS/CSS3
- jQuery 3.4.1
- [Magnific Popup 1.1.0](https://github.com/dimsemenov/Magnific-Popup) (jQuery library)
- [Swipe 2.3.0](https://github.com/thebird/Swipe) (jQuery library)


The website is responsive with the emphasis being placed on desktop and mobile devices (not so much on tablets). The navigation bar’s position is fixed on scroll, and there’s a hamburger button in the right corner on mobile. All images which are part of the content can be zoomed-in using the **Magnific Popup** plugin, while the **Swipe** plugin is used to browse between images in the Gallery section (*lazy-loading has been implemented to reduce server response time*).

### Ajax 
To improve the overall UX some interactions between the end-user and the client rely on AJAX. For instance, poems within books are loaded asynchroniously (covering both `click` and `popstate` events).

Comments and contact messages are also processed without a page reload, while providing the user with realtime feedback about the request, such as potential validation errors or successful record creation.

### Videos
Videos are embeded using the HTML 5 `<video>` tag. For wider video support across platforms two video sources are offered:
- mp4
- webm

`ffmpeg` was used to deinterlace and trim the source video, as well as to re-encode it to cover for the available formats.

## Local project setup:

```
1. Create database
2. Edit database configuration in `resources/config.php`
3. Execute SQL dump located in `resources/ypetrov-database-structure-dump.sql`
4. Install dependencies using `composer install`
```

> **NB!** Database content is **NOT** featured in this GitHub project.

---------------
## Future development:

A Content Management System (CMS) needs to be implemented at some point. This includes a simple user authentication method, a WYSIWYG text editor and other UI tools which will allow for the content to be altered, edited and rearranged in a user-friendly way.

Additionally, sections may be subsequently added, restructured or removed from the website as more information gets introduced to the project.