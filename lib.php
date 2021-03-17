<?php
//updates needed
//l84 need bbcode parser, do bbcode(htmlspecialchars($whatevergoeshere))
//l52,64 prevent encoding attack
//stackoverflow
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function startsWith( $haystack, $needle ) {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}
//end stackoverflow
//start init
function init() {
  mkdir("passwordmgr");
  mkdir("passwordmgr/passwords");
  mkdir("passwordmgr/users");
  mkdir("post-comments");
  mkdir("posts");
  return true;
}
//end init
//start users
function newuser($user,$pwd) {
  $pwdh=hash("sha512",$pwd);
  $cluser=clean($user);
  if (file_exists("passwordmgr/users/$cluser")) {
    return false;
  } else {
    file_put_contents("passwordmgr/passwords/$cluser.$pwdh.txt","");
    file_put_contents("passwordmgr/users/$cluser","");
    return true;
  }
}
function user_exists($user) {
  $cluser=clean($user);
  if (file_exists("passwordmgr/users/$cluser")) {
    return true;
  } else {
    return false;
  }
}
function user_exists_wpwd($user,$pwd) {
  $cluser=clean($user);
  $pwdh=hash("sha512",$pwd);
  if (file_exists("passwordmgr/passwords/$cluser.$pwdh.txt")) {
    return true;
  } else {
    return false;
  }
}
//end users
//start posts
function post($user,$password,$title,$text) {
  if (user_exists_wpwd($user,$password)) {
    file_put_contents("posts/".base64_encode($title).".".clean($user).".txt",$text);
    // user abc make post "This is an encoded string",asdfg
    // file VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==.abc.txt
    // contains 'asdfg'
    return true;
  } else {
    return false;
  }
}
function viewpost($postname,$postauthor) {
  echo file_get_contents("posts/".base64_encode($postname).".".clean($postauthor).".txt");
}
function deletepost($postname,$user,$pwd) {
  if (user_exists_wpwd($user,$password)) {
    unlink("posts/".base64_encode($postname).".".clean($user).".txt");
    return true;
  } else {
    return false;
  }
}
// last one - commenting on post
function commentpost($user,$pwd,$postname,$postauthor,$comment) {
  if (user_exists_wpwd($user,$pwd)) {
    $fdir="post-comments/".base64_encode($postname).".".clean($postauthor).".txt";
    $template="<br/><hr/>$user: $comment";
    file_put_contents($fdir,file_get_contents($fdir).$template);
    return true;
  } else {
    return false;
  }
}

function readcomments($postname,$author) {
  $fdir="post-comments/".base64_encode($postname).".".clean($author).".txt";
  return file_get_contents($fdir);
}
//end posts
