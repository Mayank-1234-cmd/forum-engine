<!--ill be honest i used lots of stackoverflow-->

# forum-engine

a forum engine

```php
require 'lib.php';
$forum=new forumengine();
$forum->init(); //only used on first try, like a setup or something, makes all dirs
        //using twice deletes everything in the folders (i think)

$forum->newuser($username,$password);                           //creates new user
$forum->user_exists($username);                                 // check if user exists
$forum->user_exists_wpwd($user,$pwd);                           //check if user password is $passsword
$forum->userchangepwd($user,$newpwd,$oldpwd);                   //change password
$forum->post($user,$password,$title,$text);                     //posts a message
$forum->viewpost($postname,$postauthor);                        //views a post
$forum->deletepost($postname,$user,$pwd);                       //deletes a post
$forum->commentpost($user,$pwd,$postname,$postauthor,$comment); //comments on a post
$forum->readcomments($postname,$author);                        //reads comments on post
$forum->sortposts();                                            //sorts posts, output is at comment #2
$forum->searchposts($keyword);                                  //searches posts, output is at comment #1
#1 output
#Array(base64 enc title of post,author of post,txt),Array())
#2 output
#Array(Array(base64 encoded name of post,author of post,txt),Array()

```
