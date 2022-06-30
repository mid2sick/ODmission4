# OD mission 4

## 功能
### 已完成
1. 檢視擁有的資料夾
2. 建立新資料夾
3. 刪除資料夾與裡面的資料
4. 檢視資料夾內資料
5. 上傳 csv 後進行爬蟲並增加 csv 內的資料到資料夾內
6. 複製勾選資料到其他指定資料夾
7. 移動勾選資料到其他指定資料夾
8. 刪除勾選資料

### 未完成
1. 資料夾內的的後分類查詢
2. 資料夾內的關鍵字查詢
3. 前端的更改資料夾名字

## 登入相關檔案
- 跟登入有關的檔案都在 registration/ 裡面

## Schema

1. 使用者基本資料

	```sql
	CREATE TABLE OD_User (
		ID INT(11) UNSIGNED AUTO_INCREMENT,
		Username VARCHAR(32) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY Alias_ID (Username)
	)
	```

2. 使用者所擁有的資料夾

    ```sql
	CREATE TABLE User_Dir (
		ID INT(11) UNSIGNED AUTO_INCREMENT,
		Owner_ID INT(11) UNSIGNED NOT NULL,
		Name VARCHAR(32) NOT NULL,
		PRIMARY KEY (ID),
		UNIQUE KEY Alias_ID (Owner_ID, Name),
		FOREIGN KEY (Owner_ID) REFERENCES OD_User(ID) ON DELETE CASCADE
	)
    ```

3. 資料夾底下的文件

    ```sql
	CREATE TABLE Dir_Doc (
		ID BIGINT(21) UNSIGNED AUTO_INCREMENT,
		Dir_ID INT(11) UNSIGNED NOT NULL,
		Metadata_ID BIGINT(20) UNSIGNED NOT NULL,
		`來源系統` VARCHAR(30),
		`來源系統縮寫` VARCHAR(10),
		`題名` TEXT,
		`摘要` TEXT,
		`類目階層` TEXT,
		`原始時間記錄` TEXT,
		`西元年` SMALLINT(6),
		`起始時間` DATE,
		`結束時間` DATE,
		`典藏號` VARCHAR(100),
		`文件原系統頁面URL` TEXT,
		`Original` TEXT,
		`相關人員` TEXT,
		`相關地點` TEXT,
		`相關組織` TEXT,
		`關鍵詞` TEXT,
		PRIMARY KEY (ID),
		FOREIGN KEY (Dir_ID) REFERENCES User_Dir(ID) ON DELETE CASCADE
	)
    ```

### [User 操作資料夾與文件的 API](https://hackmd.io/3eeRLRl9T7WufTqsBcYI4Q?view)

### [User API for Development](https://hackmd.io/8Coii0NTRkSw_FA80Qxt8g?view)

## 其他
1. 若下載下來測試，必須更改 upload.php 內的 $target_dir 到想要上傳 csv 的資料夾
