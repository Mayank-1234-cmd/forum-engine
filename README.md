<!--ill be honest i used lots of stackoverflow-->

# forum-engine

a 'forum engine', no sql used

```php
require 'lib.php';
init();

newuser($username,$password); //creates new user
user_exists($username); // check if user exists
user_exists_wpwd($user,$pwd); //check if user password is $passsword
post($user,$password,$title,$text); //posts a message
viewpost($postname,$postauthor); //views a post
deletepost($postname,$user,$pwd); //deletes a post
commentpost($user,$pwd,$postname,$postauthor,$comment); //comments on a post
readcomments($postname,$author); //reads comments on post
```