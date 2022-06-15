<?php
   require_once 'Config.php';

   date_default_timezone_set('Asia/Taipei');
   //ini_set("session.cookie_httponly", 1);               // 2020-10-23: disallow browser to let client (js) retrieve cookie content

   
   // ------------------------------------------------------
   // web security guard
   // ------------------------------------------------------
   
   function my_session_start() {
      //var_export($_GET);
      //var_export($_REQUEST);            // 若 $_COOKIE 有值，會覆蓋 $_GET 的值
      //print session_name() . "\n";
      //exit(var_export($_REQUEST[session_name()]));

      // 2021-02-06: 利用 post 或 get 的 URL 參數 switch session id
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
      
      // 因為是 session 設定，用 SameSite=Lax 或 SameSite=Strict 應該 比 SameSite=None 更好？
      //header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure');    // 因為是 session 設定，用 SameSite=Strict 應該 比 SameSite=None 更好
      // 2021-01-05: 若 query 是走協定 http，則不加入 Secure：https 則加上 Secure
      if (@$_SERVER['HTTPS']) {
         header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None; Secure');
      }
      else {
         // 2020-12-31: 移除 Secure 參數設定，可讓 http 登入主頁時仍帶有 session cookie
         //             => 瀏覽器將不允許在 HTTP 下使用 session cookie （未帶有 Expires 或 Max-Age）！
         //                另外，permanent cookie 若有 SameSite=Lax 或 SameSite=Strict 似乎仍可生效
         // Chrome 預設 SameSite=Lax （若 SameSite=None，則必須設定 Secure 才會生效）
         //header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=Strict');            
         header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=None');            
      }
   }
   
?>