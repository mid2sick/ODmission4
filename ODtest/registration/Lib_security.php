<?php
   require_once 'Config.php';

   date_default_timezone_set('Asia/Taipei');
   //ini_set("session.cookie_httponly", 1);               // 2020-10-23: disallow browser to let client (js) retrieve cookie content

   
   // ------------------------------------------------------
   // web security guard
   // ------------------------------------------------------
   
   function my_session_start() {
      //var_export($_GET);
      //var_export($_REQUEST);            // �Y $_COOKIE ���ȡA�|�л\ $_GET ����
      //print session_name() . "\n";
      //exit(var_export($_REQUEST[session_name()]));

      // 2021-02-06: �Q�� post �� get �� URL �Ѽ� switch session id
      $session_key = @$_POST[session_name()];
      if (!$session_key) $session_key = @$_GET[session_name()];
      //exit($session_key);
      if (Config::$session_by_url_parameter && $session_key) {
         session_id($session_key);
         session_start();
         
         //if (!@$_SESSION['username']) exit("Error: session without username");
         if (!@$_SESSION['username']) $_SESSION['username'] = 'opendb';     // 2021-12-10

         //exit(var_export($_SESSION));
         //exit(session_id());
      }
      else {
         // 2020-12-14: due to Google cookie SameSite policy, needs to modify all session_start() invokation
         session_start();
      }
      
      // 2021-12-20
      header('Access-Control-Allow-Origin: *'); 
      
      // �]���O session �]�w�A�� SameSite=Lax �� SameSite=Strict ���� �� SameSite=None ��n�H
      //header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure');    // �]���O session �]�w�A�� SameSite=Strict ���� �� SameSite=None ��n
      // 2021-01-05: �Y query �O����w http�A�h���[�J Secure�Ghttps �h�[�W Secure
      if (@$_SERVER['HTTPS']) {
         header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure');
      }
      else {
         // 2020-12-31: ���� Secure �ѼƳ]�w�A�i�� http �n�J�D���ɤ��a�� session cookie
         //             => �s�����N�����\�b HTTP �U�ϥ� session cookie �]���a�� Expires �� Max-Age�^�I
         //                �t�~�Apermanent cookie �Y�� SameSite=Lax �� SameSite=Strict ���G���i�ͮ�
         // Chrome �w�] SameSite=Lax �]�Y SameSite=None�A�h�����]�w Secure �~�|�ͮġ^
         //header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=Strict');            
         header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None');            
      }
   }
   
?>