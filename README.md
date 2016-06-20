# LitenWiki
Tiny PHP CMS with flatfile storage.

## Features
* Really lightweight
* Question-based captcha against vandalism
* Simple markup system
* Party like it's 1996

## Installation
1. Drop the files into the desired folder on your web server
2. Edit config.php to suit your needs
3. ???
4. PROFIT!!!!

## Syntax
* `# something` - Creates a h1
* `## something` - Creates a h2
* `### something` - Creates a h3
* `_Bold_` - Bold text
* `|Italic|` - Italic text
* `----` - hr line. More dashes can also be used
* `[[Somepage]]` - Internal link
* `[[Somepage|Alternate name here]]` - Internal link with alias name
* `[[%STATS:PAGES]]` - Turns into pagecount

Lists work like so:
```
/**
** First item
** Second item
** etc.
**/
```