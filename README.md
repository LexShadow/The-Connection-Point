# The Connection Point
![Image](https://i.imgur.com/Dxlry4v.png)

## Example
 * **Clearnet** [HERE](https://lexshadow.cribcraft.co.uk/blog) (If this is offline then please submit a Issues), this might also be a few versions above the Git version as this is a live test bed.
* **Tor V3** [HERE](http://i2dny75d77eukohjtzax6tkbbd53lcsnb4g4wrzj3dbpmayxuv4osdqd.onion/?post=2020-12-20-0) (if this is down/offline give it another try, if it don't work a few hours later feel free to submit a Issue) This might be a few versions behind both the Git and **Cleanweb** versions.

* **Tor V2** [HERE](http://g2dv4s6shqictlln.onion/?post=2020-12-20-0) (if this is down/offline give it another try, if it don't work a few hours later feel free to submit a Issue) This might be a few versions behind both the Git and **Cleanweb** versions.


The connection point is a updated versions of [Dead Simple Blog](https://github.com/paintedsky/dead-simple-blog) by paintedsky

I have updated a lot since Dead Simple Blogs, changed the design and started to work on more functions for it, I liked this idea as I did something like
this in the past without markdown, that markdown seems to be a really cool idea, I also have added Font Awesome, ParsedownExta and my own ParsedownExtraFix
that give us Font Awesome, The system is no longer alost one file but this is because few files made more sence been there own files, this as been fully tested
on the tor network, view the demos above.

The Connection point used to be just a little sister of [Dead Simple Blog](https://github.com/paintedsky/dead-simple-blog) but she has grown in to something in it's
own right, there is more to be added in the future around the same ideas I always created things for, tor frendly, slow or no db servers

This blogging system supports custom pages and a comment system to boot, all based on the same ideals clean simple no database, we don't track a thing the user posts
part from the words so we can show them on the screen lol no IP, no browsr data, not even a username is taken.

Other useful things, no JS, easy to use in plain markdown, same system for pages and blogs, in the future comments and more.

## Bells & Whistles
* Bootstrap design.
* None Java Script.
* Blog returns of 8 (config).
* Blog Pagination.
* Comment System. (config)
* Standalone Page Support.
* Font Awesome Support.
* Tor Friendly(Full No Script)
* Database free.
*  -- More to come.

## Scripting/Design Languages
* PHP
* HTML
* CSS

## Installation

Clone the git, Download to Zip will do, unZip and place on your host, edit the settings in the coreserttings.php found in the config folder.

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

## Usage

1. Edit **coresettings.php** in the config folder with your text editor of choice, and change the variables listed above to your liking.
2. To Create a blog create a text file with a NUMERIC file name like so YYYY-MM-DD (e.g. 2018-10-30.txt) for the blogs.
3. To Create a page create a text file with a NUMERIC file name like so NNNN (e.g 0001.txt) for pages
4. Format text files with Markdown, or not. Whatever. ;)
5. If you need to link to image/video/audio/etc. files, you can upload them to the media folder.
6. Upload text files to the "content" directory.
7. You're done!!

## Admin
1. If you have enabled hide mode for comments you open the comments folder and open the YYYY-MM-DD-com file for the page of comments you want
here you then will be able to change Hide to View, under the random number you can also put Lock this will lock the post and you can also put
Admin there also there for making that post stand out as the Admin post.



## Page & Blog Layouts
* The first line of your file is the title line and must be a markdown title so `# Title` will convert in to the title for your link and page/blog
* The second line is the Date and poster information `<small>Posted By: <span style="color:red">Admin</span>, {fas}fa-calendar-alt{/fas} Wednesday 9th {fas}fa-snowman{/fas} December {fas}fa-snowman{/fas} 2020</small>  `
* Optional 3rd line can be used as the back link `[{fas}fa-hand-point-left{/fas} Back](?)`

We would then advice you to start your blog from line **5** or **6**
