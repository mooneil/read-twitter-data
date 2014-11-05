<?php
/**
 * Created by PhpStorm.
 * User: Mohammadhassan Khodashahi
 * Email: MH.khodashahi@gmail.com
 * Date: 10/9/2014
 * Time: 6:27 PM
 */
require "DBconnect.php";
date_default_timezone_set("Europe/Berlin");

if(isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'show_user' :
            show_user();
            break;
        case 'add_hob' :
            add_hob();
            break;
        case 'add_users' :
            $name=($_GET['name']);
            $id=($_GET['id']);
            add_twitter(-1,$name,$id);
            break;
    }
}

/*
 * add new twitter account into DB
 */
function add_hob(){
    $name=($_GET['name']);
    $location=($_GET['location']);
    $description=($_GET['description']);
    $link=dbconnect();
    echo $query="INSERT INTO `hobs` (`name`, `location`, `description`) VALUES ('".$name."', '".$location."', '".$description."');";
    mysql_query($query);
    mysql_close($link);
}
/*
 * Show followers of twitter account
 */
function show_user()
{
    $hob=trim($_GET['hob']);
    $link=dbconnect();
    $query="select *  from users where hobid like '".$hob."' order by followers_count DESC";
    $result=mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        echo "<div  class='col-lg-3 list-group-item' style='height:100px;'>
                <div class='col-lg-3'>
                    <img src='".$row['profile_image_url']."' width='auto'/>
                    </div>
                    <div class='col-lg-9'>
                    <a href='http://www.twitter.com/".$row['screen_name']."' target='_blank'><h5>Name: ".$row['name']."</h5></a>
                    <p>Followers: ".$row['followers_count']."</p>

                </div>
                </div>";
    }
}
/*
 * Show All twitter account
 */
function hobs(){
    $link=dbconnect();
    $query="select * from hobs";
    $result=mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        $count=user_count($row['id']);
        echo "<div class='list-group-item col-lg-3'>
                <a  onclick='show_user(\" ".$row['id']."\" )";
                    echo "'>
                    <h5>Hob Name: ".$row['name']."</h5>
                    <p>Followers: ";
        echo $count;
        echo"</p>
                </a>
                <button type='submit' class='btn btn-success' onclick='add_users(".$row['id'].",\"".$row['name']."\")'>Add Followers</button>
                </div>";
    }
    //mysql_close($link);
}

function user_count($hob){
    $link=dbconnect();
    $query="select count(*) as count from users where hobid like '".$hob."'";
    $result=mysql_query($query);
    $row = mysql_fetch_assoc($result);
    mysql_close($link);
    return $row['count'];
}
//function for sql injection
function sanitize($data){
    $result1 = trim($data);
    //$result = htmlspecialchars($data);
    $result = mysql_real_escape_string($result1);
    return $result;
}
/*
 * add followers of twitter account
 */
function add_twitter($courser,$hobname,$hobid)
{

    $next=0;
    $token = '';//Add Your API Token
    $token_secret = '';//Add your Token-secret
    $consumer_key = '';//Add your Consumer key
    $consumer_secret = '';//Add Your consumer secret

    $host = 'api.twitter.com';
    $method = 'GET';
    $path = '/1.1/followers/list.json'; // api call path

    $query = array( // query parameters
        'cursor' => $courser,
        'count' => 200,
        'screen_name' => $hobname,
        'skip_status' => true,
        'include_user_entities' => false
    );

    $oauth = array(
        'oauth_consumer_key' => $consumer_key,
        'oauth_token' => $token,
        'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
        'oauth_timestamp' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0'
    );

    $oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
    $query = array_map("rawurlencode", $query);

    $arr = array_merge($oauth, $query); // combine the values THEN sort

    asort($arr); // secondary sort (value)
    ksort($arr); // primary sort (key)

// http_build_query automatically encodes, but our parameters
// are already encoded, and must be by this point, so we undo
// the encoding step
    $querystring = urldecode(http_build_query($arr, '', '&'));

    $url = "https://$host$path";

// mash everything together for the text to hash
    $base_string = $method . "&" . rawurlencode($url) . "&" . rawurlencode($querystring);

// same with the key
    $key = rawurlencode($consumer_secret) . "&" . rawurlencode($token_secret);

// generate the hash
    $signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));

// this time we're using a normal GET query, and we're only encoding the query params
// (without the oauth params)
    $url .= "?" . http_build_query($query);
    $url = str_replace("&amp;", "&", $url); //Patch by @Frewuill

    $oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
    ksort($oauth); // probably not necessary, but twitter's demo does it

// also not necessary, but twitter's demo does this too


    $oauth = array_map("add_quotes", $oauth);

// this is the full value of the Authorization line
    $auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

// if you're doing post, you need to skip the GET building above
// and instead supply query parameters to CURLOPT_POSTFIELDS
    $options = array(CURLOPT_HTTPHEADER => array("Authorization: $auth"),
        //CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_HEADER => false,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false);

// do our business
    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);

    $twitter_data = json_decode($json);
    //print_r($twitter_data);
    $k=2;
    $link=dbconnect();
    foreach ($twitter_data as $value) {
        $c=count($value);
        if ($c>1) {
            foreach ($value as $v) {

                 $count=User_exist($v->screen_name,$hobid);

                if ($count==0) {
                    $des = mysql_real_escape_string($v->description);
                    $name = mysql_real_escape_string($v->name);
                    $location = mysql_real_escape_string($v->location);
                    $date= date("Y-m-d H:i:s");
                    $query1 = "INSERT INTO `users` (`name`, `screen_name`, `location`, `website`, `description`, `tid`, `followers_count`, `friends_count`, `profile_image_url`,`hobid`,`import_date`) VALUES  ('" . $name . "', '" . $v->screen_name . "', '" . $location . "', '" . $v->url . "', '" . $des . "', '" . $v->id . "', '" . $v->followers_count . "', '" . $v->friends_count . "', '" . $v->profile_image_url . "','" . $hobid . "','".$date."');";
                    //echo "<br>";
                     $result=mysql_query($query1);
                }
            }
        }else if ($c==1 and $k!=0) {
            //print_r($value);
            //echo "cou: ".$value;
            $k--;
            $next=$value;
        }

    }
    //print_r($next);
    if ($next!=0 and !is_array($next))
    {
        add_twitter($next,$hobname,$hobid);

    }else if(is_array($next)){
        echo "try it Later";
    }else if ($next==0 ) {
        echo "Added Successfully";
    }
}

function add_quotes($str)
{
    return '"' . $str . '"';
}

// user add for every hob only one time
function User_exist($sc_name,$hubid)
{
    $link=dbconnect();
    $query="select count(*) as count from users where screen_name like '".$sc_name."' and hobid like '".$hubid."' ";
    $result=mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $count =$row['count'];
    //mysql_close($link);
    return $count;

}