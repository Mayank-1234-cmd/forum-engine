<?php
//updates needed
//l84 need bbcode parser, do bbcode(htmlspecialchars($whatevergoeshere))
//l52,64 prevent encoding attack
//stackoverflow
class forumengine {
   function clean($string) {
      $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
      return preg_replace('/-+/', '', $string);
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
     mkdir("notifs");
     mkdir("attrs");
     mkdir("userattrs");
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
   function userchangepwd($user,$newpwd,$oldpwd) {
     $pwdh=hash("sha512",$newpwd);
     $cluser=clean($user);
     if (user_exists_wpwd($user,$oldpwd)) {
       file_put_contents("passwordmgr/passwords/$cluser.$pwdh.txt","");
       file_put_contents("passwordmgr/users/$cluser","");
     } else {
       ;
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
   function sortposts() {
     $output=array();
     $o1=explode("\n",shell_exec("ls posts -t | grep \\.txt"));
     foreach ($o1 as &$o2) {
       $o3=explode(".",$o2);
       array_push($output,$o3);
     }
     print_r($output);
   }
   function searchposts($keyword) {
     $sanitkeyword=escapeshellarg($keyword);
     $posts=explode("\n",shell_exec("ls -r posts | grep \\.txt | grep $sanitkeyword"));
     $output=array();
     foreach ($posts as &$post) {
       $post=explode(".",$post);
       array_push($output,$post);
     }
     print_r($output);
   }
   //end posts
   //start notifs - NOTE: you have to manually edit .htacess to restrict directory /path/to/engine/notifs/*
   //               due to security issues that idk how to fix without a db
   function notify($user,$pwd,$msg) {
     $cluser=clean($user);
     $pwdh=hash('sha512',$pwd);
     $file="notifs/".$cluser.".".$pwdh.".txt";
     if (user_exists_wpwd($user,$pwd)) {
       file_put_contents($file,"$msg\n".file_get_contents($file));
       return true;
     } else {
       return false;
     }
   }
   function clearnotifs($user,$pwd) {
     $cluser=clean($user);
     $pwdh=hash('sha512',$pwd);
     $file="notifs/".$cluser.".".$pwdh.".txt";
     if (user_exists_wpwd($user,$pwd)) {
       file_put_contents($file,"");
       return true;
     } else {
       return false;
     }
   }
   function getnotifs($user,$pwd) {
     $cluser=clean($user);
     $pwdh=hash('sha512',$pwd);
     $file="notifs/".$cluser.".".$pwdh.".txt";
     if (user_exists_wpwd($user,$pwd)) {
       return file_get_contents($file);
     } else {
       return false;
     }
   }
   //end notifs
   //start attrs

   function setpostattr($postname,$postauthor,$attrname,$attrvalue) {
     $cluser=clean($postauthor);
     // $pwdh=hash('sha512',$pwd);
     $titleb64=base64_encode($postname);
     // $file="notifs/".$cluser.".".$pwdh.".txt";
     if (user_exists($postauthor)) {
       file_put_contents("attrs/$titleb64.$postauthor.$attrname.txt",$attrvalue);
     }
   }
   function readpostattr($postname,$postauthor,$attrname,$attrvalue) {
     $cluser=clean($postauthor);
     // $pwdh=hash('sha512',$pwd);
     $titleb64=base64_encode($postname);
     // $file="notifs/".$cluser.".".$pwdh.".txt";
     if (user_exists($postauthor)) {
       return file_get_contents("attrs/$titleb64.$postauthor.$attrname.txt",$attrvalue);
     } else {
       return false;
     }
   }
   function setuserattr($username,$password,$attrname,$attrvalue) {
     $cluser=clean($username);
     // $pwdh=hash('sha512',$pwd);
     $titleb64=base64_encode($postname);
     if (user_exists_wpwd($cluser,$password)) {
       file_put_contents("userattrs/$username.$attrname.txt",$attrvalue);
       return true;
     } else {
       return false;
     }
   }
   function readuserattr($username,$password,$attrname,$attrvalue) {
     $cluser=clean($username);
     // $pwdh=hash('sha512',$pwd);
     $titleb64=base64_encode($postname);
     if (user_exists_wpwd($cluser,$password)) {
       return file_get_contents("userattrs/$username.$attrname.txt",$attrvalue);
       // return true;
     } else {
       return false;
     }
   }
   //end attrs
}
