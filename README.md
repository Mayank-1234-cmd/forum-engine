<!--ill be honest i used lots of stackoverflow-->

# forum-engine

a 'forum engine', no sql used

```php
require 'lib.php';
init(); //only used on first try, like a setup or something, makes all dirs
        //using twice deletes everything in the folders (i think)

newuser($username,$password); //creates new user
user_exists($username); // check if user exists
user_exists_wpwd($user,$pwd); //check if user password is $passsword
post($user,$password,$title,$text); //posts a message
viewpost($postname,$postauthor); //views a post
deletepost($postname,$user,$pwd); //deletes a post
commentpost($user,$pwd,$postname,$postauthor,$comment); //comments on a post
readcomments($postname,$author); //reads comments on post
sortposts(); //sorts posts, output is like
/*
Array(
    [0] => Array
        (
            [0] => base64 encoded name of post
            [1] => author of post
            [2] => txt
        )

    [1] => Array
        (
            [0] => 
        )

)*/
```
