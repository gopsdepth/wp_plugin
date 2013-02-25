=== Simple youtube search include by gopsdepth ===
Contributors: gopsdepth
Donate link: http://satjapotport.co.nf/
Tags: youtube,post
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple youtube search include plug-in is a short-code plug-in that will help you add youtube video with search terms.

== Description ==

= Overview =
Simple youtube search include plug-in is a short-code plug-in that will help you add youtube video with search terms.

= How to use =
At Version 1.0.0, I have 2 ways you can choose.

1. Simple to use, you just add this short-code
`[f2c_youtube_inc s="search terms" static="no"]`
*** 's' attribute is required attribute.*
*** 'static="no"' value will force plugin don't update a youtube video on your post, that's mean it will be get new one every time.

2. Use F2C Youtube buttons to add short-code.

= Attributes =
**s**      => search terms. Required. eg. s="batman return"

**static** => indicate a static youtube video. Set "no" only for non-static youtube video. Default is 'yes'. eg. static="no"

**output** => indicate an output. "shortcode" is youtube video or "url" is link string. Default is 'shortcode'. eg. output="url"


= How it works =
The plug-in will get your search terms form "s" attribute and send them to youtube search page then it retrieves first video link in search list. After it get the link, it will create a wordpress youtube short-code so you can use wordpress youtube short-code attributes such as width, height, etc.

When your user see a content of post, plug-in will update your post with static youtube video embed tag.

== Installation ==

1. Connect to WordPress Admin Panel
2. Click the "Plugins" menu on the left and choose "Add New". Search for “Simple youtube search include by gopsdepth” and install it.
3. Activate the plugin through the 'Plugins' menu in WordPress 
4. Place `[f2c_youtube_inc s="search terms"]` to any posts you want.

== Frequently asked questions ==

Now, no question.

== Screenshots ==

1. Place short-code following a screen shot.
2. Youtube video will automatically show without indicating a url.
3. You can use static and none-static button to add short-code. If you select a text then click button, that text will become search-term automatically.

== Changelog ==

= 1.0.0 =
* Add 2 short-code buttons on the editor.

= 0.1.1 =
* Bug fixed.
* Improve youtube search method.
* Add more attribites
	=> "static" - Set it "no" to not save youtube on post. eg. [f2c_youtube_inc s="xxx" static="no"]
	=> "output" - Set it "shortcode" for showing video(Default) or "url" for showing link to the youtube video.
* Add function "f2c_youtube_simple_data" for developer. This function will return an array of id, title, link.
* Automatically update shortcode to embed tag when content shown. This will save your youtube video as static.

== Upgrade notice ==
= 1.0.0 =
More easy to use. I already have add short-code buttons to the editor. You just select your text as search term and click my button.

= 0.1.1 =
In previous version, some search text can't retrieve a youtube video so I improve a search method.
Moreover, youtube video change every time that it's shown so I make it to be static youtube video. 