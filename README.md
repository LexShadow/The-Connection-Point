# The Connection Point
![Image](https://i.imgur.com/Dxlry4v.png)

## Example
https://lexshadow.cribcraft.co.uk/blog (If this is offline then please submite an Issues)


The connection point is a updated versions of [Dead Simple Blog](https://github.com/paintedsky/dead-simple-blog) by paintedsky

I have updated the a few things, changes the design and started to work on a few more functions for it, I liked this idea as I did something like
this in the past without markdown, that markdown seems to be a really cool idea, I also have added Font Awesome, ParsedownExta and my own ParsedownExtraFix
that give us Font Awesome.

This version also supports the very same formatting for pages, some of the changes are not ready but I am working as best I can on it, I am wanting this for my own
random blog what I want to use via Tor so this will help me keep this project going.

Other useful things, no JS, easy to use in plain markdown, same system for pages and blogs, in the future comments and more.


## Installation

Clone the git, Download to Zip will do, unZip and place on your host, edit the settings in the index page at the top
in the define section.

## Understanding Parsedown and Exta
[Parsedown](https://parsedown.org/demo)  
[ParsedownExtra](https://parsedown.org/extra/)  

With our ParsedownExtraFix we also have Tick Box's themed.


## Using Font Awesome Tags
You can use the normal html way Font Awesome shows you or you can use `{fas}fa-iconname{/fas}` and this will also convert
it to Font Awesome for you, Titles also support this.

# Changeable Settings 
View our site version [View](https://lexshadow.cribcraft.co.uk/blog/?post=0003.txt)
Some of the settings are yet to fully be used and are there as placeholders, some settings wont be listed here yet
all useable settings are below.
## Used Settings
* **SITE_TITLE**: This is the site name what is shown in the tab, and on the main part of the page at the top
edit this to what ever you would like. ~ this is also used for the copyright
* **STIE_SUB_TITLE** This is the little message under the main title

## Usage

1. Edit **index.php** with your text editor of choice, and change the variables listed above to your liking.
2. To Create a blog create a text file with a NUMERIC file name like so YYYY-MM-DD (e.g. 2018-10-30.txt) for the blogs.
3. To Create a page create a text file with a NUMERIC file name like so NNNN (e.g 0001.txt) for pages
4. Format text files with Markdown, or not. Whatever. ;)
5. If you need to link to image/video/audio/etc. files, you can upload them to the media folder.
6. Upload text files to the "content" directory.
7. You're done!!



## Page & Blog Layouts
* The first line of your file is the title line and must be a markdown title so `# Title` will convert in to the title for your link and page/blog
* The second line is the Date and poster information `<small>Posted By: <span style="color:red">Admin</span>, {fas}fa-calendar-alt{/fas} Wednesday 9th {fas}fa-snowman{/fas} December {fas}fa-snowman{/fas} 2020</small>  `
* Optional 3rd line can be used as the back link `[{fas}fa-hand-point-left{/fas} Back](?)`

We would then advice you to start your blog from line **5** or **6**
