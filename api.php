<?php
include_once 'config.php';

if($DEFUALT_PASSWORD!=""){
    if($DEFUALT_PASSWORD!=$_COOKIE["githubpass"]){
        if($_COOKIE["githubpass"]=="") echo "nopass";
        else echo "wrongpass";
        exit();
    }
}

if($_GET["init"]){
    if(is_dir($_GET["dir"])){
        echo "true";
    }
    else echo "false";
}

if($_GET["commit"]){
    $changed_files = 0;
    $ignored_file = 0;
    $ignore_files = [];
    $ignore_folder = [];
    $last_change = file_get_contents($_GET["dir"]."/.gitlastchanged");
    $gitignore = file_get_contents($_GET["dir"]."/.gitignore");
    if($gitignore!=""){
        foreach(explode("\n", $gitignore) as $gitignore_each){
            if(substr($gitignore_each,0,1)=="/") array_push($ignore_folder, $_GET["dir"].$gitignore_each);
            else array_push($ignore_files, $gitignore_each);
        }
    }
    lf_commit($_GET["dir"], $ignore_files, $ignore_folder);
    echo '<p class="white"><span class="green">'.$changed_files.'</span> files added <span class="red">'.$ignored_file.'</span> files ignored</p>';
}

function lf_commit($dir, $ignore_files, $ignore_folder){
    $ffs = scandir($dir);
    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);
    if (count($ffs) < 1)
        return;
    foreach($ffs as $ff){
        if(is_dir($dir.'/'.$ff)) lf_commit($dir.'/'.$ff, $ignore_files, $ignore_folder);
        else {
            if(in_array($ff, $ignore_files)===true){
                $GLOBALS['ignored_file']++;
                //echo "File ".$dir.'/'.$ff." <span class='red'>ignored</span><br>";
            }
            else if(is_sub_dir($dir, $ignore_folder)===true){
                $GLOBALS['ignored_file']++;
                //echo "<span class='red'>File ".$dir.'/'.$ff." ignored</span><br>";
            }
            else $GLOBALS['changed_files']++;
            //else echo "File ".$dir.'/'.$ff." changed<br>";
        }
    }
}

function is_sub_dir($path, $ignore_folder) {

    foreach($ignore_folder as $each){
        if(explode($each, $path)[0]=="") return TRUE;
    }
    return FALSE;
}

if($_GET["push"]){
    $last_change = file_get_contents($_COOKIE["githubfld"]."/.gitlastchanged");
    lf_push($_COOKIE["githubfld"], $last_change);
    echo '<p class="green">Successfully pushed to Github</p>';
}

function lf_push($dir, $last_change){
    $ffs = scandir($dir);
    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);
    if (count($ffs) < 1)
        return;
    foreach($ffs as $ff){
        if(is_dir($dir.'/'.$ff)) lf_push($dir.'/'.$ff, $last_change);
        else {
            if($last_change!=filemtime($dir.'/'.$ff)){
                push($dir,$ff);
            }
        }
    }
}

function push($fld,$file){
    $repo = base64_decode($_COOKIE["githubrepo"]);
    if($repo=="default") $repo = $GLOBALS["DEFUALT_GITHUB_REPO"];
    $url = 'https://api.github.com/repos/'.explode('/',$repo)[3].'/'.str_replace('.git', '', explode('/',$repo)[4]).'/contents/'.str_replace($_COOKIE["githubfld"]."/", "", $fld.'/'.$file);
    $base64 = base64_encode(file_get_contents($fld.'/'.$file));
    $msg = $_COOKIE["githubcommit"];
    if($msg=="default") $msg = $GLOBALS["DEFUALT_GITHUB_COMMIT_MESSAGE"];
    $data = '{
      "message": "'.str_replace('"','',$msg).'",
      "committer": {
        "name": "'.explode('/',$repo)[3].'",
        "email": "'.explode('/',$repo)[3].'"
      },
      "content": "'.$base64.'"
    }';
    $username = 'Niyko';
    $token = $_COOKIE["githubkey"];
    if($token=="default") $token = $GLOBALS["DEFUALT_GITHUB_AUTH_KEY"];
    $curl_url = $url;
    $curl_token_auth = 'Authorization: token ' . $token;
    $ch = curl_init($curl_url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'User-Agent: $username', $curl_token_auth ));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response);
    if($response->sha!="") echo '<p class="white">'.$fld.'/'.$file.' <span class="green">added</span></p>';
    else if($response->message=="Invalid request") echo '<p class="white">'.$fld.'/'.$file.' <span class="yellow">not changed</span></p>';
    else if($response->message=="Not Found"){
        echo '<p class="red">Invalid git push url </p>';
        exit();
    }
    else echo '<p class="white">'.$fld.'/'.$file.' <span class="red">failed</span></p>';
}
?>