<?php
   define('SYS_NAME', 'DocuSky-Prototype');       // 2015-03-11: DocuSky
   define('SYS_VERSION', '0.21');                 // 2019-09-12: v0.21
   
   // 利用 class 的 static variables 儲存「使用上近乎全域」的組態變數
   class Config {
      public static $sys_intalled_path;
      public static $sys_root_path;
      
      public static $sys_name;                                     
      public static $sys_version;
      public static $sys_title;
      
      public static $root_db_name;
      public static $host;
      public static $host_username;
      public static $host_password;
      
      public static $db_empty_sql_mode = true;             // 2020-08-05: 將 sql_mode 清空 -- true to allow auto-truncation or missing values (applied in DatabaseConnector.class.php)
      
      public static $session_name = 'DocuSky_SID';         // 2018-12-27: SID stands for "session id" (PHP default 'PHPSESSID')
      public static $session_by_url_parameter = true;      // 2021-02-06: 若有在 url 參數指定 session name，就透過它的值來取得 session （因應瀏覽器從 http 轉 https 將不再傳遞 session cookie，也替未來用 stateless method 舖一丁點路）
      
      //public static $debugging_level;
      public static $sys_output_to_console;                // 是否將系統訊息輸出到 console。若設為 true，online actions (e.g., ajax delete db) 的輸出訊息就會被顯示在頁面上。
      public static $sys_output_to_file;                   // 是否將系統訊息輸出到檔案（通常 $msg_output_to_console, $msg_output_to_file 恰有一者為 true）
      public static $sys_output_path;                      // system output path
      public static $sys_fp;                               // file pointer for system logs
      public static $sys_logfile_option;                       
                                                           
      public static $user_err_output_to_console;           // 是否將使用者（例如操作錯誤）所導致的 error message 直接輸出到 console (or Web page)
      public static $user_err_output_to_file;              
      public static $user_err_output_path;                 
      public static $user_err_fp;                          // file pointer for user-action logs
      public static $user_err_logfile_option;  
      
      public static $log_user_action_by_system;            // 2017-08-13
      public static $log_user_login_repeat_times;          // 2018-02-26
      
      // 2017-07-23
      public static $log_db_conn;
      
      // 2017-09-01
      public static $session_timeout;                          // in seconds
      
      // 2018-02-24
      public static $auto_add_searching_across_corpuses = true;              // 2018-02-24, 2018-08-21 因應凡煒需求打開
      public static $all_corpuses_query_permit_feature_analysis = false;     // 2019-02-26: 是否在 [ALL] 文獻集查詢下，允許 feature analysis（特徵型後分類）
      
      // 2019-11-15
      public static $auto_extract_udef_tags = false;           // 是否自動從文件提取 "Udef_" 開頭的標籤進行後分類（若強迫需由 metadata management tool 設定，則設為 false）
      public static $auto_extract_event_relations = true;      // 2020-11-08: 是否自動將 <Event> 中的 relations （目前只有 <BinRel>）提取為「類似 metatags」來進行後分類

      public static $max_capacity_quota;                       // 2018-01-03
      public static $max_refdata_size;                         // 2018-01-03
      public static $max_db_corpus_datafile_size;              // 2018-01-03
                                                           
      public static $background_process_tick_filename;         // background 記錄「我目前還是 正常工作」的計時檔
      public static $background_process_tick_maxsec;           // 若超過此秒數，表示 background process 可能已經掛掉，或者 hang 在某個 Office Component 的 dialog box 下 
                        
      // assumes using Sphinx for fulltext indexing
      public static $sphinx_path;                              // 2016-04-23: 便於切換到此目錄，執行 indexer
      public static $indexing_mod_digits;                      // 2016-04-23: // 將索引切分為多個檔案（以使用者 id % n 來切分），其中 mod_number 的位數（例如位數 2，表示 MOD 10^2）
      public static $indexing_basecmd;                         // 2016-04-23: 執行 sphinx 'indexer --config <conf_filename> --rotate ix*' 時的指令
                                                               
      public static $max_ft_indexed_databases;                 // 全文索引最多的資料庫數量（在 sphinx.conf 設定的資料庫數量）
      public static $max_main_databases;                       // 最多幾個 main databases
      public static $max_text_databases;                       // 最多幾個 text databases -- 不可超過 $max_ft_indexed_databases
      public static $max_personal_databases_in_default;        // 預設每位使用者最多可擁有多少個 text databases
      public static $max_users;                                // 最多幾位使用者（因每位使用者需建立一個 main database，因此取決於 $max_main_databases）
      public static $max_fetch_size;                           // 2019-09-12: 最多回傳筆數（先前在 QueryResultFetcher 固定為 100,000）
                                                               
      public static $max_job_queue_zombie_retry;               // 2017-04-24: 設定「若失敗，最多可以重試幾次」
                                                               
      public static $max_upload_files;                         // 2016-11-03: 最多可一次上載幾個檔案
      public static $max_file_upload_size_mb;                  // 2016-11-02: 最大上載檔案（以 MB 計）

      public static $max_upload_webtool_zip_files;             // 2016-12-26: 最多可一次上載幾份 .zip 檔（as reg web tool）
      public static $max_file_upload_webtool_zip_size_mb;      // 2016-12-26: 最大可上載的 .zip 檔
      public static $max_webtool_unzip_path_depth;             // 2017-12-09: 解壓前無法預知 zip 檔的路徑深度。為了避免惡意、誤用或濫用，解壓時需對路徑深度加以限制。
      public static $max_webtool_unzip_files_extracted;        // 2017-12-09: 解壓前無法預知 zip 檔解壓後的檔案數量。為了避免惡意、誤用或濫用，解壓時需對解壓後展開的檔案數量。
      public static $webtool_unzip_skip_invalid;               // 2017-12-09: true 跳過不允許的檔案，false 將這些檔案 rename

      public static $max_upload_corpus_datafiles;              // 2017-12-07: 最多可一次上載幾個 doc_attachment 的附加檔（圖檔）
      public static $max_file_upload_corpus_datafile_size_mb;  // 2016-11-02: doc_attachment 最大上載檔案（以 MB 計）

      public static $max_doc_prev_next_size;                   // 2016-11-05: <filename>,<op>n 可取得 <filename> 前後 n 篇文件。在此指定 n 的最大值。
      public static $max_page_size;                            // 2021-08-07
      
      public static $max_post_classification_items;            // 後分類預設的最大回傳項目數量
      public static $max_feature_analysis_items;               // 詞頻分析預設的最大回傳項目數量
      public static $default_post_classification_items;        // 2021-08-29: 後分類預設項目數（通常是 200）
      public static $default_feature_analysis_items;           // 2021-08-29: 詞頻分析預設項目數（通常是 200）
      public static $pc_cues_move_to_last;                     // 若後分類 (pc) 的 cue 值屬於此陣列，則將該 cue 移至列表最末端
      
      public static $max_docs_auto_complete_analysis;          // 2021-09-25: 若未超過此值，feature analysis 將不需完整分析，例如跳過計算 t->q 所需的統計資訊
      public static $max_features_auto_complete_analysis;      // 2021-09-27: 若從 {USER/OPEN}_DB_CORPUS_FEATURE 取得的 features 數量高過此值，將只對高頻 tags 進行統計
      
      public static $hide_pc_spotlights;                       // 2019-05-04: 例如 array('GE1/GE2/GE3/GE4/GE5', 'CT1/CT2/CT3/CT4/CT5')，陣列中的 spotlight titles 將不會被 auto_set_db_post_classification_spotlights() 放入 CORPUS_POST_CLASSIFICATION
                                                             
      public static $display_mining_links;                     // 是否顯示「採礦」連結
      public static $display_edit_reindexing_links;            // 是否顯示「編輯」與「重新索引」連結
      public static $display_open_databases;                   // 是否顯示「開放的資料庫」連結
      public static $display_open_tools;                     
      public static $display_group_databases;                  // 是否顯示「群組資料庫」連結
                                                             
      public static $ip_valid_filename;                        // 允許的 ip 區段檔名
      public static $ip_banned_filename;                       // 禁止的 ip 區段檔名
                                                             
      public static $out_xml_path;                             // 存放 xslt render 之前 xml 的路徑
      public static $web_path_prefix;                          // 例如，所有的 web pages 都放在此目錄下（輸出 logs 或 out_xml 需使用）
                                                             
      public static $delete_upload_file_after_insersion;     
      public static $archive_uploading_path;                   // 暫存 file upload (zip of archived xml's) 的目錄（file_upload.php 使用）
      public static $procedure_uploading_path;                 // 暫存 procedure-xml upload 的目錄
      public static $mining_result_uploading_path;             // 暫存 mining-result upload 的目錄
                                                             
      public static $db_ad_year_offset = 100000;               // 2016-08-04: 將西元年 YYYY 加上此值，才是儲存在資料庫 AD_YEAR 的值（或可有利於處理西元前的年份 -- offset 亦可設為零）
      
      public static $db_deletion_verbosely = true;             // 2016-10-28: 刪除文字庫時，不完全利用 CASCADE，自己先把 features, documents 移除
      //public static $qjob_db_deletion_docs_threshold = 0;    // 2016-10-28: 只有當文字庫文件數量大於等於此數，才會將刪除文字庫的動作放入 queue job
      
      public static $queue_scanning_period = 5;                // 2016-08-04: 若 background process 還活著，每幾秒檢查 JOB_QUEUE 一次
      public static $enable_qjob_db_construction = true;       // 2016-08-06: for webApi uploadXmlFilesToBuildDbJson.php
      public static $enable_qjob_db_deletion = true;           // 2016-10-28: for webApi deleteDbJson.php
      public static $enable_qjob_webtool_unzip = true;         // 2016-10-07, 2017-12-09
      public static $max_unzip_dir_depth = 10;                 // 2017-06-04: 僅允許建構最多幾層的目錄結構（3 表示允許最多三個 '/'，例如允許 'MyTool/js/jquery-ui/code.js'）

      public static $enable_account_without_review = false;    // 2016-09-07: 申請帳號後，直接顯示密碼（不需經審核後郵寄密碼）
      public static $out_debugging_path;
      
      public static $role_system = 'SystemUser';
      public static $role_sysadmin = 'SysAdmin';
      public static $role_regadmin = 'RegAdmin';
      public static $role_commonuser = 'CommonUser';
      public static $role_poweruser = 'PowerUser';
      public static $role_demouser = 'DemoUser';
      
      public static $upload_path_root = 'uploads';                           // big5...
      public static $upload_path_db_xml = 'db-xml';                          // 2016-10-05: 將這些上載的資料，利用檔案結構儲存...
      public static $upload_path_webtool_zip = 'webtool-zip';                // 2016-10-06, 2017-12-09: 將這些上載的資料，利用檔案結構儲存...
      public static $upload_path_db_corpus_datafile = 'db-corpus-datafile';  // 2017-12-07: 將這些上載的資料，利用檔案結構儲存...
      public static $user_tools_rootpath = 'userTools';
      public static $docu_tools_rootpath = 'docuTools';
      public static $docu_tools_accounts = array('system', 'opendb', 'opentool', 'public');    // 這些帳號的工具，將使用 Config::$docu_tools_rootpath
      
      public static $doc_fields_int_type;                                    // 2016-11-01: 在此指定，有哪些 DOCUMENT 資料表的欄位為 integer type
      public static $doc_fields_num_type;                                    // 2018-09-04: 數值型態，包含整數和小數
      
      public static $valid_doc_ext_types;                                    // 2019-07-18: DOC_EXTENSION 表格中，EXT_TYPE 的可能值（目前不開放自訂）
      public static $corpus_all_allows_common_spotlights;                    // 2019-07-26: [ALL] 下是否允許顯示「共同的 metadata 後分類」 (QueryResultFetcher)
      
      public static function init_config() {
         $file_dir = dirname( __FILE__ );
         self::$sys_intalled_path = dirname($file_dir);        // 此 config 放在 include 子目錄下
         self::$sys_root_path = self::$sys_intalled_path;
         
         self::$sys_name = SYS_NAME;          
         self::$sys_version = SYS_VERSION;
         self::$sys_title = self::$sys_name . ' v' . self::$sys_version;
         
         self::$root_db_name = 'DHP_EARLY_PROTOTYPE';
         self::$host = '127.0.0.1';
         self::$host_username = 'thdl';
         self::$host_password = 'thdl';
         
         //self::$debugging_level = 1;
         self::$sys_output_to_console = false;
         self::$sys_output_to_file = true;
         self::$sys_output_path = 'logs/sys_logs';
         self::$sys_fp = null;
         self::$sys_logfile_option = 'd';                  // 'm', 'd', 'h' (one logfile per month, day, or hour)

         self::$user_err_output_to_console = true;
         self::$user_err_output_to_file = true;
         self::$user_err_output_path = 'logs/user_err_logs';
         self::$user_err_fp = null;
         self::$user_err_logfile_option = 'd';              // 'm', 'd', 'h' (one logfile per month, day, or hour)
         
         self::$log_user_action_by_system = false;          // 2017-08-13: 在 LOG_USER_ACTION 不記錄 'system' 的動作
         self::$log_user_login_repeat_times = 100;          // 2018-02-26: 若在 session 仍生效期間，不斷進行 login，則每 n 次僅記錄一次 action
           
         self::$log_db_conn = false;                        // can use Config::set_log_db_conn() to change this
         self::$session_timeout = 60 * 60;                  // 60 minutes
         
         self::$max_capacity_quota = '800MB';
         self::$max_refdata_size = '100MB';
         self::$max_db_corpus_datafile_size = '300MB';
         
         self::$background_process_tick_filename = 'background_process.tick';
         self::$background_process_tick_maxsec = 1800;      // 30 分鐘內一定要有 tick 記錄，否則將認為 background process 已經掛了（2015-02-16: 從 Excel 轉出，拷貝資料庫、或者計算 doc_fetures 都可能會需要數分鐘...） 
         
         self::$sphinx_path = "C:/Sphinx/bin";
         self::$indexing_mod_digits = 2;                    // 將索引切分為多個檔案（以使用者 id % n 來切分 ==> 日後應該用 user_db id 來切），其中 mod_number 的位數（例如位數 2，表示 MOD 10^2） -- 2016-09-02: 但若設為 3，searchd 會跑出「Too many open files」的錯誤！
         self::$indexing_basecmd = "indexer --config ./sphinx.conf --rotate";       // 呼叫時，需先將 <uid_mod_remainder> 置換成 uid MOD 10^{mod_digits} 的結果（餘數）
         
         self::$max_ft_indexed_databases = 500;
         self::$max_main_databases = 50;
         self::$max_text_databases = 300;
         self::$max_personal_databases_in_default = 5;      // 預設每位使用者最多可擁有多少個 text databases
         self::$max_users = self::$max_main_databases;
         self::$max_unzip_dir_depth = 5;                    // 對所有 zip 檔解壓設下限制
         self::$max_fetch_size = 100000;                    // 2019-09-12
         
         self::$max_job_queue_zombie_retry = 1;                // 最多只重覆一次
         
         self::$max_upload_files = 3;                          // 目前先限制一次只能上載單一檔案
         self::$max_file_upload_size_mb = 132;                 // 若過大，目前 client (browser) 的 uploadMultipart 可能會吃爆記憶體
         
         self::$max_upload_webtool_zip_files = 1;              // 目前，所有 RegWebTool 的檔案都需壓縮在一個 .zip 中
         self::$max_file_upload_webtool_zip_size_mb = 10;
         self::$max_webtool_unzip_path_depth = 4;              // 2017-06-04, 2017-12-09僅允許建構最多幾層的目錄結構（3 表示允許最多三個 '/'，例如允許 'MyTool/js/jquery-ui/code.js'）
         self::$max_webtool_unzip_files_extracted = 1000;      // 2017-12-09
         self::$webtool_unzip_skip_invalid = false;            // true 跳過 invalid files，false 更改檔名

         self::$max_upload_corpus_datafiles = 200;             // 一次最多只能上載 200 個 attachment 檔案
         self::$max_file_upload_corpus_datafile_size_mb = 10;  // 每個檔案最大幾 MB
         
         self::$max_page_size = 2000;                          // 2021-08-07: 在 QueryResultFetcher.class.php 以 const MAX_PAGE_SIZE = Config::$max_page_size 管控
         self::$max_doc_prev_next_size = 20;                   // 最多查詢「前後」20篇文件
         
         self::$max_post_classification_items = 2000;          // 後分類最大回傳項目數量 (hard limit) -- 預設數量，放在函式輸入參數的預設值 (例如檢索頁呼叫 QueryResultFetcher 的 get_query_post_classification()，metadata/tags 最大值設為 500/200)
         self::$max_feature_analysis_items = 2000;             // 詞頻分析最大回傳項目數量 (hard limit)
         
         self::$default_post_classification_items = 200;       // 2021-08-29
         self::$default_feature_analysis_items = 200;          // 2021-08-29
         
         self::$pc_cues_move_to_last = array('-',              // 後分類需移到最後的 cues
                                             'undefined',
                                             '(-180.000000,-180.000000)',  // 2018-10-20: (-180,-180) 代表沒有座標 => THDL 的 (-1,-1) import 時轉為 (-180,-180)
                                             //'(-1.000000,-1.000000)'     // 2018-10-20: (-1,-1) 在 THDL 代表沒有座標
                                             );     
         
         self::$max_docs_auto_complete_analysis = 2000;                                           // 2021-09-25: 本機測試或可只設為 500
         self::$max_features_auto_complete_analysis = self::$default_feature_analysis_items * 2;  // 2021-09-27
         
         self::$hide_pc_spotlights = array('CT1/CT2/CT3/CT4/CT5', 'GE1/GE2/GE3/GE4/GE5',
                                           'CAT4', 'CAT5', 'GEO4', 'GEO5',                        // 2019-05-04: 這些 spotlights 將不會被自動計算、放入 CORPUS_POST_CLASSIFICATION」
                                           'GEO1/GEO2/GEO3', 'TP1/TP2/TP3', 'CAT1/CAT2/CAT3',     // 2019-05-09: 與 'GEO', 'TOPIC', 'CAT' 用途重覆
                                           );
         
         self::$display_mining_links = false;
         self::$display_edit_reindexing_links = true;
         self::$display_open_databases = true;
         self::$display_open_tools = true;
         self::$display_group_databases = true;
         
         self::$ip_banned_filename = '/ip_config/banned_ip.conf';
         self::$ip_valid_filename  = '/ip_config/valid_ip.conf';
         
         self::$out_xml_path = 'out_xml';
         self::$web_path_prefix = 'WebApi';
         
         self::$delete_upload_file_after_insersion = true;
         self::$archive_uploading_path       = 'temp/uploads';
         self::$procedure_uploading_path     = 'temp/imports-procedure-xml';
         self::$mining_result_uploading_path = 'temp/imports-mining-result';
         
         self::$out_debugging_path = 'temp/debugging';
         
         self::$corpus_all_allows_common_spotlights = false;           // 預設不能顯示除了 CORPUS 之外的其他後分類
         
         self::initialize();
      }
      
      private static function initialize() {
         self::$sys_output_path      = self::get_actual_path_and_create_subdirectories(self::$sys_output_path);
         self::$user_err_output_path = self::get_actual_path_and_create_subdirectories(self::$user_err_output_path);
         self::$out_xml_path         = self::get_actual_path_and_create_subdirectories(self::$out_xml_path);
         
         self::$archive_uploading_path       = self::get_actual_path_and_create_subdirectories(self::$archive_uploading_path);
         self::$procedure_uploading_path     = self::get_actual_path_and_create_subdirectories(self::$procedure_uploading_path);
         self::$mining_result_uploading_path = self::get_actual_path_and_create_subdirectories(self::$mining_result_uploading_path);

         self::$out_debugging_path = self::get_actual_path_and_create_subdirectories(self::$out_debugging_path);
         
         self::$doc_fields_int_type = array('ACCESS_CONTROL', 'DOC_XML_FORMAT_NAME_ID',
                                            'DOC_FILENAME_INDEX', 'SRC_ARCHIVE_DOC_SEQUENCE',
                                            'DOC_AUTHOR_ORDER', 'DOC_SOURCE_ORDER', 'DOC_SUBSOURCE_ORDER',
                                            'DOC_COMPILATION_ORDER', 'DOC_DISPLAY_ORDER',
                                            'GEO_LEVEL1_ORDER', 'GEO_LEVEL2_ORDER', 'GEO_LEVEL3_ORDER',
                                            'DOC_CATEGORY_L1_ORDER', 'DOC_CATEGORY_L2_ORDER', 'DOC_CATEGORY_L3_ORDER',
                                            'DOC_TOPIC_L1_ORDER', 'DOC_TOPIC_L2_ORDER', 'DOC_TOPIC_L3_ORDER',
                                            'DATE_DYNASTY_ORDER', 'DATE_EMPEROR_TITLE_ORDER', 'DATE_ERA_ORDER',
                                            'DATE_AD_DATE', 'DATE_AD_YEAR', 'DATE_AD_YEARMONTH',
                                            'DATE_JULIAN_DAY',
                                            'DATE_CH_YEAR', 'DATE_CH_LEAP_MONTH', 'DATE_CH_MONTH', 'DATE_CH_DAY',
                                            'MONTHDAY_ORDER', 'MONTHDAY_MONTH_ORDER', 'MONTHDAY_DAY_ORDER',
                                            'TIMESEQ_NUMBER', 'TIMESEQ_NOT_BEFORE', 'TIMESEQ_NOT_AFTER', 'TIMESEQ_PRECISION',
                                            'DOC_EVAL_SCORE', 'DOC_TREE_HEIGHT',
                                            'DOC_TITLE_WORD_COUNT', 'DOC_CONTENT_WORD_COUNT', 'DOC_FT_TITLE_WORD_COUNT', 'DOC_FT_CONTENT_WORD_COUNT'
                                            );
         self::$doc_fields_num_type = array_merge(self::$doc_fields_int_type, array('GEO_X', 'GEO_Y'));     // 2018-09-04
         
         self::$valid_doc_ext_types = array('related', 'relevant', 'simdocs', 'family', 'cluster');         // 2019-07-18

         //$paths = array(self::$sys_output_path, self::$user_err_output_path, self::$out_xml_path, 
         //               self::$archive_uploading_path, self::$procedure_uploading_path, self::$mining_result_uploading_path);
         //foreach ($paths as $path) {
         //   print $path . "\n";
         //   if (!file_exists($path)) mkdir($path);
         //}

         session_name(self::$session_name);      // 2018-12-27
         
      }
      
      public static function get_actual_path_and_create_subdirectories($path_rel_to_root) {
         $root_dir = dirname(dirname(__FILE__)) . '/' . self::$web_path_prefix;      // 當前 script 的 path 應為 X/include，利用 dirname 取得 X
         $path_rel_to_root = str_replace(DIRECTORY_SEPARATOR, '/', $path_rel_to_root);
         $segments = explode('/', $path_rel_to_root);
         $sub_path = $root_dir;
         if (!file_exists($sub_path)) mkdir($sub_path);
         foreach ($segments as $segment) {
            $sub_path .=  '/' . $segment;
            if (!file_exists($sub_path)) mkdir($sub_path);
         }
         return $sub_path;             // 回傳絕對路徑
      }
      
      public static function set_log_db_conn($bool) {
         Config::$log_db_conn = $bool;
      }
   }

   Config::init_config();
 
?>