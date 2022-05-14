# OD mission 4
- 註：這只是初步設計，還未考量到 server 內部的 thread 分配

## 目前 Database 的 table
1. login: 紀錄使用者資訊

![Screenshot from 2022-05-14 21-21-28](https://user-images.githubusercontent.com/34702573/168427711-51f48786-4093-4a0a-9f93-85a65e4d3259.png)

2. directory: 紀錄每個資料夾內 metadata 的 id（id 最終該怎麼存還待任務一確定）
附註：下圖的 documentID 是手動加上去的，目前程式還無法編輯此部份

![Screenshot from 2022-05-14 21-23-51](https://user-images.githubusercontent.com/34702573/168427714-38f8249f-4155-4235-9d30-b0c370597f79.png)

3. serverVar: 紀錄 server 自己的變數，目前只紀錄了所有資料夾的數量，作為下個新資料夾的編號
附註：亦即儲存 directoryID 的最大值 + 1

![Screenshot from 2022-05-14 21-24-03](https://user-images.githubusercontent.com/34702573/168427716-75cae918-c932-4b48-9f88-3fd6221d347b.png)

## 登入機制
- 跟登入有關的檔案都在 registration/ 裡面（除了 index.php 是在外面）
- 登入流程：

![login](https://user-images.githubusercontent.com/34702573/168427490-101a314c-4a0e-4230-a60e-4f5108f04543.png)

## 目前完成功能
1. 註冊使用者資料
2. 登入登出
3. 使用者可以開新資料夾
4. 可以從 DB 抓使用者開的資料夾
5. 可以上傳 csv 到指定資料夾

## 待完成
1. 刪除資料夾功能
2. 接 mission2 的 parse ID、丟給 search engine 查詢、分配 thread 回傳要爬的 id 給 mission2
3. 編輯 table `directory` 內 的 documentIDs
4. 可以瀏覽每個資料夾內 metadata 的功能
5. metadata 的後分類查詢、關鍵字查詢

## 其他
1. 若下載下來測試，必須更改 upload.php 內的 $target_dir 到想要上傳 csv 的資料夾
