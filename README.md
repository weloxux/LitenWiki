# LitenWiki
Tiny PHP CMS with flatfile storage.

## Features
* Really lightweight
* Question-based captcha against bots
* Simple markup system
* Templates!
* Party like it's 1996

## Installation
1. Drop the files into the desired folder on your web server
2. Move or copy config.example.php to config.php
3. Edit config.php to suit your needs
4. ???
5. PROFIT!!!!

## Syntax
* `# something` - Creates a h1
* `## something` - Creates a h2
* `### something` - Creates a h3
* `_Bold_` - Bold text
* `|Italic|` - Italic text
* `----` - hr line. More dashes can also be used
* `[[Somepage]]` - Internal link
* `[[Somepage|Alternate name here]]` - Internal link with alias name
* `{{X}}` - Insert template with name X

Lists work like so:
```
/**
** First item
** Second item
** etc.
**/
```
