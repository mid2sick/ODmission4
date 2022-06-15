<?php
   define('SYS_NAME', 'DocuSky-Prototype');       // 2015-03-11: DocuSky
   define('SYS_VERSION', '0.21');                 // 2019-09-12: v0.21
   
   // �Q�� class �� static variables �x�s�u�ϥΤW��G����v���պA�ܼ�
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
      
      public static $db_empty_sql_mode = true;             // 2020-08-05: �N sql_mode �M�� -- true to allow auto-truncation or missing values (applied in DatabaseConnector.class.php)
      
      public static $session_name = 'DocuSky_SID';         // 2018-12-27: SID stands for "session id" (PHP default 'PHPSESSID')
      public static $session_by_url_parameter = true;      // 2021-02-06: �Y���b url �Ѽƫ��w session name�A�N�z�L�����ȨӨ��o session �]�]���s�����q http �� https �N���A�ǻ� session cookie�A�]�����ӥ� stateless method �E�@�B�I���^
      
      //public static $debugging_level;
      public static $sys_output_to_console;                // �O�_�N�t�ΰT����X�� console�C�Y�]�� true�Aonline actions (e.g., ajax delete db) ����X�T���N�|�Q��ܦb�����W�C
      public static $sys_output_to_file;                   // �O�_�N�t�ΰT����X���ɮס]�q�` $msg_output_to_console, $msg_output_to_file �꦳�@�̬� true�^
      public static $sys_output_path;                      // system output path
      public static $sys_fp;                               // file pointer for system logs
      public static $sys_logfile_option;                       
                                                           
      public static $user_err_output_to_console;           // �O�_�N�ϥΪ̡]�Ҧp�ާ@���~�^�ҾɭP�� error message ������X�� console (or Web page)
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
      public static $auto_add_searching_across_corpuses = true;              // 2018-02-24, 2018-08-21 �]���Z�m�ݨD���}
      public static $all_corpuses_query_permit_feature_analysis = false;     // 2019-02-26: �O�_�b [ALL] ���m���d�ߤU�A���\ feature analysis�]�S�x��������^
      
      // 2019-11-15
      public static $auto_extract_udef_tags = false;           // �O�_�۰ʱq��󴣨� "Udef_" �}�Y�����Ҷi�������]�Y�j���ݥ� metadata management tool �]�w�A�h�]�� false�^
      public static $auto_extract_event_relations = true;      // 2020-11-08: �O�_�۰ʱN <Event> ���� relations �]�ثe�u�� <BinRel>�^�������u���� metatags�v�Ӷi������

      public static $max_capacity_quota;                       // 2018-01-03
      public static $max_refdata_size;                         // 2018-01-03
      public static $max_db_corpus_datafile_size;              // 2018-01-03
                                                           
      public static $background_process_tick_filename;         // background �O���u�ڥثe�٬O ���`�u�@�v���p����
      public static $background_process_tick_maxsec;           // �Y�W�L����ơA��� background process �i��w�g�����A�Ϊ� hang �b�Y�� Office Component �� dialog box �U 
                        
      // assumes using Sphinx for fulltext indexing
      public static $sphinx_path;                              // 2016-04-23: �K������즹�ؿ��A���� indexer
      public static $indexing_mod_digits;                      // 2016-04-23: // �N���ޤ������h���ɮס]�H�ϥΪ� id % n �Ӥ����^�A�䤤 mod_number ����ơ]�Ҧp��� 2�A��� MOD 10^2�^
      public static $indexing_basecmd;                         // 2016-04-23: ���� sphinx 'indexer --config <conf_filename> --rotate ix*' �ɪ����O
                                                               
      public static $max_ft_indexed_databases;                 // ������޳̦h����Ʈw�ƶq�]�b sphinx.conf �]�w����Ʈw�ƶq�^
      public static $max_main_databases;                       // �̦h�X�� main databases
      public static $max_text_databases;                       // �̦h�X�� text databases -- ���i�W�L $max_ft_indexed_databases
      public static $max_personal_databases_in_default;        // �w�]�C��ϥΪ̳̦h�i�֦��h�֭� text databases
      public static $max_users;                                // �̦h�X��ϥΪ̡]�]�C��ϥΪ̻ݫإߤ@�� main database�A�]�����M�� $max_main_databases�^
      public static $max_fetch_size;                           // 2019-09-12: �̦h�^�ǵ��ơ]���e�b QueryResultFetcher �T�w�� 100,000�^
                                                               
      public static $max_job_queue_zombie_retry;               // 2017-04-24: �]�w�u�Y���ѡA�̦h�i�H���մX���v
                                                               
      public static $max_upload_files;                         // 2016-11-03: �̦h�i�@���W���X���ɮ�
      public static $max_file_upload_size_mb;                  // 2016-11-02: �̤j�W���ɮס]�H MB �p�^

      public static $max_upload_webtool_zip_files;             // 2016-12-26: �̦h�i�@���W���X�� .zip �ɡ]as reg web tool�^
      public static $max_file_upload_webtool_zip_size_mb;      // 2016-12-26: �̤j�i�W���� .zip ��
      public static $max_webtool_unzip_path_depth;             // 2017-12-09: �����e�L�k�w�� zip �ɪ����|�`�סC���F�קK�c�N�B�~�Ω��ݥΡA�����ɻݹ���|�`�ץ[�H����C
      public static $max_webtool_unzip_files_extracted;        // 2017-12-09: �����e�L�k�w�� zip �ɸ����᪺�ɮ׼ƶq�C���F�קK�c�N�B�~�Ω��ݥΡA�����ɻݹ������i�}���ɮ׼ƶq�C
      public static $webtool_unzip_skip_invalid;               // 2017-12-09: true ���L�����\���ɮסAfalse �N�o���ɮ� rename

      public static $max_upload_corpus_datafiles;              // 2017-12-07: �̦h�i�@���W���X�� doc_attachment �����[�ɡ]���ɡ^
      public static $max_file_upload_corpus_datafile_size_mb;  // 2016-11-02: doc_attachment �̤j�W���ɮס]�H MB �p�^

      public static $max_doc_prev_next_size;                   // 2016-11-05: <filename>,<op>n �i���o <filename> �e�� n �g���C�b�����w n ���̤j�ȡC
      public static $max_page_size;                            // 2021-08-07
      
      public static $max_post_classification_items;            // ������w�]���̤j�^�Ƕ��ؼƶq
      public static $max_feature_analysis_items;               // ���W���R�w�]���̤j�^�Ƕ��ؼƶq
      public static $default_post_classification_items;        // 2021-08-29: ������w�]���ؼơ]�q�`�O 200�^
      public static $default_feature_analysis_items;           // 2021-08-29: ���W���R�w�]���ؼơ]�q�`�O 200�^
      public static $pc_cues_move_to_last;                     // �Y����� (pc) �� cue ���ݩ󦹰}�C�A�h�N�� cue ���ܦC��̥���
      
      public static $max_docs_auto_complete_analysis;          // 2021-09-25: �Y���W�L���ȡAfeature analysis �N���ݧ�����R�A�Ҧp���L�p�� t->q �һݪ��έp��T
      public static $max_features_auto_complete_analysis;      // 2021-09-27: �Y�q {USER/OPEN}_DB_CORPUS_FEATURE ���o�� features �ƶq���L���ȡA�N�u�ﰪ�W tags �i��έp
      
      public static $hide_pc_spotlights;                       // 2019-05-04: �Ҧp array('GE1/GE2/GE3/GE4/GE5', 'CT1/CT2/CT3/CT4/CT5')�A�}�C���� spotlight titles �N���|�Q auto_set_db_post_classification_spotlights() ��J CORPUS_POST_CLASSIFICATION
                                                             
      public static $display_mining_links;                     // �O�_��ܡu���q�v�s��
      public static $display_edit_reindexing_links;            // �O�_��ܡu�s��v�P�u���s���ޡv�s��
      public static $display_open_databases;                   // �O�_��ܡu�}�񪺸�Ʈw�v�s��
      public static $display_open_tools;                     
      public static $display_group_databases;                  // �O�_��ܡu�s�ո�Ʈw�v�s��
                                                             
      public static $ip_valid_filename;                        // ���\�� ip �Ϭq�ɦW
      public static $ip_banned_filename;                       // �T� ip �Ϭq�ɦW
                                                             
      public static $out_xml_path;                             // �s�� xslt render ���e xml �����|
      public static $web_path_prefix;                          // �Ҧp�A�Ҧ��� web pages ����b���ؿ��U�]��X logs �� out_xml �ݨϥΡ^
                                                             
      public static $delete_upload_file_after_insersion;     
      public static $archive_uploading_path;                   // �Ȧs file upload (zip of archived xml's) ���ؿ��]file_upload.php �ϥΡ^
      public static $procedure_uploading_path;                 // �Ȧs procedure-xml upload ���ؿ�
      public static $mining_result_uploading_path;             // �Ȧs mining-result upload ���ؿ�
                                                             
      public static $db_ad_year_offset = 100000;               // 2016-08-04: �N�褸�~ YYYY �[�W���ȡA�~�O�x�s�b��Ʈw AD_YEAR ���ȡ]�Υi���Q��B�z�褸�e���~�� -- offset ��i�]���s�^
      
      public static $db_deletion_verbosely = true;             // 2016-10-28: �R����r�w�ɡA�������Q�� CASCADE�A�ۤv���� features, documents ����
      //public static $qjob_db_deletion_docs_threshold = 0;    // 2016-10-28: �u�����r�w���ƶq�j�󵥩󦹼ơA�~�|�N�R����r�w���ʧ@��J queue job
      
      public static $queue_scanning_period = 5;                // 2016-08-04: �Y background process �٬��ۡA�C�X���ˬd JOB_QUEUE �@��
      public static $enable_qjob_db_construction = true;       // 2016-08-06: for webApi uploadXmlFilesToBuildDbJson.php
      public static $enable_qjob_db_deletion = true;           // 2016-10-28: for webApi deleteDbJson.php
      public static $enable_qjob_webtool_unzip = true;         // 2016-10-07, 2017-12-09
      public static $max_unzip_dir_depth = 10;                 // 2017-06-04: �Ȥ��\�غc�̦h�X�h���ؿ����c�]3 ��ܤ��\�̦h�T�� '/'�A�Ҧp���\ 'MyTool/js/jquery-ui/code.js'�^

      public static $enable_account_without_review = false;    // 2016-09-07: �ӽбb����A������ܱK�X�]���ݸg�f�֫�l�H�K�X�^
      public static $out_debugging_path;
      
      public static $role_system = 'SystemUser';
      public static $role_sysadmin = 'SysAdmin';
      public static $role_regadmin = 'RegAdmin';
      public static $role_commonuser = 'CommonUser';
      public static $role_poweruser = 'PowerUser';
      public static $role_demouser = 'DemoUser';
      
      public static $upload_path_root = 'uploads';                           // big5...
      public static $upload_path_db_xml = 'db-xml';                          // 2016-10-05: �N�o�ǤW������ơA�Q���ɮ׵��c�x�s...
      public static $upload_path_webtool_zip = 'webtool-zip';                // 2016-10-06, 2017-12-09: �N�o�ǤW������ơA�Q���ɮ׵��c�x�s...
      public static $upload_path_db_corpus_datafile = 'db-corpus-datafile';  // 2017-12-07: �N�o�ǤW������ơA�Q���ɮ׵��c�x�s...
      public static $user_tools_rootpath = 'userTools';
      public static $docu_tools_rootpath = 'docuTools';
      public static $docu_tools_accounts = array('system', 'opendb', 'opentool', 'public');    // �o�Ǳb�����u��A�N�ϥ� Config::$docu_tools_rootpath
      
      public static $doc_fields_int_type;                                    // 2016-11-01: �b�����w�A������ DOCUMENT ��ƪ���쬰 integer type
      public static $doc_fields_num_type;                                    // 2018-09-04: �ƭȫ��A�A�]�t��ƩM�p��
      
      public static $valid_doc_ext_types;                                    // 2019-07-18: DOC_EXTENSION ��椤�AEXT_TYPE ���i��ȡ]�ثe���}��ۭq�^
      public static $corpus_all_allows_common_spotlights;                    // 2019-07-26: [ALL] �U�O�_���\��ܡu�@�P�� metadata ������v (QueryResultFetcher)
      
      public static function init_config() {
         $file_dir = dirname( __FILE__ );
         self::$sys_intalled_path = dirname($file_dir);        // �� config ��b include �l�ؿ��U
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
         
         self::$log_user_action_by_system = false;          // 2017-08-13: �b LOG_USER_ACTION ���O�� 'system' ���ʧ@
         self::$log_user_login_repeat_times = 100;          // 2018-02-26: �Y�b session ���ͮĴ����A���_�i�� login�A�h�C n ���ȰO���@�� action
           
         self::$log_db_conn = false;                        // can use Config::set_log_db_conn() to change this
         self::$session_timeout = 60 * 60;                  // 60 minutes
         
         self::$max_capacity_quota = '800MB';
         self::$max_refdata_size = '100MB';
         self::$max_db_corpus_datafile_size = '300MB';
         
         self::$background_process_tick_filename = 'background_process.tick';
         self::$background_process_tick_maxsec = 1800;      // 30 �������@�w�n�� tick �O���A�_�h�N�{�� background process �w�g���F�]2015-02-16: �q Excel ��X�A������Ʈw�B�Ϊ̭p�� doc_fetures ���i��|�ݭn�Ƥ���...�^ 
         
         self::$sphinx_path = "C:/Sphinx/bin";
         self::$indexing_mod_digits = 2;                    // �N���ޤ������h���ɮס]�H�ϥΪ� id % n �Ӥ��� ==> ������ӥ� user_db id �Ӥ��^�A�䤤 mod_number ����ơ]�Ҧp��� 2�A��� MOD 10^2�^ -- 2016-09-02: ���Y�]�� 3�Asearchd �|�]�X�uToo many open files�v�����~�I
         self::$indexing_basecmd = "indexer --config ./sphinx.conf --rotate";       // �I�s�ɡA�ݥ��N <uid_mod_remainder> �m���� uid MOD 10^{mod_digits} �����G�]�l�ơ^
         
         self::$max_ft_indexed_databases = 500;
         self::$max_main_databases = 50;
         self::$max_text_databases = 300;
         self::$max_personal_databases_in_default = 5;      // �w�]�C��ϥΪ̳̦h�i�֦��h�֭� text databases
         self::$max_users = self::$max_main_databases;
         self::$max_unzip_dir_depth = 5;                    // ��Ҧ� zip �ɸ����]�U����
         self::$max_fetch_size = 100000;                    // 2019-09-12
         
         self::$max_job_queue_zombie_retry = 1;                // �̦h�u���Ф@��
         
         self::$max_upload_files = 3;                          // �ثe������@���u��W����@�ɮ�
         self::$max_file_upload_size_mb = 132;                 // �Y�L�j�A�ثe client (browser) �� uploadMultipart �i��|�Y�z�O����
         
         self::$max_upload_webtool_zip_files = 1;              // �ثe�A�Ҧ� RegWebTool ���ɮ׳������Y�b�@�� .zip ��
         self::$max_file_upload_webtool_zip_size_mb = 10;
         self::$max_webtool_unzip_path_depth = 4;              // 2017-06-04, 2017-12-09�Ȥ��\�غc�̦h�X�h���ؿ����c�]3 ��ܤ��\�̦h�T�� '/'�A�Ҧp���\ 'MyTool/js/jquery-ui/code.js'�^
         self::$max_webtool_unzip_files_extracted = 1000;      // 2017-12-09
         self::$webtool_unzip_skip_invalid = false;            // true ���L invalid files�Afalse ����ɦW

         self::$max_upload_corpus_datafiles = 200;             // �@���̦h�u��W�� 200 �� attachment �ɮ�
         self::$max_file_upload_corpus_datafile_size_mb = 10;  // �C���ɮ׳̤j�X MB
         
         self::$max_page_size = 2000;                          // 2021-08-07: �b QueryResultFetcher.class.php �H const MAX_PAGE_SIZE = Config::$max_page_size �ޱ�
         self::$max_doc_prev_next_size = 20;                   // �̦h�d�ߡu�e��v20�g���
         
         self::$max_post_classification_items = 2000;          // ������̤j�^�Ƕ��ؼƶq (hard limit) -- �w�]�ƶq�A��b�禡��J�Ѽƪ��w�]�� (�Ҧp�˯����I�s QueryResultFetcher �� get_query_post_classification()�Ametadata/tags �̤j�ȳ]�� 500/200)
         self::$max_feature_analysis_items = 2000;             // ���W���R�̤j�^�Ƕ��ؼƶq (hard limit)
         
         self::$default_post_classification_items = 200;       // 2021-08-29
         self::$default_feature_analysis_items = 200;          // 2021-08-29
         
         self::$pc_cues_move_to_last = array('-',              // ������ݲ���̫᪺ cues
                                             'undefined',
                                             '(-180.000000,-180.000000)',  // 2018-10-20: (-180,-180) �N��S���y�� => THDL �� (-1,-1) import ���ର (-180,-180)
                                             //'(-1.000000,-1.000000)'     // 2018-10-20: (-1,-1) �b THDL �N��S���y��
                                             );     
         
         self::$max_docs_auto_complete_analysis = 2000;                                           // 2021-09-25: �������թΥi�u�]�� 500
         self::$max_features_auto_complete_analysis = self::$default_feature_analysis_items * 2;  // 2021-09-27
         
         self::$hide_pc_spotlights = array('CT1/CT2/CT3/CT4/CT5', 'GE1/GE2/GE3/GE4/GE5',
                                           'CAT4', 'CAT5', 'GEO4', 'GEO5',                        // 2019-05-04: �o�� spotlights �N���|�Q�۰ʭp��B��J CORPUS_POST_CLASSIFICATION�v
                                           'GEO1/GEO2/GEO3', 'TP1/TP2/TP3', 'CAT1/CAT2/CAT3',     // 2019-05-09: �P 'GEO', 'TOPIC', 'CAT' �γ~����
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
         
         self::$corpus_all_allows_common_spotlights = false;           // �w�]������ܰ��F CORPUS ���~����L�����
         
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
         $root_dir = dirname(dirname(__FILE__)) . '/' . self::$web_path_prefix;      // ��e script �� path ���� X/include�A�Q�� dirname ���o X
         $path_rel_to_root = str_replace(DIRECTORY_SEPARATOR, '/', $path_rel_to_root);
         $segments = explode('/', $path_rel_to_root);
         $sub_path = $root_dir;
         if (!file_exists($sub_path)) mkdir($sub_path);
         foreach ($segments as $segment) {
            $sub_path .=  '/' . $segment;
            if (!file_exists($sub_path)) mkdir($sub_path);
         }
         return $sub_path;             // �^�ǵ�����|
      }
      
      public static function set_log_db_conn($bool) {
         Config::$log_db_conn = $bool;
      }
   }

   Config::init_config();
 
?>