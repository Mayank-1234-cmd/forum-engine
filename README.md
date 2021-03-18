<!--ill be honest i used lots of stackoverflow-->

# forum-engine

a forum engine

NOTICE: In .htacess, you have to manually restrict /path/to/engine/notifs/* because of a security issue.

If you're using attrs for private info too, then restrict /path/to/engine/userattrs/* and /path/to/engine/attrs/*. 

```php
require '/path/to/engine/lib.php';
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
$forum->notify($user,$password,$message);                       //notify user a msg
$forum->clearnotifs($user,$password);                           //clear notifs
$forum->getnotifs($user,$password);                             //read notifs
$forum->setpostattr($postname,$postauthor,$attrname,$attrvalue);//set post attr
$forum->readpostattr($postname,$postauthor,$attrname);          //read post attr
$forum->setuserattr($username,$password,$attrname,$attrvalue);  //set user attr
$forum->readuserattr($username,$password,$attrname);            //read user attr
#1 output
#Array(Array(base64 enc title of post,author of post,txt),Array())
#2 output
#Array(Array(base64 encoded name of post,author of post,txt),Array()
#post attr usage example
function likePost($postname,$postauthor) {
  $likes=$forum->readpostattr($postname,$postauthor,"likes");
  $forum->setpostattr($postname,$postauthor,"likes",$likes+1);
}
function getLikes($postname,$postauthor) {
  return $forum->getpostattr($postname,$postauthor,"likes");
}
```
