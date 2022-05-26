# OD mission 4
- 註：這只是初步設計，還未考量到 server 內部的 thread 分配

## 功能
### 已完成
1. 註冊使用者資料
2. 登入登出
3. 使用者可以開新資料夾
4. 可以從 DB 抓使用者開的資料夾資料
5. 可以上傳 csv 到本機指定資料夾
6. 可以瀏覽每個資料夾內 metadata

### 待完成
1. 刪除資料夾功能
2. 接 mission2 的 parse ID、丟給 search engine 查詢、分配 thread 回傳要爬的 id 給 mission2  
![圖片](https://user-images.githubusercontent.com/34702573/169698811-a21e3a8d-8549-4de2-a211-69290ad55f55.png)  
3. 根據 mission2 的 parse ID 將 OD sky（DB 內所有 metadata 的那份 table）內相應的 metadata 複製到 `directory` table（個人資料夾）的 metadata 欄位
4. 刪除 `directory` table 內 的 metadata
5. metadata 的後分類查詢、關鍵字查詢
6. 跟 mission 3 的修改合併
7. 跟 mission 5 合併
8. 套上 vue3

## 後台已有的 API
### 1. createDir.php:   
client 端 submit name='createDir' 的 input 時此 API 會被呼叫。  
此 API 的輸入為 username 與 newDir（欲創建資料夾的名字），在 `directory` table 建立一個 row，其中 dirName = newDir, username = username    
### 2. getDirList.php:  
此 API 的輸入為 username，輸出為一個 DIR object，DIR 的結構如下：  
```php
class DIR {
        public $IDs;
        public $names;
    }
```
其中的 IDs 和 names 皆為 json 型態，是此 username 擁有的所有 directory 的 id 與 name。  
詳細處理方法可參考呼叫此 API 的 updateDirList.js  
### 3. seeMetadata.php:  
此 API 的輸入為 username 與 id（directory 的 id），輸出為此 directory 內所有 metadata 的 json 檔。
詳細處理方法可參考呼叫此 API 的 openDir.js  
### 4. upload.php:  
client 端 submit name='submitCSV' 的 input 時此 API 會被呼叫。  
此 API 的輸入為檔案的名字，會將此檔案儲存在本機的 $targetDir 處，請記得在不同主機上時更新此變數。  

## 目前 Database 的 table
1. login: 紀錄使用者資訊

![Screenshot from 2022-05-22 21-24-54](https://user-images.githubusercontent.com/34702573/169697477-1d67047d-e71e-4701-b2bf-70425dd52e0a.png)

2. directory: 紀錄每個資料夾的資訊，其中 metadata 欄位為此資料夾所有的 metadata 的 json 檔
附註：下圖的 metadata 是手動加上去的，目前程式還無法編輯此部份

![Screenshot from 2022-05-22 21-26-33](https://user-images.githubusercontent.com/34702573/169697478-a259cd85-f83d-450d-a1db-5f85e1045b6b.png)

## 登入機制
- 跟登入有關的檔案都在 registration/ 裡面（除了 index.php 是在外面）
- 登入流程（尚未更新成和任務三合併的部份）：

![login](https://user-images.githubusercontent.com/34702573/168427490-101a314c-4a0e-4230-a60e-4f5108f04543.png)

## Schema

1. 使用者所擁有的資料夾

    ```sql
    /*
	把大括號改成小括號
	`login` 是紀錄 user data 的 table
	還要檢查 Owner_ID 跟 login 裡 ID 的編碼是否一樣（目前是 utf8mb4_general_ci）
	*/

	CREATE TABLE User_Dir(
	    ID INT(32) UNSIGNED AUTO_INCREMENT,
	    Owner_ID VARCHAR(32) NOT NULL,
	    Name VARCHAR(32) NOT NULL,
	    PRIMARY KEY (ID),
	    UNIQUE KEY Alias_ID (Owner_ID, Name),
	    FOREIGN KEY (Owner_ID) REFERENCES login(ID) ON DELETE CASCADE
	)
    ```
    
    > **TODO**: What is the schema of the table which stores user information?

2. 資料夾底下的 metadata

    ```sql
    CREATE TABLE Dir_Doc {
        Dir_ID INTEGER NOT NULL,
        Doc_ID INTEGER NOT NULL,
        PRIMARY KEY (Dir_ID, DOC_ID),
        FOREIGN KEY (Dir_ID) REFERENCES User_Dir(ID) ON DELETE CASCADE,
        FOREIGN KEY (Doc_ID) REFERENCES Metadata(ID)
    }
    ```

## 其他
1. 若下載下來測試，必須更改 upload.php 內的 $target_dir 到想要上傳 csv 的資料夾
