<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/medoo.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/functions.php');

if(isset($_REQUEST['login']))
{
    // Login procedure
    if(!isset($_REQUEST['phone']) || !isset($_REQUEST['code_post']))
    {
        exit(json_encode(['error'=>'wrong input data']));
    }
    checkDDOS('auth',$medoo,10,5);
    
    $phone = clean($_REQUEST['phone']);
    $code = clean( $_REQUEST['code_post'] );


    $result = $medoo->get('userlist','*',['reg_tel'=>$phone]);
    if(!$result)
    {
        exit(json_encode(
            $medoo->error()[2]==null
                ? ['error'=>'wrong password']
                : dberror($medoo)
        ));
    }
    $hash_user = sha1($result['salt'].$code);
    if($hash_user === $result['hash'])
    {
        $_SESSION['auth'] = true;
        exit(json_encode(['success'=>'true']));
    }
    else
    {
        exit(json_encode(['error'=>'wrong password']));
    }
}
elseif(isset($_REQUEST['register']))
{
    if(!isset($_REQUEST['phone']))
    {
        exit(json_encode(['error'=>'wrong input data']));
    }

    checkDDOS('reg',$medoo,1,5);

    $code = generate_pass(5);

    $phone = clean($_REQUEST['phone']);

    $salt = substr(sha1($code), 10, 15);
    $hash = sha1($salt . $code);

    /* Checking for duplicates */
    $result = $medoo->get('userlist','*',['reg_tel'=>$phone]);
    if($result)
    {
        exit(json_encode(['error'=>'user already exists']));
    }
    if($medoo->error()[2]!=null)
    {
        exit(json_encode(dberror($medoo)));
    }

    $result = $medoo->insert('userlist',[
        'reg_tel'=>$phone,
        'hash'=>$hash,
        'salt'=>$salt,
        'role'=>$phone,
    ]);
    exit(json_encode($result?['success'=>'true',
        'verification_code'=>$code,
        'phone'=>$phone,
    ]:(dberror($medoo))));
}
else
{
    exit(json_encode(['error'=>'wrong_method']));
}